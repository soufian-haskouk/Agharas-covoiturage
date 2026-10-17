<?php
require_once __DIR__ . '/includes/auth-check.php';

require_once 'includes/db.php';
require_once 'includes/header-admin.php';

// Initialisation des variables
$sort = $_GET['sort'] ?? 'date_trajet';
$order = $_GET['order'] ?? 'DESC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Construction des filtres
$where = [];
$params = [];

if (!empty($_GET['depart'])) {
    $where[] = "t.depart LIKE ?";
    $params[] = '%' . $_GET['depart'] . '%';
}

if (!empty($_GET['arrive'])) {
    $where[] = "t.arrive LIKE ?";
    $params[] = '%' . $_GET['arrive'] . '%';
}

if (!empty($_GET['date'])) {
    $where[] = "t.date_trajet = ?";
    $params[] = $_GET['date'];
}

// Construction de la requête principale
$sql = "SELECT t.*, u.nom AS conducteur_nom, u.prenom AS conducteur_prenom 
        FROM trajet t
        JOIN users u ON t.conducteur_id = u.id";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Modification importante ici - utilisation de paramètres positionnels pour la pagination
$sql .= " ORDER BY $sort $order LIMIT ?, ?";

// Exécution requête principale
$stmt = $pdo->prepare($sql);

// Binding des paramètres de filtre
foreach ($params as $i => $param) {
    $stmt->bindValue($i + 1, $param);
}

// Binding des paramètres de pagination (ajoutés à la fin)
$stmt->bindValue(count($params) + 1, $offset, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $per_page, PDO::PARAM_INT);

$stmt->execute();
$trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total (pour pagination)
$count_sql = "SELECT COUNT(*) FROM trajet t JOIN users u ON t.conducteur_id = u.id";
if (!empty($where)) {
    $count_sql .= " WHERE " . implode(" AND ", $where);
}

$count_stmt = $pdo->prepare($count_sql);
foreach ($params as $i => $param) {
    $count_stmt->bindValue($i + 1, $param);
}
$count_stmt->execute();
$total_trajets = $count_stmt->fetchColumn();
$total_pages = ceil($total_trajets / $per_page);

?>

<div class="content">
    <h1>Gestion des Trajets</h1>
    
    <!-- Formulaire de Filtre -->
    <form method="get" class="filter-form">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        
        <div class="filter-row">
            <div class="filter-group">
                <label>Départ:</label>
                <input type="text" name="depart" value="<?= htmlspecialchars($_GET['depart'] ?? '') ?>" 
                       placeholder="Ville de départ">
            </div>
            
            <div class="filter-group">
                <label>Arrivée:</label>
                <input type="text" name="arrive" value="<?= htmlspecialchars($_GET['arrive'] ?? '') ?>" 
                       placeholder="Ville d'arrivée">
            </div>
            
            <div class="filter-group">
                <label>Date:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-filter">Filtrer</button>
            <a href="manage_trajets.php" class="btn-reset">Réinitialiser</a>
        </div>
    </form>

    <!-- Affichage des résultats -->
    <div class="table-responsive">
    <table class="trajets-table  ">
        <thead>
            <tr>
                <th>ID</th>
                <th>
                    <a href="?sort=depart&order=<?= $sort === 'depart' && $order === 'ASC' ? 'DESC' : 'ASC' ?>&<?= http_build_query($_GET) ?>">
                        Itinéraire <?= $sort === 'depart' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </a>
                </th>
                <th>Date/Heure</th>
                <th>Conducteur</th>
                <th>Places</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trajets as $trajet): ?>
            <tr>
                <td><?= $trajet['id'] ?></td>
                <td>
                    <?= htmlspecialchars($trajet['depart']) ?> → 
                    <?= htmlspecialchars($trajet['arrive']) ?>
                </td>
                <td>
                    <?= date('d/m/Y', strtotime($trajet['date_trajet'])) ?> à 
                    <?= date('H:i', strtotime($trajet['heure_depart'])) ?>
                </td>
                <td>
                    <?= htmlspecialchars($trajet['conducteur_prenom'] . ' ' . $trajet['conducteur_nom']) ?>
                </td>
                <td><?= $trajet['places_disponibles'] ?></td>
                <td><?= $trajet['prix'] ?> DH</td>
                <td>
                    <span class="status-<?= $trajet['status'] ?>">
                        <?= $trajet['status'] === 'active' ? 'Actif' : 'Terminé' ?>
                    </span>
                </td>
                <td>
                    <a href="edit_trajet.php?id=<?= $trajet['id'] ?>" class="btn-edit badge btn-edit bg-primary text-white text-decoration-none" title="Edit">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                    <a href="#" class="btn-delete badge btn-delete bg-danger text-white text-decoration-none" title="Delete"
                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                    data-id="<?= $trajet['id'] ?>">
                        <i class="fas fa-trash-alt me-1"></i>Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Supprimer ce trajet?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>





    <!-- Pagination -->
   <!-- Dans la section pagination de votre manage_trajets.php -->
    <div class="pagination">
        <?php 
        // Créer une copie des paramètres GET et supprimer 'page'
        $queryParams = $_GET;
        unset($queryParams['page']);
        $queryString = http_build_query($queryParams);
        ?>
        
        <?php if ($page > 1): ?>
            <a href="?<?= $queryString ?>&page=<?= $page - 1 ?>">Précédent</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?<?= $queryString ?>&page=<?= $i ?>" 
            <?= $i === $page ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?<?= $queryString ?>&page=<?= $page + 1 ?>">Suivant</a>
        <?php endif; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    var deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var trajetId = this.getAttribute('data-id');
            var deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
            deleteConfirmBtn.href = 'delete_trajet.php?id=' + trajetId;
        });
    });
});
</script>

<?php require_once 'includes/footer-admin.php'; ?>