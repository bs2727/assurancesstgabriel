<?php
session_start();
include_once("../backend/functions.php");
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
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../section/index.php">Accueil</a>
                </li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../section/about.php">Nos activités</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../section/products.php">Nos contrats</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../section/crew.php">Nos équipes</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../section/contact.php">Nous contacter</a></li>
                <?php if (getCurrentUserRole() != 0) { ?>
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/myaccount.php">Mon compte</a></li>
                <?php } ?>
                <?php if (getCurrentUserRole() == 1) { ?>
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/contrat.php">Contrat</a></li>
                <?php } ?>
                <?php if (getCurrentUserRole() == 2) { ?>
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase"
                            href="../pages/pannelcontrat.php">Gestion contrat</a></li>
                <?php } ?>
                <?php if (getCurrentUserRole() == 3) { ?>
                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="../pages/adminpannel.php">Gestion</a></li>
                <?php } ?>
            </ul>
            <div class="d-flex">
                <?php if (!isUserLoggedIn()) { ?>
                    <button type="button" id="loginButton" class="btn btn-secondary text-white mx-2">Connexion</button>
                    <button type="button" id="registerButton" class="btn btn-secondary text-white mx-2">Inscription</button>
                <?php } else { ?>
                    <button type="button" id="DisconnectButton"
                        class="btn btn-secondary text-white mx-2">Déconnexion</button>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

<script>
    <?php if (!isset($_SESSION["loggedin"])) { ?>
        document.getElementById('loginButton').onclick = function () {
            window.location.href = '../pages/formlogin.php';
        };
        document.getElementById('registerButton').onclick = function () {
            window.location.href = '../pages/formregister.php';
        };
    <?php } else { ?>
        document.getElementById('DisconnectButton').onclick = function () {
            window.location.href = '../backend/disconnect.php';
        };
    <?php } ?>
</script>