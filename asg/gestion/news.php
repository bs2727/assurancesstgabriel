<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary functions
require_once "../backend/functions.php";
if (getCurrentUserRole() < 3)
    include_once("../backend/redirect.php");
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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h2>Créer une nouvelle actualité</h2>
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data" class="mt-4">
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" name="title" id="title" required placeholder="Titre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="caption" class="form-label">Description</label>
                        <textarea name="caption" id="caption" required placeholder="Description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Mots-clés</label>
                        <input type="text" name="keywords" id="keywords" required placeholder="Mots-clés" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" id="image" required class="form-control">
                    </div>
                    <div class="text-center">
                        <button type="submit" name="create_news" class="btn btn-primary">Créer l'actualité</button>
                    </div>
                </form>
                <?php
                if (isset($_POST['create_news'])) {
                    try {
                        // Ensure the file was uploaded successfully
                        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            // Upload the image
                            $uploadedImageName = uploadImage($_FILES['image']);

                            // Create the news item
                            createNews(
                                $_POST['date'],
                                htmlspecialchars($_POST['title']),
                                htmlspecialchars($_POST['caption']),
                                htmlspecialchars($_POST['keywords']),
                                $uploadedImageName
                            );

                            echo "<div class='alert alert-success mt-4'>L'actualité a été créée avec succès.</div>";
                        } else {
                            throw new Exception("Erreur lors de l'upload de l'image.");
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger mt-4'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php 
    // Include footer
    require_once "../includes/footer.php"; 
    ?>

</body>
</html>
