<?php
session_start();    
include_once("db.php");

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Récupération de l'utilisateur dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Le mot de passe est correct, on démarre une session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['connecte'] = 'connecte';
        echo "<script type='text/javascript'>
        window.location.href = 'index.php';
      </script>";
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>


