<!DOCTYPE html>
<html lang="fr">
  
<?php 
session_start();
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

// Traitement 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publier'])) {
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $date_trajet = $_POST['date_trajet'];
    $heure_depart = $_POST['heure_depart'];
    $prix = $_POST['prix'];
    $places_disponibles = $_POST['places_disponibles'];
    $car = $_POST['car'] . ' '; 
    $conducteur_id = $_SESSION['user_id'];// ..................................................À remplacer par l'ID du conducteur connecté
    
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
               <?php if(isset($success)): ?>
                    <div class="alert alert-success mt-3"><?= $success ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger mt-3"><?= $error ?></div>
                <?php endif; ?>
            <div class="col-md-66 ftco-animate search-container">

              
            <form method="POST" class="search-form">
    <div class="form-row">
        <div class="col-md-66">
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
        </div>
        
        <div class="col-md-66">
            <div  class="form-group">
                <label  for="heure_depart">Heure de départ</label>
                <input type="text" class="form-control" name="heure_depart" 
       pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" 
       placeholder="HH:MM (24h)" required> </div>
            <div class="form-group">
                <label for="car">Type de véhicule</label>
                <input placeholder="Ex: BMW " type="texte" class="form-control" id="car" name="car"  required>
            </div>
            <div class="form-group">
                <label for="prix">Prix par passager (DH)</label>
                <input placeholder="Free : 00 "  type="number" class="form-control" id="prix" name="prix"  required>
            </div>
            <div class="form-group">
                <label for="places_disponibles">Places disponibles</label>
                <input type="number" class="form-control" id="places_disponibles" name="places_disponibles" min="1" max="10" required>
            </div>
        </div>
    </div>
    
    <div class="form-group text-center">
        <button type="submit" name="publier" class="btnn btn-primary">Publier </button>
        
    </div>
</form>
                
               
            </div>
        </div>
    </div>
</section>

        </div>
    </div>
</section>

<script>
// Forcer l'affichage en 24h pour les navigateurs qui supportent type="time"
document.getElementById('heure_depart').addEventListener('focus', function() {
    this.type = 'text';
    this.placeholder = 'HH:MM (24h)';
    this.addEventListener('blur', function() {
        if(this.value.match(/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/)) {
            this.type = 'time';
        }
    });
});

// Validation supplémentaire
document.querySelector('form').addEventListener('submit', function(e) {
    const heureInput = document.getElementById('heure_depart');
    if(!heureInput.value.match(/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/)) {
        e.preventDefault();
        alert('Format horaire invalide. Utilisez HH:MM (24h)');
        heureInput.focus();
    }
});
</script>
  

<style>
  .form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group select {
  width: 100%;
  padding: 5px !important;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-sizing: border-box;

}
.form-group{
    margin-bottom: 0px ;
    
}
body .col-md-6 {
    padding-left: 0 !important;

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
    margin-left: -550px;
    margin-bottom: -415px;
    margin-top: -300px;
  }
  .btnn:hover {
    background: #2a3a38;
  }
  .btn-reserve {
    background: #fdd120;
    color:#152329 ;
    text-decoration: none;
    display: inline-block;
    text-align: center;
  }
  .btn-reserve:hover {
    background: #e6c51d;
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
    padding: 10px;
    border-radius: 8px;
    padding-top: 10px;
    padding-bottom: 10px;
    
   
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
  
  /* Résultats de recherche */
  .search-results {
    padding: 50px 0;
  }
  
  .sou {
   padding-right: 300px;
   padding-left: 60px;
    text-align: center;
    margin-bottom: -70px;
    padding-bottom:-30px
  }
  .sou h2 {
    
    color: #ffffff;
    font-weight: 700;
  }



  .results-header {
    
    text-align: center;
    margin-bottom: 30px;
  }
  .results-header h2 {
    
    color:#152329 ;
    font-weight: 700;
  }
  .search-criteria {
    color: #666;
    margin-top: 10px;
  }
  .trip-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .trip-info {
    flex: 2;
  }
  .trip-route {
    margin-bottom: 10px;
  }
  .trip-route h4 {
    margin: 5px 0;
    font-size: 18px;
  }
  .trip-details p {
    margin: 5px 0;
    color: #666;
  }
  .trip-actions {
    flex: 1;
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .trip-price {
    font-size: 20px;
    font-weight: bold;
    color:#152329 ;
    margin-bottom: 10px;
  }
  
  @media (max-width: 768px) {
    .trip-card {
      flex-direction: column;
    }
    .trip-actions {
      margin-top: 15px;
      align-items: center;
      width: 100%;
    }
  }
</style>
<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche le rechargement

    var formData = new FormData(this);

    fetch('recherche_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('results').innerHTML = data;
        // Scroll automatique vers les résultats
        document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});
</script>

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
    // Validation client supplémentaire
// Validation client supplémentaire
// Validation client supplémentaire
// Validation client supplémentaire
// Validation client supplémentaire

  
    
    return true;
});
</script>

<?php include('../../templates/footer.php'); ?>	

</body>

</html>
