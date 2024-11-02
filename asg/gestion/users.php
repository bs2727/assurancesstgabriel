<?php
include '../backend/functions.php'; // Include backend functions

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (getCurrentUserRole() < 4)
    include_once("../backend/redirect.php");
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once "../includes/head.php"; ?>

<body>
    <?php require_once "../includes/header.php"; ?>
    <?php require_once "../includes/nav.php"; ?>

    <?php require_once "../includes/footer.php"; ?>
</body>
</html>
