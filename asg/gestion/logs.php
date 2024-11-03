<?php
// gestion/logs.php

include '../backend/functions.php'; // Inclure les fonctions backend

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (getCurrentUserRole() < 4) { // Vérifier si l'utilisateur a un rôle d'administrateur
    include_once("../backend/redirect.php");
    exit();
}

// Initialiser les messages
$message = '';
$message_type = '';

// Traitement des filtres
$userFilter = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? intval($_GET['user_id']) : null;
$startDate = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : null;

// Récupérer les logs avec les filtres
$logs = getLogs($userFilter, $startDate, $endDate);

// Récupérer tous les utilisateurs pour les filtres
$allUsers = getAllUsers();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2>Bonjour Administrateur,</h2>
                <p class="mb-0">Bienvenue sur la gestion des logs de votre espace administrateur.</p>
            </div>
            <div class="card-body">
                <!-- Formulaire de filtres -->
                <form method="GET" action="logs.php" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">Utilisateur</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">-- Tous les utilisateurs --</option>
                            <?php foreach ($allUsers as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>" <?php if ($userFilter == $user['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary w-100 mb-2"><i class="bi bi-filter"></i> Filtrer</button>
                        <a href="logs.php" class="btn btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Réinitialiser</a>
                    </div>
                </form>

                <!-- Afficher les logs -->
                <?php if (!empty($logs)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="logsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Adresse IP</th>
                                    <th>Date et Heure</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($log['id'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars($log['action'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($log['ip_address'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars(isset($log['timestamp']) ? date('d/m/Y H:i:s', strtotime($log['timestamp'])) : ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        Aucun log trouvé pour les critères sélectionnés.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>

    <!-- Inclure Bootstrap JS et dépendances -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Inclure DataTables JS si vous l'avez intégré -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logsTable = document.getElementById('logsTable');
            if (logsTable) {
                // Initialiser DataTables si vous l'avez intégré
                // Exemple avec DataTables
                // Assurez-vous d'avoir inclus les fichiers CSS et JS de DataTables
                $(document).ready(function() {
                    $('#logsTable').DataTable({
                        "order": [[4, "desc"]], // Trier par date décroissante
                        "pageLength": 25
                    });
                });
            }
        });
    </script>
</body>
</html>
