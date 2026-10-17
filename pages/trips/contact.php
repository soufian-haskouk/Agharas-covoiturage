<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agharas_bd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
$success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        $success = true;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../../templates/header.php'); ?>	


 
      </section>
    <!-- END nav -->

    
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../images/bg_3.jpg')" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="col-md-9  ">
                <span class="acc">
                  <a href="index.php">Accueil <i class="ion-ios-arrow-forward"></i></a>
                </span>
                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Votre message a été envoyé avec succès. Nous vous contacterons bientôt!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?php endif; ?>
              <h1 class="cn">Contactez-nous</h1>
            </div>
        </div>
      </section>

    <section class="ftco-section contact-section">
      <div class="container">
        
        
        <div class="row d-flex mb-5 contact-info">
          <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-12">
                <div class="border w-100 p-4 rounded mb-2 d-flex">
                  <div class="icon mr-3">
                    <span class="fas fa-map-marker-alt"></span>
                  </div>
                  <p>
                    <span>Address:</span> City university, ER-RACHIDIA-MOROCCO
                  </p>
                </div>
              </div>
              <div class="col-md-12">
                <div class="border w-100 p-4 rounded mb-2 d-flex">
                  <div class="icon mr-3">
                    <span class="fas fa-phone-alt"></span>
                  </div>
                  <p>
                    <span>Phone:</span>
                    <a href="tel://+212637259733">+212 637259733</a>
                  </p>
                </div>
              </div>
              <div class="col-md-12">
                <div class="border w-100 p-4 rounded mb-2 d-flex">
                  <div class="icon mr-3">
                    <span class="fas fa-envelope"></span>
                  </div>
                  <p>
                    <span>Email:</span>
                    <a href="mailto:info@Agharas.com">Agharas@gmail.com</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8 block-9 mb-md-5">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="bg-light p-5 contact-form">
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  name="name"
                  placeholder="Nom Prénom"
                  required
                />
              </div>
              <div class="form-group">
                <input
                  type="email"
                  class="form-control"
                  name="email"
                  placeholder="Votre e-mail: (exemple@gmail.com)"
                  required
                />
              </div>
              <div class="form-group">
                <input 
                  type="text" 
                  class="form-control" 
                  name="subject" 
                  placeholder="Subject" 
                  required
                />
              </div>
              <div class="form-group">
                <textarea
                  name="message"
                  id="message"
                  cols="30"
                  rows="7"
                  class="form-control"
                  placeholder="Message"
                  required
                ></textarea>
              </div>
              <div class="form-group">
                <input
                  type="submit"
                  value="Envoyer Le Message"
                  class="btn btn-primary "
                />
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    

    





    <?php include('../../templates/footer.php'); ?>	

</html>
