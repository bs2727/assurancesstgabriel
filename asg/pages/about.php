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

    <section class="page-section about-heading">
        <div class="container">
            <img class="img-fluid rounded about-heading-img mb-3 mb-lg-0" src="../assets/img/about.jpg" alt="About Image" />
            <div class="about-heading-content">
                <div class="row">
                    <div class="col-xl-9 col-lg-10 mx-auto">
                        <div class="bg-faded rounded p-5">
                            <h2 class="section-heading mb-4">
                                <span class="section-heading-upper">Vous et nous, une grande histoire d'amour</span>
                                <span class="section-heading-lower">Numéro 1 de la relation client en assurance</span>
                            </h2>
                            <p>
                                Forte d’une expertise reconnue découlant de plus de soixante quinze ans d’amélioration et
                                d’enrichissement de ses savoir-faire, elle propose à ces institutions, à leurs salariés
                                et à leurs bénévoles des garanties, des services d’assurances et un accompagnement
                                adaptés à leur mission, au meilleur coût.
                            </p>
                            <p class="mb-0">
                                Nous vous garantissons
                                <em>le meilleur</em>
                                pour vous vous sentiez en sécurité, totalement r-assurés !
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    // Include footer
    require_once "../includes/footer.php"; 
    ?>

</body>

</html>
