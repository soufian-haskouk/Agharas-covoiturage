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
                'prix' => $trip['prix']
            ];

            // Redirect to confirmation page
            header("Location: confirmation.php");
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

// Get trip informatio
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ?");
$stmt->execute([$id]);
$trip = $stmt->fetch();

if (!$trip) {
    die("Trajet non trouvé.");
}

// Prevent user from reserving their own trip
if (isset($trip['conducteur_id'], $_SESSION['user_id']) && $trip['conducteur_id'] == $_SESSION['user_id']) {
    echo '<div class="alert alert-danger" style=" margin-top:20px; padding: 50px;text-align:center;">OOPS !! Vous ne pouvez pas réserver votre propre trajet .</div>';
    include('../../templates/footer.php');
    exit();
}
?>
                               <!-- HTML              -->
<section class="ftco-section">

  <div class="container">
    <h2>Réserver votre trajet</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="trip-section">
    <div class="trip-info">
      <p><strong>Départ:</strong> <?= htmlspecialchars($trip['depart']) ?></p>
      <p><strong>Arrivée:</strong> <?= htmlspecialchars($trip['arrive']) ?></p>
      <p><strong>Date:</strong> 
        <?php 
          $date_trip = new DateTime($trip['date_trajet']);
          echo $date_trip->format('d/m/Y');
        ?>
      </p>
      <p><strong>Heure:</strong> <?= htmlspecialchars($trip['heure_depart']) ?></p>
      <p><strong>Prix:</strong> <?= htmlspecialchars($trip['prix']) ?> DH</p>
      <p><strong>Places disponibles:</strong> <?= htmlspecialchars($trip['places_disponibles']) ?></p>
    </div>

    <!-- From Uiverse.io by Spacious74 --> 
  <!-- From Uiverse.io by RodolpheANDRIEUX --> 
    <!-- From Uiverse.io by satyamchaudharydev --> 
      <button class="button" data-text="Awesome">
          <span class="actual-text">&nbsp;AGHARAS&nbsp;</span>
          <span aria-hidden="true" class="hover-text">&nbsp;AGHARAS&nbsp;</span>
      </button>

    </div>

     


    <form method="POST" action="">
      <input type="hidden" name="trajet_id" value="<?= $trip['id'] ?>">

      <div class="form-row">
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" name="prenom" class="form-control" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly required>
        </div>
        <div class="form-group">
          <label for="telephone">Téléphone</label>
          <input type="text" name="telephone" class="form-control" required>
        </div>
      </div>

    <div class="form-row">

      <div class="form-group">
        <label for="nombre_places">Nombre de places (max: <?= htmlspecialchars($trip['places_disponibles']) ?>)</label>
        <input type="number" name="nombre_places" class="form-control" min="1" 
               max="<?= htmlspecialchars($trip['places_disponibles']) ?>" value="1" required>
      </div>

      <div class="form-group">

      <button type="submit" name="confirmer" class="  animated-button"> 
        <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
    <path
      d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
    ></path>
  </svg>
  <span class="text">Confirmer Réservation</span>
  <span class="circle"></span>
  <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
    <path
      d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
    ></path>
  </svg></button>
      </div>
    </div>


    </form>
  </div>
</section>

<!-- Rest of your HTML and styles -->
<style>
  .trip-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 20px;
  margin-right:5%;
  margin: 0px 5% 20px 0px;
  
}
/* From Uiverse.io by satyamchaudharydev */ 
/* === removing default button style ===*/
.button {
  margin: 0;
  height: 50%;
  background: transparent;
  padding: 0;
  border: none;
  cursor: pointer;
}

/* button styling */
.button {
  --border-right: 6px;
  --text-stroke-color: black;
  --animation-color: gold;
  --fs-size: 4em;
  margin-top: 50px;
  letter-spacing: 3px;
  text-decoration: none;
  font-size: var(--fs-size);
  font-family: "Arial";
  position: relative;
  text-transform: uppercase;
  color: transparent;
  -webkit-text-stroke: 1px var(--text-stroke-color);
}
/* this is the text, when you hover on button */
.hover-text {
  position: absolute;
  box-sizing: border-box;
  content: attr(data-text);
  color: var(--animation-color);
  width: 0%;
  inset: 0;
  border-right: var(--border-right) solid var(--animation-color);
  overflow: hidden;
  transition: 0.5s;
  -webkit-text-stroke: 1px var(--animation-color);
}
/* hover */
.button:hover .hover-text {
  width: 100%;
  filter: drop-shadow(0 0 23px var(--animation-color))
}

     /* Professional, modern reservation form styling with accent color and image */
.containerr {
  background: #fff;
  max-width: 600px;
  margin: 60px auto;
  box-shadow: 0 4px 24px rgba(212, 178, 33, 0.10);
  border-radius: 16px;
  padding: 32px 24px 24px 24px;
  position: relative;
  overflow: hidden;
}
.containerr::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 160px;
  background: url('../images/bg_3.jpg') center/cover no-repeat;
  border-radius: 16px 16px 0 0;
  z-index: 1;
  opacity: 0.25;
}

h2 {
  text-align: center;
  margin-bottom: 28px;
  margin-top: 15px;
  font-size: 35px;
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
  color: #282828;
  letter-spacing: 1px;
}

.trip-info {
  width: 50%;  height: 10%;

  max-width: 100%;
  margin: 0 0 24px 0;
  box-shadow: 0 2px 8px rgba(212, 178, 33, 0.08);
  border: 1.5px solid #d4b221;
  padding: 18px 20px;
  border-radius: 10px;
  background-color: #fffbe6;
}
.trip-info p {
  margin: 8px 0;
  font-size: 16px;
  border: center;
  font-weight: 500;
  color: #333;
}

.form-row {
  display: flex;
  gap: 18px;
  margin-bottom: 12px;
}
.form-group {
  flex: 1;
  margin-bottom: 10px;
  text-align: left;
  padding: 0;
}
label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  color: #152329;
  font-size: 15px;
}
input[type="text"],
input[type="email"],
input[type="number"] {
  width: 100%;
  padding: 10px 12px;
  border: 1.5px solid #d4b221;
  border-radius: 6px;
  box-sizing: border-box;
  font-size: 15px;
  background: #fff;
  transition: border 0.2s;
}
input[type="text"]:focus,
input[type="email"]:focus,
input[type="number"]:focus {
  border-color: #bfa01d;
  outline: none;
}

.alert-danger {
  background: #fffbe6;
  color: #856404;
  border: 1.5px solid #d4b221;
  border-radius: 8px;
  padding: 10px 20px;
  margin-bottom: 18px;
  text-align: center;
}
@media (max-width: 600px) {
  .containerr {
    padding: 12px 4px 16px 4px;
  }
  .form-row {
    flex-direction: column;
    gap: 0;
  }
}

/* From Uiverse.io by gharsh11032000 */ 
.animated-button {
  position: relative;
  margin-top: 20px;
  width: 50%;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-left: 25%;
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  gap: 4px;
  padding: 10px 36px;
  border: 4px solid;
  border-color: transparent;
  font-size: 16px;
  background-color: inherit;
  border-radius: 100px;
  font-weight: 600;
  color: #ff7a00;
  box-shadow: 0 0 0 2px #ff7a00;
  cursor: pointer;
  overflow: hidden;
  transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.animated-button svg {
  position: absolute;
  width: 24px;
  fill: #152329;
  z-index: 9;
  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
}

.animated-button .arr-1 {
  right: 16px;
}

.animated-button .arr-2 {
  left: -25%;
}

.animated-button .circle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 20px;
  height: 20px;
  background-color: #fdd21f;
  border-radius: 50%;
  opacity: 0;
  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
}

.animated-button .text {
  position: relative;
  z-index: 1;
  transform: translateX(-12px);
  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
}

.animated-button:hover {
  box-shadow: 0 0 0 12px transparent;
  color: #212121;
  border-radius: 12px;
}

.animated-button:hover .arr-1 {
  right: -25%;
}

.animated-button:hover .arr-2 {
  left: 16px;
}

.animated-button:hover .text {
  transform: translateX(12px);
}

.animated-button:hover svg {
  fill: #212121;
}

.animated-button:active {
  scale: 0.95;
  box-shadow: 0 0 0 4px #fdd21f;
}

.animated-button:hover .circle {
  width: 220px;
  height: 220px;
  opacity: 1;
}

.modal-message p {
        color: #222;
        font-size: 18px;
        margin-bottom: 24px;
        text-align: left;
    }

    </style>

    <?php
include('../../templates/footer.php'); ?>



