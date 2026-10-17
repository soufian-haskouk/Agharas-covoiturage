<?php
require_once __DIR__ . '/../../../templates/config.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
        throw new Exception('Coordonnées GPS manquantes');
    }

    $lat = floatval($_GET['lat']);
    $lng = floatval($_GET['lng']);
    $radius = 20; // 20km radius

    $query = "SELECT t.*, u.nom, u.prenom,
              ROUND((6371 * ACOS(
                  COS(RADIANS(:lat)) * COS(RADIANS(depart_lat)) * 
                  COS(RADIANS(depart_lng) - RADIANS(:lng)) + 
                  SIN(RADIANS(:lat)) * SIN(RADIANS(depart_lat))
              )), 1) AS distance
              FROM trajet t
              JOIN users u ON t.conducteur_id = u.id
              WHERE t.status = 'active'
              AND t.places_disponibles > 0
              AND depart_lat IS NOT NULL
              HAVING distance < :radius
              ORDER BY distance ASC
              LIMIT 10";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['lat' => $lat, 'lng' => $lng, 'radius' => $radius]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatage des données
    foreach ($trips as &$trip) {
        $trip['prix'] = number_format($trip['prix'], 2);
    }

    echo json_encode(['success' => true, 'data' => $trips]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>