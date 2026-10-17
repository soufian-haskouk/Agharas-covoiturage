<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'agharas_bd';
$username = 'root';
$password = '';

// if (!isset($_GET['key']) || $_GET['key'] !== '12345678') {
   // die("Accès non autorisé"); }
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour mettre à jour les trajets terminés
    $sql = "UPDATE trajet
            SET status = 'completed'
            WHERE status = 'active'
              AND CONCAT(date_trajet, ' ', heure_depart) < NOW()";

    $affectedRows = $pdo->exec($sql);

    echo "Mise à jour effectuée avec succès. $affectedRows trajet(s) marqué(s) comme 'completed'.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
