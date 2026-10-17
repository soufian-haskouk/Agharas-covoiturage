<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="shortcut icon" href="images/LogoFv.png" type="image/x-icon"> 

  <meat name="theme-color" content="#f1c81e">
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />    
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/LogoFv.png"  type="image/x-icon">

  <title>AGHARAS</title>

  <!-- slider stylesheet -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <!-- font awesome style -->
  
  <link rel="stylesheet" href="css\lib\fontawesome\webfonts\bootstrap.min.css" />

  <link href="css\lib\fontawesome\webfonts\all.min.css" rel="stylesheet" />
  
  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Dosis:500|Poppins:400,600,700&display=swap" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
   
  <div class="hero_area">
    <!-- header section starts -->
     
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php">
            <img src="images/Logo.png" alt="AGHARAS" class="logo-icon">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
          </button>
          <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Accueil</a>
              </li>
            </ul>
            <div class="user_option">
             <!-- <a href="#" class="search-icon">
                <i class="fa fa-search"></i>
                Recherche
              </a> -->
              <a href="contact.php" class="announce-icon">
                <i class="fa fa-envelope"></i>
                Contact
              </a>
              <a href="sevice.php" class="cart-icon">
                <i class="fa fa-briefcase"></i>
                Services
              </a>
              <a href="Login.php" class="person-icon">
              <i class="fa fa-user"></i>
              Connexion
            </a>
            
           
            </div>
          </div>
        </nav>
      </div>
    </header>

<?php
// Initialisation
$errors = [];
$success = '';

// CONNEXION BDD
try {
    $pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. VALIDATION reCAPTCHA
    if(empty($_POST['g-recaptcha-response'])) {
        $errors[] = "Veuillez compléter le reCAPTCHA";
    } else {
        $secret = "6LfZFXArAAAAAOz208hjbW1Ys3Bd-cvUUAVacv2D"; // Clé secrète de test
        $response = $_POST['g-recaptcha-response'];
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $captcha = json_decode($verify);
        
        if(!$captcha->success) {
            $errors[] = "Échec de la vérification reCAPTCHA";
        }
    }

    // 2. SI reCAPTCHA OK, VALIDER LE FORMULAIRE
    if(empty($errors)) {
        // Nettoyage des données
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $telephone = preg_replace('/[^0-9]/', '', $_POST['telephone']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validation des champs
        if(empty($nom)) $errors[] = "Le nom est requis";
        if(empty($prenom)) $errors[] = "Le prénom est requis";
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
        if(strlen($telephone) < 9) $errors[] = "Téléphone invalide";
        if($password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas";

        // Vérification email existant
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) $errors[] = "Cet email est déjà utilisé";

        // INSERTION SI TOUT EST OK
        if(empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, telephone, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $telephone, $hashed_password]);
                
                $success = "Inscription réussie! Vous pouvez maintenant vous connecter.";
                // Réinitialiser les champs après succès
                $_POST = array();
            } catch(PDOException $e) {
                $errors[] = "Erreur lors de l'inscription: " . $e->getMessage();
            }
        }
    }
}
?>

<style>
    
.hero-wrap {
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  position: relative;
  min-height: 100vh;
}

    /* Registration Form Styling */
    .registration-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .search-container {
        background: #fdd120;
        padding: 18px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-top: 20px;
        margin-left: -190px;
        margin-bottom: 50px;
    }
    
    .search-form {
        background: white;
        padding: 25px;
        border-radius: 8px;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }
    
    .form-group {
        padding: 0 15px;
        margin-bottom: 20px;
        width: 100%;
        position: relative;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color:#152329 ;
        box-shadow: 0 0 0 2px rgba(21, 35, 33, 0.2);
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color:#152329 ;
    }
    
    .btnn {
        background:#152329 ;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s;
    }
    
    .btnn:hover {
        background: #2a3a38;
    }
    
    .textcenter {
        text-align: center;
        margin-top: 20px;
    }
    
    .textcenter a {
        color:#152329 ;
        font-weight: 600;
        text-decoration: none;
    }
    
    .textcenter a:hover {
        text-decoration: underline;
        color:rgb(21, 21, 213) !important;
    }
    .sou h2 {
    color: #ffffff;
    font-weight: 700;
    font-size: 40px;
    margin-bottom: -20px;
    text-align: center;
    padding-right: 160px;

}
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Password requirements styling */
    .password-requirements {
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }
    .password-requirements div {
        margin-bottom: 5px;
    }
    .password-requirements .valid {
        color: green;
    }
    .password-requirements .invalid {
        color: red;
    }
    
    /* Password toggle eye */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 42px;
        cursor: pointer;
        color: #777;
        margin-top: -30px;
    }
    .toggle-password:hover {
        color: #333;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .search-container {
            padding: 20px;
            margin-left: 0;
        }
        
        .sou h2 {
            padding-right: 0;
            font-size: 32px;
        }
    }
</style>

<section class="hero-wrap hero-wrap-3 js-fullheight" style="background-image: url('../pages/images/bg_3.jpg')" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="col-md-9">
            <span class="acc">
                <a href="Accueil.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
            </span>

            <section class="ftco-section ftco-search">
                <div class="container">
                    <div class="row sou justify-content-center">
                        <h2>Inscription</h2>
                        <div class="col-md-8 ftco-animate search-container">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?= htmlspecialchars($error) ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <?= htmlspecialchars($success) ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="search-form">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nom">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" placeholder="tapez votre nom" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="prenom">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="tapez votre Prénom" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@gmail.com"  required>
                                    </div>

                                    <div class="form-group col-md-6 ">
                                        <label for="telephone">Téléphone</label>
                                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="0612345678" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <div style="position: relative;">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                                    </div>
                                    <div class="password-requirements">
                                        <div id="lower-upper"><i class="fas fa-times"></i> Lowercase & Uppercase</div>
                                        <div id="number"><i class="fas fa-check"></i> Number (0-9)</div>
                                        <div id="length"><i class="fas fa-check"></i> At least 8 Character</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirmer le mot de passe</label>
                                    <div style="position: relative;">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LfZFXArAAAAAHF4tAKtDEJmYOOWIsBGXQgGt1zy"></div>
                                    <?php if(isset($errors) && in_array("Veuillez compléter le reCAPTCHA", $errors)): ?>
                                        <small class="text-danger">Le reCAPTCHA est requis</small>
                                    <?php endif; ?>
                                </div>

                                <button type="submit" class="btnn">S'inscrire</button>
                            </form>
                            
                             
                            <div class="textcenter mt-3">
                                <p>Déjà un compte? <a href="login.php">Se connecter</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
<script src="https://www.google.com/recaptcha/api.js?hl=fr" async defer></script>

<script>
// Password validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const lowerUpper = document.getElementById('lower-upper');
    const number = document.getElementById('number');
    const length = document.getElementById('length');

    // Check for lowercase and uppercase
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
        lowerUpper.innerHTML = '<i class="fas fa-check"></i> Lowercase & Uppercase';
        lowerUpper.classList.add('valid');
        lowerUpper.classList.remove('invalid');
    } else {
        lowerUpper.innerHTML = '<i class="fas fa-times"></i> Lowercase & Uppercase';
        lowerUpper.classList.add('invalid');
        lowerUpper.classList.remove('valid');
    }

    // Check for number
    if (/\d/.test(password)) {
        number.innerHTML = '<i class="fas fa-check"></i> Number (0-9)';
        number.classList.add('valid');
        number.classList.remove('invalid');
    } else {
        number.innerHTML = '<i class="fas fa-times"></i> Number (0-9)';
        number.classList.add('invalid');
        number.classList.remove('valid');
    }

    // Check length
    if (password.length >= 8) {
        length.innerHTML = '<i class="fas fa-check"></i> At least 8 Character';
        length.classList.add('valid');
        length.classList.remove('invalid');
    } else {
        length.innerHTML = '<i class="fas fa-times"></i> At least 8 Character';
        length.classList.add('invalid');
        length.classList.remove('valid');
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.querySelector(`[onclick="togglePassword('${fieldId}')"]`);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include('footer.php'); ?>