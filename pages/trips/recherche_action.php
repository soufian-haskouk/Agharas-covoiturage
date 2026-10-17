<?php
$host_soufian = 'localhost';
$dbname_souf = 'agharas_bd'; 
$username_souf= 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host_soufian;dbname=$dbname_souf", $username_souf, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['depart']) && isset($_POST['arrive']) && isset($_POST['date'])) {
        $depart = $_POST['depart'];
        $arrive = $_POST['arrive'];
        $date = $_POST['date'];

        $sql = "SELECT * FROM trajet
                WHERE depart = :depart 
                AND arrive = :arrive 
                AND status = 'active'
                AND date_trajet = :date
                ORDER BY heure_depart";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':depart', $depart);
        $stmt->bindParam(':arrive', $arrive);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            foreach ($results as $trip) {
                echo ' <div class="trip-card">
                    <div class="trip-info">
                        <div class="trip-route">
                            <h4>Départ : <strong>' . htmlspecialchars($trip['depart']) . '</strong></h4>
                            <h4>Arrivée : <strong>' . htmlspecialchars($trip['arrive']) . '</strong></h4>
                        </div>
                        <div class="trip-details">
                            <p><strong>Date :</strong> ' . htmlspecialchars($trip['date_trajet']) . '</p>
                            <p><strong>Heure de départ :</strong> ' . htmlspecialchars($trip['heure_depart']) . '</p>
                            <p><strong>véhicule :</strong> ' . htmlspecialchars($trip['car']) . '</p>
                            <p><strong>Places disponibles :</strong> ' . htmlspecialchars($trip['places_disponibles']) . '</p>
                        </div>
                    </div>
                    <div class="trip-actions">
                        <span class="trip-price">' . htmlspecialchars($trip['prix']) . ' DH</span>
                        <a href="reservation.php?id=' . $trip['id'] . '" class="btnn btn-reserve">RÉSERVER</a>
                    </div>
                </div>
                ';
            }
        } else {
            echo '<div class="alert alert-info text-center">Aucun trajet disponible pour votre recherche.</div>';
        }
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
