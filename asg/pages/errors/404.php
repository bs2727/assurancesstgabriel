<?php
// pages/errors/404.php

// Désactiver l'affichage des erreurs en production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
?>
<head>
    <?php include '../includes/head.php'; ?>
    <title>Page Non Trouvée - 404</title>

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/nav.php'; ?>

    <div class="container error-container">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white text-center">
                <h2>Erreur 404</h2>
                <p>Page Non Trouvée</p>
            </div>
            <div class="card-body text-center">
                <h4 class="mb-4">Oups ! La page que vous recherchez n'existe pas.</h4>
                <p>Il semble que l'adresse que vous avez saisie est incorrecte ou que la page a été déplacée.</p>
                <a href="/asg/pages/index.php" class="btn btn-primary mt-3">
                    <i class="bi bi-house-door-fill"></i> Retour à l'Accueil
                </a>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Assurances Saint-Gabriel - Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
