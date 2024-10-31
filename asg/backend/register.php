<?php
include_once("../backend/db.php");
session_start();

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = '1';
    $cp = $_POST['cp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash du mot de passe

    // Vérification que l'utilisateur n'existe pas déjà
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "Cet email est déjà utilisé.";
    } else {
        // Insertion dans la base de données
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role, cp) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$username, $email, $password, $role, $cp])) {
            echo "<script type='text/javascript'>
            window.location.href = '../pages/index.php';
          </script>";
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
?>