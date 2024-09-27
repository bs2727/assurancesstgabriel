<?php
session_start(); // Vérifie si l'utilisateur est connecté

if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si non connecté
    header('Location: login.php');
    exit;
}

echo "Bienvenue, " . $_SESSION['username'] . "!";
?>

<a href="logout.php">Déconnexion</a>
