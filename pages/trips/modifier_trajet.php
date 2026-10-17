<?php
session_start();
include('../../templates/header.php');



$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer le trajet à modifier
$trajet_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ? AND conducteur_id = ?");
$stmt->execute([$trajet_id, $_SESSION['user_id']]);
$trajet = $stmt->fetch();

if (!$trajet) {
    $_SESSION['error'] = "Trajet non trouvé ou non autorisé";
    header("Location: mes_trajets_conducteur.php");
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $date_trajet = $_POST['date_trajet'];
    $heure_depart = $_POST['heure_depart'];
    $places_disponibles = intval($_POST['places_disponibles']);
    $prix = floatval($_POST['prix']);
    $car = $_POST['car'];

    $stmt = $pdo->prepare("UPDATE trajet SET 
                          depart = ?, 
                          arrive = ?, 
                          date_trajet = ?, 
                          heure_depart = ?, 
                          places_disponibles = ?, 
                          prix = ?, 
                          car = ? 
                          WHERE id = ? AND conducteur_id = ?");
    $stmt->execute([
        $depart, $arrive, $date_trajet, $heure_depart, 
        $places_disponibles, $prix, $car, 
        $trajet_id, $_SESSION['user_id']
    ]);

    $_SESSION['message'] = "Trajet modifié avec succès";
    header("Location: mes_trajets_conducteur.php");
    exit();
}
?>

<div class="content">
    <h2>Modifier le Trajet:</h2>
    
    <form method="POST" class="trajet-form" action="">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Départ</label>
                <select name="depart" class="form-control" required>
                    <option value="">Choisir une ville</option>
                    <?php
                    $villes = ['Errachidia', 'Ouarzazate', 'Casablanca', 'Rabat', 'Marrakech'];
                    foreach ($villes as $ville) {
                        $selected = $trajet['depart'] === $ville ? 'selected' : '';
                        echo "<option value=\"$ville\" $selected>$ville</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Arrivée</label>
                <select name="arrive" class="form-control" required>
                    <option value="">Choisir une ville</option>
                    <?php
                    foreach ($villes as $ville) {
                        $selected = $trajet['arrive'] === $ville ? 'selected' : '';
                        echo "<option value=\"$ville\" $selected>$ville</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Date</label>
                <input type="date" name="date_trajet" class="form-control" 
                       value="<?= $trajet['date_trajet'] ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label>Heure</label>
                <input type="time" name="heure_depart" class="form-control" 
                       value="<?= substr($trajet['heure_depart'], 0, 5) ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Places disponibles</label>
                <input type="number" name="places_disponibles" class="form-control" 
                       value="<?= $trajet['places_disponibles'] ?>" min="1" required>
            </div>
            <div class="form-group col-md-6">
                <label>Prix (DH)</label>
                <input type="number" name="prix" class="form-control" 
                       value="<?= $trajet['prix'] ?>" min="1" step="0.01" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Véhicule</label>
            <input type="text" name="car" class="form-control" 
                   value="<?= htmlspecialchars($trajet['car']) ?>" required>
        </div>
        <div class="form-row">
         <div class="form-group col-md-6">
 

         <button type="submit" class="btn btn-primary">Enregistrer</button>
         </div>
            <div class="form-group col-md-6">
        <a href="mes_trajets_conducteur.php" class="btn btn-secondary">Annuler</a>
            </div>
        </div>

    </form>
</div>


<style>


.trajet-form {
    display: absolute;
    max-width: 800px;
    margin: 0px auto;
    background: white;
    padding: 40px ;
    margin-bottom: 30px;


    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.contr {
      
        max-width: 800px;
        margin: 20px auto;
        padding: 40px ;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(238, 200, 33, 0.95);
    }
    
    h2 {
        text-align: center;
        margin-bottom: 30px;
        color:#152329 ;
        font-weight: 700;
        padding-top: 20px;
    }
    .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

    .form-group { margin-bottom: 15px; padding: 10px; }
    .form-control { width: 100%; padding: 20px; border: 1px solid #ddd; border-radius: 4px; }
    .btn { padding: 8px 15px; border-radius: 4px; color: white; text-decoration: none; }
    .btn-primary { background-color: #007bff; border: none; }
    .btn-secondary { background-color: #6c757d; }
</style>

<?php include('../../templates/footer.php'); ?>