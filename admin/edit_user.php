<?php
require_once 'includes/db.php';

// Check if user ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_users.php?error=invalid_id');
    exit;
}

$user_id = (int)$_GET['id'];

// Check if user exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$user_id]);

if ($stmt->rowCount() === 0) {
    header('Location: manage_users.php?error=user_not_found');
    exit;
}

// Delete user
try {
    $pdo->beginTransaction();
    
    // First delete any related reservations
    $pdo->prepare("DELETE FROM reservations WHERE user_id = ?")->execute([$user_id]);
    
    // Then delete any trips where user is conducteur
    $pdo->prepare("DELETE FROM trajet WHERE conducteur_id = ?")->execute([$user_id]);
    
    // Finally delete the user
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    
    $pdo->commit();
    
    header('Location: manage_users.php?success=user_deleted');
} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: manage_users.php?error=delete_failed');
}

exit;