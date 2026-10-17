<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Email et mot de passe sont requis";
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        $stmt = $pdo->prepare("SELECT users.* FROM users 
                              JOIN admins ON users.id = admins.user_id
                              WHERE users.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['admin'] = true;
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Identifiants incorrects ou pas de droits admin";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Agharas</title>
    <link rel="shortcut icon" href="../pages/images/LogoFv.png" type="image/x-icon"> 

    
</head>
<body>

    <div class="login-container">
        <img src="../pages/images/Logo.png" alt="Agharas" class="logo">
        <h1>Connexion <br> Admin</h1>
        
        <?php if (isset($_GET['logout'])): ?>
            <div class="success">Vous avez été déconnecté avec succès</div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe: </label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
            
            <div class="links">
                <a href="..\pages\forgetpassword.php">Mot de passe oublié ?</a><br>
            </div>
        </form>
    </div>
</div>


    <style>
        :root {
            --primary:#152329 ;
            --accent: #fdd120;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background:  /*   linear-gradient(rgba(47, 47, 50, 0.7)),      */
             url('../pages/images/bg_3.jpg') no-repeat ;height: 1150px  center center fixed;
             border-image : fill 0 linear-gradient(#0003, #000);
            background-size: cover !important;
            margin: 0;
            padding: 0;
            position: flex;

            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            
        }
        
        
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        content: '';
        opacity: .3;
        background: #000000;
        height: 850px; }
       
         
         
       
           
          

        
        .login-container  {
            background: /*rgba(255, 255, 255, 0.95);*/ #fff;
            border: 3px solid #ffd421;
            border-radius: 10px;
            box-shadow: 0 0px 15px #ffd421 , 0px 0px 15px #ffd421 inset ;
            width: 100%;
            max-width: 300px;
            padding: 40px;
            text-align: center;
        margin: 44px auto;
        margin-left: 160px;
        display: flex;
    flex-wrap: wrap;


        }
        
        .logo {
            width: 120px;
        }
        
        h1 {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600px;
            padding-top: 20px ;
            word-wrap: break-word;
            white-space: pre-wrap;
            text-align: ;
        }
        
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 500;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(21, 35, 33, 0.1);
        }
        
        .btn-login {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: #0a1615;
        }
        
        .links {
            margin-top: 20px;
            font-size: 14px;
        }
        
        .links a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .links a:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        
        .error {
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.1);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success {
            color: #27ae60;
            background: rgba(39, 174, 96, 0.1);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</body>
</html>