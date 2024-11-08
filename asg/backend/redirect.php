
    <?php require_once "../includes/head.php"; ?>
    <body>
        <?php require_once "../includes/header.php"; ?>
        <?php require_once "../includes/nav.php"; ?>
        <script>
        setTimeout(function() {
        window.location.href = '../backend/redirect.php';
          }, 5000);
        </script>
        <div class="container my-5">
            <div class="alert alert-warning text-center" role="alert">
                <h2 class="section-heading mb-3">
                    <span class="section-heading-upper">Vous n'êtes pas connecté</span>
                    <br>
                    <span class="section-heading-lower">Redirection vers la page de connexion...</span>
                </h2>
            </div>
        </div>

        <?php require_once "../includes/footer.php"; ?>
    </body>
    </html>
    <?php
    exit();