<?php
require_once __DIR__ . '/../../templates/config.php';
session_start();

// Debug - Vérification de la connexion et de la session
if (!isset($pdo)) {
    die("Erreur: La configuration de la base de données n'est pas chargée");
}

// Récupération des informations utilisateur si connecté
$userLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
  

<head>


     
   <!-- Basic -->
 <meta charset="utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />  
 <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <!-- Site Metas --> 
 <meta name="keywords" content="" />
<meta name="description" content="" />
  <meta name="author" content="" /> 

  <title>AGHARAS</title>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
  />
  <!--
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

  <link
    href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap"
    rel="stylesheet"
  />
  <link rel="icon" type="image/x-icon" href="images/Logo.png">
  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css" />
  <link rel="stylesheet" href="css/animate.css" />

  <link rel="stylesheet" href="css/owl.carousel.min.css" />
  <link rel="stylesheet" href="css/owl.theme.default.min.css" />
  <link rel="stylesheet" href="css/magnific-popup.css" />

  <link rel="stylesheet" href="css/aos.css" />

  <link rel="stylesheet" href="css/ionicons.min.css" />

  <link rel="stylesheet" href="css/bootstrap-datepicker.css" />
  <link rel="stylesheet" href="css/jquery.timepicker.css" />

  <link rel="stylesheet" href="css/flaticon.css" />
  <link rel="stylesheet" href="css/icomoon.css" />   -->

  <link rel="shortcut icon" href="../../pages/images/LogoFv.png" type="image/x-icon"> 


  <link rel="stylesheet" href="..\css\lib\fontawesome\webfonts\bootstrap.min.css" />
  <link href="..\css\lib\fontawesome\webfonts\all.min.css" rel="stylesheet" />



  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>

  <div class="hero_area">
  <!-- header section starts -->
   
  <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container">
        <a class="navbar-brand" href="Accueil.php">
          <img src="../images/Logo.png" alt="AGHARAS" class="logo-icon">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span></span>
        </button>
        <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="Accueil.php">Accueil</a>
            </li>
          </ul>
          <div class="user_option">
            
            <a href="contact.php" class="announce-icon">
              <i class="fa fa-envelope"></i>
              Contact
            </a>
            <a href="sevice.php" class="cart-icon">
              <i class="fa fa-briefcase"></i>
              Services
            </a>
            <a href="recherche.php" class="person-icon">
            <i class="fa fa-search"></i>
            Recherche
          </a>
          <a href="add.php" class="search-icon">
              <i class="fa fa-plus-circle"></i>
            Publier annonce
          </a> 
          <!--<a href="mes_trajets_conducteur.php" class="driver-icon">
                    <i class="fa fa-car"></i>
                    Mes Trajets
          </a> -->
          
          
          <div class="dropdown">
            <a href="#" class="dropdown-toggle" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-question-circle"></i>
              
            </a>
            <div class="dropdown-menu" aria-labelledby="aboutDropdown">
              <a class="dropdown-item" href="#a-propos">A propos de nous</a>
              <a class="dropdown-item" href="#pourquoi">Pourquoi nous</a>
              <a class="dropdown-item" href="#AA_blog">Blog</a>
            </div>
            
          </div>
          <!-- Add this to your header where you want the profile dropdown -->
          <div class="user-menu">
            <div class="user-avatar" id="userMenuBtn">
              <i class="fas fa-user-circle"></i>
              <span></span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="user-dropdown" id="userDropdown">
             <!-- <a href="#"><i class="fas fa-plus-circle"></i> Publier un trajet</a>-->
              <a href="..\trips\mes_trajets_conducteur.php"><i class="fas fa-car"></i> Mes trajets</a>
              <a href="..\trips\mes_reservations.php"><i class="fas fa-exchange-alt"></i> Réservations</a>

            <!--  <a href="#"><i class="fas fa-inbox"></i> Messages</a>-->
              <div class="dropdown-divider"></div>
              <a href="mon_compte.php"><i class="fas fa-user"></i> Mon profil</a>
              <div class="dropdown-divider"></div>
             <!-- <a href="#"><i class="fas fa-credit-card"></i> Paiements</a>-->
              <a href="logout_confirm.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
          </div>
          </div>
        </div>
      </nav>
    </div>
  </header> 

<style>
  /* Styles pour l'accordéon */
.recommendation-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 10px;
    background: white;
}

.recommendation-header {
    padding: 15px;
    cursor: pointer;
    display: flex;
    justify-content: between;
    align-items: center;
}

.recommendation-header h5 {
    margin: 0;
    flex-grow: 1;
}

.recommendation-price {
    font-weight: bold;
    color: #007bff;
}

.recommendation-driver {
    color: #666;
    font-size: 14px;
}

.recommendation-arrow {
    transition: transform 0.3s;
}

.recommendation-arrow.rotated {
    transform: rotate(180deg);
}

.recommendation-details {
    padding: 0 15px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.recommendation-details.open {
    padding: 15px;
    max-height: 500px;
}

.trip-details p {
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.trip-details i {
    width: 20px;
    margin-right: 8px;
    color: #007bff;
}

  /* Styles pour la section de recommandations personnalisées */
  /* Styles pour la section de recommandations personnalisées */
.recommendations-container {
    max-height: 500px; /* Hauteur fixe */
    overflow-y: auto; /* Scroll si trop de contenu */
}

.recommendation-list {
    /* Pas de hauteur fixe ici, le scroll gère le surplus */
}
  /* Styles pour la section de recommandations personnalisées */
.recommendations-container .row {
    display: flex;
    flex-wrap: wrap;
}

.recommendations-container .recommendation-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.recommendations-container .recommendation-card h5 {
    color: #333;
    margin-bottom: 10px;
}

.recommendations-container .ai-badge {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
}

.recommendations-container .trip-details p {
    margin-bottom: 5px;
    font-size: 14px;
}

.recommendations-container .trip-details i {
    width: 20px;
    color: #007bff;
}

/* Limiter l'affichage à 2 trajets initialement */
.recommendations-container .row .col-md-6 {
    display: none;
}

.recommendations-container .row .col-md-6:nth-child(-n+2) {
    display: block;
}

/* Bouton Voir plus */
.voir-plus-btn {
    display: block;
    width: 200px;
    margin: 10px auto;
    text-align: center;
    background: #007bff;
    color: white;
    padding: 10px 10px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.voir-plus-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
}</style>
 
    <!-- soufian  end header section    linear-gradient(rgba(47, 47, 50, 0.7)), -->

    <div class="hero-wrap   ftco-degree-bg" style="background-image:   url('../images/bg_1.jpg') ;"  data-stellar-background-ratio="0.5">
     <div class="overlay"></div>

      <span class=" text-center">
      <h1 class="row mb-4  col-lg-8 hhh   ">Un moyen rapide et facile pour faire du covoiturage professionnel</h1>
      </span>
    <!-- slider section -->
     
  <section class="recommendation_section position-relative">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="recommendation-container">
          <!-- Search Box -->
          <div class="search-box">
            <h2>Trouvez votre trajet idéal</h2>
          </div>

          <!-- Recommendation Box -->
          <div class="recommendation-box">
            <div class="recommendation-header">
              <h3><i class="fas fa-star"></i> Recommandations pour vous</h3>
            </div>

            <div class="recommendation-tabs">
              <ul class="nav nav-tabs" id="recommendationTabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="popular-tab" data-toggle="tab" href="#popular" role="tab">
                    <i class="fas fa-fire"></i> Populaires
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab">
                    <i class="fas fa-history"></i> Votre historique
                  </a>
                </li>
                <li  class="nav-item">
                  <a class="nav-link active"  id="personalized-tab" data-toggle="tab" href="#personalized" role="tab">
                    <i class="fas fa-robot"></i> Personnalisées
                  </a>
                </li>
              </ul>

              <div class="tab-content" id="recommendationTabsContent">
                <div class="tab-pane fade" id="popular" role="tabpanel">
                  <div class="recommendation-list" id="popularTrips">
                    <!-- Popular trips will be loaded here via AJAX -->
                  </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel">
                  <div class="recommendation-list" id="historyTrips">
                    <?php if($userLoggedIn): ?>
                      <!-- History trips will be loaded here via AJAX -->
                    <?php else: ?>
                      <div class="login-prompt">
                        <p>Connectez-vous pour voir vos trajets recommandés basés sur votre historique.</p>
                        <a href="login.php" class="btn btn-primary">Se connecter</a>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="tab-pane fade show active" id="personalized" role="tabpanel">
                  <div id="recommendations-container" class="recommendations-container">
                    <div class="loading-spinner" style="text-align: center;">
                      <i class="fas fa-spinner fa-spin fa-2x"></i>
                      <p>Chargement de vos recommandations...</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Recommendation System JavaScript -->


<script>
  // pour aAI
  
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chargement des recommandations...');
    loadRecommendations();
});
function loadRecommendations() {
    fetch('/myProjet/pages/get_recommendations.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            const container = document.getElementById('recommendations-container');
            
            if (data.success && data.recommendations && data.recommendations.length > 0) {
                let html = '<div class="recommendation-list">';
                
                // Afficher seulement 2 trajets initialement
                const initialCount = Math.min(2, data.recommendations.length);
                
                data.recommendations.forEach((trip, index) => {
                    const isHidden = index >= initialCount;
                    
                    html += `
                    <div class="recommendation-card" ${isHidden ? 'style="display: none;"' : ''}>
                        <div class="recommendation-header" onclick="toggleRecommendation(${index})">
                            <div>
                                <h5>${trip.depart} → ${trip.arrive}</h5>
                                <p class="recommendation-driver">${trip.prenom} ${trip.nom}</p>
                            </div>
                            <div class="text-right">
                                <div class="recommendation-price">${trip.prix} MAD</div>
                                <i class="fas fa-chevron-down recommendation-arrow"></i>
                            </div>
                        </div>
                        <div class="recommendation-details" id="details-${index}">
                            <div class="trip-details">
                                <p><i class="fas fa-calendar"></i> ${trip.date_trajet}</p>
                                <p><i class="fas fa-clock"></i> ${trip.heure_depart}</p>
                                <p><i class="fas fa-users"></i> ${trip.places_disponibles} places</p>
                                <p><i class="fas fa-car"></i> ${trip.car}</p>
                            </div>
                            <a href="reservation.php?id=${trip.id}" class="btn btn-primary btn-sm btn-block">
                                Réserver maintenant
                            </a>
                        </div>
                    </div>`;
                });
                
                html += '</div>';
                
                // Ajouter le bouton Voir plus seulement si plus de 2 trajets
                if (data.recommendations.length > initialCount) {
                    html += `
                    <button class="voir-plus-btn" onclick="showAllRecommendations()">
                        Voir plus de trajets (${data.recommendations.length - initialCount})
                    </button>`;
                }
                
                container.innerHTML = html;
                
            } else {
                container.innerHTML = `
                    <div class="text-center">
                        <p>${data.message || 'Aucune recommandation disponible pour le moment.'}</p>
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Erreur fetch:', error);
            document.getElementById('recommendations-container').innerHTML = `
                <div class="text-center text-muted">
                    <p>Service de recommandation temporairement indisponible</p>
                </div>`;
        });
}

// Fonction pour afficher tous les trajets
function showAllRecommendations() {
    const allCards = document.querySelectorAll('.recommendation-card');
    allCards.forEach(card => {
        card.style.display = 'block';
    });
    
    // Cacher le bouton Voir plus
    const voirPlusBtn = document.querySelector('.voir-plus-btn');
    if (voirPlusBtn) {
        voirPlusBtn.style.display = 'none';
    }
}

// Fonction pour toggle l'accordéon (inchangée)
function toggleRecommendation(index) {
    const details = document.getElementById(`details-${index}`);
    const header = details.previousElementSibling;
    const arrow = header.querySelector('.recommendation-arrow');
    
    details.classList.toggle('open');
    arrow.classList.toggle('rotated');
}
</script>


<script>
  //POUR DATA
$(document).ready(function() {
  // Modifiez la fonction loadPopularTrips() comme suit :
  function loadPopularTrips() {
    $.ajax({
      url: 'api/get_popular_trips.php',
      method: 'GET',
      dataType: 'html', // Important pour recevoir du HTML
      beforeSend: function() {
        $('#popularTrips').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p>Chargement des trajets...</p></div>');
      },
      success: function(data) {
        // Vérifier si la réponse contient des données valides
        if (data.includes('trip-card')) {
          // Séparer les cartes de trajet
          const trips = $(data).filter('.trip-card').toArray();
          
          let html = '';
          // Afficher seulement les 2 premiers trajets
          trips.slice(0, 2).forEach(trip => {
            html += trip.outerHTML;
          });

          // Ajouter le bouton "Voir plus" si nécessaire
          if (trips.length > 2) {
            html += `
            <div class="text-center mt-3">
              <button class="btn btn-sm btn-outline-primary show-more-btn">
                Voir plus de trajets (${trips.length - 2})
              </button>
              <div class="more-trips" style="display:none">
                ${trips.slice(2).map(trip => trip.outerHTML).join('')}
              </div>
            </div>`;
          }

          $('#popularTrips').html(html);

          // Gestion du bouton "Voir plus"
          $('.show-more-btn').click(function() {
            $(this).hide();
            $('.more-trips').slideDown();
          });
        } else {
          $('#popularTrips').html(data || '<div class="alert alert-info">Aucun trajet populaire disponible</div>');
        }
      },
      error: function() {
        $('#popularTrips').html('<div class="alert alert-danger">Erreur de chargement des trajets</div>');
      }
    });
  }

  // Chargez les trajets populaires au clic sur l'onglet
  $('a[href="#popular"]').on('click', function(e) {
    if ($('#popularTrips').children().length === 0) {
      loadPopularTrips();
    }
  });

  // Chargez au premier affichage si l'onglet est actif
  if ($('#popular').hasClass('active')) {
    loadPopularTrips();
  }

  // Load History Trips if logged in
  <?php if($userLoggedIn): ?>
  function loadHistoryTrips() {
    $.ajax({
      url: 'api/get_history_trips.php',
      method: 'GET',
      success: function(data) {
        $('#historyTrips').html(data);
      }
    });
  }
  loadHistoryTrips();
  <?php endif; ?>

  // Initial load
  loadPopularTrips();
});
</script>

<!-- CSS pour la section de recommandation -->
<style>
.recommendation_section {
  padding: 80px 0;
  border-radius: 10px;
  margin-top: -75px;
  position: relative;
  
  z-index: 2;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.recommendation-container {
  background: white;
  border-radius: 10px;
  margin-left: 10px;
  padding: 30px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.search-box {
  margin-bottom: 30px;
  text-align: center;
}

.search-box h2 {
  color: #2c3e50;
  margin-bottom: 20px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  
}

.search-form .input-with-icon {
  position: relative;
}

.search-form .input-with-icon i {
  position: absolute;
  left: 15px;
  top: 12px;
  color: #7f8c8d;
}

.search-form input {
  padding-left: 40px;
  height: 50px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

.recommendation-box {
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;
}

.recommendation-header {
  background: #f8f9fa;
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}


.recommendation-header h3 {
  margin: 0;
  font-size: 1.2rem;
  color: #2c3e50;
}

.recommendation-header h3 i {
  color: #f39c12;
  margin-right: 10px;
}

.recommendation-tabs .nav-tabs {
  border-bottom: 1px solid #eee;
}

.recommendation-tabs .nav-link {
  border: none;
  color: #7f8c8d;
  font-weight: 500;
  padding: 12px 20px;
}

.recommendation-tabs .nav-link.active {
  color: #3498db;
  border-bottom: 3px solid #3498db;
  background: transparent;
}

.recommendation-tabs .nav-link i {
  margin-right: 8px;
}

.recommendation-list {
  padding: 20px;
  min-height: 300px;
}



.trip-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.12);
 
    transform: translateY(-3px);
}

.trip-info {
  flex: 1;
  padding: 15px;
}

.trip-route {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 8px;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.trip-date {
  color: #7f8c8d;
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.trip-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.trip-price {
    font-size: 1.3rem;
    font-weight: bold;
    color: #27ae60;
    margin-left: 10px;
    white-space: nowrap;
}

.trip-seats {
  color: #7f8c8d;
}

.trip-driver {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 8px;
}
.trip-reservations {
    display: inline-block;
    background: #f8f9fa;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #6c757d;
}
.trip-details-btn {
    display: block;
    text-align: right;
    margin-top: 10px;
}

/* Correction spécifique pour le contenu chargé via "Voir plus" */
.more-trips .trip-card {
    margin-top: 15px;
          margin-left: -280px;

    
    
    
}
.btn-details {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 5px 15px;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-details:hover {
    background-color: #2980b9;
}

.loading-spinner {
  text-align: center;
  padding: 50px 0;
}

.login-prompt {
  text-align: center;
  padding: 50px 0;
}

.login-prompt p {
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .recommendation_section {
    padding: 40px 0;
  }
  
  .search-form .form-group {
    margin-bottom: 15px;
  }
  
  .trip-card {
    flex-direction: column;
  }
}
.recommendation-list {
  padding: 10px;
  max-height: 400px; /* Hauteur fixe */
  overflow-y: auto; /* Activation du scroll */
  scrollbar-width: thin;
}

.recommendation-list::-webkit-scrollbar {
  width: 8px;
}

.recommendation-list::-webkit-scrollbar-thumb {
  background-color: #3498db;
  border-radius: 4px;
}

.recommendation-list::-webkit-scrollbar-track {
  background-color: #f1f1f1;
}

.trip-card {
  margin-bottom: 15px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: white;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.trip-card:not(:first-child) {
  margin-top: 10px;
}
/* Style pour les cartes de trajet compactes */
.compact-trip-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    background: white;
    transition: all 0.3s ease;
}

.compact-trip-card:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.compact-trip-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.compact-trip-price {
    font-weight: bold;
    color: #2ecc71;
    font-size: 1.1rem;
}

.compact-trip-route {
    font-weight: 500;
}

.compact-trip-driver {
    font-size: 0.85rem;
    color: #666;
}

.show-more-container {
    text-align: center;
    margin-top: 15px;
}

.show-more-btn {
    background: none;
    border: none;
    color: #3498db;
    cursor: pointer;
    font-weight: 500;
}

/* Conteneur scrollable */
.recommendation-list-container {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 5px;
}

/* Style pour les onglets */
.nav-tabs .nav-link {
    padding: 8px 15px;
    font-size: 0.9rem;
}
/* Amélioration des cartes de trajet */


.trip-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.12);
    transform: translateY(-3px);
}




.trip-route {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 8px;
    font-weight: 500;
}

.trip-price {
    font-size: 1.3rem;
    font-weight: bold;
    color: #27ae60;
    margin-bottom: 10px;
}

.trip-driver {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: #7f8c8d;
}

/* Bouton Voir plus */
.show-more-btn {
    transition: all 0.3s;
}

.show-more-btn:hover {
    background-color: #3498db;
    color: white;
}

/* Conteneur scrollable */
.recommendation-list {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 10px;
}

/* Style de la scrollbar */
.recommendation-list::-webkit-scrollbar {
    width: 6px;
}

.recommendation-list::-webkit-scrollbar-thumb {
    background-color: #3498db;
    border-radius: 3px;
}

</style>





    <!-- end slider section -->
  </div>
  </div>

  


  <!-- end book section -->


  <!-- about section -->

  <section  id="a-propos" class="  about_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="../images/about-img.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                A PROPOS DE NOUS?
              </h2>
            </div>
            <p>


AGHARAS incarne l'innovation, la fiabilité et la durabilité dans le domaine du covoiturage.
 Notre plateforme offre une solution moderne et conviviale pour connecter les conducteurs et les passagers,
  rendant ainsi les déplacements plus pratiques que jamais.
  Avec notre engagement envers la sécurité, la qualité du service et notre vision d'un avenir plus durable, 
  nous sommes fiers de jouer un rôle actif dans la transformation des habitudes de déplacement tout en préservant l'environnement. 
Rejoignez-nous chez Ibn_Tofail_Covoiturage pour une expérience de covoiturage enrichissante et responsable.
            </p>
            <a href="">
              Lire la suite
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- why section -->

  <section id="pourquoi" class="why_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Pourquoi nous ?
        </h2>
      </div>
      <div class="why_container">
        <div class="row">
          <div class="col-md-4">
            <div class="box b1">
              <div class="img-box">
                <img src="../images/w1.png" alt="" class="" />
              </div>
              <div class="detail-box">
                <h5>
                   Réservation Rapide & Facile
                </h5>
                <p>
                  Agharas simplifie le covoiturage avec une plateforme intuitive qui vous permet de 
                  trouver et réserver un trajet en quelques clics seulement. Plus besoin de perdre du 
                  temps à chercher !


                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box b2">
              <div class="img-box">
                <img src="../images/w2.png" alt="" class="" />
              </div>
              <div class="detail-box">
                <h5>
                  Sécurité Optimale
                </h5>
                <p>Tous nos conducteurs sont vérifiés et notés par la communauté.
                   Nous priorisons votre sécurité avec un système d'évaluation transparent 
                   et des profils authentifiés.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box b3">
              <div class="img-box">
                <img src="../images/w3.png" alt="" class="" />
              </div>
              <div class="detail-box">
                <h5>
                  Satisfaction Garantie
                </h5>
                <p>Rejoignez des milliers d'utilisateurs satisfaits ! Avec Agharas, 
                  économisez sur vos trajets tout en réduisant votre empreinte écologique.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end why section -->

  <!-- app section -->

  <section class="app_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Téléchargez notre application              </h2>
            </div>
            <p>
              Découvrez une nouvelle manière de voyager grâce à notre application mobile intuitive et rapide.
            </p>
            <div class="btn-box">
              <a href="">
                <img src="../images/app_store.png" alt="">
              </a>
              <a href="">
                <img src="../images/google_play.png" alt="">
              </a>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>

  <!-- end app section -->

  <!-- blog section -->

  <section  id ="AA_blog" class="blog_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
                 Dernier Notre Blog

        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="img-box">
              <img src="../images/b1.jpg" alt="">
            </div>
            <div class="detail-box">
              <h6 class="blog_date">
                21 July 2025
              </h6>
              <h5>
                Conseils pour Économiser grâce au Covoiturage
              </h5>
              
              <p>  Face à la hausse des prix du carburant, le covoiturage est devenu une solution intelligente pour conducteurs et passagers.
               Voici nos meilleurs conseils :
              <p>
              </p>
Choisissez des compagnons de voyage fiables grâce aux systèmes d'évaluation.
              </p>
              </p>
Planifiez votre itinéraire à l'avance pour éviter les embouteillages.
              </p>
              <P>
                Maintenez une communication claire avec vos covoitureurs......
              </P>
              <a href="">
                Lire la suite
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="img-box">
              <img src="../images/b2.jpg" alt="">
            </div>
            <div class="detail-box">
              <h6 class="blog_date">
                22 July 2025
              </h6>
              <h5>
                Sécurité & Confiance
              </h5>
              <p>
                Covoiturer en toute sécurité : Le guide complet
              </p>
              <p>3 règles pour vérifier un profil (note, commentaires, vérification...)</p>
              <p>Que faire en cas de problème ?</p>
              <a href="">
                Lire la suite
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end blog section -->

  <!-- client section -->

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Ce que disent nos clients ?
        </h2>
      </div>
    </div>
    <div class="container">
      <div class="col-md-9 mx-auto px-0">
        <div id="carouselExample2Controls" class="carousel slide" data-trip="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExample2Controls" data-slide-to="0" class="active">
              <img src="../images/c1.jpg" alt="">
            </li>
            <li data-target="#carouselExample2Controls" data-slide-to="1">
              <img src="../images/c2.jpg" alt="">
            </li>
            <li data-target="#carouselExample2Controls" data-slide-to="2">
              <img src="../images/c3.jpg" alt="">
            </li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="detail-box">
                <h5>
                  Yasmine El Amrani
                </h5>
                <h6>
                  Employé de bureau
                </h6>
                <p>
                  <i class="fa fa-quote-left" aria-hidden="true"></i>
                  Je l’utilise chaque soumaine pour aller au travail.
                   J’ai fait des rencontres incroyables et je gagne du temps et de l’argent.
                   Une application vraiment utile.

<i class="fa fa-quote-right" aria-hidden="true"></i>
                </p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="detail-box">
                <h5>
                  abderrahim TAMRA
                </h5>
                <h6>
                  Étudiant à l’université
                </h6>
                <p>
                  <i class="fa fa-quote-left" aria-hidden="true"></i>
                  Grâce à Agharass, je peux faire mes trajets universitaires sans stress.
                   C’est rapide, économique et surtout très sécurisé. 
                   Je recommande à tous les étudiants !

                  <i class="fa fa-quote-right" aria-hidden="true"></i>
                </p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="detail-box">
                <h5>
                  Malika  ayt Bennani
                </h5>
                <h6>
                  Commercial itinérant
                </h6>
                <p>
                  <i class="fa fa-quote-left" aria-hidden="true"></i>Avec mon emploi du temps chargé, le covoiturage via Agharass m’a permis d’optimiser mes trajets et de réduire mes frais de déplacement.
                   Un vrai plus pour les pros. <i class="fa fa-quote-right" aria-hidden="true"></i>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>

    <!-- end client section -->
               
    


    
    <section class="ftco-counter ftco-section img bg-light" id="section-counter">
			<div class="overlay"></div>
    	<div class="container">
    		<div class="row">
          <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text text-border d-flex align-items-center">
                <strong class="number" data-number="15">0</strong>
                <span>Années <br>d'expériences</span>
              </div>
            </div>  
          </div>
          <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text text-border d-flex align-items-center">
                <strong class="number" data-number="1090">0</strong>
                <span>Trajets  <br>proposés/mois</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text text-border d-flex align-items-center">
                <strong class="number" data-number="1590">0</strong>
                <span>Membres <br>actifs</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text d-flex align-items-center">
                <strong class="number"  data-number="26">0</strong>
                <span>Villes  <br>connectées </span>
              </div>
            </div>
          </div>
        </div>
    	</div>
    </section>	
  

  

  
  <footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2>
            <img src="../images/Logo.png" alt="AGHARAS Logo" class="logo-icon" style="height: 80px;">

            </h2>
            <p>Roulez malin, covoiturez entre voisins ,

              Moins de voitures, plus de partage  bienvenue sur Agharas.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-twitter"></span></a></li>
                <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-facebook-f"></span></a></li>
                <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-instagram"></span></a></li>
                <li class="ftco-animate"><a href="US/haskouk.html"><span class="fab fa-linkedin-in"></span></a></li> <!-- LinkedIn هنا زدت -->

              </ul>
              
          </div>
        </div>
        <div class="col-md">
          <div class="ftco-footer-widget mb-4 ml-md-5">
            <h2 class="ftco-heading-2">Information</h2>
            <ul class="list-unstyled">
              <li><a href="#" class="py-2 d-block">A propos</a></li>
              <li><a href="#" class="py-2 d-block">Services</a></li>
              <li><a href="#" class="py-2 d-block">Conditions générales</a></li>
              <li><a href="#" class="py-2 d-block">garantie du meilleur prix</a></li>
              <li><a href="#" class="py-2 d-block">Politique &amp; de confidentialité des Cookies</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md">
           <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Support du client</h2>
            <ul class="list-unstyled">
              <li><a href="#" class="py-2 d-block">FAQ</a></li>
              <li><a href="#" class="py-2 d-block">Option de paiement</a></li>
              <li><a href="#" class="py-2 d-block">Conseil de réservations </a></li>
              <li><a href="#" class="py-2 d-block">Comment ça marche</a></li>
              <li><a href="#" class="py-2 d-block">Contactez-nous</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Avez vous des questions?</h2>
            <div class="block-23 mb-3">
              <ul>
                <li><span class="fas fa-map-marker-alt"></span><span class="text"> city university, ERRACHIDIA</span></li>
                <li><span class="fas fa-phone-alt"></span><span class="text">+212 637259733</span></li>
                <li><a href="#"><span class="fas fa-envelope"></span><span class="text">Agharas@gmail.com</span></a></li>
              </ul>
              
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">

          <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart color-danger" aria-hidden="true"></i> by <a href="US/haskouk.html" target="_blank">anas_soufian</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
        </div>
      </div>
    </div>
  </footer>

  


<!-- loader -->
<!-- <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>   -->
<!-- footer section -->
<footer class="footer_section">
  <div class="container">
    <p>
      &copy; <span id="displayYear"></span> Agharas
    </p>
  </div>
</footer>
<!-- footer section -->
<script>

// JavaScript to toggle the dropdown
document.getElementById('profileBtn').addEventListener('click', function() {
  var dropdown = document.getElementById('profileDropdown');
  if (dropdown.style.display === 'block') {
    dropdown.style.display = 'none';
  } else {
    dropdown.style.display = 'block';
  }
});

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
  if (!event.target.matches('.profile-btn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.style.display === 'block') {
        openDropdown.style.display = 'none';
      }
    }
  }
});

</script>
  <script src="../js/jquery-3..1.min.js"></script>
  <script src="../js/bootstrap.js"></script>
  <script src="../js/custom.js"></script>
  <script src="../js/main.js"></script>
  <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Popper -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Your other scripts... -->






  


  <style>
    .ftco-degree-bg {
  position: relative; }
  .ftco-degree-bg:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -33px;
    overflow: visible;
    width: 100%;
    height: 160px;
    z-index: 0;
    -webkit-transform: skewY(-1deg);
    -moz-transform: skewY(-1deg);
    -ms-transform: skewY(-1deg);
    -o-transform: skewY(-1deg);
    transform: skewY(-1deg);
    background-color: #f8f9fa; }
    /* User Menu Styles */
.user-menu {
  position: relative;
  display: inline-block;
  padding-right:-15px;
}
.col-lg-11 {

  padding-right: 0px;
}
.user-avatar {
  display: flex;
  align-items: center;
  padding: 10px 15px ;
  cursor: pointer;
  color: #333;
  transition: all 0.3s ease;
  border-radius: 30px;
  background: rgba(244, 195, 17, 0.93);
}

.user-avatar:hover {
  background: rgba(255,255,255,0.3);
}

.user-avatar i:first-child {
  font-size: 24px;
  margin-right: 8px;
  color: #4a6baf;
}

.user-avatar i:last-child {
  font-size: 12px;
  margin-left: 8px;
  transition: transform 0.3s ease;
}

.user-dropdown {
  position: absolute;
  right: -50px !important;
  top: 100%;
  width: 220px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  padding: 10px 0;
  margin-top: 5px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all 0.3s ease;
  z-index: 1000;
}

.user-dropdown a {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  color: #333;
  text-decoration: none;
  transition: all 0.2s ease;
}

.user-dropdown a:hover {
  background: #f5f7fa;
  color: #4a6baf;
}

.user-dropdown a i {
  width: 24px;
  margin-right: 12px;
  color: #6c757d;
}

.dropdown-divider {
  height: 1px;
  background: #e9ecef;
  margin: 8px 0;
}

/* When dropdown is active */
.user-menu.active .user-dropdown {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.user-menu.active .user-avatar i:last-child {
  transform: rotate(180deg);
}
   
  </style>
</body>

</html>











