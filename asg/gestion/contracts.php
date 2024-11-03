<?php
// gestion/contracts.php

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

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isUserLoggedIn() || getCurrentUserRole() < 4) {
    header('Location: ../pages/login.php');
    exit();
}

// Initialiser le message
$message = '';

// Traitement du formulaire de réponse à une simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'respond_simulation') {
        $simulationId = $_POST['simulation_id'] ?? null;
        $reponse = trim($_POST['reponse'] ?? '');

        if ($simulationId && !empty($reponse)) {
            // Mettre à jour le statut de la simulation et enregistrer la réponse
            $result = respondSimulation($simulationId, $reponse);
            if ($result['success']) {
                $message = "La réponse a été envoyée au client.";
            } else {
                $message = $result['message'];
            }
        } else {
            $message = "Veuillez fournir une réponse valide.";
        }
    }
}

// Récupérer toutes les simulations en attente et répondues
$simulationsEnAttente = getSimulationsByStatus('En attente');
$simulationsRepondues = getSimulationsByStatus('Répondu');

// Récupérer tous les contrats
$contrats = getAllContracts();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <h2>Gestion des Contrats et Simulations</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="gestionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="simulations-en-attente-tab" data-bs-toggle="tab" data-bs-target="#simulations-en-attente" type="button" role="tab" aria-controls="simulations-en-attente" aria-selected="true">Simulations en attente</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="simulations-repondues-tab" data-bs-toggle="tab" data-bs-target="#simulations-repondues" type="button" role="tab" aria-controls="simulations-repondues" aria-selected="false">Simulations répondues</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contrats-tab" data-bs-toggle="tab" data-bs-target="#contrats" type="button" role="tab" aria-controls="contrats" aria-selected="false">Contrats Créés</button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="gestionTabsContent">
            <!-- Simulations en attente -->
            <div class="tab-pane fade show active" id="simulations-en-attente" role="tabpanel" aria-labelledby="simulations-en-attente-tab">
                <h3 class="mt-4">Simulations en attente</h3>
                <?php if (!empty($simulationsEnAttente)): ?>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Types d'Assurance</th>
                                <th>Informations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($simulationsEnAttente as $simulation): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($simulation['date_creation'])); ?></td>
                                    <td><?php echo htmlspecialchars($simulation['first_name'] . ' ' . $simulation['last_name']); ?></td>
                                    <td>
                                        <?php
                                        $typesAssurance = json_decode($simulation['types_assurance'], true);
                                        echo htmlspecialchars(implode(', ', $typesAssurance));
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $informations = json_decode($simulation['informations'], true);
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
                                        <!-- Formulaire pour répondre à la simulation -->
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="respond_simulation">
                                            <input type="hidden" name="simulation_id" value="<?php echo htmlspecialchars($simulation['id']); ?>">
                                            <div class="mb-3">
                                                <label for="reponse_<?php echo htmlspecialchars($simulation['id']); ?>" class="form-label">Réponse</label>
                                                <textarea class="form-control" id="reponse_<?php echo htmlspecialchars($simulation['id']); ?>" name="reponse" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">Envoyer la Réponse</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune simulation en attente.</p>
                <?php endif; ?>
            </div>

            <!-- Simulations répondues -->
            <div class="tab-pane fade" id="simulations-repondues" role="tabpanel" aria-labelledby="simulations-repondues-tab">
                <h3 class="mt-4">Simulations répondues</h3>
                <?php if (!empty($simulationsRepondues)): ?>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Types d'Assurance</th>
                                <th>Réponse</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($simulationsRepondues as $simulation): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($simulation['date_reponse'])); ?></td>
                                    <td><?php echo htmlspecialchars($simulation['first_name'] . ' ' . $simulation['last_name']); ?></td>
                                    <td>
                                        <?php
                                        $typesAssurance = json_decode($simulation['types_assurance'], true);
                                        echo htmlspecialchars(implode(', ', $typesAssurance));
                                        ?>
                                    </td>
                                    <td><?php echo nl2br(htmlspecialchars($simulation['reponse'])); ?></td>
                                    <td><?php echo htmlspecialchars($simulation['statut']); ?></td>
                                    <td>
                                        <?php if ($simulation['statut'] == 'Répondu'): ?>
                                            <form method="POST" action="" style="display:inline-block;">
                                                <input type="hidden" name="action" value="accept_simulation">
                                                <input type="hidden" name="simulation_id" value="<?php echo htmlspecialchars($simulation['id']); ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                                            </form>
                                            <form method="POST" action="" style="display:inline-block;">
                                                <input type="hidden" name="action" value="decline_simulation">
                                                <input type="hidden" name="simulation_id" value="<?php echo htmlspecialchars($simulation['id']); ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                            </form>
                                        <?php else: ?>
                                            --
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune simulation répondue.</p>
                <?php endif; ?>
            </div>

            <!-- Contrats Créés -->
            <div class="tab-pane fade" id="contrats" role="tabpanel" aria-labelledby="contrats-tab">
                <h3 class="mt-4">Contrats Créés</h3>
                <?php if (!empty($contrats)): ?>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Types d'Assurance</th>
                                <th>Informations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contrats as $contrat): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($contrat['date_creation'])); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['first_name'] . ' ' . $contrat['last_name']); ?></td>
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
                                        <a href="../compte/contracts.php?id=<?php echo htmlspecialchars($contrat['id']); ?>" class="btn btn-primary btn-sm">Voir le contrat</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun contrat créé.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>


    
</body>
</html>