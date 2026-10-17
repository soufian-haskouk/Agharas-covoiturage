

require_once 'includes/auth-check.php';
<?php
require_once 'includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header("Location: manage_users.php?error=invalid_id");
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // 1. First get user's email
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $email = $stmt->fetchColumn();
    
    if ($email === false) {
        throw new Exception("User not found");
    }

    // 2. Temporarily disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 3 Delete (by email)
    $pdo->prepare("DELETE FROM reservations WHERE email = ?")->execute([$email]);

    $pdo->prepare("DELETE FROM reservations WHERE conducteur_id = ?")->execute([$user_id]);

    $pdo->prepare("DELETE FROM trajet WHERE conducteur_id = ?")->execute([$user_id]);

    // . Fin delete the user
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

    // . Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Commit transaction
    $pdo->commit();

    header("Location: manage_users.php?success=user_deleted");
    exit;

} catch (Exception $e) {
    // Re-enable foreign key checks if they were disabled
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        $pdo->rollBack();
    }

    // Special handling for user ID 1
    if ($user_id === 1) {
        header("Location: manage_users.php?error=protected_user");
    } else {
        // Handle other errors
        $errorMessage = "ERREUR DE SUPRIMER"; //"An error occurred";
        if (strpos($e->getMessage(), '1451') !== false) {
            $errorMessage = "Cannot delete user with existing trips/reservations";
        }
        header("Location: manage_users.php?error=" . urlencode($errorMessage));
    }
    exit;
}