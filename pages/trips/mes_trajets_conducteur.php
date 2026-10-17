<?php
session_start();
include('../../templates/header.php');

// Vérifier si l'utilisateur est connecté et est un conducteur
//if (!isset($_SESSION['logged_in']) || !isset($_SESSION['est_conducteur'])) {
 //   header("Location: ../Login.php");
 //   exit();
//}

$pdo = new PDO('mysql:host=localhost;dbname=agharas_bd', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Traitement de la suppression
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $pdo->prepare("DELETE FROM trajet WHERE id = ? AND conducteur_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $_SESSION['message'] = "Trajet supprimé avec succès";
    header("Location: mes_trajets_conducteur.php");
    exit();
}

// Récupérer les trajets du conducteur
$stmt = $pdo->prepare("SELECT * FROM trajet WHERE conducteur_id = ? ORDER BY date_trajet DESC");
$stmt->execute([$_SESSION['user_id']]);
$trajets = $stmt->fetchAll();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="contt">
    <h2>Gérer Mes Trajets:</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Liste des trajets -->
    <div class="trajets-table">
        <table class="table">
            <thead>
                <tr>
                    <th> Ville de Départ</th>
                    <th>Ville d'Arrivée</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places restant</th>
                    <th>Gérer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                <tr>
                    <td><?= htmlspecialchars($trajet['depart']) ?></td>
                    <td><?= htmlspecialchars($trajet['arrive']) ?></td>
                    <td><?= date('m/d/Y', strtotime($trajet['date_trajet'])) ?></td>
                    <td><?= substr($trajet['heure_depart'], 0, 5) ?></td>
                    <td><?= $trajet['places_disponibles'] ?></td>
                    <td>
                        <a href="voir_reservations.php?id=<?= $trajet['id'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i>Voir
                        </a>
                        <a href="modifier_trajet.php?id=<?= $trajet['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <a href="#" 
                        class="btn btn-sm btn-danger" 
                        onclick="confirmDeleteTrajet(<?= $trajet['id'] ?>, event)">
                        <i class="fas fa-trash-alt me-1"></i>Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulaire pour ajouter un nouveau trajet -->
    <div class="nouveau-trajet">
    <span class="accb">
               
            
        <h3>Nouveau Trajet : <a href="add.php"> Publier Annance  <i class="ion-ios-arrow-forward"></i></a></h3>
    </span>    
    </div>
</div>

<style>

    .contt {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color:rgba(249, 249, 249, 0.8);
        }
        
    
    .trajets-table {
        margin-bottom: 40px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .table th, .table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }
    
    .table th {
        background-color:#152329 ;
        color: white;
    }
    
    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-size: 14px;
    }
    
    .btn-info { background-color: #17a2b8; }
    .btn-warning { background-color: #ffc107; color: #212529; }
    .btn-danger { background-color: #dc3545; }
    
    .btn:hover {
        opacity: 0.9;
    }
    
    .nouveau-trajet {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .alert {
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    h2 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        font-weight: bold;
        font-size: 32px;
        

    }
    h3{
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        font-weight: bold;
        font-size: 28px;

    }

   .accb{
    padding-left;
   }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
</style>

<script>
function confirmDeleteTrajet(trajetId, event) {
    event.preventDefault(); // Empêche le lien de se déclencher immédiatement
    
    Swal.fire({
        title: 'Confirmation de suppression',
        text: "Êtes-vous sûr de vouloir supprimer ce trajet ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        backdrop: `
            rgba(0,0,123,0.4)
            url("/images/nyan-cat.gif")
            left top
            no-repeat
        `,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `mes_trajets_conducteur.php?supprimer=${trajetId}`;
        }
    });
}
</script>

<?php include('../../templates/footer.php'); ?>