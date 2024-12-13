<?php
// compte/contracts.php

// Activer l'affichage des erreurs (uniquement en développement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Récupérer les contrats de l'utilisateur
$contrats = getUserContracts(getCurrentUserID());
?>
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Mes Contrats</h2>
                <a href="../compte/simulations.php" class="btn btn-light btn-sm"><i class="bi bi-plus-circle"></i> Nouveau Contrat</a>
            </div>
            <div class="card-body">
                <?php if (!empty($contrats)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date de Création</th>
                                    <th>Types d'Assurance</th>
                                    <th>Informations</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contrats as $contrat): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($contrat['date_creation'])); ?></td>
                                        <td>
                                            <?php
                                            $typesAssurance = json_decode($contrat['types_assurance'], true);
                                            echo htmlspecialchars(implode(', ', $typesAssurance));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $informations = json_decode($contrat['informations'], true);
                                            foreach ($informations as $type => $infos) {
                                                echo "<strong>" . htmlspecialchars($type) . " :</strong><br>";
                                                foreach ($infos as $key => $value) {
                                                    echo htmlspecialchars(ucfirst($key)) . ": " . htmlspecialchars($value) . "<br>";
                                                }
                                                echo "<br>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="contrat.php?id=<?php echo htmlspecialchars($contrat['id']); ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-file-earmark-text"></i> Voir le contrat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        Vous n'avez aucun contrat pour le moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>


</body>
</html>