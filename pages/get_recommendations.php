<?php
session_start();
// require_once 'config/database.php'; // Décommente si tu as ce fichier
$host = 'localhost';
$dbname = 'agharas_bd';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // URL de l'API Python - IMPORTANT: utiliser 127.0.0.1
    $api_url = "http://127.0.0.1:5000/recommend?user_id=" . $user_id;
    
    // Initialiser cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    // Exécuter la requête
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        $data = json_decode($response, true);
        
        // DEBUG: Log pour voir ce qui est retourné
        error_log("API Response: " . print_r($data, true));
        
        if (isset($data['success']) && $data['success'] && !empty($data['recommended_rides'])) {
            echo json_encode([
                'success' => true,
                'recommendations' => $data['recommended_rides'],
                'count' => $data['count']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'recommendations' => [],
                'message' => $data['message'] ?? 'Aucune recommandation disponible pour le moment'
            ]);
        }
    } else {
        error_log("Erreur API recommandation - HTTP Code: " . $http_code . " Response: " . $response);
        echo json_encode([
            'success' => false, 
            'message' => 'Service de recommandation temporairement indisponible'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Erreur recommandation: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur lors de la récupération des recommandations'
    ]);
}
?>