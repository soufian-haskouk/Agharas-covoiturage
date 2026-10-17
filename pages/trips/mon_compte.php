<?php
session_start();
require_once '../../templates/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte - AGHARAS</title>
    
    <?php include '../../templates/header.php'; ?>


    <link rel="shortcut icon" href="../../../pages/images/LogoFv.png" type="image/x-icon"> 


  
    <link href="..\..\css\lib\fontawesome\webfonts\all.min.css" rel="stylesheet" />



    <link rel="stylesheet" href="../css/style.css" /> 

  
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
    <?php require_once '../../templates/config.php';?>

    <main class="account-container">
        <div class="account-header">
            <div class="avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <h1><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
            <?php
            // Afficher la note moyenne du conducteur (utilisateur)
            $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as nb FROM driver_ratings WHERE driver_id = ?");
            $stmt->execute([$user['id']]);
            $rating_data = $stmt->fetch();
            $avg_rating = $rating_data && $rating_data['nb'] > 0 ? round($rating_data['avg_rating'], 1) : null;
            ?>
            <div style="margin-bottom: 15px;margin-top: 8px;margin-left: 8px;   text-align: center;">
              <strong> </strong>
              <?php if ($avg_rating): ?>
                <span style="color: #ffc107; font-size: 18px;      vertical-align: middle;">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= floor($avg_rating)): ?>
                      ★
                    <?php elseif ($i - $avg_rating < 1): ?>
                      <span style="position:relative; display:inline-block; width:18px;">
                        <span style="position:absolute; width:<?= ($avg_rating - floor($avg_rating)) * 100 ?>%; overflow:hidden; color:#ffc107;">★</span>
                        <span style="color:#ddd;">★</span>
                      </span>
                    <?php else: ?>
                      <span style="color:#ddd;">★</span>
                    <?php endif; ?>
                  <?php endfor; ?>
                </span>
                <span style="font-size:15px; color:#333;">(<?= $avg_rating ?>/5)</span>
                <span style="font-size:13px; color:#888;">(<?= $rating_data['nb'] ?> note)</span>
              <?php else: ?>
                <span style="color:#888; font-size:15px;">Pas encore de note</span>
              <?php endif; ?>
            </div>
        </div>

        <div class="account-grid">
            <!-- Navigation Sidebar -->
            <nav class="account-nav">
                <a href="edit_profile.php" class="active"><i class="fas fa-user"></i> Edit</a>
                <a href="mes_trajets_conducteur.php"><i class="fas fa-car"></i> Mes Trajets</a>
                <a href="mes_reservations.php"><i class="fas fa-exchange-alt"></i> Réservations</a>
            <!--    <a href="edit_profile.php"><i class="fas fa-cog"></i> Paramètres</a>-->
                <a href="logout_confirm.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </nav>

            <!-- Main Content -->
            <section class="account-content">
                <h2><i class="fas fa-id-card"></i> Informations Personnelles</h2>
                <div class="profile-info">
                    <div class="info-item">
                        <span class="label">Nom complet:</span>
                        <span class="value"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Email:</span>
                        <span class="value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Téléphone:</span>
                        <span class="value"><?= $user['telephone'] ? htmlspecialchars($user['telephone']) : 'Non renseigné' ?></span>
                    </div>
                </div>

               <h2><i class="fas fa-chart-line"></i> Statistiques</h2>
<?php
// Nombre de trajets proposés par l'utilisateur (conducteur)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM trajet WHERE conducteur_id = ?");
$stmt->execute([$user['id']]);
$trajets_count = $stmt->fetchColumn();

// Nombre total de passagers transportés (somme des réservations validées sur ses trajets)
$stmt = $pdo->prepare("SELECT SUM(nombre_places) FROM reservations WHERE conducteur_id = ?");
$stmt->execute([$user['id']]);
$passagers_count = $stmt->fetchColumn();
if ($passagers_count === null) $passagers_count = 0;

?>
<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-car"></i>
        <span class="count"><?= $trajets_count ?></span>
        <span class="label">Trajets proposés</span>
    </div>
    <div class="stat-card">
        <i class="fas fa-users"></i>
        <span class="count"><?= $passagers_count ?></span>
        <span class="label">Passagers transportés</span>
    </div>
    <div class="stat-card">
        <i class="fas fa-star"></i>
        <span class="count"><?= $avg_rating !== null ? $avg_rating : '—' ?></span>
        <span class="label">Note moyenne</span>
    </div>
</div> 
            </section>
        </div>
    </main>

<style>
 html, body {
  background: #f9f9f9 !important;
}
 .account-header h1 {
  
  text-align: center;
  font-size: 38px;
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
  color: #152321;
  letter-spacing: 1.5px;
  background: linear-gradient(90deg, #fdd120 0%, #d4b221 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  text-shadow: 0 2px 8px rgba(212, 178, 33, 0.10), 0 1px 0 black;
}

    /* Nouveau CSS pour mon_compte.php - Version améliorée */

/* Couleurs principales */
:root {
  --primary-color:rgb(1, 3, 5);
  --secondary-color: #2c3e50;
  --accent-color: #f39c12;
  --light-gray:#f9ce1e;
  --dark-gray: #333;
  --text-color: #444;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: var(--text-color);
  background-color: #f9f9f9;
  margin: 0;
  padding: 0;
}

/* Conteneur principal */
.account-container {
  max-width: 1200px;
  /*margin: 2rem auto;*/
  padding: 50px 1.5rem;
  animation: fadeIn 0.5s ease-in-out;
}




.avatar {
    
    position: relative;
    width: 120px;
    height: 120px;
    padding-left: -70px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
  }
  
  .avatar:hover {
    transform: scale(1.05);
  }



/* Navigation */
.account-nav {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2.5rem;
    background: white;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }
  
  .account-nav a {
    padding: 0.8rem 1.5rem;
    border-radius: 30px;
    text-decoration: none;
    color: var(--dark-gray);
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--light-gray);
  }
  
  .account-nav a i {
    font-size: 1.1rem;
  }
  
  .account-nav a:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(74, 107, 175, 0.3);
  }
  
  .account-nav a.active {
    background: var(--primary-color);
    color: white;
  }

 
/* Sections de contenu */
.account-content {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
  }
.account-grid{
      background: white !important;

}
  .account-content h2 {
  color: #263f44;
  font-size: 26px;
  margin-bottom: 1.2rem;
  border-bottom: 1.5px solid #e0e0e0;
  padding-bottom: 0.5rem;
  letter-spacing: 0.5px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  background: none;
  border-radius: 0;
  box-shadow: none;
}
  
  .profile-info {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px 24px;
  margin-bottom: 28px;
  background: none;
  border-radius: 0;
  box-shadow: none;
  padding: 0;
}
.info-item {
  background: #fff;
  border-radius: 8px;
  padding: 14px 16px;
  box-shadow: 0 1px 6px rgba(44, 62, 80, 0.06);
  display: flex;
  flex-direction: column;
  gap: 1px;
  font-size: 16px;
  border-left: 3px solid #d4b221;
  border-top: 1px solid #f2f2f2;
  border-bottom: 1px solid #f2f2f2;
  border-right: 1px solid #f2f2f2;
  transition: box-shadow 0.2s;
}
.info-item:hover {
  box-shadow: 0 4px 16px rgba(44, 62, 80, 0.10);
}
.info-item .label {
  color: #888;
  font-weight: 500;
  font-size: 14px;
  margin-bottom: 1px;
}
.info-item .value {
  color: #263f44;
  font-weight: 500;
  font-size: 16px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
  margin-top: 14px;
  margin-bottom: 8px;
}
.stat-card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 6px rgba(44, 62, 80, 0.07);
  padding: 22px 0 14px 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-bottom: 3px solid #d4b221;
  border-top: 1px solid #f2f2f2;
  border-right: 1px solid #f2f2f2;
  border-left: 1px solid #f2f2f2;
  transition: box-shadow 0.2s, border-bottom 0.2s;
}
.stat-card i {
  color: #d4b221;
  font-size: 2rem;
  margin-bottom: 7px;
}
.stat-card .count {
  font-size: 1.7rem;
  font-weight: bold;
  color: #263f44;
  margin-bottom: 2px;
}
.stat-card .label {
  color: #888;
  font-size: 1rem;
  font-weight: 500;
  letter-spacing: 0.2px;
}
.stat-card:hover {
  box-shadow: 0 6px 18px rgba(44, 62, 80, 0.13);
  border-bottom: 3px solid #263f44;
  background: #fafbfc;
}

@media (max-width: 900px) {
  .profile-info {
    grid-template-columns: 1fr;
    padding: 0;
  }
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
  .profile-info, .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .account-nav {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .account-content {
    padding: 1.5rem;
  }
}

</style>


</body>
</html>