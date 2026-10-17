<!DOCTYPE html>
<html lang="en">

<?php include('../../templates/header.php'); ?>	

<!-- Hero Section -->
<section class="hero-wrap hhh hero-wrap-2 js-fullheight" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../images/bg_3.jpg')" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row align-items-center justify-content-center" style="height: 400px;">
            <div class="col-md-9 text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="Accueil.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-gold" aria-current="page">Nos Services</li>
                    </ol>
                </nav>
                <h1 class="text-white display-4 mb-4">Découvrez Nos Services</h1>
                <p class="text-light lead">Une mobilité intelligente, économique et écologique</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5 hh bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5  mb-4">Nos Avantages</h3>
                        <div class="service-features">
                            <a href="#" class="feature-card" data-title="Trajets Réguliers ou Occasionnels" data-desc="Profitez de trajets flexibles, que ce soit pour vos déplacements quotidiens ou des voyages occasionnels. Trouvez facilement un conducteur ou un passager selon vos besoins.">
                                <div class="feature-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <span>Trajets Réguliers ou Occasionnels</span>
                            </a>
                            
                            <a href="#" class="feature-card" data-title="Service Client Réactif" data-desc="Notre équipe d'assistance est disponible pour répondre rapidement à toutes vos questions et résoudre vos problèmes, assurant une expérience sans souci.">
                                <div class="feature-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <span>Service Client Réactif</span>
                            </a>
                            
                            <a href="#" class="feature-card" data-title="Trajets Écologiques et Responsables" data-desc="Réduisez votre empreinte carbone en partageant vos trajets. Ensemble, contribuons à un avenir plus vert et responsable.">
                                <div class="feature-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <span>Trajets Écologiques</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="service-description" id="service-description-box">
                            <div class="badge bg-gold text-dark mb-3">Première au Maroc</div>
                            <h2 id="service-title" class="mb-4">Réservation Facile et Rapide</h2>
                            <p id="service-desc" class="lead text-muted mb-4">
                                Avec AGHARAS, réserver un trajet n'a jamais été aussi simple. Notre plateforme conviviale vous permet de trouver et de réserver rapidement des trajets avec des conducteurs fiables et des passagers partageant les mêmes intérêts.
                            </p>
                            <p id="service-desc-continued">
                                Fini les tracas liés à la planification : réservez votre place en quelques clics et partez l'esprit tranquille. 
                                Conçu spécialement pour répondre aux besoins de mobilité au Maroc, notre service favorise une expérience locale, économique et respectueuse de l'environnement.
                            </p>
                            
                            <div class="service-benefits mt-5 pt-4 border-top">
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="benefit-item ">
                                            <div class="benefit-icon mb-2">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <span>Gain de temps</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="benefit-item ">
                                            <div class="benefit-icon mb-2">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <span>Économies</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="benefit-item">
                                            <div class="benefit-icon mb-2">
                                                <i class="fas fa-leaf"></i>
                                            </div>
                                            <span>Écologique</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="benefit-item ">
                                            <div class="benefit-icon mb-2">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <span>Communauté fiable</span>
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

<?php include('../../templates/footer.php'); ?>

<script>
// Feature button click handler
document.addEventListener('DOMContentLoaded', function() {
    const featureCards = document.querySelectorAll('.feature-card');
    const serviceTitle = document.getElementById('service-title');
    const serviceDesc = document.getElementById('service-desc');
    const serviceDescContinued = document.getElementById('service-desc-continued');
    const benefits = document.querySelector('.service-benefits');

    const defaultContent = {
        title: 'Réservation Facile et Rapide',
        desc: `Avec AGHARAS, réserver un trajet n'a jamais été aussi simple. Notre plateforme conviviale vous permet de trouver et de réserver rapidement des trajets avec des conducteurs fiables et des passagers partageant les mêmes intérêts.`,
        continued: `Fini les tracas liés à la planification : réservez votre place en quelques clics et partez l'esprit tranquille. Conçu spécialement pour répondre aux besoins de mobilité au Maroc, notre service favorise une expérience locale, économique et respectueuse de l'environnement.`
    };

    featureCards.forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            serviceTitle.textContent = this.getAttribute('data-title');
            serviceDesc.textContent = this.getAttribute('data-desc');
            serviceDescContinued.textContent = '';
            benefits.style.display = 'none';
            
            // Add active class
            featureCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Reset to default on click anywhere in the description box
    document.getElementById('service-description-box').addEventListener('click', function(e) {
        if (e.target === this) {
            resetContent();
        }
    });

    function resetContent() {
        serviceTitle.textContent = defaultContent.title;
        serviceDesc.textContent = defaultContent.desc;
        serviceDescContinued.textContent = defaultContent.continued;
        benefits.style.display = '';
        featureCards.forEach(c => c.classList.remove('active'));
    }
});
</script>

<style>
/* Color Variables */
:root {
    --primary: #152321;
    --secondary: #fdd120;
    --gold-light: #fdd120;
    --gold-dark: #d4b221;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
}

/* Hero Section */

.hh{
  margin-top: -80px;
}
.hhh{
  padding-top: 150px;
}
.hero-wrap .breadcrumb {
    background: transparent;
    padding: 0;
}

.hero-wrap .breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.3s;
}

.hero-wrap .breadcrumb-item a:hover {
    color: var(--gold-light);
}

.hero-wrap .breadcrumb-item.active {
    color: var(--gold-light);
}

.hero-wrap .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.5);
}

.text-gold {
    color: var(--gold-light);
}

/* Cards */
.card {
    border-radius: 12px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Feature Cards */
.service-features {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.feature-card {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 8px;
    text-decoration: none;
    color: var(--dark);
    transition: all 0.3s;
    border: 1px solid rgba(0,0,0,0.05);
}

.feature-card:hover, .feature-card.active {
    background: var(--primary);
    color: white;
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.feature-card:hover .feature-icon, .feature-card.active .feature-icon {
    background: var(--gold-light);
    color: var(--primary);
}

.feature-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(253, 209, 32, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: var(--gold-dark);
    transition: all 0.3s;
}

/* Service Description */
#service-description-box {
    transition: all 0.3s;
}

#service-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 1rem;
    position: relative;
}

#service-title:after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--gold-light), var(--gold-dark));
    margin: 15px 0;
    border-radius: 2px;
}

#service-desc {
    font-size: 1.1rem;
    color: var(--gray);
    line-height: 1.7;
}

/* Benefits */
.benefit-item {
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s;
}

.benefit-item:hover {
    background: rgba(253, 209, 32, 0.1);
}

.benefit-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold-light), var(--gold-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Badge */
.badge.bg-gold {
    background: linear-gradient(135deg, var(--gold-light), var(--gold-dark));
    color: var(--primary);
    font-weight: 600;
    padding: 5px 15px;
    border-radius: 20px;
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .hero-wrap {
        height: auto !important;
        padding: 100px 0;
    }
    
    .service-features {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .feature-card {
        flex: 1 1 100%;
    }
}

@media (max-width: 767.98px) {
    #service-title {
        font-size: 1.5rem;
    }
    
    .benefit-item {
        margin-bottom: 15px;
    }
}
</style>
</html>