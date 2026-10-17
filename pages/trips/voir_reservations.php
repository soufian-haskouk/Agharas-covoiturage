<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

include('../../templates/header.php');


$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifier que le trajet appartient bien au conducteur
$trajet_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = ? AND conducteur_id = ?");
$stmt->execute([$trajet_id, $_SESSION['user_id']]);
$trajet = $stmt->fetch();

if (!$trajet) {
    $_SESSION['error'] = "Trajet non trouvé ou non autorisé";
    header("Location: mes_trajets_conducteur.php");
    exit();
}

// Récupérer les réservations pour ce trajet
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE trajet_id = ? ORDER BY date_reservation DESC");
$stmt->execute([$trajet_id]);
$reservations = $stmt->fetchAll();

// Calculer le total des places réservées
$total_places = 0;
foreach ($reservations as $res) {
    $total_places += $res['nombre_places'];
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="contr">
    <h2>Réservations pour le trajet :<span> <?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrive']) ?></span></h2>
    <div class="trajet-info">
        <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($trajet['date_trajet'])) ?></p>
        <p><strong>Heure:</strong> <?= substr($trajet['heure_depart'], 0, 5) ?></p>
        <p><strong>Total places réservées:</strong> <?= $total_places ?></p>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <div class="reservations-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Places</th>
                    <th>Date Réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservations)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune réservation pour ce trajet</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['prenom']) ?> <?= htmlspecialchars($reservation['nom']) ?></td>
                        <td><?= htmlspecialchars($reservation['email']) ?></td>
                        <td><?= htmlspecialchars($reservation['telephone']) ?></td>
                        <td><?= $reservation['nombre_places'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation['date_reservation'])) ?></td>
                        <td class="actions">
                            <a href="mailto:<?= $reservation['email'] ?>?subject=Trajet <?= $trajet['depart'] ?>-<?= $trajet['arrive'] ?>&body=Bonjour <?= $reservation['prenom'] ?>," 
                               class="btn btn-contact" 
                               title="Envoyer un email">
                               <i class="fas fa-envelope"></i>
                            </a>
                            <a href="tel:<?= str_replace(' ', '', $reservation['telephone']) ?>" 
                               class="btn btn-call" 
                               title="Appeler">
                               <i class="fas fa-phone"></i>
                            </a>
                            
                            <a href="#" 
                            class="btn btn-cancel" 
                            title="Annuler"
                            onclick="confirmCancelReservation(event, <?= $reservation['id'] ?>, <?= $trajet_id ?>)">
                            <i class="fas fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="actions-footer">
        <a href="mes_trajets_conducteur.php " >
            <i class="fa fa-angle-left"></i> Retour à mes trajets
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
              </a>

       
    </div>
</div>

<!-- Script JavaScript -->
<script>
function confirmCancelReservation(event, reservationId, trajetId) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Annulation de réservation',
       
        showCancelButton: true,
        confirmButtonText: 'Oui, annuler',
        cancelButtonText: 'Non, garder',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'border-radius-10'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `annuler_reservation_admin.php?id=${reservationId}&trajet_id=${trajetId}`;
        }
    });
}
</script>

<style>
    .contr {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .contr h2 {
        
         text-align: center;
  
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
  letter-spacing: 1.5px;
  background: linear-gradient(90deg, #fdd120 0%, #d4b221 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  text-shadow: 0 2px 8px rgba(212, 178, 33, 0.10), 0 1px 0 black;
  border-radius: 12px;
  padding: 10px 0 8px 0;
  box-shadow: 0 2px 8px rgba(212, 178, 33, 0.08);
    }
    .contr h2 span {
        color: #007bff !important;
    }
    .actions-footer{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        font-weight: bold;
        font-weight: 800;


    }
    
    .trajet-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    
    .trajet-info p {
        margin: 5px 0;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
     .table td {
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        text-align: left;
        font-size: 25px;
        background-color: #d6e7fc;
        color: #000;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s ease;

    }
    .table th, .table td {
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        text-align: left;
    }

    
    .table th {
        background-color:#152329 ;
        color: white;
        font-weight: 500;
    }
    
    .table tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-success {
        background-color: #d4edda;
        justify-content: center;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .actions {
        display: flex;
        gap: 5px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-contact {
        background-color: #17a2b8;
    }
    
    .btn-call {
        background-color: #28a745;
    }
    
    .btn-cancel {
        background-color: #dc3545;
    }
    
    .btn-back {
        background-color: #6c757d;
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
    }
    
    .btn:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }
    
    .text-center {
        text-align: center;
        font-weight: 600;
        color:#facf1f !important;
        
        
    }

    
.border-radius-10 {
    border-radius: 10px !important;
}
.btn-cancel {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
    transition: all 0.3s ease;
}
.btn-cancel:hover {
    background-color: #c82333;
    transform: scale(1.05);
}
</style>

<?php include('../../templates/footer.php'); ?>