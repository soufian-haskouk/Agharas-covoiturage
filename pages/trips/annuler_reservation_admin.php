<?php
session_start();

//if (!isset($_SESSION['logged_in']) || !isset($_SESSION['est_conducteur'])) {
  //  header("Location: ../Login.php");
    //exit();
//}

$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']);
    
    try {
        $pdo->beginTransaction();
        
        // 1. Vérifier que la réservation existe et appartient à un trajet du conducteur
        $stmt = $pdo->prepare("SELECT r.* FROM reservations r
                              JOIN trajet t ON r.trajet_id = t.id
                              WHERE r.id = ? AND t.conducteur_id = ?");
        $stmt->execute([$reservation_id, $_SESSION['user_id']]);
        $reservation = $stmt->fetch();
        
        if (!$reservation) {
            throw new Exception("Réservation non trouvée ou non autorisée");
        }
        
        // 2. Restaurer les places dans le trajet
        $stmt = $pdo->prepare("UPDATE trajet 
                              SET places_disponibles = places_disponibles + ?, 
                                  status = 'active' 
                              WHERE id = ?");
        $stmt->execute([$reservation['nombre_places'], $reservation['trajet_id']]);
        
        // 3. Supprimer la réservation
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$reservation_id]);
        
        $pdo->commit();
        
        $_SESSION['message'] = "Réservation annulée avec succès. Les places ont été restituées.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
    }
}

header("Location: voir_reservations.php?id=" . $reservation['trajet_id']);
exit();
?>