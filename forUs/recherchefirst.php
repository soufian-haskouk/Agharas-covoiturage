<!DOCTYPE html>
<html lang="en">

<?php include('../../templates/header.php'); ?>	

  <!-- Hero Section -->
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../images/bg_3.jpg')" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="col-md-9  ">
                <span class="acc">
                  <a href="Accueil.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
                </span>
              
              <h1 class="cn">Nos Services</h1>
            </div>
        </div>
      </section>

  <!-- Services Section -->
  <section class="services-section py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="service-price">À partir de 50 MAD</div>
            <div class="service-button">
            </div><ul class="service-features">
                <li class="mama"><a href="#" class="btn btn-primary feature-link" data-title="Trajets Réguliers ou Occasionnels" data-desc="Profitez de trajets flexibles, que ce soit pour vos déplacements quotidiens ou des voyages occasionnels. Trouvez facilement un conducteur ou un passager selon vos besoins.">Trajets Réguliers ou Occasionnels</a></li>
                <li class="mama"><a href="#" class="btn btn-primary feature-link" data-title="Service Client Réactif" data-desc="Notre équipe d'assistance est disponible pour répondre rapidement à toutes vos questions et résoudre vos problèmes, assurant une expérience sans souci.">Service Client Réactif</a></li>
                <li class="mama"><a href="#" class="btn btn-primary feature-link" data-title="Trajets Écologiques et Responsables" data-desc="Réduisez votre empreinte carbone en partageant vos trajets. Ensemble, contribuons à un avenir plus vert et responsable.">Trajets Écologiques et Responsables</a></li>
              </ul>
          </div>
        </div>
        
        <div class="col-lg-8">
          <div class="service-description" id="service-description-box">
            <h2 id="service-title">Réservation Facile et Rapide</h2>
            <p id="service-desc">Avec AGHARAS, réserver un trajet n'a jamais été aussi simple. Notre plateforme conviviale vous permet de trouver et de réserver rapidement des trajets avec des conducteurs fiables et des passagers partageant les mêmes intérêts.
              Fini les tracas liés à la planification : réservez votre place en quelques clics et partez l'esprit tranquille. 
              Conçu spécialement pour répondre aux besoins de mobilité au Maroc, notre service favorise une expérience locale, économique et respectueuse de l’environnement.
            </p>
            <div class="service-benefits">
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Gain de temps</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Économies</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Écologique</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Communauté fiable</span>
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
const featureLinks = document.querySelectorAll('.feature-link');
const serviceTitle = document.getElementById('service-title');
const serviceDesc = document.getElementById('service-desc');
const benefits = document.querySelector('.service-benefits');

const defaultContent = {
  title: 'Réservation Facile et Rapide',
  desc: `Avec AGHARAS, réserver un trajet n'a jamais été aussi simple. Notre plateforme conviviale vous permet de trouver et de réserver rapidement des trajets avec des conducteurs fiables et des passagers partageant les mêmes intérêts.\nFini les tracas liés à la planification : réservez votre place en quelques clics et partez l'esprit tranquille.\nConçu spécialement pour répondre aux besoins de mobilité au Maroc, notre service favorise une expérience locale, économique et respectueuse de l’environnement.`
};

featureLinks.forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    serviceTitle.textContent = this.getAttribute('data-title');
    serviceDesc.textContent = this.getAttribute('data-desc');
    benefits.style.display = 'none';
  });
});

// Optional: Reset to default on double click on the title
serviceTitle.addEventListener('dblclick', function() {
  serviceTitle.textContent = defaultContent.title;
  serviceDesc.textContent = defaultContent.desc;
  benefits.style.display = '';
});
</script>
<style>
.service-features .feature-link {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  background: linear-gradient(90deg, #fdd120 0%, #d4b221 100%);
  color: #152321;
  font-weight: 600;
  border: none;
  font-size: 16px;
  box-shadow: 0 2px 8px rgba(212, 178, 33, 0.10);
  transition: background 0.3s, color 0.3s, box-shadow 0.3s;
}
.service-features .feature-link:hover, .service-features .feature-link.active {
  background: #152321;
  color: #fdd120;
}
#service-description-box {
  transition: box-shadow 0.3s, background 0.3s;
  background: #f4f4f4;
     border-radius: 6px;


  padding: 32px 28px 24px 28px;
  min-height: 260px;
}
#service-title {
  font-size: 2rem;
  font-weight: bold;
  color: #152321;
  margin-bottom: 18px;
  background: linear-gradient(90deg, #fdd120 0%, #d4b221 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
}
#service-desc {
  font-size: 1.1rem;
  color: #222;
  line-height: 1.6;
  margin-bottom: 18px;
  min-height: 80px;
}
</style>
    </html>
