<?php
require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $message = $userController->login($email, $password);
}
?>

<?php include 'header.php'; ?>

<h2>Se connecter</h2>

<?php if ($message): ?>
    <p style="color: yellow;"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-container">
  <form action="connectAccount.php" method="post" class="auth-form">
    <input type="email" name="email" placeholder="Email" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <input type="password" name="password" placeholder="Mot de passe" required 
           style="display: block; width: 80%; max-width: 400px; padding: 12px; margin: 10px auto; background-color: #e0e0e0; border: 1px solid #ccc; border-radius: 25px; text-align: center; color: #333; font-size: 16px;">
    <button type="submit" 
            style="display: block; margin: 20px auto; padding: 12px 20px; background-color: #007BFF; color: white; border: none; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer; transition: all 0.3s ease;">
      Se connecter
    </button>
  </form>
</div>



<?php include 'footer.php'; ?>
