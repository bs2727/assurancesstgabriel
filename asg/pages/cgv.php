<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary functions
require_once "../backend/functions.php";
?>
<!DOCTYPE html>
<html lang="en">

<?php 
// Include head section
require_once "../includes/head.php"; 
?>

<body>
    <?php 
    // Include header and navigation
    require_once "../includes/header.php"; 
    require_once "../includes/nav.php"; 
    ?>

    <h1>CGV</h1>

    <?php 
    // Include footer
    require_once "../includes/footer.php"; 
    ?>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="asg/js/scripts.js"></script>
</body>

</html>
