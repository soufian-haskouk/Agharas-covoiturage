<?php
require_once 'includes/db.php';

// Vérifier l'ID du trajet
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_trajets.php?error=invalid_id');
    exit;
}

$trajet_id = (int)$_GET['id'];

try {
    // Commencer une transaction
    $pdo->beginTransaction();
    
    // 1. Supprimer les réservations associées
    $pdo->prepare("DELETE FROM reservations WHERE trajet_id = ?")->execute([$trajet_id]);
    
    // 2. Supprimer le trajet
    $pdo->prepare("DELETE FROM trajet WHERE id = ?")->execute([$trajet_id]);
    
    // Valider la transaction
    $pdo->commit();
    
    header('Location: manage_trajets.php?success=deleted');
    exit;
    
} catch (PDOException $e) {
    // Annuler en cas d'erreur
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: manage_trajets.php?error=delete_failed');
    exit;
}