<?php
include_once "class/database.class.php";
include "db_connect.php";

session_start();

$messages = array();  // Message d'erreur

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits

$user = $_SESSION["user"];

$productID = isset($_POST['productID']) ? $_POST['productID'] : "";
//$typeConso = isset($_POST['typeConso']) ? $_POST['typeConso'] : "";

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

//fait une validation pour la carte de credit
if (isset($_POST['submit'])) {
    $cc_name = isset($_POST['cc_name']) ? $_POST['cc_name'] : '';
    $cc_number = isset($_POST['cc_number']) ? $_POST['cc_number'] : '';
    $cc_expiration = isset($_POST['cc_expiration']) ? $_POST['cc_expiration'] : '';
    $cc_cvv = isset($_POST['cc_cvv']) ? $_POST['cc_cvv'] : '';

    $cc_name = filter_var($cc_name, FILTER_SANITIZE_STRING);
    $cc_number = filter_var($cc_number, FILTER_SANITIZE_STRING);
    $cc_expiration = filter_var($cc_expiration, FILTER_SANITIZE_STRING);
    $cc_cvv = filter_var($cc_cvv, FILTER_SANITIZE_STRING);

    if (empty($cc_name)) {
        $messages[] = "le nom de la carte est obligatoire";
    }
    if (empty(trim($cc_number))) {
        $messages[] = "le numéro de la carte est obligatoire";
    }
    if (empty(trim($cc_expiration))) {
        $messages[] = "la date d'expiration est obligatoire";
    }
    if (empty(trim($cc_cvv))) {
        $messages[] = "le cvv est obligatoire";
    }
    if (strlen($cc_number) < 16) {
        $messages[] = "le numéro de la carte doit avoir 16 chiffres";
    }
    if (strlen($cc_expiration) < 4) {
        $messages[] = "la date d'expiration doit avoir 4 chiffres";
    }
    if (strlen($cc_cvv) < 3) {
        $messages[] = "le cvv doit avoir 3 chiffres";
    }
    if (empty($messages)) {
        header("Location: payConf.php");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Page de paiement</title>

<?php
    include('header.php');
?>

    <main style="padding: 12%">

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">

                <ul class="list-group mb-3">
                    <span>Commande N° <?= $lignes[0]['id_commande'] ?> </span>
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
                        <span>Total TTC (en eur)</span>
                        <strong><?= $_SESSION['total_commande'] + $_SESSION['total_commande']*0.05;  ?> €</strong>
                    </li>
                </ul>

            </div>
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">adresse de payement</h4>
                <form class="needs-validation" method="post">
                    <?php
                    if (count($messages) > 0) {//TODO: afficher les messages d'erreur

                        foreach ($messages as $message) {
                            echo "<h6 class='alert alert-danger' >" . $message . "</h6>";
                        }
                    }
                    ?>
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
                            <label for="cc_name" class="form-label">Nom de la carte</label>
                            <input name="cc_name" type="text" class="form-control" id="cc_name" placeholder="">

                        </div>

                        <div class="col-md-6">
                            <label for="cc_number" class="form-label">Numero carte de crédit</label>
                            <input name="cc_number" type="number" class="form-control" id="cc_number" placeholder="">
                        </div>

                        <div class="col-md-3">
                            <label for="cc_expiration" class="form-label">Expiration</label>
                            <input name="cc_expiration" type="month" class="form-control" id="cc_expiration" placeholder="">
                        </div>

                        <div class="col-md-3">
                            <label for="cc_cvv" class="form-label">CVV</label>
                            <input name="cc_cvv" type="number" class="form-control" id="cc_cvv" placeholder="">
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-25 btn btn-primary btn-lg" type="submit" name="submit" id="submit" >Payer</button> &ensp;
                    <button class="w-25 btn btn-secondary btn-lg" type="button" onclick="location.href='list.php'">Annuler</button>
                </form>
            </div>
        </div>
    </main>
<?php
    include "footer.php";

?>
