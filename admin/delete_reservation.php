<?php
require_once 'includes/db.php';

// Vérifier l'ID de réservation
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_reservations.php?error=invalid_id');
    exit;
}

$reservation_id = (int)$_GET['id'];

// Vérifier que la réservation existe
$stmt = $pdo->prepare("SELECT id FROM reservations WHERE id = ?");
$stmt->execute([$reservation_id]);

if ($stmt->rowCount() === 0) {
    header('Location: manage_reservations.php?error=not_found');
    exit;
}

// Suppression
try {
    $pdo->beginTransaction();
    
    // D'abord vérifier le trajet associé
    $trajet_stmt = $pdo->prepare("
        SELECT t.id, t.places_disponibles, r.nombre_places 
        FROM trajet t
        JOIN reservations r ON t.id = r.trajet_id
        WHERE r.id = ?
    ");
    $trajet_stmt->execute([$reservation_id]);
    $trajet_info = $trajet_stmt->fetch();
    
    // Supprimer la réservation
    $pdo->prepare("DELETE FROM reservations WHERE id = ?")->execute([$reservation_id]);
    
    // Mettre à jour les places disponibles si le trajet existe encore
    if ($trajet_info) {
        $new_places = $trajet_info['places_disponibles'] + $trajet_info['nombre_places'];
        $pdo->prepare("UPDATE trajet SET places_disponibles = ? WHERE id = ?")
            ->execute([$new_places, $trajet_info['id']]);
    }
    
    $pdo->commit();
    header('Location: manage_reservations.php?success=deleted');
    exit;
    
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Delete reservation error: " . $e->getMessage());
    header('Location: manage_reservations.php?error=delete_failed');
    exit;
}