<?php
session_start();
session_destroy(); // Destruction de la session
header('Location: login.php'); // Redirection vers la page de connexion
exit;
?>
