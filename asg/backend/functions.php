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



echo getCurrentUserRole();
function isUserLoggedIn()
{
    include '../backend/db.php';
    if (isset($_SESSION['loggedin'])) {
        return true;
    } else {
        return false;
    }
}

// all users
function getAllUsers()
{
    include '../backend/db.php';

    // Prepare SQL statement to fetch id, username, email, and role of all users
    $stmt = $pdo->prepare("SELECT id, username, email, role FROM users");
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
?>