<?php
// gestion/users.php

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

// Traitement de la soumission du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    // Récupérer et assainir les données du formulaire
    $userId = intval($_POST['user_id']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $role = intval($_POST['role']);

    // Validation des données
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $message = "Tous les champs sont obligatoires.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'email n'est pas valide.";
        $message_type = "danger";
    } else {
        // Mettre à jour l'utilisateur
        $updateSuccess = updateUser($userId, $firstName, $lastName, $email, $role);
        if ($updateSuccess) {
            $message = "Utilisateur mis à jour avec succès.";
            $message_type = "success";
        } else {
            $message = "Erreur lors de la mise à jour de l'utilisateur.";
            $message_type = "danger";
        }
    }
}

// Récupérer tous les utilisateurs
$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once "../includes/head.php"; ?>
<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <h2>Gestion des Utilisateurs</h2>

        <!-- Afficher les messages de feedback -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo htmlspecialchars($message_type); ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($users)): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php
                                // Définir les rôles en fonction des valeurs
                                $roles = [
                                    1 => 'Utilisateur',
                                    2 => 'Collaborateur',
                                    3 => 'Manager',
                                    4 => 'Administrateur'
                                ];
                                echo htmlspecialchars($roles[$user['role']] ?? 'Rôle Inconnu');
                                ?>
                            </td>
                            <td>
                                <!-- Bouton pour ouvrir le modal d'édition -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo htmlspecialchars($user['id']); ?>">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </button>
                                
                                <!-- Modal d'édition -->
                                <div class="modal fade" id="editUserModal<?php echo htmlspecialchars($user['id']); ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo htmlspecialchars($user['id']); ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <form method="POST" action="users.php">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel<?php echo htmlspecialchars($user['id']); ?>">Modifier l'Utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                          </div>
                                          <div class="modal-body">
                                              <input type="hidden" name="action" value="update_user">
                                              <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                              
                                              <div class="mb-3">
                                                  <label for="first_name_<?php echo htmlspecialchars($user['id']); ?>" class="form-label">Prénom</label>
                                                  <input type="text" class="form-control" id="first_name_<?php echo htmlspecialchars($user['id']); ?>" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                              </div>
                                              
                                              <div class="mb-3">
                                                  <label for="last_name_<?php echo htmlspecialchars($user['id']); ?>" class="form-label">Nom</label>
                                                  <input type="text" class="form-control" id="last_name_<?php echo htmlspecialchars($user['id']); ?>" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                              </div>
                                              
                                              <div class="mb-3">
                                                  <label for="email_<?php echo htmlspecialchars($user['id']); ?>" class="form-label">Email</label>
                                                  <input type="email" class="form-control" id="email_<?php echo htmlspecialchars($user['id']); ?>" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                              </div>
                                              
                                              <div class="mb-3">
                                                  <label for="role_<?php echo htmlspecialchars($user['id']); ?>" class="form-label">Rôle</label>
                                                  <select class="form-select" id="role_<?php echo htmlspecialchars($user['id']); ?>" name="role" required>
                                                      <option value="">-- Sélectionnez --</option>
                                                      <?php foreach ($roles as $key => $roleName): ?>
                                                          <option value="<?php echo htmlspecialchars($key); ?>" <?php if ($user['role'] == $key) echo 'selected'; ?>>
                                                              <?php echo htmlspecialchars($roleName); ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                                          </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>
    </div>

    <?php require_once "../includes/footer.php"; ?>

    <!-- Inclure Bootstrap JS et dépendances -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
