<!DOCTYPE html>
<html lang="en">
    <?php include_once("head.php"); ?>
    <body>
		<?php include_once("header.php"); ?>
        <?php include_once("nav.php"); ?>




<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-dark text-white text-center">
                    <h3>Connexion</h3>
                </div>
                <div class="card-body">
                <form action="login.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="password">Mot de passe:</label>
    <input type="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

                </div>
            </div>
        </div>
    </div>
</div>






        <?php include_once("footer.php"); ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
