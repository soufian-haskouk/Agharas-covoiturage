<?php
session_start();

// Verify user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../Login.php");
    exit();
}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // 1. Get reservation details and verify ownership
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND email = ?");
        $stmt->execute([$_POST['reservation_id'], $_SESSION['user_email']]);
        $reservation = $stmt->fetch();

        if (!$reservation) {
            throw new Exception("Réservation non trouvée ou non autorisée");
        }

        // 2. First delete any related ratings
        $stmt = $pdo->prepare("DELETE FROM driver_ratings WHERE reservation_id = ?");
        $stmt->execute([$_POST['reservation_id']]);

        // 3. Check if trip exists
        $stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ?");
        $stmt->execute([$reservation['trajet_id']]);
        $trip = $stmt->fetch();

        if ($trip) {
            // 4. Update available seats AND reactivate trip if needed
            $stmt = $pdo->prepare("UPDATE trajet 
                                  SET places_disponibles = places_disponibles + ?, 
                                      status = 'active' 
                                  WHERE id = ?");
            $stmt->execute([
                $reservation['nombre_places'], 
                $reservation['trajet_id']
            ]);
        } else {
            // 5. Trip doesn't exist - can't restore seats
            throw new Exception("Ce trajet n'existe plus. Les places ne peuvent être restaurées.");
        }

        // 6. Now delete the reservation
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$_POST['reservation_id']]);

        // Commit transaction
        $pdo->commit();

        $_SESSION['success'] = "Réservation annulée avec succès";
        header("Location: mes_reservations.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
        header("Location: mes_reservations.php");
        exit();
    }
}

// If not POST request or missing data
header("Location: mes_reservations.php");
exit();
?>