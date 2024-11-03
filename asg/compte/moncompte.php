<?php
// ../compte/moncompte.php

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fonctions backend
require_once "../backend/functions.php";

// Vérifier si l'utilisateur est connecté
if (!isUserLoggedIn()) {
    include_once("../backend/redirect.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$firstName = getCurrentUserFname();
$userRole = getCurrentUserRole();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php
    require_once "../includes/header.php";
    require_once "../includes/nav.php";
    ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2>Bonjour <?php echo htmlspecialchars($firstName); ?>,</h2>
            </div>
            <div class="card-body">
                    <h2>Bienvenue sur votre espace</h2>
                <div class="row mt-4">
                    <div class="col-md-4 text-center">
                        <a href="informations.php" class="btn btn-outline-primary btn-lg w-100 mb-3">
                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i><br>
                            Mes informations
                        </a>
                    </div>

                    <div class="col-md-4 text-center">
                        <a href="simulations.php" class="btn btn-outline-success btn-lg w-100 mb-3">
                            <i class="bi bi-bar-chart-line" style="font-size: 2rem;"></i><br>
                            Mes simulations
                        </a>
                    </div>

                    <div class="col-md-4 text-center">
                        <a href="contracts.php" class="btn btn-outline-warning btn-lg w-100 mb-3">
                            <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i><br>
                            Mes contrats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>
</body>
</html>