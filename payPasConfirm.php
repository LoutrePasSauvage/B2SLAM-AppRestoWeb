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

if (empty($_SESSION["user"])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>PayPasConf</title>

<?php
    include('header.php');
?>

    <main style="padding: 15%">

        <div class="container">
            <h1 class="text-danger">Paiement non confirmé</h1>

            <h4 class="mb-3">adresse de payement</h4>
                <form class="needs-validation" novalidate="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Utilisateur</label>
                            <input type="text" class="form-control" id="firstName" placeholder="" value="<?= $login ?>" disabled required="">
                            <div class="invalid-feedback">
                                Prenom valide requis
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email </label>
                            <input type="email" class="form-control" id="email" placeholder="exemple@limayrac.fr" disabled value="<?= $email ?>">
                            <div class="invalid-feedback">
                                Entrez une e-mail valide
                            </div>
                        </div>
                    </div>
                <ul class="list-group mb-3">
                    <h4 class="mb-3">Commande N° <?= $lignes[0]['id_commande'] ?> </h4>
                    <?php foreach ($lignes as $row) {
                        $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [':id_produit' => $row['id_produit']]);
                        echo "<li class=' list-group-item d-flex justify-content-between lh-sm'>
                        <div>
                            <h6 class='my-0'> <i class='fa-solid fa-utensils fa-sm'></i> &ensp;" . $the_product[0]['libelle']."</h6>
                            <small class='text-muted'>". $the_product[0]['descProduit'] ." </small>
                        </div>
                        <span class='text-muted'> ". $the_product[0]['prix_ht'] ." € </span>
                    </li>"; }?>
                    <li class='list-group-item d-flex justify-content-between'>
                        <span>Total (en eur)</span>
                        <strong><?= $_SESSION['total_commande'] + $_SESSION['total_commande']*0.05;  ?> €</strong>
                        <span> <?= $_SESSION['typeConso'] ?></span>
                    </li>
                </ul>
                    <button class=" btn btn-success btn-lg" type="button" onclick="location.href='pay.php'">Revenir a la page de paiement</button>
                    <button class=" btn btn-success btn-lg" type="button" onclick="location.href='list.php'">Revenir a la page d'accueil</button>

                </form>
        </div>
    </main>
    <?php
    include "footer.php";
    ?>
