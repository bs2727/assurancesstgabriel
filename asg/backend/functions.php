<?php


// current user
function getCurrentUserID()
{
    include '../backend/db.php';
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['id'] ?? null;
}

function getCurrentUserMail()
{
    include '../backend/db.php';
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['email'] ?? null;
}

function getCurrentUserName()
{
    include '../backend/db.php';
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['username'] ?? null;
}


function getCurrentUserRole()
{
    include '../backend/db.php';

    $stmt = $pdo->prepare("SELECT role FROM users WHERE email = :email");
    $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['role'] ?? null;
}


function isUserLoggedIn()
{
    include '../backend/db.php';
    if (isset($_SESSION['loggedin'])) {
        return true;
    } else {
        return false;
    }
}

// Update user's email and password
function updateUserCredentials($id, $newEmail, $newPassword)
{
    include '../backend/db.php';

    try {
        // Update email and password in the database
        $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE id = :id");
        $stmt->bindParam(':email', $newEmail);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Function to handle JSON input for AJAX calls to update credentials
function handleUpdateUserCredentialsRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['email'], $data['password'])) {
        $id = $_SESSION['id'];
        $newEmail = $data['email'];
        $newPassword = $data['password'];

        // Call updateUserCredentials to perform the update
        $response = updateUserCredentials($id, $newEmail, $newPassword);

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    }
}

// Check if this file is accessed directly for an AJAX call to update user credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'updateCredentials') {
    handleUpdateUserCredentialsRequest();
}

// all users
function getAllUsers()
{
    include '../backend/db.php';

    // Prepare SQL statement to fetch id, username, email, and role of all users
    $stmt = $pdo->prepare("SELECT id, username, email, role, cp FROM users");
    $stmt->execute();

    // Fetch all results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update user field function
function updateUser($id, $field, $value)
{
    include '../backend/db.php';

    // Allow only specific fields to be updated to prevent SQL injection
    $allowedFields = ['username', 'email', 'role'];
    if (!in_array($field, $allowedFields)) {
        return ['success' => false, 'message' => 'Invalid field specified'];
    }

    try {
        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE users SET $field = :value WHERE id = :id");
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Function to handle JSON input for AJAX calls
function handleUpdateUserRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'], $data['field'], $data['value'])) {
        $id = $data['id'];
        $field = $data['field'];
        $value = $data['value'];

        // Call updateUser to perform the update
        $response = updateUser($id, $field, $value);

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    }
}

// Check if this file is accessed directly for an AJAX call to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleUpdateUserRequest();
}

function deleteUser($id)
{
    include '../backend/db.php';

    try {
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Function to handle JSON input for AJAX delete requests
function handleDeleteUserRequest()
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $id = $data['id'];

        // Call deleteUser to perform the deletion
        $response = deleteUser($id);

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    }
}

// Check if this file is accessed directly for an AJAX call to delete user data
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    handleDeleteUserRequest();
}

function createNews($pdo, $date, $title, $caption, $keywords, $image) {
    $sql = "INSERT INTO news (date, title, caption, keywords, image) VALUES (:date, :title, :caption, :keywords, :image)";
    $stmt = $pdo->prepare($sql);
    
    try {
        // Validate input data
        if (empty($date) || empty($title) || empty($caption) || empty($keywords) || empty($image)) {
            throw new Exception("Invalid request data: All fields are required.");
        }
        
        $stmt->execute([
            ':date' => $date,
            ':title' => $title,
            ':caption' => $caption,
            ':keywords' => $keywords,
            ':image' => $image
        ]);
        echo json_encode(["success" => true, "message" => "Actualité créée avec succès."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erreur lors de la création de l'actualité : " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function uploadImage($file) {
    // Directory where the images will be saved
    $targetDir = realpath(__DIR__ . '/../uploads/') . '/';
    
    // Generate a unique name for the file to prevent overwriting
    $fileName = basename($file["name"]);
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExtension;
    $targetFilePath = $targetDir . $newFileName;

    // Check if the file is an actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        throw new Exception("Le fichier n'est pas une image valide.");
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        throw new Exception("Le fichier est trop volumineux (maximum 5MB).");
    }

    // Allow certain file formats
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        throw new Exception("Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }

    // Attempt to move the uploaded file to the target directory
    if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        throw new Exception("Erreur lors du téléchargement de l'image.");
    }

    // Return the new file name to be saved in the database
    return $newFileName;
}
?>