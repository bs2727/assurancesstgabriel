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

    <section class="page-section clearfix">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-fluid mb-3 mb-lg-0 rounded" src="../assets/img/intro.jpg" alt="Introduction Image" />
                </div>
                <div class="col-lg-6">
                    <div class="intro-text text-center bg-faded p-5 rounded">
                        <h2 class="section-heading mb-4">
                            <span class="section-heading-upper">Saint Gabriel</span>
                            <span class="section-heading-lower"></span>
                        </h2>
                        <p class="mb-3">
                            Les Assurances Saint Gabriel sont l’assureur de l’économie solidaire, la mutuelle de tous
                            ceux qui s’engagent : associations, ONG à but humanitaire et caritatif, organismes
                            sanitaires et sociaux, enseignement, institutions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section cta">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="cta-inner bg-faded text-center rounded">
                        <h2 class="section-heading mb-4">
                            <span class="section-heading-upper">À vos côtés</span>
                            <span class="section-heading-lower">Au plus proche de vos besoins</span>
                        </h2>
                        <p class="mb-0">
                            Assureur mutualiste de l’économie solidaire, Saint-Gabriel met à la disposition de ses
                            sociétaires une expertise, une capacité d’écoute et une volonté d’innovation reconnues.
                            <br><br>
                            Parce que notre but est d’être là dans tous les moments clés de votre vie, notre panel
                            d’assurances couvre tous les aléas et tous les biens que vous souhaitez, de façon évolutive
                            et dans le respect de nos valeurs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section about-heading">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="cta-inner bg-faded text-center rounded">
                        <h2 class="section-heading mb-4">Dernières Actualités</h2>
                        <?php
                        $pdo = getDatabaseConnection();
                        $stmt = $pdo->query("SELECT * FROM news ORDER BY date DESC LIMIT 5");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='news-item mb-4'>";
                            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                            echo "<p>" . htmlspecialchars(date('d-m-Y', strtotime($row['date']))) . "</p>";
                            echo "<p>" . htmlspecialchars($row['caption']) . "</p>";
                            
                            // Ensure image path is secure and correctly pointing to the uploads folder
                            $imagePath = "../uploads/" . htmlspecialchars(basename($row['image']));
                            if (file_exists($imagePath) && !empty($row['image'])) {
                                echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($row['title']) . "' style='max-width:100%; height:auto;'/>";
                            }
                            echo "</div>";
                        }
                        ?>
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
