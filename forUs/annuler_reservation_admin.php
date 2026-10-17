rate draver 
<?php
session_start();

// Check if reservation data exists
//if (!isset($_SESSION['reservation_data'])) {
 //   header("Location: recherche.php");
 //   exit();}

$reservation = $_SESSION['reservation_data'];

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Process rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO driver_ratings (reservation_id, driver_id, passenger_id, rating) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $reservation['reservation_id'],
            $reservation['conducteur_id'],
            $_SESSION['user_id'],
            $rating
        ]);
        
        header("Location: confirmation.php?rated=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de l'enregistrement de l'évaluation: " . $e->getMessage();
    }
}

// Check if user already rated this trip
$already_rated = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM driver_ratings 
                          WHERE reservation_id = ? AND passenger_id = ?");
    $stmt->execute([$reservation['reservation_id'], $_SESSION['user_id']]);
    $already_rated = $stmt->fetch() !== false;
}
?>

<section class="ftco-section">
  <div class="ccon">
    <h2>Évaluer votre conducteur</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($already_rated): ?>
      <div class="alert alert-info">
        Vous avez déjà évalué ce conducteur. Merci!
      </div>
      <a href="confirmation.php" class="button" style="margin-top: 20px;">
        <span>Retour à la confirmation</span>
      </a>
    <?php else: ?>
      <form method="POST" action="">
        <div class="rating" style="text-align: center; margin: 30px 0;">
          <input type="radio" id="star5" name="rating" value="5" required />
          <label for="star5"></label>
          <input type="radio" id="star4" name="rating" value="4" />
          <label for="star4"></label>
          <input type="radio" id="star3" name="rating" value="3" />
          <label for="star3"></label>
          <input type="radio" id="star2" name="rating" value="2" />
          <label for="star2"></label>
          <input type="radio" id="star1" name="rating" value="1" />
          <label for="star1"></label>
        </div>
        
        <div style="text-align: center;">
          <button type="submit" class="button" style="margin-top: 10px;">
            <span>Soumettre l'évaluation</span>
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>

<style>
    body{
        background-color: black;
    }
.rating {
  display: inline-block;
  position: relative;
  height: 50px;
  line-height: 50px;
  font-size: 50px;
  direction: rtl;
  justify-items: center;
}

.rating label {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 50px;
  cursor: pointer;
  color: #ddd;
    transition: color 0.3s;

}

.rating label:before {
  content: "★";
  position: absolute;
}

.rating input {
  display: none;
}

.rating label:hover,
.rating label:hover ~ label,
.rating input:checked ~ label {
  color: #ffc107;
}
</style>

