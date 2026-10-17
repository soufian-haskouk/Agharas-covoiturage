<?php
require_once __DIR__ . '/../../../templates/config.php';
header('Content-Type: text/html; charset=utf-8');

try {
    $query = "SELECT t.*, u.nom, u.prenom, COUNT(r.id) as reservations
              FROM trajet t
              JOIN users u ON t.conducteur_id = u.id
              LEFT JOIN reservations r ON t.id = r.trajet_id
              WHERE t.status = 'active'
              AND t.places_disponibles > 0
              GROUP BY t.id
              ORDER BY reservations DESC, t.created_at DESC
              LIMIT 10"; // Augmentez la limite si nécessaire
    
    $stmt = $pdo->query($query);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($trips)) {
        echo '<div class="alert alert-info">Aucun trajet populaire disponible</div>';
        exit;
    }
    
   foreach ($trips as $trip) {
    echo '
    <div class="trip-card">
        <div class="trip-route">
            <span>'.$trip['depart'].' → '.$trip['arrive'].'</span>
            <span class="trip-price">'.number_format($trip['prix'], 2).' MAD</span>
        </div>
        <div class="trip-driver">'.$trip['prenom'].' '.$trip['nom'].'</div>
        <div>
            <span class="trip-reservations">'.$trip['reservations'].' réservations</span>
        </div>
        <div class="trip-details-btn">
            <a href="reservation.php?id='.$trip['id'].'" class="btn-details">Voir détails</a>
        </div>
    </div>';
}
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erreur: '.$e->getMessage().'</div>';
}
?>