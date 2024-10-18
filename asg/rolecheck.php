<?php
function getCurrentUserRole() {
    include 'db.php';
    session_start();
    $stmt = $pdo->prepare("SELECT Role FROM users where id = ". $_SESSION['user_id']);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $result;
}

echo getCurrentUserRole();
?>