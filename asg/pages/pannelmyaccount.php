<?php include_once("../backend/functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../includes/head.php");

?>

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
                                <h2 class="section-heading mb-4">
                                    <span class="section-heading-upper">Bonjour <?php echo getCurrentUserName() ;
                                    switch (getCurrentUserRole()) {
                                        case 1:
                                            echo ' Nouveau client'; 
                                            break;
                                        case 2:
                                            echo ' Déjà client'; 
                                            break;
                                        case 3:
                                            echo ' Collaborateur'; 
                                            break;
                                        case 4:
                                            echo ' Administrateur';
                                            break;
                                        default:
                                            echo ' Rôle inconnu';
                                            break;
                                    }
                                    
                                    ?></span>

                                    <span class="section-heading-lower"></span>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php } ?>
    </section>

    <?php include_once("../includes/footer.php"); ?>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="asg/js/scripts.js"></script>
</body>

</html>