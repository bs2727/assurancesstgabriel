<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary functions
require_once "../backend/functions.php";
?>

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

    <div class="container mt-5">
        <h1>Politique de Confidentialité</h1>
        <!-- Add your content for the privacy policy here -->
    </div>

    <?php 
    // Include footer
    require_once "../includes/footer.php"; 
    ?>
</body>
</html>
