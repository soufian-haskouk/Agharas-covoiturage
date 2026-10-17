<?php
require_once __DIR__ . '/../../templates/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($_SESSION['user_id']) && isset($data['lat']) && isset($data['lng'])) {
        // Sauvegarder la position de l'utilisateur
        $stmt = $pdo->prepare("UPDATE users SET last_lat = ?, last_lng = ? WHERE id = ?");
        $stmt->execute([$data['lat'], $data['lng'], $_SESSION['user_id']]);
        
        $_SESSION['user_location'] = [
            'lat' => $data['lat'],
            'lng' => $data['lng']
        ];
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
    }
}
?>