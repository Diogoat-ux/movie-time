<?php
require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $message = $userController->register($name, $surname, $email, $password);
}
?>


<body>
    <?php include 'header.php'; ?>

    <h2>Créer un compte</h2>

    <?php if ($message): ?>
        <p style="color: yellow;"><?php echo $message; ?></p>
    <?php endif; ?>

    <div class="form-container">
  <form action="createAccount.php" method="post" class="auth-form">
    <input type="text" name="name" placeholder="Prénom" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <input type="text" name="surname" placeholder="Nom" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <input type="email" name="email" placeholder="Email" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <input type="password" name="password" placeholder="Mot de passe" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <input type="password" name="confirm_password" placeholder="Confirmez votre mot de passe" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <button type="submit" class="btn-classique" 
            style="display: block; margin: 20px auto; width: auto; padding: 12px 20px;">
      Confirmer
    </button>
  </form>
</div>



<script>
document.querySelector("form.auth-form").addEventListener("submit", function(e) {
    var password = this.password.value;
    var confirmPassword = this.confirm_password.value;
    if (password !== confirmPassword) {
        openPopup("succès", "Les mots de passe ne correspondent pas. Veuillez réessayer.");
        e.preventDefault();
    }
});
</script>



    <?php include 'footer.php'; ?>
</body>
</html>
