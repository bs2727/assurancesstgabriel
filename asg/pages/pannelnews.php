<?php include_once("../backend/functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../includes/head.php"); 
include_once("../backend/db.php");?>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php include_once("../includes/nav.php"); ?>

    <section class="page-section about-heading">
        <?php if (isUserLoggedIn() == false) { ?>
            <div class="product-item">
                <div class="product-item-title d-flex">
                    <div class="bg-faded p-5 d-flex ms-auto rounded">
                        <h2 class="section-heading mb-0">
                            <span class="section-heading-upper">Vous n'êtes pas connecté </span>
                            <span class="section-heading-lower">Redirection vers la page de connexion</span>
                        </h2>
                    </div>
                </div>
                <?php
                echo "<script type='text/javascript'>
                            setTimeout(function() {
                                window.location.href = '../pages/index.php';
                            }, 5000);
                          </script>";
                ?>
            </div>
        <?php } else { ?>
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
                                        // Upload image first
                                        $uploadedImageName = uploadImage($_FILES['image']);
                                        // Create news using the uploaded image name
                                        createNews($pdo, $_POST['date'], $_POST['title'], $_POST['caption'], $_POST['keywords'], $uploadedImageName);
                                    } catch (Exception $e) {
                                        echo "<div class='alert alert-danger mt-4'>" . $e->getMessage() . "</div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
    </section>

    <?php include_once("../includes/footer.php"); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/n
