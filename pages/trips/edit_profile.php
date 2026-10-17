<?php
session_start();
require_once '../../templates/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process form data
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    // Basic validation
    $errors = [];
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET prenom = ?, nom = ?, email = ?, telephone = ? WHERE id = ?");
        if ($stmt->execute([$prenom, $nom, $email, $telephone, $_SESSION['user_id']])) {
            $success = "Profil mis à jour avec succès!";
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } else {
            $errors[] = "Erreur lors de la mise à jour";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil - AGHARAS</title>
    <link rel="stylesheet" href="../css/account.css">
</head>
<body>
   <?php include '../../templates/header.php'; ?>

    <main class="account-container">
        <div class="account-grid">
            <section class="account-content">
                <h2><i class="fas fa-user-edit"></i> Modifier Mon Profil</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert success">
                        <p><?= htmlspecialchars($success) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>">
                    </div>
                    
                    <button type="submit" class="btnn">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
            </section>
        </div>
    </main>

<style>

.btnn {
    background:#152329 ;
    color: #fff;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .btnn:hover {
    background: #2a3a38;
  }
.form-group select {
  border: 1px solid #ccc;
  border-radius: 100px;
  box-sizing: border-box;
}
.settings-form {
    margin-top: 1.5rem;}
.form-group{
    padding-left: 40px;


}


.form-group label {
    
    display: block ;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #555;
}
.account-container{
    width: 100%;
    height: 600px !important;

    font-size: 1rem;
    transition: border 0.3s ease;
    
    padding: 50px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 30px;
    
}

.form-control {
    width: 100%;
    height: 600px !important;

    font-size: 1rem;
    transition: border 0.3s ease;
    
    padding-left: -90px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 30px;
    
}

.form-control:focus {
    border-color: #4a6baf;
    outline: none;
    box-shadow: 0 0 0 3px rgba(74, 107, 175, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.btn-save {
   
    background: #4a6baf;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    margin-top: 1rem;
}

.btn-save:hover {
    background: #3a5a9f;
}

.avatar-upload {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.avatar-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #f0f4f9;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-upload-btn {
    background: #f8f9fa;
    border: 1px dashed #ddd;
    padding: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-upload-btn:hover {
    background: #e9ecef;
    border-color: #4a6baf;
}
</style>

    <?php include '../../templates/footer.php'; ?>
</body>
</html>