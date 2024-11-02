<?php
include '../backend/functions.php'; // Include backend functions

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (getCurrentUserRole() < 4) {
    include_once("../backend/redirect.php");
    exit(); // Ensure script execution stops after redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once "../includes/head.php"; ?>

<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <?php if (getCurrentUserRole() >= 4): ?>
                    <a href="news.php" class="icon btn btn-primary d-flex flex-column align-items-center">
                        <img src="../assets/img/gestion/news.png" alt="News" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Actualités</div>
                    </a>
                <?php else: ?>
                    <span class="icon btn btn-secondary d-flex flex-column align-items-center disabled">
                        <img src="../assets/img/gestion/news.png" alt="News" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Actualités</div>
                    </span>
                <?php endif; ?>
            </div>

            <div class="col-md-4 text-center">
                <?php if (getCurrentUserRole() >= 4): ?>
                    <a href="contracts.php" class="icon btn btn-primary d-flex flex-column align-items-center">
                        <img src="../assets/img/gestion/contract.png" alt="Contract" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Contrat</div>
                    </a>
                <?php else: ?>
                    <span class="icon btn btn-secondary d-flex flex-column align-items-center disabled">
                        <img src="../assets/img/gestion/contract.png" alt="Contract" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Contrats</div>
                    </span>
                <?php endif; ?>
            </div>

            <div class="col-md-4 text-center">
                <?php if (getCurrentUserRole() >= 4): ?>
                    <a href="users.php" class="icon btn btn-primary d-flex flex-column align-items-center">
                        <img src="../assets/img/gestion/users.png" alt="Users" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Utilisateurs</div> 
                    </a>
                <?php else: ?>
                    <span class="icon btn btn-secondary d-flex flex-column align-items-center disabled">
                        <img src="../assets/img/gestion/users.png" alt="Users" class="mb-2" style="width: 100px; height: 100px;">
                        <div class="icon-name">Utilisateurs</div>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once "../includes/footer.php"; ?>
</body>
</html>
