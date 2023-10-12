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
    <title>Page de paiement</title>

<?php
    include('header.php');
?>

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
                            <h6 class='my-0'> <i class='fa-solid fa-utensils fa-sm'></i> &ensp; &ensp; " . $the_product[0]['libelle']."</h6>
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

                    <button class="w-25 btn btn-primary btn-lg" type="button" onclick="location.href='payConf.php'">Payer</button> &ensp;
                    <button class="w-25 btn btn-secondary btn-lg" type="button" onclick="location.href='list.php'">Annuler</button>
                </form>
            </div>
        </div>
    </main>
<?php
    include "footer.php";

?>
