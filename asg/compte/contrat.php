<?php
// compte/contrat.php

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
    header('Location: ../pages/login.php');
    exit();
}

// Récupérer l'ID du contrat depuis l'URL
$contratId = $_GET['id'] ?? null;

if (!$contratId) {
    echo "Contrat introuvable.";
    exit();
}

// Récupérer le contrat correspondant
$contrat = getContractById($contratId);

// Vérifier que le contrat existe et appartient à l'utilisateur connecté
if (!$contrat || $contrat['user_id'] != getCurrentUserID()) {
    echo "Vous n'avez pas accès à ce contrat.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h2>Assurances Saint-Gabriel</h2>
                <p>Adresse de l'entreprise | Téléphone | Email</p>
            </div>
            <div class="card-body">
                <h4 class="text-center">Détails du Contrat</h4>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($contrat['first_name'] . ' ' . $contrat['last_name']); ?></p>
                        <p><strong>Date de création :</strong> <?php echo date('d/m/Y', strtotime($contrat['date_creation'])); ?></p>
                        <p><strong>Type d'Assurance :</strong> <?php
                            $typesAssurance = json_decode($contrat['types_assurance'], true);
                            echo htmlspecialchars(implode(', ', $typesAssurance));
                        ?></p>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $informations = json_decode($contrat['informations'], true);
                        foreach ($informations as $type => $infos) {
                            echo "<h5 class='mt-3'>" . htmlspecialchars($type) . " :</h5>";
                            echo "<table class='table table-bordered'>";
                            foreach ($infos as $key => $value) {
                                echo "<tr><th class='bg-light'>" . htmlspecialchars(ucfirst($key)) . " :</th><td>" . htmlspecialchars($value) . "</td></tr>";
                            }
                            echo "</table>";
                        }
                        ?>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="contrat_pdf.php?id=<?php echo htmlspecialchars($contrat['id']); ?>" class="btn btn-success">
                        <i class="bi bi-download"></i> Télécharger le contrat en PDF
                    </a>
                </div>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Merci de votre confiance.</p>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>


</body>
</html>
