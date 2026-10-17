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
        <h2>Recherche de Trajet </h2>
            <div class="col-md-6 ftco-animate search-container">
              
                <form  method="POST" action="recherchee.php" class="search-form"  id="searchForm">
                  
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
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <button type="submit"  name="search" class="btnn btn-primary btn-block">Recherche</button>
                </form>
            </div>
        </div>
    </div>
</section>

         <!--   <h1 class="cn">Recherche de Trajet</h1> -->
        </div>
    </div>
</section>
<?php
$depart = $_POST['depart'] ?? null;
$arrive = $_POST['arrive'] ?? null;
$date = $_POST['date'] ?? null;

if ($depart && $arrive && $date) {
    $stmt = $pdo->prepare("SELECT * FROM trajet 
    WHERE depart = ? 
    AND arrive = ? 
    AND date_trajet = ? 
    AND status = 'active' 
    AND places_disponibles > 0");
    $stmt->execute([$depart, $arrive, $date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = []; // Aucun résultat si formulaire non soumis
}
?>

<section   class="ftco-section  search-results">
    <div class="container">
        <div id="results" class="row justify-content-center">
            <div class="col-md-8">
                <div class="results-header">
                    <h2>Veuillez entrer ces informations.</h2>
                    <p class="search-criteria">
                    Lieu de Départ: <strong><?= htmlspecialchars($depart) ?></strong> | 
                    Lieu d'Arrivée: <strong><?= htmlspecialchars($arrive) ?></strong> | 
                        Date: <strong>
                        <?php
                         if (!empty($date)) {
                          $date_obj = new DateTime($date);
                          echo $date_obj->format('D m/d/Y');
                        }
                        ?>
                    </p>
                </div>
                
                <?php foreach ($results as $trip) { ?>
                <div class="trip-card">
                    <div class="trip-info">
                        <div class="trip-route">
                            <h4>Départ : <strong><?= htmlspecialchars($trip['depart']) ?></strong></h4>
                            <h4>Arrivée : <strong><?= htmlspecialchars($trip['arrive']) ?></strong></h4>
                        </div>
                        <div class="trip-details">
                            <p><strong>Date :</strong> <?= htmlspecialchars($trip['date_trajet']) ?></p>
                            <p><strong>Heure de départ :</strong> <?= htmlspecialchars($trip['heure_depart']) ?></p>
                            <p><strong>véhicule :</strong> <?= htmlspecialchars($trip['car']) ?></p>
                            <p><strong>Places disponibles :</strong> <?= htmlspecialchars($trip['places_disponibles']) ?></p>
                        </div>
                    </div>
                    <div class="trip-actions">
                        <span class="trip-price"><?= htmlspecialchars($trip['prix']) ?> DH</span>
                        <a href="reservation.php?id=<?= $trip['id'] ?>" class="btnn btn-reserve">RÉSERVER</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php
$depart = $_POST['depart'] ?? null;
$arrive = $_POST['arrive'] ?? null;
$date = $_POST['date'] ?? null;

if ($depart && $arrive && $date) {
    $stmt = $pdo->prepare("SELECT * FROM trajet 
    WHERE depart = ? 
    AND arrive = ? 
    AND date_trajet = ? 
    AND status = 'active' 
    AND places_disponibles > 0"); 
    $stmt->execute([$depart, $arrive, $date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = []; // Aucun résultat si formulaire non soumis
}
?>


<?php include('../../templates/footer.php'); ?>	

<style>
  .form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group select {
  width: 100%;
  padding: 0 !important;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-sizing: border-box;
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
    color: #fff !important;

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
  
  /* Résultats de recherche */
  .search-results {
    padding: 50px 0;
  }
  
  .sou {
    
    text-align: center;
    margin-bottom: 30px;
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

</body>
</html>


// <!-- footer section -->
// <!-- footer section -->

// <!-- footer section -->

// <!-- footer section -->

