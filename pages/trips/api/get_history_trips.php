<?php
require_once __DIR__ . '/../../../templates/config.php';
session_start();

header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    // First, get the top 3 most frequent routes from user's history
    $topRoutesQuery = "SELECT t2.depart, t2.arrive
                      FROM reservations r
                      JOIN trajet t2 ON r.trajet_id = t2.id
                      WHERE r.email = (SELECT email FROM users WHERE id = :user_id)
                      GROUP BY t2.depart, t2.arrive
                      ORDER BY COUNT(*) DESC
                      LIMIT 3";
    
    $stmt = $pdo->prepare($topRoutesQuery);
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $topRoutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($topRoutes)) {
        echo '<div class="alert alert-info">Aucune recommandation basée sur votre historique</div>';
        exit;
    }
    
    // Build the WHERE clause for the main query
    $whereClauses = [];
    $params = ['user_id' => $_SESSION['user_id']];
    
    foreach ($topRoutes as $index => $route) {
        $departKey = "depart_" . $index;
        $arriveKey = "arrive_" . $index;
        $whereClauses[] = "(t.depart = :$departKey AND t.arrive = :$arriveKey)";
        $params[$departKey] = $route['depart'];
        $params[$arriveKey] = $route['arrive'];
    }
    
    $whereClause = implode(" OR ", $whereClauses);
    
    // Main query to get recommended trips
    $query = "SELECT t.*, u.nom, u.prenom
              FROM trajet t
              JOIN users u ON t.conducteur_id = u.id
              WHERE t.status = 'active'
              AND t.places_disponibles > 0
              AND ($whereClause)
              AND t.conducteur_id != :user_id  -- Don't recommend user's own trips
              ORDER BY t.date_trajet ASC
              LIMIT 5";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($trips)) {
        echo '<div class="alert alert-info">Aucun trajet disponible pour vos destinations fréquentes</div>';
        exit;
    }
    
    foreach ($trips as $trip) {
        echo '
        <div class="trip-card">
            <div class="trip-info">
                <div class="trip-route">
                    <span class="depart">'.htmlspecialchars($trip['depart']).'</span>
                    <i class="fas fa-arrow-right"></i>
                    <span class="arrive">'.htmlspecialchars($trip['arrive']).'</span>
                </div>
                <div class="trip-date">
                    <i class="far fa-calendar-alt"></i> '.date('d/m/Y', strtotime($trip['date_trajet'])).'
                    <i class="far fa-clock"></i> '.substr($trip['heure_depart'], 0, 5).'
                </div>
                <div class="trip-meta">
                    <div class="trip-price">'.$trip['prix'].' MAD</div>
                    <div class="trip-seats"><i class="fas fa-user-friends"></i> '.$trip['places_disponibles'].' places</div>
                </div>
                <div class="trip-driver">
                    <span>Chaufeur</span>
                    <span>'.htmlspecialchars($trip['prenom']).' '.htmlspecialchars($trip['nom']).'</span>
                </div>
                <div class="history-badge">
                    <i class="fas fa-history"></i> Basé sur votre historique
                </div>
                <a href="reservation.php?id='.$trip['id'].'" class="btn btn-sm btn-primary mt-2">Voir détails</a>
            </div>
        </div>';
    }
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erreur: '.htmlspecialchars($e->getMessage()).'</div>';
}
?>