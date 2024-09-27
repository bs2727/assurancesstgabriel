<nav class="navbar navbar-expand-lg navbar-dark py-lg-4" id="mainNav">
    <div class="container">
        <a class="navbar-brand text-uppercase fw-bold d-lg-none" href="index.php">St Gabriel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="index.php">Accueil</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="about.php">Nos activités</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="products.php">Nos contrats</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="crew.php">Nos équipes</a></li>
                <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="contact.php">Nous contacter</a></li>
            </ul>
            <div class="d-flex">
                <button type="button" id="loginButton" class="btn btn-secondary text-white mx-2">Connexion</button>
                <button type="button" id="registerButton" class="btn btn-secondary text-white mx-2">Inscription</button>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('loginButton').onclick = function() {
        window.location.href = 'formlogin.php';
    };
    document.getElementById('registerButton').onclick = function() {
        window.location.href = 'formregister.php';
    };
</script>
