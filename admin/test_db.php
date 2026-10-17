<?php
require_once 'includes/db.php';

try {
    // Test connection
    $pdo->query("SELECT 1");
    echo "Database connection successful!<br>";
    
    // Test DELETE permission
    $testId = 999999; // Non-existent ID
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$testId]);
    echo "DELETE permission works! (affected rows: " . $stmt->rowCount() . ")";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}