<?php
require_once 'includes/db.php';
require_once 'includes/header-admin.php';

// Vérifier l'ID de réservation
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_reservations.php?error=invalid_id');
    exit;
}

$reservation_id = (int)$_GET['id'];

// Récupérer la réservation avec les infos du trajet
$stmt = $pdo->prepare("
    SELECT r.*, t.depart, t.arrive, t.date_trajet, t.heure_depart
    FROM reservations r
    JOIN trajet t ON r.trajet_id = t.id
    WHERE r.id = ?
");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header('Location: manage_reservations.php?error=not_found');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $telephone = preg_replace('/[^0-9]/', '', $_POST['telephone']);
    $places = (int)$_POST['places'];

    if ($email === false) {
        $error = "Email invalide";
    } elseif ($places < 1) {
        $error = "Nombre de places invalide";
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE reservations SET
                nom = ?, prenom = ?, email = ?, telephone = ?, nombre_places = ?
                WHERE id = ?
            ");
            $stmt->execute([$nom, $prenom, $email, $telephone, $places, $reservation_id]);
            
            header('Location: manage_reservations.php?success=updated');
            exit;
        } catch (PDOException $e) {
            $error = "Erreur de base de données: " . $e->getMessage();
        }
    }
}
?>

<div class="content">
    <h1>Modifier Réservation #<?= $reservation_id ?></h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="reservation-info">
        <h3>Trajet:</h3>
        <p>
            <?= htmlspecialchars($reservation['depart']) ?> → <?= htmlspecialchars($reservation['arrive']) ?><br>
            Le <?= date('d/m/Y', strtotime($reservation['date_trajet'])) ?> à <?= date('H:i', strtotime($reservation['heure_depart'])) ?>
        </p>
    </div>
    
    <form method="post" class="reservation-form">
        <div class="form-row">
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($reservation['nom']) ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom:</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($reservation['prenom']) ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($reservation['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Téléphone:</label>
                <input type="tel" name="telephone" value="<?= htmlspecialchars($reservation['telephone']) ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Nombre de places:</label>
            <input type="number" name="places" min="1" max="10" 
                   value="<?= (int)$reservation['nombre_places'] ?>" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-save">Enregistrer</button>
            <a href="manage_reservations.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer-admin.php'; ?>