<?php
// ../compte/informations.php

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

// Récupérer les informations de l'utilisateur pour pré-remplir le formulaire
$userId = getCurrentUserID();
$firstName = getCurrentUserFName();
$lastName = getCurrentUserLName();
$email = getCurrentUserInfo('email');
$phone = getCurrentUserPhone();
$address = getCurrentUserAddress();

// Gérer la soumission du formulaire pour la mise à jour des informations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $updatedFirstName = trim($_POST['first_name']);
    $updatedLastName = trim($_POST['last_name']);
    $updatedEmail = trim($_POST['email']);
    $updatedPhone = trim($_POST['phone']);
    $updatedAddress = trim($_POST['address']);

    if ($updatedFirstName && $updatedLastName && $updatedEmail && $updatedPhone && $updatedAddress) {
        $updateResult = updateUserInfo($userId, $updatedFirstName, $updatedLastName, $updatedEmail, $updatedPhone, $updatedAddress);
        if ($updateResult['success']) {
            $success_message = "Vos informations ont été mises à jour avec succès.";
            // Mettre à jour les variables de session
            $_SESSION['first_name'] = $updatedFirstName;
            $_SESSION['last_name'] = $updatedLastName;
            $_SESSION['email'] = $updatedEmail;
        } else {
            $error_message = $updateResult['message'];
        }
    } else {
        $error_message = "Tous les champs sont requis pour la mise à jour.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Mes Informations</title>
<body>
    <?php
    require_once "../includes/header.php";
    require_once "../includes/nav.php";
    ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Mes Informations</h2>
            </div>
            <div class="card-body">
                <p class="lead">Vous pouvez consulter et modifier vos informations personnelles ci-dessous.</p>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="" method="post" class="mt-4">
                    <input type="hidden" name="update_info" value="1">

                    <div class="mb-3">
                        <label for="first_name" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Numéro de téléphone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>


</body>
</html>