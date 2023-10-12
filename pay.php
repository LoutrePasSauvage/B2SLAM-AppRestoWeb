<?php
include_once "class/database.class.php";
include "db_connect.php";

session_start();

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits

$user = $_SESSION["user"];

$productID = isset($_POST['productID']) ? $_POST['productID'] : "";

if (!$user) {
    echo "<p> Vous n'êtes pas connecté ! </p>";
}

if ($user) {
    $login = $user['login'];
    $email = $user['email'];
    $produits = $db->SelectDb("SELECT * FROM produit;", NULL);
    $lignes =  $db->SelectDb("SELECT * FROM `ligne`, user WHERE user.id_user = :id_user;", [":id_user" => $user['id_user']]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de payement</title>
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<nav>
    <ul class="menu">
        <li class="menu-item">
            <a href="index.php">
                <img class="logoResto" src="img/logoResto.png" width=20%/>
            </a>
        </li>
        <li class="menu-item a-menu"><a href="index.php">Accueil</a></li>
        <li class="menu-item a-menu"><a href="connexion.php">Connexion</a></li>
    </ul>
</nav>
    <main style="padding: 15%">

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">

                </h4>

                <ul class="list-group mb-3">
                    <?php foreach ($lignes as $row) {
                        $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [':id_produit' => $row['id_produit']]);
                    echo "<li class=' list-group-item d-flex justify-content-between lh-sm'>
                        <div>
                            <h6 class='my-0'>" . $the_product[0]['libelle']."</h6>
                            <small class='text-muted'>". $the_product[0]['descProduit'] ." </small>
                        </div>
                        <span class='text-muted'> ". $the_product[0]['prix_ht'] ." € </span>
                    </li>"; }?>
                    <li class='list-group-item d-flex justify-content-between'>
                        <span>Total (en €)</span>
                        <strong><?= $_SESSION['total_commande'] + $_SESSION['total_commande']*0.05;  ?> €</strong>
                    </li>
                </ul>

            </div>
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">adresse de payement</h4>
                <form class="needs-validation" novalidate="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Utilisateur</label>
                            <input type="text" class="form-control" id="firstName" placeholder="" value="<?= $login ?>" required="">
                            <div class="invalid-feedback">
                                Prenom valide requis
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email </label>
                            <input type="email" class="form-control" id="email" placeholder="exemple@limayrac.fr" value="<?= $email ?>">
                            <div class="invalid-feedback">
                                Entrez une e-mail valide
                            </div>
                        </div>

                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Carte de crédit</h4>

                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label for="cc-name" class="form-label">Nom de la carte</label>
                            <input type="text" class="form-control" id="cc-name" placeholder="" required="">
                            <div class="invalid-feedback">
                                Nom de carte requis
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="cc-number" class="form-label">Numero carte de crédit</label>
                            <input type="text" class="form-control" id="cc-number" placeholder="" required>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-expiration" class="form-label">Expiration</label>
                            <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-25 btn btn-primary btn-lg" type="button" onclick="location.href='payConf.php'">Payer</button> &ensp; <button class="w-25 btn btn-secondary btn-lg" type="button" onclick="location.href='list.php'">Annuler</button>
                </form>
            </div>
        </div>
    </main>
<footer>
    &copy Restaurant de qualité
</footer>
</body>
</html>

<!--Js for boot strap-->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
