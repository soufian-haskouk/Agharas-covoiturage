
<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$success = '';

// Verify token
if (!isset($_GET['token'])) {
    die("Lien de réinitialisation invalide");
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['reset_expires']) < time()) {
    die("Lien de réinitialisation invalide ou expiré");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);
        
        $success = "Votre mot de passe a été réinitialisé avec succès!";
    }
}
?>

<style>
  
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
                        <h2>Réinitialiser le mot de passe</h2>
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
                                    <p>Vous pouvez maintenant <a href="login.php">vous connecter</a> avec votre nouveau mot de passe.</p>
                                </div>
                            <?php else: ?>
                                <form method="POST" class="search-form">
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>

                                    <button type="submit" class="btnn">Réinitialiser le mot de passe</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>




<?php include('../templates/footer.php'); ?>




<style>
    /* Registration Form Styling */
    .registration-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .search-container {
        background: #fdd120;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-top: 20px;
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
}
    /* Alert Messages */
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .search-container {
            padding: 20px;
        }
    }
</style>