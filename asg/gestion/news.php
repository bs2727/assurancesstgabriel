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

    <section class="page-section about-heading">
            <section class="page-section cta">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 mx-auto">
                            <div class="cta-inner bg-faded text-center rounded">
                                <h2 class="section-heading mb-4">Créer une nouvelle actualité</h2>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <input type="date" name="date" required placeholder="Date" class="form-control mb-3">
                                    <input type="text" name="title" required placeholder="Titre" class="form-control mb-3">
                                    <textarea name="caption" required placeholder="Description" class="form-control mb-3"></textarea>
                                    <input type="text" name="keywords" required placeholder="Mots-clés" class="form-control mb-3">
                                    <input type="file" name="image" required class="form-control mb-3">
                                    <button type="submit" name="create_news" class="btn btn-primary">Créer l'actualité</button>
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
                </div>
            </section>
    </section>

    <?php 
    // Include footer
    require_once "../includes/footer.php"; 
    ?>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="../js/scripts.js"></script>
</body>
</html>
