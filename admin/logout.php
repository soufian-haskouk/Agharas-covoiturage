<?php
session_start();

// Check if confirmation was received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php?logout=success');
    exit;
}

// If not confirmed, show confirmation page
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmer la déconnexion</title>
    <style>
        .logout-container {
            max-width: 500px;
            margin: 170px auto;
            padding: 20px;
            text-align: center;
            background: white;
            color:#152329 ;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: #e74c3c;
            color: white;
        }
        .btn-cancel {
            background-color:rgba(8, 77, 123, 0.86);
            color: white;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>Êtes-vous sûr de vouloir vous déconnecter ?</h2>
        <form method="POST">
            <button type="submit" name="confirm_logout" class="btn btn-confirm">
                Oui, déconnecter
            </button>
            <a href="dashboard.php" class="btn btn-cancel">
                Non, annuler
            </a>
        </form>
    </div>
</body>
</html>