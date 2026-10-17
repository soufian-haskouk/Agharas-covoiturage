<?php
session_start();
include('../../templates/header.php');

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch logged-in user info for pre-filling email
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process form submission first
if (isset($_POST['confirmer'])) {
    $trajet_id = intval($_POST['trajet_id']);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $nombre_places = intval($_POST['nombre_places']);
    $date_reservation = date('Y-m-d H:i:s'); 

    $stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ?");
    $stmt->execute([$trajet_id]);
    $trip = $stmt->fetch();

    if ($trip) {
        if ($nombre_places <= $trip['places_disponibles']) {
            $conducteur_id = $trip['conducteur_id'];
            
            // Insert reservation
            $stmt = $pdo->prepare("INSERT INTO reservations (trajet_id, nom, prenom, email, telephone, nombre_places, date_reservation, conducteur_id) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $trajet_id, 
                $nom, 
                $prenom, 
                $email, 
                $telephone, 
                $nombre_places, 
                $date_reservation, 
                $conducteur_id  
            ]);
            
            // Get the last inserted reservation ID
            $reservation_id = $pdo->lastInsertId();
            
            // Calculate new available seats
            $new_available_seats = $trip['places_disponibles'] - $nombre_places;
    
            if ($new_available_seats <= 0) {
                // Delete the trip if no more seats
                $stmt = $pdo->prepare("UPDATE trajet SET places_disponibles = 0, status = 'completed' WHERE id = ?");
                $stmt->execute([$trajet_id]);
            } else {
                // Update available seats if not full
                $stmt = $pdo->prepare("UPDATE trajet SET places_disponibles = ? WHERE id = ?");
                $stmt->execute([$new_available_seats, $trajet_id]);
            }

            // Store reservation data in session for PDF generation
            $_SESSION['reservation_data'] = [
                'reservation_id' => $reservation_id,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'nombre_places' => $nombre_places,
                'date_reservation' => $date_reservation,
                'trajet_id' => $trajet_id,
                'depart' => $trip['depart'],
                'arrive' => $trip['arrive'],
                'date_trajet' => $trip['date_trajet'],
                'heure_depart' => $trip['heure_depart'],
                'prix' => $trip['prix'],
                'conducteur_id' => $conducteur_id
            ];

            // Redirect to confirmation page
            header("Location: confirmation1.php");
            exit();
        } else {
            $error = "Erreur: Nombre de places demandé indisponible.";
        }
    } else {
        $error = "Erreur: Trajet introuvable.";
    }
}

// Check if id is provided
if (!isset($_GET['id'])) {
    die("Aucun trajet sélectionné.");
}

$id = intval($_GET['id']);

// Get trip information
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ?");
$stmt->execute([$id]);
$trip = $stmt->fetch();

if (!$trip) {
    die("Trajet non trouvé.");
}

// Prevent user from reserving their own trip
if (isset($trip['conducteur_id'], $_SESSION['user_id']) && $trip['conducteur_id'] == $_SESSION['user_id']) {
    echo '<div class="alert alert-danger" style="margin-top:20px; padding: 50px;text-align:center;">OOPS !! Vous ne pouvez pas réserver votre propre trajet.</div>';
    include('../../templates/footer.php');
    exit();
}
?>

<!-- HTML -->
<section class="reservation-section">
  <div class="reservation-container">
    <div class="reservation-header">
      <h2>Réserver votre trajet</h2>
      <div class="header-decoration"></div>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="trip-card">
      <div class="trip-details">
        <div class="detail-item">
          <i class="fas fa-map-marker-alt"></i>
          <span><strong>Départ:</strong> <?= htmlspecialchars($trip['depart']) ?></span>
        </div>
        <div class="detail-item">
          <i class="fas fa-flag-checkered"></i>
          <span><strong>Arrivée:</strong> <?= htmlspecialchars($trip['arrive']) ?></span>
        </div>
        <div class="detail-item">
          <i class="far fa-calendar-alt"></i>
          <span><strong>Date:</strong> 
            <?php 
              $date_trip = new DateTime($trip['date_trajet']);
              echo $date_trip->format('d/m/Y');
            ?>
          </span>
        </div>
        <div class="detail-item">
          <i class="far fa-clock"></i>
          <span><strong>Heure:</strong> <?= htmlspecialchars($trip['heure_depart']) ?></span>
        </div>
        <div class="detail-item">
          <i class="fas fa-tag"></i>
          <span><strong>Prix:</strong> <?= htmlspecialchars($trip['prix']) ?> DH</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-users"></i>
          <span><strong>Places disponibles:</strong> <?= htmlspecialchars($trip['places_disponibles']) ?></span>
        </div>
        <?php
        // Afficher la note moyenne du conducteur
        $conducteur_id = $trip['conducteur_id'];
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as nb FROM driver_ratings WHERE driver_id = ?");
        $stmt->execute([$conducteur_id]);
        $rating_data = $stmt->fetch();
        $avg_rating = $rating_data && $rating_data['nb'] > 0 ? round($rating_data['avg_rating'], 1) : null;
        ?>
        </div>
        <div class="detail-item">
          <i class="fas fa-star"></i>
          <span><strong>Évaluation du chauffeur:</strong> 
            <?php if ($avg_rating): ?>
              <span class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?php if ($i <= floor($avg_rating)): ?>
                    ★
                  <?php elseif ($i - $avg_rating < 1): ?>
                    <span class="partial-star" style="width:<?= ($avg_rating - floor($avg_rating)) * 100 ?>%">★</span>
                  <?php else: ?>
                    <span class="empty-star">★</span>
                  <?php endif; ?>
                <?php endfor; ?>
                <span class="rating-text">(<?= $avg_rating ?>/5)</span>
                <span class="rating-count">(<?= $rating_data['nb'] ?> avis)</span>
              </span>
            <?php else: ?>
              <span class="no-rating">Pas encore de note</span>
            <?php endif; ?>
          </span>
        </div>
      
      
      <div class="brand-animation">
        <span class="brand-text">AGHARAS</span>
        <span class="brand-highlight"></span>
      </div>
    </div>

    <form method="POST" action="" class="reservation-form">
      <input type="hidden" name="trajet_id" value="<?= $trip['id'] ?>">

      <div class="form-row">
        <div class="form-group">
          <label for="nom">Nom</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" name="nom" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" name="prenom" class="form-control" required>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly required>
          </div>
        </div>
        <div class="form-group">
          <label for="telephone">Téléphone</label>
          <div class="input-with-icon">
            <i class="fas fa-phone"></i>
            <input type="text" name="telephone" class="form-control" min="1" placeholder="0612345678"  required>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="nombre_places">Nombre de places (max: <?= htmlspecialchars($trip['places_disponibles']) ?>)</label>
          <div class="input-with-icon">
            <i class="fas fa-chair"></i>
            <input type="number" name="nombre_places" class="form-control" min="1" 
                   max="<?= htmlspecialchars($trip['places_disponibles']) ?>" value="1" required>
          </div>
        </div>
      </div>

      <button type="submit" name="confirmer" class="confirm-button">
        <span class="button-text">Confirmer Réservation</span>
        <span class="button-icon">
          <i class="fas fa-check-circle"></i>
        </span>
        <span class="button-effect"></span>
      </button>
    </form>
  </div>
</section>

<style>
/* Base styles */
.reservation-section {
  background-image: linear-gradient(to left, #BDBBBE 0%, #9D9EA3 100%), radial-gradient(88% 271%, rgba(255, 255, 255, 0.25) 0%, rgba(254, 254, 254, 0.25) 1%, rgba(0, 0, 0, 0.25) 100%), radial-gradient(50% 100%, rgba(255, 255, 255, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%);
 background-blend-mode: normal, lighten, soft-light;
  padding: 40px 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.reservation-container {
  background: white;
  width: 90%;
  max-width: 900px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding: 30px;
  margin: 40px auto;
  position: relative;
  overflow: hidden;
}

.reservation-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 8px;
  background: linear-gradient(90deg, #fdd120 0%, #152329 100%);
}

.reservation-header {
  text-align: center;
  margin-bottom: 30px;
  position: relative;
}

.reservation-header h2 {
  font-size: 32px;
  color: #152329;
  margin-bottom: 10px;
  font-weight: 700;
  position: relative;
  display: inline-block;
}

.header-decoration {
  width: 80px;
  height: 4px;
  background: #fdd120;
  margin: 0 auto;
  border-radius: 2px;
}

/* Trip card styles */
.trip-card {
  background: #f9f9f9;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 30px;
  border: 1px solid #eee;
  position: relative;
  overflow: hidden;
}

.trip-card::after {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 100px;
  height: 100px;
  background: #fdd120;
  opacity: 0.1;
  border-radius: 50%;
  transform: translate(50px, -50px);
}

.trip-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px;
}

.detail-item {
  display: flex;
  align-items: center;
  padding: 8px 0;
}

.detail-item i {
  color: #fdd120;
  margin-right: 10px;
  width: 20px;
  text-align: center;
}

.star-rating {
  color: #ffc107;
  font-size: 18px;
  letter-spacing: 2px;
  position: relative;
  display: inline-block;
}

.empty-star {
  color: #ddd;
}

.partial-star {
  position: absolute;
  top: 0;
  left: 0;
  overflow: hidden;
}

.rating-text {
  font-size: 14px;
  color: #555;
  margin-left: 5px;
}

.rating-count {
  font-size: 13px;
  color: #888;
  margin-left: 3px;
}

.no-rating {
  color: #888;
  font-size: 14px;
}

/* Brand animation */
.brand-animation {
  text-align: center;
  margin-top: 20px;
  position: relative;
}

.brand-text {
  font-size: 36px;
  font-weight: 700;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #f5d742 0%, #f5a623 50%, #f5d742 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: relative;
  display: inline-block;
  z-index: 1;
  letter-spacing: 1px;
  padding: 0 5px;
  animation: shine 3s infinite alternate;
}

/* Optional: Add a subtle underline for elegance */
.brand-text::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -5px;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #f5a623, transparent);
  opacity: 0.7;
}

/* Shimmer animation */
@keyframes shine {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 100% 50%;
  }
}
.input-with-icon {
  position: relative;
  width: 100%;
}

.input-with-icon .fas {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #fdd120; /* Adjust icon color */
  pointer-events: none; /* Prevents clicking on the icon */
}

.input-with-icon input {
  width: 100%;
  padding-left: 35px !important; /* Ensures text doesn't overlap the icon */
  box-sizing: border-box;
}

/* Optional: Style for better UX */
.form-control {
  height: 40px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

.form-control:focus {
  border-color: #fdd120; /* Matching your gradient theme */
  outline: none;
  box-shadow: 0 0 5px rgba(253, 209, 32, 0.3);
}

.brand-highlight {
  position: absolute;
  bottom: 5px;
  left: 0;
  width: 100%;
  height: 12px;
  background: rgba(253, 210, 32, 0.3);
  z-index: 0;
  animation: highlightPulse 3s infinite;
}

@keyframes highlightPulse {
  0% { width: 0%; left: 50%; }
  50% { width: 100%; left: 0; }
  100% { width: 0%; left: 0; }
}

/* Form styles */
.reservation-form {
  margin-top: 20px;
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  flex: 1;
  min-width: 250px;
}

label {
  display: block;
  margin-bottom: 8px;
  color: #152329;
  font-weight: 600;
  font-size: 14px;
}

.input-with-icon {
  position: relative;
}

.input-with-icon i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #fdd120;
}

.form-control {
  width: 100%;
  padding: 12px 15px 12px 40px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s;
  background: #fafafa;
}

.form-control:focus {
  border-color: #fdd120;
  box-shadow: 0 0 0 3px rgba(253, 210, 32, 0.2);
  background: white;
  outline: none;
}

/* Button styles */
.confirm-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(90deg, #fdd120 0%, #e6c01d 100%);
  color: #152329;
  border: none;
  padding: 15px 30px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  overflow: hidden;
  position: relative;
  transition: all 0.3s;
  width: 100%;
  margin-top: 10px;
  box-shadow: 0 4px 15px rgba(253, 210, 32, 0.3);
}

.confirm-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(253, 210, 32, 0.4);
}

.confirm-button:active {
  transform: translateY(0);
}

.button-text {
  position: relative;
  z-index: 1;
}

.button-icon {
  margin-left: 10px;
  font-size: 18px;
  position: relative;
  z-index: 1;
  transition: all 0.3s;
}

.confirm-button:hover .button-icon {
  transform: rotate(360deg);
}

.button-effect {
  position: absolute;
  width: 100%;
  height: 200%;
  background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.3) 100%);
  top: -50%;
  left: -100%;
  transform: rotate(45deg);
  transition: all 0.5s;
}

.confirm-button:hover .button-effect {
  left: 100%;
}

/* Alert styles */
.alert-danger {
  background: #fff3f3;
  color: #d32f2f;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  border-left: 4px solid #d32f2f;
}

/* Responsive styles */
@media (max-width: 768px) {
  .reservation-container {
    width: 95%;
    padding: 20px;
  }
  
  .reservation-header h2 {
    font-size: 26px;
  }
  
  .trip-details {
    grid-template-columns: 1fr;
  }
  
  .brand-text {
    font-size: 28px;
  }
  
  .form-group {
    min-width: 100%;
  }
}
</style>

<?php
include('../../templates/footer.php'); ?>