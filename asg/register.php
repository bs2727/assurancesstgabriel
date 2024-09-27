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
                    <h3>Inscription</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" placeholder="Nom d'utilisateur">
                        </div>
                        <div class="form-group">
                            <label for="email">Adresse Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm-password">
                        </div>
                        <button type="submit" class="btn btn-dark btn-block">Inscription</button>
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
