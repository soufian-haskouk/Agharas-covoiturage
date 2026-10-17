<!DOCTYPE html>
<html lang="fr">
  
<?php include('../../templates/header.php'); ?>	
 

<section class="hero-wrap hero-wrap-3 js-fullheight" style="background-image: url('../../pages/images/bg_3.jpg')" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="col-md-9">
            <span class="acc">
                <a href="index.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
            </span>
            <h1 class="cn">Recherche de Trajet</h1>
        </div>
    </div>
</section>

<section class="ftco-section ftco-search">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 ftco-animate search-container">
                <form action="#" method="post" class="search-form">
                    <div class="form-group">
                        <label for="departure">Lieu de départ</label>
                        <select class="form-control" id="departure" name="departure">
                            <option value="" selected disabled>Sélectionner une ville</option>
                            <option value="Tanger">Tanger</option>
                            <option value="Casablanca">Casablanca</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Marrakech">Marrakech</option>
                            <option value="Ouarzazate">Ouarzazate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="arrival">Lieu d'arrivée</label>
                        <select class="form-control" id="arrival" name="arrival">
                            <option value="" selected disabled>Sélectionner une ville</option>
                            <option value="Tanger">Tanger</option>
                            <option value="Casablanca">Casablanca</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Marrakech">Marrakech</option>
                            <option value="Ouarzazate">Ouarzazate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="jj/mm/aaaa">
                    </div>
                    <button type="submit" name="search" class="btnn btn-primary btn-block">RECHERCHER</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Search Results Section -->
<?php
// This would be replaced with your actual PHP code to process the form and fetch results
if (isset($_POST['search'])) {
    $departure = $_POST['departure'] ?? '';
    $arrival = $_POST['arrival'] ?? '';
    $date = $_POST['date'] ?? '';
    
    // Sample results data - replace with your database query
    $sampleResults = [
        [
            'departure' => 'Tanger',
            'arrival' => 'Ouarzazate',
            'date' => '2024-05-09',
            'duration' => '2h 34m',
            'price' => '350 MAD'
        ],
        [
            'departure' => 'Tanger',
            'arrival' => 'Ouarzazate',
            'date' => '2024-05-09',
            'duration' => '3h 15m',
            'price' => '400 MAD'
        ]
    ];
    
    if (!empty($sampleResults)) {
?>
<section class="ftco-section search-results">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="results-header">
                    <h2>Résultats de la recherche</h2>
                </div>
                
                <?php foreach ($sampleResults as $trip) { ?>
                <div class="trip-card">
                    <div class="trip-info">
                        <div class="trip-route">
                            <h4>Départ : <strong><?php echo $trip['departure']; ?></strong></h4>
                            <h4>Arrivée : <strong><?php echo $trip['arrival']; ?></strong></h4>
                        </div>
                        <div class="trip-details">
                            <p><strong>Date :</strong> <?php echo $trip['date']; ?></p>
                            <p><strong>Durée :</strong> <?php echo $trip['duration']; ?></p>
                        </div>
                    </div>
                    <div class="trip-actions">
                        <span class="trip-price"><?php echo $trip['price']; ?></span>
                        <button class="btnn btn-reserve">RÉSERVER</button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php
    }
}
?>

<?php include('../../templates/footer.php'); ?>	

<style>
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
  
  /* Search Results Styles */
  .search-results {
    padding: 50px 0;
  }
  .results-header {
    text-align: center;
    margin-bottom: 30px;
  }
  .results-header h2 {
    color:#152329 ;
    font-weight: 700;
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
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .trip-card {
      flex-direction: column;
      text-align: center;
    }
    .trip-actions {
      margin-top: 15px;
      align-items: center;
    }
  }
</style>

<script>
  //see today's date as default
  document.addEventListener('DOMContentLoaded', function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').value = today;
  });
</script>

</body>
</html>