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

    <section class="page-section">
        <div class="container">
            <div class="product-item">
                <div class="product-item-title d-flex">
                    <div class="bg-faded p-5 d-flex ms-auto rounded">
                        <h2 class="section-heading mb-0">
                            <span class="section-heading-upper">Assurances vie, santé, auto, habitation...</span>
                            <span class="section-heading-lower">Nous sommes là pour prévenir et assurer vos biens, votre famille, et vous-même</span>
                        </h2>
                    </div>
                </div>
                <img class="product-item-img mx-auto d-flex rounded img-fluid mb-3 mb-lg-0"
                    src="../assets/img/products-01.jpg" alt="Assurances Image 1" />
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="product-item">
                <div class="product-item-title d-flex">
                    <div class="bg-faded p-5 d-flex me-auto rounded">
                        <h2 class="section-heading mb-0">
                            <span class="section-heading-upper">Assurance solidaire</span>
                            <span class="section-heading-lower">Nos tarifs sont des plus accessibles</span>
                        </h2>
                    </div>
                </div>
                <img class="product-item-img mx-auto d-flex rounded img-fluid mb-3 mb-lg-0"
                    src="../assets/img/products-02.jpg" alt="Assurances Image 2" />
                <div class="product-item-description d-flex ms-auto">
                    <div class="bg-faded p-5 rounded">
                        <p class="mb-0">Assureur mutualiste de l’économie solidaire, Saint-Gabriel met à la disposition
                            de ses sociétaires une expertise, une capacité d’écoute et une volonté d’innovation
                            reconnues.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="product-item">
                <div class="product-item-title d-flex">
                    <div class="bg-faded p-5 d-flex ms-auto rounded">
                        <h2 class="section-heading mb-0">
                            <span class="section-heading-upper">Plus de 75 ans d'expertise</span>
                            <span class="section-heading-lower">N°1 de l'économie solidaire</span>
                        </h2>
                    </div>
                </div>
                <img class="product-item-img mx-auto d-flex rounded img-fluid mb-3 mb-lg-0"
                    src="../assets/img/products-03.jpg" alt="Assurances Image 3" />
                <div class="product-item-description d-flex me-auto">
                    <div class="bg-faded p-5 rounded">
                        <p class="mb-0">Forte d’une expertise reconnue découlant de plus de soixante-quinze ans
                            d’amélioration et d’enrichissement de ses savoir-faire, elle propose à ces institutions, à
                            leurs salariés et à leurs bénévoles des garanties, des services d’assurances et un
                            accompagnement adaptés à leur mission, au meilleur coût.</p>
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
    <script src="../js/scripts.js"></script>
</body>

</html>
