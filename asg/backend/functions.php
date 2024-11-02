<?php

// Database Configuration
$host = 'localhost';
$db = 'assurancessaintgab';
$user = 'root'; // Ensure this is a string
$pass = ''; // Ensure this is a string
$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Database Connection Function
function getDatabaseConnection() {
    global $dsn, $user, $pass, $options;
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}


// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password']) && !isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $loginResult = login($email, $password);
        if (!$loginResult['success']) {
            $error_message = $loginResult['message'];
        }
    } else {
        $error_message = 'Email et mot de passe sont requis.';
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['cp'], $_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cp = trim($_POST['cp']);

    if ($username && $email && $password && $cp) {
        $registerResult = register($username, $email, $password, $cp);
        if (!$registerResult['success']) {
            $error_message = $registerResult['message'];
        }
    } else {
        $error_message = 'Tous les champs sont requis.';
    }
}

// Login Function
function login($email, $password) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['loggedin'] = true;
        setcookie('PHPSESSID', session_id(), time() + (86400 * 30), "/", "", false, true);

        // Redirect to the account panel page
        header('Location: ../pages/pannelmyaccount.php');
        exit();
    } else {
        return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
    }
}

// Registration Function
function register($username, $email, $password, $cp) {
    $pdo = getDatabaseConnection();
    $role = '1';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        return ['success' => false, 'message' => "Cet email est déjà utilisé."];
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role, cp) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$username, $email, $hashedPassword, $role, $cp])) {
            header('Location: ../pages/index.php');
            exit();
        } else {
            return ['success' => false, 'message' => "Erreur lors de l'inscription."];
        }
    }
}

// Function to handle user disconnection
function disconnect() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    header("Location: ../pages/index.php");
    exit();
}

// Handle disconnect request directly in functions.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'disconnect') {
    disconnect();
}

// User Utility Functions
function getCurrentUserID() {
    return getCurrentUserInfo('id');
}

function getCurrentUserMail() {
    return getCurrentUserInfo('email');
}

function getCurrentUserName() {
    return getCurrentUserInfo('username');
}

function getCurrentUserRole() {
    return getCurrentUserInfo('role') ?? -1;
}

function getCurrentUserInfo($field) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Start session if not already started
    }
    
    if (!isset($_SESSION['id'])) {
        return null; // User is not logged in
    }

    $pdo = getDatabaseConnection(); // Ensure this returns a valid PDO object
    $stmt = $pdo->prepare("SELECT $field FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch();
    return $result[$field] ?? null; // Return the requested field or null
}


function isUserLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}



// Create News
function createNews($date, $title, $caption, $keywords, $image) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("INSERT INTO news (date, title, caption, keywords, image) VALUES (:date, :title, :caption, :keywords, :image)");

    try {
        if (empty($date) || empty($title) || empty($caption) || empty($keywords) || empty($image)) {
            throw new Exception("All fields are required.");
        }

        $stmt->execute([
            ':date' => $date,
            ':title' => $title,
            ':caption' => $caption,
            ':keywords' => $keywords,
            ':image' => $image
        ]);

        echo json_encode(["success" => true, "message" => "News successfully created."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error creating news: " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

// Upload Image Function
function uploadImage($file) {
    $targetDir = realpath(__DIR__ . '/../uploads/') . '/';
    $fileName = basename($file["name"]);
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExtension;
    $targetFilePath = $targetDir . $newFileName;

    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        throw new Exception("Le fichier n'est pas une image valide.");
    }
    if ($file["size"] > 5000000) {
        throw new Exception("Le fichier est trop volumineux (maximum 5MB).");
    }
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        throw new Exception("Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }
    if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        throw new Exception("Erreur lors du téléchargement de l'image.");
    }
    return $newFileName;
}

?>
