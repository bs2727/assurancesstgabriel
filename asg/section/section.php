<?php
//on va regarder s'il existe une variable id dans l'url
//et on va tester si elle n'est pas vide
if (isset($_GET["id"]) && ($_GET["id"] != "")) { //on va récupérer le mot clé et le stocker dans une variable
   $motcle = $_GET["id"];
}
//sinon, on va stocker le mot clé accueil dans notre variable
else {
   $motcle = "accueil";
}
//on va switcher en fonction du mot clé et inclure la page associée au mot clé
switch ($motcle) {
   case "accueil":
      include_once("../section/index.php");
      break;
   case "equipe":
      include_once("../section/about.php");
      break;
   case "produits":
      include_once("../section/contact.php");
      break;
   case "devis":
      include_once("../section/crew.php");
      break;
   default:
      include_once("../section/products.php");
      break;
}
?>