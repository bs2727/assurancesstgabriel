<?php
// compte/simulations.php

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

// Initialiser les messages
$success_message = '';
$error_message = '';

// Vérifier si l'utilisateur a une simulation active
$hasActive = hasActiveSimulation(getCurrentUserID());

// Traitement du formulaire de création ou d'action sur les simulations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_simulation') {
        if ($hasActive) {
            $error_message = "Vous avez déjà une simulation active. Veuillez la compléter ou la refuser avant d'en créer une nouvelle.";
        } else {
            // Récupérer les types d'assurance sélectionnés
            $typesAssurance = $_POST['types_assurance'] ?? [];
            if (empty($typesAssurance)) {
                $error_message = "Veuillez sélectionner au moins un type d'assurance.";
            } else {
                // Récupérer les informations supplémentaires en fonction des types d'assurance sélectionnés
                $informations = [];

                if (in_array('Auto', $typesAssurance)) {
                    $informations['Auto'] = [
                        'marque' => trim($_POST['marque'] ?? ''),
                        'modele' => trim($_POST['modele'] ?? ''),
                        'annee' => trim($_POST['annee'] ?? ''),
                        'immatriculation' => trim($_POST['immatriculation'] ?? ''),
                    ];
                }

                if (in_array('Habitation', $typesAssurance)) {
                    $informations['Habitation'] = [
                        'adresse_logement' => trim($_POST['adresse_logement'] ?? ''),
                        'surface' => trim($_POST['surface'] ?? ''),
                        'type_logement' => trim($_POST['type_logement'] ?? ''),
                        'valeur_estimee' => trim($_POST['valeur_estimee'] ?? ''),
                    ];
                }

                if (in_array('Santé', $typesAssurance)) {
                    $informations['Santé'] = [
                        'age' => trim($_POST['age'] ?? ''),
                        'situation_familiale' => trim($_POST['situation_familiale'] ?? ''),
                        'historique_medical' => trim($_POST['historique_medical'] ?? ''),
                    ];
                }

                if (in_array('Vie', $typesAssurance)) {
                    $informations['Vie'] = [
                        'beneficiaires' => trim($_POST['beneficiaires'] ?? ''),
                        'capital' => trim($_POST['capital'] ?? ''),
                    ];
                }

                // Valider les informations
                $validationErrors = validateSimulationInput($typesAssurance, $informations);
                if (!empty($validationErrors)) {
                    $error_message = implode('<br>', $validationErrors);
                } else {
                    // Créer la simulation
                    $result = createSimulation(getCurrentUserID(), $typesAssurance, $informations);
                    if ($result) {
                        $success_message = "Votre simulation a été soumise avec succès.";
                        $hasActive = true; // Mise à jour de la variable
                    } else {
                        $error_message = "Erreur lors de la création de la simulation.";
                    }
                }
            }
        }
    } elseif ($_POST['action'] === 'accept_simulation') {
        $simulationId = $_POST['simulation_id'] ?? null;

        if ($simulationId) {
            $result = acceptSimulation($simulationId);
            if ($result['success']) {
                $success_message = "Vous avez accepté la simulation. Un contrat a été créé.";
                $hasActive = false; // L'utilisateur peut maintenant créer une nouvelle simulation
            } else {
                $error_message = $result['message'];
            }
        } else {
            $error_message = "ID de simulation invalide.";
        }
    } elseif ($_POST['action'] === 'decline_simulation') {
        $simulationId = $_POST['simulation_id'] ?? null;

        if ($simulationId) {
            $result = declineSimulation($simulationId);
            if ($result['success']) {
                $success_message = "Vous avez refusé la simulation.";
                $hasActive = false; // L'utilisateur peut maintenant créer une nouvelle simulation
            } else {
                $error_message = $result['message'];
            }
        } else {
            $error_message = "ID de simulation invalide.";
        }
    }
}

// Récupérer les simulations de l'utilisateur
$simulations = getUserSimulations(getCurrentUserID());

// Fonction de validation des entrées de simulation
function validateSimulationInput($typesAssurance, $informations) {
    $errors = [];

    foreach ($typesAssurance as $type) {
        if (!isset($informations[$type])) {
            $errors[] = "Informations manquantes pour le type d'assurance : " . htmlspecialchars($type);
            continue;
        }

        foreach ($informations[$type] as $field => $value) {
            if (empty($value)) {
                $errors[] = "Le champ '" . htmlspecialchars($field) . "' pour l'assurance '" . htmlspecialchars($type) . "' est requis.";
            }
        }
    }

    return $errors;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <h2>Mes Simulations</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de création de simulation -->
        <?php if (!$hasActive): ?>
            <h3>Nouvelle Simulation</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="create_simulation">

                <div class="mb-3">
                    <label for="types_assurance" class="form-label">Types d'Assurance</label>
                    <select class="form-select" id="types_assurance" name="types_assurance[]" multiple required>
                        <option value="Auto">Auto</option>
                        <option value="Habitation">Habitation</option>
                        <option value="Santé">Santé</option>
                        <option value="Vie">Vie</option>
                    </select>
                    <small class="form-text text-muted">Maintenez la touche Ctrl (Windows) ou Commande (Mac) pour sélectionner plusieurs options.</small>
                </div>

                <!-- Conteneur pour les champs supplémentaires -->
                <div id="additional-fields"></div>

                <button type="submit" class="btn btn-primary">Soumettre la Simulation</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info">
                Vous avez déjà une simulation active. Veuillez la compléter ou la refuser avant d'en créer une nouvelle.
            </div>
        <?php endif; ?>

        <!-- Liste des simulations -->
        <h3 class="mt-5">Historique des Simulations</h3>
        <?php if (!empty($simulations)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Types d'Assurance</th>
                        <th>Statut</th>
                        <th>Réponse</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($simulations as $simulation): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($simulation['date_creation'])); ?></td>
                            <td>
                                <?php
                                $typesAssurance = json_decode($simulation['types_assurance'], true);
                                echo htmlspecialchars(implode(', ', $typesAssurance));
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($simulation['statut']); ?></td>
                            <td>
                                <?php
                                if ($simulation['statut'] == 'Répondu') {
                                    echo nl2br(htmlspecialchars($simulation['reponse']));
                                } elseif ($simulation['statut'] == 'Acceptée' || $simulation['statut'] == 'Refusée') {
                                    echo 'Simulation ' . strtolower($simulation['statut']);
                                } else {
                                    echo 'En attente de réponse';
                                }
                                ?>
                            </td>
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
                                <?php elseif ($simulation['statut'] == 'Acceptée'): ?>
                                    <a href="contracts.php?id=<?php echo htmlspecialchars($simulation['id']); ?>" class="btn btn-primary btn-sm">Voir le contrat</a>
                                <?php else: ?>
                                    --
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez aucune simulation pour le moment.</p>
        <?php endif; ?>
    </div>

    <?php require_once "../includes/footer.php"; ?>


    <!-- Script pour afficher les champs supplémentaires en fonction des types d'assurance sélectionnés -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typesAssuranceSelect = document.getElementById('types_assurance');
            const additionalFieldsContainer = document.getElementById('additional-fields');

            typesAssuranceSelect.addEventListener('change', function() {
                const selectedOptions = Array.from(typesAssuranceSelect.selectedOptions).map(option => option.value);

                // Vider le conteneur des champs supplémentaires
                additionalFieldsContainer.innerHTML = '';

                if (selectedOptions.includes('Auto')) {
                    // Ajouter les champs spécifiques pour l'assurance auto
                    const autoFields = `
                        <h4>Informations sur le véhicule</h4>
                        <div class="mb-3">
                            <label for="marque" class="form-label">Marque</label>
                            <input type="text" class="form-control" id="marque" name="marque" required>
                        </div>
                        <div class="mb-3">
                            <label for="modele" class="form-label">Modèle</label>
                            <input type="text" class="form-control" id="modele" name="modele" required>
                        </div>
                        <div class="mb-3">
                            <label for="annee" class="form-label">Année</label>
                            <input type="number" class="form-control" id="annee" name="annee" required>
                        </div>
                        <div class="mb-3">
                            <label for="immatriculation" class="form-label">Numéro d'immatriculation</label>
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
                        </div>
                    `;
                    additionalFieldsContainer.insertAdjacentHTML('beforeend', autoFields);
                }

                if (selectedOptions.includes('Habitation')) {
                    // Ajouter les champs spécifiques pour l'assurance habitation
                    const habitationFields = `
                        <h4>Informations sur le logement</h4>
                        <div class="mb-3">
                            <label for="adresse_logement" class="form-label">Adresse du logement</label>
                            <input type="text" class="form-control" id="adresse_logement" name="adresse_logement" required>
                        </div>
                        <div class="mb-3">
                            <label for="surface" class="form-label">Surface (en m²)</label>
                            <input type="number" class="form-control" id="surface" name="surface" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_logement" class="form-label">Type de logement</label>
                            <select class="form-select" id="type_logement" name="type_logement" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="Appartement">Appartement</option>
                                <option value="Maison">Maison</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="valeur_estimee" class="form-label">Valeur estimée (€)</label>
                            <input type="number" class="form-control" id="valeur_estimee" name="valeur_estimee" required>
                        </div>
                    `;
                    additionalFieldsContainer.insertAdjacentHTML('beforeend', habitationFields);
                }

                if (selectedOptions.includes('Santé')) {
                    // Ajouter les champs spécifiques pour l'assurance santé
                    const santeFields = `
                        <h4>Informations personnelles pour l'assurance santé</h4>
                        <div class="mb-3">
                            <label for="age" class="form-label">Âge</label>
                            <input type="number" class="form-control" id="age" name="age" required>
                        </div>
                        <div class="mb-3">
                            <label for="situation_familiale" class="form-label">Situation familiale</label>
                            <select class="form-select" id="situation_familiale" name="situation_familiale" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                                <option value="Divorcé(e)">Divorcé(e)</option>
                                <option value="Veuf(ve)">Veuf(ve)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="historique_medical" class="form-label">Historique médical</label>
                            <textarea class="form-control" id="historique_medical" name="historique_medical" rows="3" required></textarea>
                        </div>
                    `;
                    additionalFieldsContainer.insertAdjacentHTML('beforeend', santeFields);
                }

                if (selectedOptions.includes('Vie')) {
                    // Ajouter les champs spécifiques pour l'assurance vie
                    const vieFields = `
                        <h4>Informations pour l'assurance vie</h4>
                        <div class="mb-3">
                            <label for="beneficiaires" class="form-label">Bénéficiaires</label>
                            <input type="text" class="form-control" id="beneficiaires" name="beneficiaires" required>
                        </div>
                        <div class="mb-3">
                            <label for="capital" class="form-label">Capital assuré (€)</label>
                            <input type="number" class="form-control" id="capital" name="capital" required>
                        </div>
                    `;
                    additionalFieldsContainer.insertAdjacentHTML('beforeend', vieFields);
                }
            });

            // Déclencher l'événement 'change' au chargement pour afficher les champs si des options sont déjà sélectionnées
            typesAssuranceSelect.dispatchEvent(new Event('change'));
        });
    </script>
</body>
</html>