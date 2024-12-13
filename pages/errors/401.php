
<?php
// pages/errors/401.php

// Désactiver l'affichage des erreurs en production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
?>
<head>
    <?php include '../includes/head.php'; ?>
    <title>Non Autorisé - 401</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/nav.php'; ?>

    <div class="container error-container">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white text-center">
                <h2>Erreur 401</h2>
                <p>Non Autorisé</p>
            </div>
            <div class="card-body text-center">
                <h4 class="mb-4">Vous devez vous authentifier pour accéder à cette ressource.</h4>
                <p>Veuillez vous connecter ou contacter l'administrateur si vous pensez que c'est une erreur.</p>
                <a href="/asg/pages/formlogin.php" class="btn btn-primary mt-3">
                    <i class="bi bi-box-arrow-in-right"></i> Se Connecter
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
