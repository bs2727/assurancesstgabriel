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

    <section class="page-section cta">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="cta-inner bg-faded text-center rounded">
                        <h2 class="section-heading mb-5">
                            <span class="section-heading-upper">Pour nous contacter</span>
                            <span class="section-heading-lower">Vous pouvez venir nous voir</span>
                        </h2>
                        <ul class="list-unstyled list-hours mb-5 text-left mx-auto">
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Bagneux
                                <span class="ms-auto">21 rue de la lisette 92220 Bagneux</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Bailleul
                                <span class="ms-auto">240 Rue de Lille, 59270 Bailleul</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Granville
                                <span class="ms-auto">4 place Cambernon, 50400 Granville</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Montigny
                                <span class="ms-auto">2 rue de la montagne 45170 Montigny</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Saint Jean de Vedas
                                <span class="ms-auto">31 avenue Georges Clemenceau, 34400 Saint Jean de Vedas</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Maillezais
                                <span class="ms-auto">39 rue du grand port, 85440 Maillezais</span>
                            </li>
                            <li class="list-unstyled-item list-hours-item d-flex">
                                Agence de Frangy
                                <span class="ms-auto">72 rue Haute 74270 Frangy</span>
                            </li>
                        </ul>
                        <p class="address mb-5">
                            <em>
                                <strong>Vous pouvez aussi utiliser le formulaire suivant</strong>
                                <br />
                                Remplissez tous les champs
                            </em>
                        </p>
                        <p class="mb-0">
                            <small><em>Pour nous appeler</em></small>
                            <br />
                            0146576122
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section about-heading">
        <div class="container">
            <img class="img-fluid rounded about-heading-img mb-3 mb-lg-0" src="../assets/img/about.jpg" alt="About Our Cafe" />
            <div class="about-heading-content">
                <div class="row">
                    <div class="col-xl-9 col-lg-10 mx-auto">
                        <div class="bg-faded rounded p-5">
                            <h2 class="section-heading mb-4">
                                <span class="section-heading-upper">Strong Coffee, Strong Roots</span>
                                <span class="section-heading-lower">About Our Cafe</span>
                            </h2>
                            <p>Founded in 1987 by the Hernandez brothers, our establishment has been serving up rich
                                coffee sourced from artisan farmers in various regions of South and Central America. We
                                are dedicated to travelling the world, finding the best coffee, and bringing it back to
                                you here in our cafe.</p>
                            <p class="mb-0">
                                We guarantee that you will fall in
                                <em>lust</em>
                                with our decadent blends the moment you walk inside until you finish your last sip. Join
                                us for your daily routine, an outing with friends, or simply just to enjoy some alone
                                time.
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

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="asg/js/scripts.js"></script>
</body>

</html>
