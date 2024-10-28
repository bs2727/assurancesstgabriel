<!DOCTYPE html>
<html lang="en">
<?php include_once("../includes/head.php"); ?>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php include_once("../includes/nav.php"); ?>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white text-center">
                        <h3>Inscription</h3>
                    </div>
                    <div class="card-body">
                        <form action="../backend/register.php" method="POST">
                            <label for="username">Nom d'utilisateur:</label>
                            <input type="text" name="username" required>

                            <label for="email">Email:</label>
                            <input type="email" name="email" required>

                            <label for="password">Mot de passe:</label>
                            <input type="password" name="password" required>

                            <button type="submit">S'inscrire</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php include_once("../includes/footer.php"); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="asg/js/scripts.js"></script>
</body>

</html>