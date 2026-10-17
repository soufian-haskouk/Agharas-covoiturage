<?php
session_start();
include('../../templates/header.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}
// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
 //   header("Location: ../Login.php");
 ///   exit();                                                                                  
//}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get user's reservations
$stmt = $pdo->prepare("SELECT r.*, t.depart, t.arrive, t.date_trajet, t.heure_depart, t.prix 
                      FROM reservations r
                      JOIN trajet t ON r.trajet_id = t.id
                      WHERE r.email = ?
                      ORDER BY r.date_reservation DESC");
$stmt->execute([$_SESSION['user_email']]);
$reservations = $stmt->fetchAll();
?>

<section class="ftco-section">
    <div class="containerrr">
        <h2>Mes Réservations :</h2>
        
        <?php if (empty($reservations)): ?>
            <div class="alert alert-info">
                Vous n'avez aucune réservation pour le moment.
            </div>
        <?php else: ?>
            <div class="reservations-list">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card">
                        <div class="reservation-info">
                            <h4>Trajet: <?= htmlspecialchars($reservation['depart']) ?> → <?= htmlspecialchars($reservation['arrive']) ?></h4>
                            <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($reservation['date_trajet'])) ?></p>
                            <p><strong>Heure:</strong> <?= htmlspecialchars($reservation['heure_depart']) ?></p>
                            <p><strong>Places réservées:</strong> <?= htmlspecialchars($reservation['nombre_places']) ?></p>
                            <p><strong>Prix total:</strong> <?= htmlspecialchars($reservation['nombre_places'] * $reservation['prix']) ?> DH</p>
                            <p><strong>Date de réservation:</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_reservation'])) ?></p>
                        </div>
                        <div class="reservation-actions">
                            <form method="POST" action="annuler_reservation.php">
                                <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                <input type="hidden" name="trajet_id" value="<?= $reservation['trajet_id'] ?>">
                                <input type="hidden" name="nombre_places" value="<?= $reservation['nombre_places'] ?>">
                               
                                <!-- From Uiverse.io by cssbuttons-io --> 

                                <button type="submit" class="noselect"><span class="text">annuler</span><span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                                    </svg></span></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .containerrr {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
   .containerrr h2 {
  
  text-align: center;
  margin-bottom: 28px;
  margin-top: 15px;
  font-size: 38px;
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
  color: #152321;
  letter-spacing: 1.5px;
  background: linear-gradient(90deg, #fdd120 0%, #d4b221 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  text-shadow: 0 2px 8px rgba(212, 178, 33, 0.10), 0 1px 0 black;
  border-radius: 12px;
  padding: 10px 0 8px 0;
  box-shadow: 0 2px 8px rgba(212, 178, 33, 0.08);
}
    
    .reservations-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .reservation-card {
        background: #fcf9b4;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .reservation-info {
        flex: 2;
    }
    
    .reservation-info h4 {
        margin: 0 0 10px 0;
        color:#152329 ;
    }
    
    .reservation-info p {
        margin: 5px 0;
        color: #555;
    }
    
    .reservation-actions {
        flex: 1;
        text-align: right;
         margin-right: -100px ;

    }
    
    
    
    .alert {
        padding: 15px;
        border-radius: 5px;
        text-align: center;
        margin: 20px 0;
    }
    
    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
    }

/* From Uiverse.io by cssbuttons-io */ 
button {
            cursor: pointer;

 width: 150px;
 height: 50px;
 cursor: pointer;
 display: flex;
 align-items: center;
 background: red;
 border: none;
 border-radius: 5px;
 box-shadow: 1px 1px 3px rgba(0,0,0,0.15);
 background: #e62222;
}

button, button span {
 transition: 200ms;
}

button .text {
 transform: translateX(35px);
 color: white;
 font-weight: bold;
}

button .icon {
 position: absolute;
 border-left: 1px solid #c41b1b;
 transform: translateX(110px);
 height: 40px;
 width: 40px;
 display: flex;
 align-items: center;
 justify-content: center;
}

button svg {
 width: 15px;
 fill: #eee;
}

button:hover {
 background: #ff3636;
}

button:hover .text {
 color: transparent;
}

button:hover .icon {
 width: 150px;
 border-left: none;
 transform: translateX(0);
}

button:focus {
 outline: none;
}

button:active .icon svg {
 transform: scale(0.8);
}


</style>

<?php include('../../templates/footer.php'); ?>