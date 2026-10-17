<!DOCTYPE html>
<html lang="fr">
  
<?php 
include('../../templates/header.php');

// Connexion à la base de données
$host_soufian = 'localhost';
$dbname_souf = 'agharas_bd'; 
$username_souf= 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host_soufian;dbname=$dbname_souf", $username_souf, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publier'])) {
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $date_trajet = $_POST['date_trajet'];
    $heure_depart = $_POST['heure_depart'];
    $prix = $_POST['prix'];
    $places_disponibles = $_POST['places_disponibles'];
    $car = $_POST['car'] . ' heures'; // Formatage de la durée
    $conducteur_id = 1; // À remplacer par l'ID du conducteur connecté
    
    try {
        $stmt = $pdo->prepare("INSERT INTO trajet (depart, arrive, date_trajet, heure_depart, prix, places_disponibles, car, conducteur_id, created_at) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$depart, $arrive, $date_trajet, $heure_depart, $prix, $places_disponibles, $car, $conducteur_id]);
        
        $success = "Trajet publié avec succès!";
    } catch (PDOException $e) {
        $error = "Erreur lors de la publication du trajet: " . $e->getMessage();
    }
}
?>	
 

<section class="hero-wrap hero-wrap-3 js-fullheight" style="background-image: url('../../pages/images/bg_3.jpg')" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="col-md-9">
            <span class="acc">
                <a href="Accueil.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
            </span>
            

<section class="ftco-section ftco-search">
    <div class="container">
        <div class="row sou justify-content-center">
        <h2>Publier un Nouveau Trajet</h2>
            <div class="col-md-6 ftco-animate search-container">
              
                <form method="POST" class="search-form">
                  
                    <div class="form-group">
                        <label for="depart">Lieu de départ</label>
                        <select class="form-control" id="depart" name="depart" required>
                            <option value="" selected disabled>Sélectionner une ville</option>
                            <option value="Errachidia">Errachidia</option>
                            <option value="Ouarzazate">Ouarzazate</option>
                            <option value="Tanger">Tanger</option>
                            <option value="Casablanca">Casablanca</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Marrakech">Marrakech</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="arrive">Lieu d'arrivée</label>
                        <select class="form-control" id="arrive" name="arrive" required>
                            <option value="" selected disabled>Sélectionner une ville</option>
                            <option value="Errachidia">Errachidia</option>
                            <option value="Ouarzazate">Ouarzazate</option>
                            <option value="Tanger">Tanger</option>
                            <option value="Casablanca">Casablanca</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Marrakech">Marrakech</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_trajet">Date du trajet</label>
                        <input type="date" class="form-control" id="date_trajet" name="date_trajet" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="heure_depart">Heure de départ</label>
                        <input type="time" class="form-control" id="heure_depart" name="heure_depart" required>
                    </div>
                    <div class="form-group">
                        <label for="car">Durée estimée (en heures)</label>
                        <input type="number" class="form-control" id="car" name="car" min="1" max="24" required>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix par passager (DH)</label>
                        <input type="number" class="form-control" id="prix" name="prix" min="10" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="places_disponibles">Places disponibles</label>
                        <input type="number" class="form-control" id="places_disponibles" name="places_disponibles" min="1" max="10" required>
                    </div>
                    <button type="submit" name="publier" class="btnn btn-primary btn-block">Publier le trajet</button>
                </form>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success mt-3"><?= $success ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger mt-3"><?= $error ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

        </div>
    </div>
</section>


<style>
  .form-group input[type="text"],
  .form-group input[type="email"],
  .form-group input[type="password"],
  .form-group input[type="number"],
  .form-group input[type="date"],
  .form-group input[type="time"],
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-sizing: border-box;
    margin-bottom: 15px;
  }
  
  .btnn {
    background:#152329 ;
    color: #fff;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
  }
  
  .btnn:hover {
    background: #2a3a38;
  }
  
  .search-form {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 30px;
  }
  
  .search-container {
    background: #fdd120;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
    margin-top: 30px;
  }
  
  .hero-wrap.hero-wrap-3 {
    height: 600px !important;
    position: relative;
  }
  
  .hero-wrap.hero-wrap-3 .overlay {
    width: 100%;
    opacity: .3;
    height: 600px;
  }
  
  .sou {
    text-align: center;
    margin-bottom: 30px;
  }
  
  .sou h2 {
    color: #ffffff;
    font-weight: 700;
  }
  
  .alert {
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
  }
  
  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }
  
  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }
</style>

<script>
// Validation client supplémentaire
document.querySelector('form').addEventListener('submit', function(e) {
    const depart = document.getElementById('depart').value;
    const arrive = document.getElementById('arrive').value;
    
    if (depart === arrive) {
        e.preventDefault();
        alert('Le lieu de départ et d\'arrivée doivent être différents');
        return false;
    }
    
    const dateTrajet = new Date(document.getElementById('date_trajet').value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (dateTrajet < today) {
        e.preventDefault();
        alert('La date du trajet ne peut pas être dans le passé');
        return false;
    }
    
    // Validation du prix (doit être un nombre décimal)
    const prix = document.getElementById('prix').value;
    if (isNaN(prix) || parseFloat(prix) <= 0) {
        e.preventDefault();
        alert('Veuillez entrer un prix valide');
        return false;
    }
    
    return true;
});
</script>

</body>
</html>