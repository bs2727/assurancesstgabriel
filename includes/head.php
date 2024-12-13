<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="fr"></html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Assurances Saint Gabriel - Nous sommes là pour vous, à vos côtés, dans les mauvais comme dans les bons moments de votre vie.">
<meta name="author" content="Assurances Saint Gabriel Team">
<title>Assurances Saint Gabriel</title>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/
@5.1.0/dist/css/bootstrap.min.css">
<!-- Font Awesome icons (free version) -->
<script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>

<!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet">

<!-- Core theme CSS (includes Bootstrap) -->
<link href="../css/styles.css" rel="stylesheet">
