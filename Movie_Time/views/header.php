<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Movie Time</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        
<script src="../Javascript/Popup.js"></script>
<script src="../Javascript/ConfirmatioPopUp.js"></script>
<script src="../Javascript/OpenInputPopUp.js"></script>
        <div class="navbar">
            <a href="../views/index.php" class="logo">Movie Time</a>
            
            <form action="../public/Router.php" method="get" class="search-form">
                <input type="hidden" name="controller" value="index">
                <input type="hidden" name="action" value="search">
                <input type="text" name="query" placeholder="Rechercher un film ou une série" required>
                <button type="submit">Rechercher</button>
            </form>

            <div class="button-group">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- If the user is logged in -->
                    <button id="moncompte" class="btn-classique">Mon compte</button>
                    <button id="afficherListes" class="btn-classique">Afficher ma liste</button>
                    <button id="logout" class="btn-classique">Se déconnecter</button>
                <?php else: ?>
                    <!-- If the user is not logged in -->
                    <button id="créeruncompte" class="btn-classique">Créer un compte</button>
                    <button id="seconnecter" class="btn-classique">Se connecter</button>
                <?php endif; ?>
            </div>
        </div>

        <script>
            // For connected users
            <?php if (isset($_SESSION['user'])): ?>
                // "My account” button
                document.getElementById('moncompte').addEventListener('click', function() {
                    window.location.href = '../views/displayAccount.php';
                });

                // "Show my list” button
                document.getElementById('afficherListes').addEventListener('click', function() {
                    window.location.href = '../views/displayLists.php';
                });

                // "Logout” button
                document.getElementById('logout').addEventListener('click', function() {
                    window.location.href = '../public/Router.php?controller=user&action=logout';
                });
            <?php else: ?>
                // For offline users
                document.getElementById('créeruncompte').addEventListener('click', function() {
                    window.location.href = '../views/createAccount.php';
                });
                document.getElementById('seconnecter').addEventListener('click', function() {
                    window.location.href = '../views/connectAccount.php';
                });
            <?php endif; ?>
        </script>
    </header>

    <main>
