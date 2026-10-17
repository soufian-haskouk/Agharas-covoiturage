<?php
session_start();
include('../../templates/header.php');

// Check if reservation data exists
if (!isset($_SESSION['reservation_data'])) {
    header("Location: recherche.php");
    exit();
}

$reservation = $_SESSION['reservation_data'];
?>

<section class="ftco-section">
  <div class="ccon">
    <h2>Réservation Confirmée:</h2>
    
    <div class="alert alert-success">
      Votre réservat a été confirmée avec succès!
    </div>
    
     <div class="form-row">
         <div class="form-group">

            <h3>Détails du réservation:</h3>
            <p><strong>Référence:</strong> #<?= htmlspecialchars($reservation['reservation_id']) ?></p>
            <p><strong>Nom:</strong> <?= htmlspecialchars($reservation['nom']) ?> <?= htmlspecialchars($reservation['prenom']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($reservation['email']) ?></p>
            <p><strong>Téléphone:</strong> <?= htmlspecialchars($reservation['telephone']) ?></p>
            <p><strong>Nombre de places:</strong> <?= htmlspecialchars($reservation['nombre_places']) ?></p>
         </div>
         <div class="form-group">

            <h3>Détails du trajet:</h3>
            <p><strong>Départ:</strong> <?= htmlspecialchars($reservation['depart']) ?></p>
            <p><strong>Arrivée:</strong> <?= htmlspecialchars($reservation['arrive']) ?></p>
            <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($reservation['date_trajet'])) ?></p>
            <p><strong>Heure:</strong> <?= htmlspecialchars($reservation['heure_depart']) ?></p>
            <p><strong>Prix total:</strong> <?= htmlspecialchars($reservation['prix'] * $reservation['nombre_places']) ?> DH</p>
         </div>
    </div>

    
    <div class="actions">
      <!-- From Uiverse.io by Niranjan360 --> 
      <a href="mes_reservations.php" class="button">
        <span>Les Réservations</span>
</a>

      <!-- From Uiverse.io by andrew-demchenk0 --> 
      
      <a href="generate_pdf.php" class="buttoon" type="button">
        <span class="buttoon__text">Télécharger Reçu  </span>
        <span class="buttoon__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg"><path d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z"></path><path d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z"></path><path d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z"></path></svg></span>
      </a>
    </div>
  </div>
</section>
<style>

/* From Uiverse.io by Niranjan360 */ 
.button {
  padding:50px 0px 50px 50px ;
  cursor: pointer;
  background-color: white;
  padding: 8px 25px;
  border: solid 2px gold;
  border-radius: 20px;
  position: relative;
  overflow: hidden;
  transition: 0.1s linear 0.1s;
    width: 250px;

}
.button span {
  color: black;
font-weight: bold;  
  letter-spacing: 2px;
  position: relative;
  z-index: 2;
  transition: 0.2s linear 0.1s;
}
.button::after {
  display: block;
  content: "";
  background-color: gold;
  width: 580px;
  height: 300px;
  border-radius: 50%;
  position: absolute;
  top: 100%;
  transform: translateX(-40%);
  transition: 0.2s linear 0.1s;
  z-index: 1;
}
.button:hover {
  box-shadow: 0px 0px 20px gold;
}
.button:hover::after {
  top: -50%;
}
.button:hover span {
  color: black;
  letter-spacing: 4px;
}

/* Add a beautiful accent color to the confirmation page */
.reservation-details {
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
}
.alert-success {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50%;
  margin: 0 auto 20px auto;
  padding: 10px 20px;
  border-radius: 8px;
  box-sizing: border-box;
}
.form-row {
  display: flex;
  flex-direction: row;
  gap: 30px;
  margin-bottom: 15px;
}
.form-group {
  flex: 1;
  margin-bottom: 10px;
  background: #f4f4f4;
  border: 2px solid #263f44;
  border-radius: 10px;
  padding: 20px 18px 18px 18px;
  box-shadow: 0 2px 12px rgba(212, 178, 33, 0.08);
}
.form-group h3 {
  color: #464058;
  margin-bottom: 15px;
    font-weight: bold;
}
.form-group p strong {
  color: #d4b221;
}
.alert-success {
  justify-content: center;
  width: 50%;
align-items:center;  padding: 10px 20px;

}
.actions {
  display: flex;

  gap: 10px;
  margin-top: 20px ;
  justify-content: center !important;
  align-items: center;
  
}

.ccon {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 24px rgba(212, 178, 33, 0.10);
  padding: 32px 24px 24px 24px;
  margin-top: 40px;
}
h2 {
  
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
</style>

<?php include('../../templates/footer.php'); ?>