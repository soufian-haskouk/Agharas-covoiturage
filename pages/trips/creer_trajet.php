<?php
session_start();


$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart = $_POST['depart'];
    $arrive = $_POST['arrive'];
    $date_trajet = $_POST['date_trajet'];
    $heure_depart = $_POST['heure_depart'];
    $places_disponibles = intval($_POST['places_disponibles']);
    $prix = floatval($_POST['prix']);
    $car = $_POST['car'];
    $conducteur_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO trajet 
                          (depart, arrive, date_trajet, heure_depart, places_disponibles, prix, car, conducteur_id) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $depart, $arrive, $date_trajet, $heure_depart, 
        $places_disponibles, $prix, $car, $conducteur_id
    ]);

    $_SESSION['message'] = "Nouveau trajet créé avec succès";
    header("Location: mes_trajets_conducteur.php");
    exit();
}

header("Location: mes_trajets_conducteur.php");
exit();
?>