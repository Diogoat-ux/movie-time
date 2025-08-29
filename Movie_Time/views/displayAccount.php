<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/connectAccount.php");
    exit();
}

$userController = new UserController();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = "✅ Vos informations ont été mises à jour.";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Movie Time</title>
    <link rel="stylesheet" href="../public/style.css">
</head>

<?php include 'header.php'; ?>

<h2>Mon compte</h2>

<?php if ($message): ?>
    <p style="color: yellow;"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-container">
  <form action="moncompte.php" method="post" class="auth-form" id="updateForm">
    <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>" placeholder="Prénom" required 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    <input type="text" name="surname" value="<?php echo htmlspecialchars($_SESSION['user']['surname']); ?>" placeholder="Nom" required 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" placeholder="Email" required 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    
    <input type="password" name="current_password" placeholder="Mot de passe actuel" 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    <input type="password" name="confirm_new_password" placeholder="Confirmez le nouveau mot de passe" 
           style="display:block; width:80%; max-width:400px; padding:12px; margin:10px auto; background-color:#e0e0e0; border:1px solid #ccc; border-radius:25px; text-align:center; color:#333; font-size:16px;">
    
    <button type="submit" class="btn-classique" 
            style="display:block; margin:20px auto; padding:12px 20px;">
      Mettre à jour
    </button>
  </form>
</div>

<script>
document.getElementById("updateForm").addEventListener("submit", function(e) {
    var currentPwd = this.current_password.value.trim();
    var newPwd = this.new_password.value.trim();
    var confirmPwd = this.confirm_new_password.value.trim();

    if (newPwd !== "" || confirmPwd !== "") {
        if (currentPwd === "") {
            alert("Veuillez saisir votre mot de passe actuel pour changer votre mot de passe.");
            e.preventDefault();
            return;
        }
        if (newPwd !== confirmPwd) {
            alert("Le nouveau mot de passe et sa confirmation ne correspondent pas.");
            e.preventDefault();
            return;
        }
    }
});
</script>

<?php include 'footer.php'; ?>
