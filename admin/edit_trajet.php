<?php
require_once 'includes/db.php';
require_once 'includes/header-admin.php';

// Vérifier si l'ID du trajet existe
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_trajets.php');
    exit;
}

$trajet_id = (int)$_GET['id'];

// Récupérer les données du trajet
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ?");
$stmt->execute([$trajet_id]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    header('Location: manage_trajets.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart = htmlspecialchars($_POST['depart']);
    $arrive = htmlspecialchars($_POST['arrive']);
    $date_trajet = $_POST['date_trajet'];
    $heure_depart = $_POST['heure_depart'];
    $prix = (float)$_POST['prix'];
    $places = (int)$_POST['places'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE trajet SET 
            depart = ?, arrive = ?, date_trajet = ?, 
            heure_depart = ?, prix = ?, places_disponibles = ?, 
            status = ? 
            WHERE id = ?");
        
        $stmt->execute([
            $depart, $arrive, $date_trajet,
            $heure_depart, $prix, $places,
            $status, $trajet_id
        ]);
        
        header('Location: manage_trajets.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur de mise à jour: " . $e->getMessage();
    }
}
?>

<div class="content">
    <h1>Modifier le Trajet #<?= $trajet_id ?></h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="post" class="trajet-form">
        <div class="form-group">
            <label>Ville de départ:</label>
            <input type="text" name="depart" value="<?= htmlspecialchars($trajet['depart']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Ville d'arrivée:</label>
            <input type="text" name="arrive" value="<?= htmlspecialchars($trajet['arrive']) ?>" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date_trajet" value="<?= $trajet['date_trajet'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Heure:</label>
                <input type="time" name="heure_depart" value="<?= substr($trajet['heure_depart'], 0, 5) ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Prix (DH):</label>
                <input type="number" step="0.01" name="prix" value="<?= $trajet['prix'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Places disponibles:</label>
                <input type="number" name="places" value="<?= $trajet['places_disponibles'] ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Statut:</label>
            <select name="status">
                <option value="active" <?= $trajet['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                <option value="completed" <?= $trajet['status'] === 'completed' ? 'selected' : '' ?>>Terminé</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-save">Enregistrer</button>
            <a href="manage_trajets.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer-admin.php'; ?>