<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer la déconnexion - AGHARAS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .confirm-box {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-logout {
            background: #e74c3c;
            color: white;
            border: 2px solid #e74c3c;
        }
        .btn-logout:hover {
            background:rgba(29, 18, 17, 0.77);
            border-color:rgb(233, 194, 22);
        }
        .btn-cancel {
            background: white;
            color: #2c3e50;
            border: 2px solid #ddd;
        }
        .btn-cancel:hover {
            border-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="confirm-box">
        <h2>Êtes-vous sûr de vouloir vous déconnecter ?</h2>
        <p>Vous devrez vous reconnecter pour accéder à votre compte.</p>
        
        <div class="btn-group">
            <a href="deconnexion.php" class="btn btn-logout">Oui, me déconnecter</a>
            <a href="javascript:history.back()" class="btn btn-cancel">Non, annuler</a>
        </div>
    </div>
</body>
</html>