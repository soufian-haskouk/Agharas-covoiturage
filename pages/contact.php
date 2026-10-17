<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agharas_bd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
$success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        $success = true;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AGHARAS</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/LogoFv.png" type="image/x-icon"> 
    <link rel="stylesheet" href="css\lib\fontawesome\webfonts\bootstrap.min.css" />
    <link href="css\lib\fontawesome\webfonts\all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>
  <body>
   
  <!-- Navigation  -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="images/Logo.png" alt="Agharas"  class="logo-icon" height="40">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">ACCUEIL</a></li>
          <li class="nav-item"><a class="nav-link " href="sevice.php">SERVICES</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">CONTACT</a></li>
        </ul>
        <div class="d-flex">
          <a href="Login.php" class="btn btn-warning ms-3">Connexion</a>
        </div>
      </div>
    </div>
  </nav>

    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg')" data-stellar-background-ratio="0.5">
        
    <div class="overlay"></div>
        <div class="container">
            <div class="col-md-9  ">
                <span class="acc">
                  <a href="index.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
                </span>
                <?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
  Votre message a été envoyé avec succès. Nous vous contacterons bientôt!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeSuccessAlert()">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?>
        
              <h1 class="cn">Contactez-nous</h1>
            </div>
        </div>
      </section>

    <section class="ftco-section contact-section" style="margin-top: 40px;">
  <div class="container">
    <div class="row d-flex mb-5 contact-info" style="margin-top: 20px;">
      <div class="col-md-4">
        <div class="row mb-5">
          <div class="col-md-12">
            <div class="border w-100 p-4 rounded mb-2 d-flex" style="margin-top: 20px;">
              <div class="icon mr-3">
                <span class="fas fa-map-marker-alt"></span>
              </div>
              <p>
                <span>Address:</span> City university, ER-RACHIDIA-MOROCCO
              </p>
            </div>
          </div>
          <div class="col-md-12">
            <div class="border w-100 p-4 rounded mb-2 d-flex" style="margin-top: 20px;">
              <div class="icon mr-3">
                <span class="fas fa-phone-alt"></span>
              </div>
              <p>
                <span>Phone:</span>
                <a href="tel://+212637259733">+212 637259733</a>
              </p>
            </div>
          </div>
          <div class="col-md-12">
            <div class="border w-100 p-4 rounded mb-2 d-flex" style="margin-top: 20px;">
              <div class="icon mr-3">
                <span class="fas fa-envelope"></span>
              </div>
              <p>
                <span>Email:</span>
                <a href="mailto:info@Agharas.com">Agharas@gmail.com</a>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8 block-9 mb-md-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="bg-light p-5 contact-form">

              <div class="inputBox">
              <div class="">
                <input  name="name" type="text"  required="" type="text">
                <span> Nom et Prenom</span>
              </div>
              </div>
              
             
              
               <div class="inputBox">
              <div class="">
                <input name="email" type="email"  required="" type="text">
                <span> Votre Email</span>
              </div>
              </div>
              
              <div class="inputBox">
                <!-- From Uiverse.io by saidbl1 --> 
              <div class="">
                <input name="subject" required="" type="text">
                <span> subject</span>
              </div>
                
              </div>
              <div class="form-group">
                <textarea
                  name="message"
                  id="message"
                  cols="30"
                  rows="7"
                  class="form-control"
                  placeholder="Message"
                  required
                ></textarea>
              </div>
              <div class="form-group">
                <input
                  type="submit"
                  value="Envoyer le message"
                  class="btn btn-gold py-3 px-5"
                />
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    





<footer class="ftco-footer ftco-bg-dark ftco-section">
<div class="container">
  <div class="row mb-5">
    <div class="col-md">
      <div class="ftco-footer-widget mb-4">
        <h2>
        <img src="images/Logo.png" alt="AGHARAS Logo"  class="logo-icon" style="height: 80px;">

        </h2>
        <p>Roulez malin, covoiturez entre voisins ,

          Moins de voitures, plus de partage  bienvenue sur Agharas.</p>
          <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
            <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-twitter"></span></a></li>
            <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-facebook-f"></span></a></li>
            <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-instagram"></span></a></li>
            <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-linkedin-in"></span></a></li> <!-- LinkedIn هنا زدت -->

          </ul>
          
      </div>
    </div>
    <div class="col-md">
      <div class="ftco-footer-widget mb-4 ml-md-5">
        <h2 class="ftco-heading-2">Information</h2>
        <ul class="list-unstyled">
          <li><a href="#" class="py-2 d-block">A propos</a></li>
          <li><a href="#" class="py-2 d-block">Services</a></li>
          <li><a href="#" class="py-2 d-block">Conditions générales</a></li>
          <li><a href="#" class="py-2 d-block">garantie du meilleur prix</a></li>
          <li><a href="#" class="py-2 d-block">Politique &amp; de confidentialité des Cookies</a></li>
        </ul>
      </div>
    </div>
    <div class="col-md">
       <div class="ftco-footer-widget mb-4">
        <h2 class="ftco-heading-2">Support du client</h2>
        <ul class="list-unstyled">
          <li><a href="#" class="py-2 d-block">FAQ</a></li>
          <li><a href="#" class="py-2 d-block">Option de paiement</a></li>
          <li><a href="#" class="py-2 d-block">Conseil de réservations </a></li>
          <li><a href="#" class="py-2 d-block">Comment ça marche</a></li>
          <li><a href="#" class="py-2 d-block">Contactez-nous</a></li>
        </ul>
      </div>
    </div>
    <div class="col-md">
      <div class="ftco-footer-widget mb-4">
        <h2 class="ftco-heading-2">Avez vous des questions?</h2>
        <div class="block-23 mb-3">
          <ul>
            <li><span class="fas fa-map-marker-alt"></span><span class="text"> city university, ERRACHIDIA</span></li>
            <li><span class="fas fa-phone-alt"></span><span class="text">+212 637259733</span></li>
            <li><a href="#"><span class="fas fa-envelope"></span><span class="text">Agharas@gmail.com</span></a></li>
          </ul>
          
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">

      <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart color-danger" aria-hidden="true"></i> by <a href="US/haskouk.html" target="_blank">anas_soufian</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
    </div>
  </div>
</div>
</footer>



<!-- loader
 <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>   

 -->
    <script src="js\bootstrap.bundle.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
// Fermer l'alerte et effacer le message du formulaire
function closeSuccessAlert() {
  var alert = document.getElementById('success-alert');
  if(alert) alert.style.display = 'none';
  // Efface le champ message
  var messageField = document.getElementById('message');
  if(messageField) messageField.value = '';
}
</script>
<style>
/* Ajout du style bouton or */
.btn-gold {
  background: linear-gradient(90deg, #ffe082 0%, #fdd21f 100%);
  color: #222;
  border: none;
  font-weight: 600;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(191, 161, 74, 0.10);
}
.btn-gold:hover, .btn-gold:focus {
  background: linear-gradient(90deg, #bfa14a 0%, #fdd21f 100%);
  color: #fff;
  box-shadow: 0 4px 16px rgba(191, 161, 74, 0.18);
}

/* From Uiverse.io by saidbl1 */ 
.inputBox {
  position: relative;
  padaing: 10px 0;
  margin: 10px 0;
}

.inputBox input {
  padding: 15px 20px;
  outline: none;
  background: transparent;
  border-radius: 5px;
  color: black;
  border: 1px solid#ee8456;
  font-size: 1em;
}

.inputBox span {
  position: absolute;
  left: 0;
  padding: 15px 20px;
  pointer-events: none;
  font-size: 1em;
  transition: 0.4s cubic-bezier(0.05, 0.81, 0, 0.93);
  color: #ee8456;
  letter-spacing: 0.1em;
}

.inputBox input:focus ~ span,
.inputBox input:valid ~ span {
  font-size: 0.7em;
  transform: translateX(14px) translateY(-7.5px);
  padding: 0 5px;
  border-radius: 2px;
  background: #212121;
  letter-spacing: 0em;
  border: 1px solid #ee8456;
}

.ftco-section.contact-section {
  margin-top: 40px;
}
.contact-info .border {
  margin-top: 20px;
}
</style>
  </body>
</html>
