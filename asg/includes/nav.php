<?php
// Include necessary functions
require_once "../backend/functions.php";
?>
<nav class="navbar navbar-expand-lg navbar-dark py-lg-4" id="mainNav">
    <div class="container">
        <a class="navbar-brand text-uppercase fw-bold d-lg-none" href="../index.php">St Gabriel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/index.php">Accueil</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/about.php">Nos activités</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/products.php">Nos contrats</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/crew.php">Nos équipes</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/contact.php">Nous contacter</a></li>
                <?php if (getCurrentUserRole() == 1 || getCurrentUserRole() == 2): ?> 
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../compte/moncompte.php">Mon compte</a></li>
                <?php endif; ?>
                <?php if (getCurrentUserRole() >= 4): ?> 
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../gestion/gestion.php">Gestion</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if (!isUserLoggedIn()): ?>
                    <button type="button" id="loginButton" class="btn btn-secondary text-white mx-2">Connexion</button>
                    <button type="button" id="registerButton" class="btn btn-secondary text-white mx-2">Inscription</button>
                <?php else: ?>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="action" value="disconnect">
                        <button type="submit" id="DisconnectButton" class="btn btn-secondary text-white mx-2">Déconnexion</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    <?php if (!isUserLoggedIn()): ?>
        document.getElementById('loginButton').onclick = function () {
            window.location.href = '../pages/formlogin.php';
        };
        document.getElementById('registerButton').onclick = function () {
            window.location.href = '../pages/formregister.php';
        };
    <?php endif; ?>
</script>
