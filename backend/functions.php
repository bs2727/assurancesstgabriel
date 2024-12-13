<?php

// Database Configuration
function getDatabaseConnection() {
        $host = 'localhost';
        $db = 'assurancessaintgab'; // Remplacez par le nom de votre base de données
        $user = 'root'; // Remplacez par votre nom d'utilisateur
        $pass = ''; // Remplacez par votre mot de passe
        $charset = 'utf8mb4';
    
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activer les exceptions d'erreurs
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mode de récupération des données
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactiver l'émulation des requêtes préparées
        ];
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // En production, ne jamais afficher les erreurs directement
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }


// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle login form submission
    if (isset($_POST['email'], $_POST['password']) && !isset($_POST['register'])) {
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
    if (isset($_POST['register'])) {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $password = $_POST['password'];
        $consent = isset($_POST['consent']);

        if ($firstName && $lastName && $email && $phone && $address && $password && $consent) {
            $registerResult = register($firstName, $lastName, $email, $phone, $address, $password);
            if (!$registerResult['success']) {
                $error_message = $registerResult['message'];
            }
        } else {
            $error_message = 'Tous les champs sont requis et vous devez accepter la politique de confidentialité.';
        }
    }

    // Handle disconnect request
    if (isset($_POST['action']) && $_POST['action'] === 'disconnect') {
        disconnect();
    }
}

// Login Function
// Fonction de connexion
function login($email, $password) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['loggedin'] = true;

        // Enregistrer le log de connexion
        logAction($user['id'], "Connexion réussie.");

        // Rediriger vers la page du compte
        header('Location: ../compte/moncompte.php');
        exit();
    } else {
        return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
    }
}

// Registration Function
function register($firstName, $lastName, $email, $phone, $address, $password) {
    $pdo = getDatabaseConnection();
    $role = 1; // Rôle par défaut pour un nouvel utilisateur
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'message' => "Cet email est déjà utilisé."];
    } else {
        // Insérer le nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$firstName, $lastName, $email, $phone, $address, $hashedPassword, $role])) {
            // Connecter l'utilisateur après l'inscription
            $userId = $pdo->lastInsertId();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['id'] = $userId;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['loggedin'] = true;

            // Enregistrer le log d'inscription
            logAction($userId, "Inscription réussie.");

            // Rediriger vers la page du compte
            header('Location: ../compte/moncompte.php');
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

    $userId = $_SESSION['id'] ?? null;

    session_unset();
    session_destroy();

    if ($userId !== null) {
        // Enregistrer le log de déconnexion
        logAction($userId, "Déconnexion réussie.");
    }

    header("Location: ../pages/index.php");
    exit();
}
// Fonctions utilitaires pour l'utilisateur
function getCurrentUserID() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['id'] ?? null;
}
function getCurrentUserRole() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['role'] ?? null;
}
function getCurrentUserFname() {
    return getCurrentUserInfo('first_name');
}

function getCurrentUserLName() {
    return getCurrentUserInfo('last_name');
}

function getCurrentUserMail() {
    return getCurrentUserInfo('email');
}

function getCurrentUserPhone() {
    return getCurrentUserInfo('phone');
}

function getCurrentUserAddress() {
    return getCurrentUserInfo('address');
}
function getCurrentUserInfo($field) {
    static $userInfo = null;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id'])) {
        return null;
    }

    if ($userInfo === null) {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();
        $userInfo = $stmt->fetch();
    }

    return $userInfo[$field] ?? null;
}

// news
// Create News
function createNews($date, $title, $caption, $keywords, $image) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("INSERT INTO news (date, title, caption, keywords, image) VALUES (:date, :title, :caption, :keywords, :image)");

    try {
        if (empty($date) || empty($title) || empty($caption) || empty($keywords) || empty($image)) {
            throw new Exception("Tous les champs sont requis.");
        }

        $stmt->execute([
            ':date' => $date,
            ':title' => $title,
            ':caption' => $caption,
            ':keywords' => $keywords,
            ':image' => $image
        ]);

        // Enregistrer le log
        $currentUserId = getCurrentUserID();
        $newsId = $pdo->lastInsertId();
        $action = "Créé une nouvelle actualité ID $newsId.";
        logAction($currentUserId, $action);

        echo json_encode(["success" => true, "message" => "Actualité créée avec succès."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erreur lors de la création de l'actualité : " . $e->getMessage()]);
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
    if ($file["size"] > 5000000) { // 5MB
        throw new Exception("Le fichier est trop volumineux (maximum 5MB).");
    }
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        throw new Exception("Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }
    if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        throw new Exception("Erreur lors du téléchargement de l'image.");
    }

    // Enregistrer le log de téléchargement d'image
    $currentUserId = getCurrentUserID();
    $action = "Téléchargé une image : $newFileName.";
    logAction($currentUserId, $action);

    return $newFileName;
}

// Vérifier si l'utilisateur est connecté
function isUserLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}


// Fonction pour mettre à jour les informations de l'utilisateur
function updateUserInfo($userId, $firstName, $lastName, $email, $phone, $address) {
    $pdo = getDatabaseConnection();
    try {
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$firstName, $lastName, $email, $phone, $address, $userId]);

        // Enregistrer le log
        $currentUserId = getCurrentUserID();
        $action = "Mis à jour les informations personnelles.";
        logAction($currentUserId, $action);

        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors de la mise à jour des informations : ' . $e->getMessage()];
    }
}

// Fonction pour créer une simulation
function createSimulation($userId, $typesAssurance, $informations) {
    $pdo = getDatabaseConnection();
    $statut = 'En attente';
    $typesAssuranceJson = json_encode($typesAssurance);
    $informationsJson = json_encode($informations);

    $stmt = $pdo->prepare("INSERT INTO simulations (user_id, types_assurance, informations, statut, date_creation) VALUES (?, ?, ?, ?, NOW())");
    $createSuccess = $stmt->execute([$userId, $typesAssuranceJson, $informationsJson, $statut]);

    if ($createSuccess) {
        // Enregistrer le log
        $simulationId = $pdo->lastInsertId();
        $action = "Créé une nouvelle simulation ID $simulationId.";
        logAction($userId, $action);
    }

    return $createSuccess;
}


// Fonction pour récupérer les simulations d'un utilisateur
function getUserSimulations($userId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT s.*, u.first_name, u.last_name FROM simulations s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}


// Vérifier si l'utilisateur a des contrats actifs (exemple)
function isContractActive() {
    $userId = getCurrentUserID();
    $contrats = getUserContracts($userId);
    return !empty($contrats);
}

function getSimulationById($simulationId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT s.*, u.first_name, u.last_name FROM simulations s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
    $stmt->execute([$simulationId]);
    return $stmt->fetch();
}

// Fonction pour accepter une simulation et créer un contrat
function acceptSimulation($simulationId) {
    $pdo = getDatabaseConnection();
    $simulation = getSimulationById($simulationId);

    if (!$simulation) {
        return ['success' => false, 'message' => 'Simulation introuvable.'];
    }

    if ($simulation['statut'] != 'Répondu') {
        return ['success' => false, 'message' => 'La simulation n\'est pas au statut "Répondu".'];
    }

    try {
        // Créer le contrat
        $stmt = $pdo->prepare("INSERT INTO contrats (user_id, simulation_id, types_assurance, informations, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([
            $simulation['user_id'],
            $simulation['id'],
            $simulation['types_assurance'],
            $simulation['informations']
        ]);

        // Mettre à jour le statut de la simulation
        updateSimulationStatus($simulationId, 'Acceptée');

        // Enregistrer le log
        $currentUserId = getCurrentUserID();
        $contractId = $pdo->lastInsertId();
        $action = "Accepté la simulation ID $simulationId et créé le contrat ID $contractId.";
        logAction($currentUserId, $action);

        return ['success' => true, 'contract_id' => $contractId];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors de la création du contrat : ' . $e->getMessage()];
    }
}

// Fonction pour récupérer un contrat spécifique par simulation_id
function getContractBySimulationId($userId, $simulationId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM contrats WHERE user_id = ? AND simulation_id = ?");
    $stmt->execute([$userId, $simulationId]);
    return $stmt->fetch();
}

function getContractById($contratId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT c.*, u.first_name, u.last_name FROM contrats c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
    $stmt->execute([$contratId]);
    return $stmt->fetch();
}

// Fonction pour refuser une simulation
function declineSimulation($simulationId) {
    $pdo = getDatabaseConnection();
    try {
        updateSimulationStatus($simulationId, 'Refusée');
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors du refus de la simulation : ' . $e->getMessage()];
    }
}



// Fonction pour récupérer toutes les simulations (pour l'admin)
function getAllSimulations() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT s.*, u.first_name, u.last_name FROM simulations s JOIN users u ON s.user_id = u.id");
    return $stmt->fetchAll();
}


// Fonction pour récupérer tous les contrats
function getAllContracts() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT c.*, u.first_name, u.last_name FROM contrats c JOIN users u ON c.user_id = u.id");
    return $stmt->fetchAll();
}

// Fonction pour récupérer toutes les simulations par statut
function getSimulationsByStatus($statut) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT s.*, u.first_name, u.last_name FROM simulations s JOIN users u ON s.user_id = u.id WHERE s.statut = ?");
    $stmt->execute([$statut]);
    return $stmt->fetchAll();
}



// Fonction pour mettre à jour le statut d'une simulation
function updateSimulationStatus($simulationId, $statut, $reponse = null) {
    $pdo = getDatabaseConnection();
    if ($reponse !== null) {
        $stmt = $pdo->prepare("UPDATE simulations SET statut = ?, reponse = ?, date_reponse = NOW() WHERE id = ?");
        $result = $stmt->execute([$statut, $reponse, $simulationId]);
    } else {
        $stmt = $pdo->prepare("UPDATE simulations SET statut = ? WHERE id = ?");
        $result = $stmt->execute([$statut, $simulationId]);
    }

    if ($result) {
        // Enregistrer le log
        $currentUserId = getCurrentUserID();
        $action = "Mis à jour le statut de la simulation ID $simulationId à '$statut'.";
        if ($reponse !== null) {
            $action .= " Réponse: $reponse.";
        }
        logAction($currentUserId, $action);
    }

    return $result;
}

function respondSimulation($simulationId, $reponse) {
    $pdo = getDatabaseConnection();
    try {
        updateSimulationStatus($simulationId, 'Répondu', $reponse);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors de la réponse à la simulation : ' . $e->getMessage()];
    }
}



// Fonction pour créer un contrat à partir d'une simulation acceptée
function createContractFromSimulation($simulationId) {
    $pdo = getDatabaseConnection();
    $simulation = getSimulationById($simulationId);

    if ($simulation && $simulation['statut'] == 'Acceptée') {
        $stmt = $pdo->prepare("INSERT INTO contrats (user_id, simulation_id, types_assurance, informations, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([
            $simulation['user_id'],
            $simulation['id'],
            $simulation['types_assurance'],
            $simulation['informations']
        ]);

        // Mettre à jour le statut de la simulation
        updateSimulationStatus($simulationId, 'Contrat créé');

        // Enregistrer le log
        $contractId = $pdo->lastInsertId();
        $currentUserId = getCurrentUserID();
        $action = "Créé le contrat ID $contractId à partir de la simulation ID $simulationId.";
        logAction($currentUserId, $action);

        return $contractId;
    } else {
        return false;
    }
}

// Fonction pour récupérer les contrats d'un utilisateur
function getUserContracts($userId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT c.*, u.first_name, u.last_name FROM contrats c JOIN users u ON c.user_id = u.id WHERE c.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
function hasActiveSimulation($userId) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM simulations WHERE user_id = ? AND statut IN ('En attente', 'Répondu')");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() > 0;

}

function getAllUsers() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role FROM users");
    $stmt->execute();
    return $stmt->fetchAll();
}

function updateUser($userId, $firstName, $lastName, $email, $role) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?");
    $updateSuccess = $stmt->execute([$firstName, $lastName, $email, $role, $userId]);

    if ($updateSuccess) {
        // Enregistrer le log
        $currentUserId = getCurrentUserID();
        $action = "Mis à jour les informations de l'utilisateur ID $userId.";
        logAction($currentUserId, $action);
    }

    return $updateSuccess;
}

function logAction($userId, $action) {
    $pdo = getDatabaseConnection();
    
    // Récupérer l'adresse IP de l'utilisateur
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action, ip_address) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $action, $ipAddress]);
}

function getLogs($userId = null, $startDate = null, $endDate = null) {
    $pdo = getDatabaseConnection();
    $query = "SELECT l.*, u.first_name, u.last_name FROM logs l JOIN users u ON l.user_id = u.id WHERE 1=1";
    $params = [];
    
    if ($userId !== null && $userId !== '') {
        $query .= " AND l.user_id = ?";
        $params[] = $userId;
    }
    
    if ($startDate !== null && $startDate !== '') {
        $query .= " AND l.timestamp >= ?";
        $params[] = $startDate . " 00:00:00";
    }
    
    if ($endDate !== null && $endDate !== '') {
        $query .= " AND l.timestamp <= ?";
        $params[] = $endDate . " 23:59:59";
    }
    
    $query .= " ORDER BY l.timestamp DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}