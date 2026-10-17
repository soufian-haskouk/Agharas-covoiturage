<?php
require_once __DIR__ . '/includes/auth-check.php';

require_once 'includes/db.php';
require_once 'includes/header-admin.php';

// Initialisation pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Construction des filtres
$where = [];
$params = [];

// Filtre par ID de trajet
if (!empty($_GET['trajet_id'])) {
    $where[] = "r.trajet_id = ?";
    $params[] = (int)$_GET['trajet_id'];
}

// Filtre par email de passager
if (!empty($_GET['email'])) {
    $where[] = "r.email LIKE ?";
    $params[] = '%' . $_GET['email'] . '%';
}

// Filtre par date
if (!empty($_GET['date'])) {
    $where[] = "DATE(r.date_reservation) = ?";
    $params[] = $_GET['date'];
}

// Requête principale
$sql = "SELECT r.*, t.depart, t.arrive, t.date_trajet, t.heure_depart,
               CONCAT(u.prenom, ' ', u.nom) AS conducteur
        FROM reservations r
        JOIN trajet t ON r.trajet_id = t.id
        JOIN users u ON t.conducteur_id = u.id";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
// Construction de la requête
$sql .= " ORDER BY r.date_reservation DESC LIMIT ?, ?";

// Ajout des paramètres de pagination
$params[] = $offset;
$params[] = $per_page;

// Binding avec vérification de type
$stmt = $pdo->prepare($sql);
foreach ($params as $i => $param) {
    $paramType = is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($i + 1, $param, $paramType);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul total
$count_sql = "SELECT COUNT(*) FROM reservations r";
if (!empty($where)) {
    $count_sql .= " WHERE " . implode(" AND ", $where);
}
$count_stmt = $pdo->prepare($count_sql);
$count_stmt                         ->execute(array_slice($params, 0, -2));
$total_reservations = $count_stmt                         ->fetchColumn(); 
$total_pages = ceil($total_reservations / $per_page);
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Gestion des Réservations</h1>
    
    <!-- Formulaire de Filtre -->
    <form method="get" class="filter-form">
        <div class="filter-row">
           
            <div class="filter-group">
                <label >Email Passager:</label>
                <input placeholder="" type="text" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label>Date Réservation:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-filter">Filtrer</button>
            <a href="manage_reservations.php" class="btn-reset">Réinitialiser</a>
        </div>
    </form>
    
    <!-- Tableau des Réservations -->
    <table class="reservations-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Trajet</th>
                <th>Passager</th>
                <th>Contact</th>
                <th>Places</th>
                <th>Date Résa</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= $res['id'] ?></td>
                <td>
                    <?= htmlspecialchars($res['depart']) ?> → <?= htmlspecialchars($res['arrive']) ?><br>
                    <small>Le <?= date('d/m/Y', strtotime($res['date_trajet'])) ?> à <?= date('H:i', strtotime($res['heure_depart'])) ?></small><br>
                    <small>Conducteur: <?= htmlspecialchars($res['conducteur']) ?></small>
                </td>
                <td>
                    <?= htmlspecialchars($res['prenom'] . ' ' . $res['nom']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($res['email']) ?><br>
                    <?= htmlspecialchars($res['telephone']) ?>
                </td>
                <td><?= $res['nombre_places'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($res['date_reservation'])) ?></td>
                <td>
                   
                         <a href="edit_reservation.php?id=<?= $res['id'] ?>" class="btn-edit badge btn-edit bg-primary text-white text-decoration-none" title="Edit">
                        <i class="fas fa-edit me-1"></i>Modifier
                        </a>                    
                        <a href="#" class="btn-delete badge btn-delete bg-danger text-white text-decoration-none" title="Delete"
                        onclick="confirmDeleteReservation(<?= $res['id'] ?>)">
                        <i class="fas fa-trash-alt me-1"></i>Supprimer
                        </a>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <?php 
        $queryParams = $_GET;
        unset($queryParams['page']);
        $queryString = http_build_query($queryParams);
        ?>
        
        <?php if ($page > 1): ?>
            <a href="?<?= $queryString ?>&page=<?= $page - 1 ?>">Précédent</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?<?= $queryString ?>&page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?<?= $queryString ?>&page=<?= $page + 1 ?>">Suivant</a>
        <?php endif; ?>
    </div>
</div>
<script>
function confirmDeleteReservation(resId) {
    Swal.fire({
        title: 'Confirmation',
        text: "Voulez-vous vraiment supprimer cette réservation ?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
       
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_reservation.php?id=' + resId;
        }
    });
}
</script>

<?php require_once 'includes/footer-admin.php'; ?>