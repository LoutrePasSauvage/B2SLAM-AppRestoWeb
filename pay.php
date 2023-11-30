<?php
include_once "class/database.class.php";
include "db_connect.php";

session_start();

$messagesName = array();  // Message d'erreur nom
$messagesNumber = array();  // Message d'erreur numéro de carte
$messagesCVV = array();  // Message d'erreur du CVV
$messagesExpiration = array();  // Message d'erreur de la date d'expiration
$cc_name = "";

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits

$user = $_SESSION["user"];

$productID = isset($_POST['productID']) ? $_POST['productID'] : "";
$submit = isset($_POST['submit']) ? $_POST["submit"] : "";
//$typeConso = isset($_POST['typeConso']) ? $_POST['typeConso'] : "";

if (!$user) {
    echo "<p> Vous n'êtes pas connecté ! </p>";
}

if ($user) {
    $login = $user['login'];
    $email = $user['email'];
    $produits = $db->SelectDb("SELECT * FROM produit;", NULL);
    if ($_SESSION['id_commande']) {
        $lignes = $db->SelectDb("SELECT * FROM `ligne`, user WHERE user.id_user = :id_user AND ligne.id_commande = :id_commande", [":id_user" => $user['id_user'], ":id_commande" => $_SESSION['id_commande']]);
    }

}

if (empty($_SESSION["user"])) {
    header("Location: index.php");
}

//retour a la list.php si commande est annuler
if (isset($_POST['Annuler'])) {
    $db->UpdateDb("UPDATE `commande` SET commande.id_etat = :id_etat WHERE id_user=:id_user AND id_commande = :id_commande;", [":id_user" => $user['id_user'], ":id_commande" => $_SESSION['id_commande'], ":id_etat" => 3]);

    header("Location: list.php");
}

//fait une validation pour la carte de credit
if (isset($_POST['submit'])) {
    $cc_name = isset($_POST['cc_name']) ? $_POST['cc_name'] : '';
    $cc_number = isset($_POST['cc_number']) ? $_POST['cc_number'] : '';
    $cc_expirationMM = isset($_POST['cc_expirationMM']) ? $_POST['cc_expirationMM'] : '';
    $cc_expirationYY = isset($_POST['cc_expirationYY']) ? $_POST['cc_expirationYY'] : '';
    $cc_cvv = isset($_POST['cc_cvv']) ? $_POST['cc_cvv'] : '';

    if (empty($cc_name)) {
        $messagesName[] = "le nom de la carte est obligatoire";
    }
    if (empty(trim($cc_number))) {
        $messagesNumber[] = "le numéro de la carte est obligatoire";
    }
    if (empty(trim($cc_expirationMM)) && empty(trim($cc_expirationYY))) {
        $messagesExpiration[] = "la date d'expiration est obligatoire";
    }
    if (empty(trim($cc_cvv))) {
        $messagesCVV[] = "le cvv est obligatoire";
    }
    if (strlen($cc_number) < 16) {
        $messagesNumber[] = "le numéro de la carte doit avoir 16 chiffres";
    }
    if (strlen($cc_expirationMM) < 2 || strlen($cc_expirationMM) > 2) {
        $messagesExpiration[] = "Le mois doit etre écrit en 2 chiffres";
    }
    if (strlen($cc_expirationYY) < 4 || strlen($cc_expirationYY) > 4) {
        $messagesExpiration[] = "L'année doit etre écrit en 4 chiffres";
    }
    if (strlen($cc_cvv) < 3) {
        $messagesCVV[] = "le cvv doit avoir 3 chiffres";
    }
    if (!preg_match('/^[0-9]*$/', $cc_cvv)) {
        $messagesCVV[] = "le cvv doit être composé de chiffres";
    }
    if (strlen($cc_number) > 16) {
        $messagesNumber[] = "le numéro de la carte doit avoir 16 chiffres maximum";
    }
    if (strlen($cc_expirationMM) == 2 && strlen($cc_expirationYY) == 4) {
        $cc_expirationMM = intval($cc_expirationMM);
        $cc_expirationYY = intval($cc_expirationYY);
        $date = getdate();
        $annee = $date['year'];
        $mois = $date['mon'];
        if ($cc_expirationYY < $annee) {
            $messagesExpiration[] = "la date d'expiration est dépassée";
        }
        if ($cc_expirationYY == $annee && $cc_expirationMM < $mois) {
            $messagesExpiration[] = "la date d'expiration est dépassée";
        }
        if ($cc_expirationMM < 1 || $cc_expirationMM > 12) {
            $messagesExpiration[] = "le mois doit etre compris entre 1 et 12";
        }
    }


    if (empty($messagesName) && empty($messagesNumber) && empty($messagesExpiration) && empty($messagesCVV)) {
        if ($cc_number == "0000000000000000") {

            header("Location: payPasConfirm.php");
        } else {
            header("Location: payConf.php");
        }
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
                            <h6 class='my-0'> <i class='fa-solid fa-utensils fa-sm'></i> &ensp;" . $the_product[0]['libelle'] . "</h6>
                            <small class='text-muted'>" . $the_product[0]['descProduit'] . " </small>
                        </div>
                        <span class='text-muted'> " . $the_product[0]['prix_ht'] . " € </span>
                    </li>";
                    } ?>
                    <li class='list-group-item d-flex justify-content-between'>
                        <span>Total TTC (en eur)</span>

                        <strong><?= $_SESSION['total_commande'] + $_SESSION['total_commande'] * 0.05; ?> € </strong>
                    </li>
                    <li class='list-group-item d-flex justify-content-between'>
                        <?= "Votre commande est " . $_SESSION['typeConso'] ?>
                    </li>
                </ul>

            </div>
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">adresse de payement</h4>
                <form class="needs-validation" method="post" id="paymentForm">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Utilisateur</label>
                            <input type="text" class="form-control" id="firstName" placeholder="" value="<?= $login ?>"
                                   required="">
                            <div class="invalid-feedback">
                                Prenom valide requis
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email </label>
                            <input type="email" class="form-control" id="email" placeholder="exemple@limayrac.fr"
                                   value="<?= $email ?>">
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
                            <input name="cc_name" type="text" class="form-control" id="cc_name" value="<?= $cc_name ?>">
                            <?php
                            if (count($messagesName) > 0) {
                                echo "<p class='btn alert-warning' >";
                                foreach ($messagesName as $message) {
                                    echo $message . '<br>';
                                }
                                "</p>";
                            }
                            ?>
                        </div>

                        <div class="col-md-6">
                            <label for="cc_number" class="form-label">Numero carte de crédit</label>

                            <input name="cc_number" type="number" class="form-control" id="cc_number"
                                   value="<?= $cc_number ?>">
                            <?php
                            if (count($messagesNumber) > 0) {

                                echo "<p class='btn alert-warning' >";
                                foreach ($messagesNumber as $message) {
                                    echo $message . '<br>';
                                }
                                "</p>";
                            }
                            ?>
                            <p class="text-info"><i class="fas fa-level-up-alt fa-rotate-90"></i> &ensp; Faire <em
                                        class="text-secondary">0000 0000
                                    0000 0000</em> pour payement pas confirmé ou <em class="text-secondary">1234 1234
                                    1234 1234</em> pour confirmé le payement.</p>
                        </div>
                        <div class="row-cols ">
                            <label for="cc_expiration" class="form-label">Expiration</label>
                            <div class="row w-50 mx-auto">
                                <input name="cc_expirationMM" type="number" class="col form-control"
                                       id="cc_expirationMM" placeholder="Mois"
                                       value="<?= $cc_expirationMM ?>">
                                <input name="cc_expirationYY" type="number" class="col form-control"
                                       id="cc_expirationYY" placeholder="Année"
                                       value="<?= $cc_expirationYY ?>">
                            </div>


                        <?php
                        if (count($messagesExpiration) > 0) {

                            echo "<p class='btn alert-warning' >";
                            foreach ($messagesExpiration as $message) {
                                echo $message . '<br>';
                            }
                            "</p>";
                        }
                        ?>
                    </div>

                    <div class="col-md-3">
                        <label for="cc_cvv" class="form-label">CVV</label>
                        <input name="cc_cvv" type="number" class="form-control" id="cc_cvv" value="<?= $cc_cvv ?>">
                        <?php
                        if (count($messagesCVV) > 0) {
                            echo "<p class='btn alert-warning' >";
                            foreach ($messagesCVV as $message) {
                                echo $message . '<br>';
                            }
                            "</p>";
                        }
                        ?>
                    </div>
            </div>

            <hr class="my-4">

            <button class="w-25 btn btn-primary btn-lg" type="submit" name="submit"
                    onclick="confirmerPaiement()">Payer
            </button>

            <form method="POST">
                <button class="w-25 btn btn-check btn-lg" type="submit" name="Annuler">Annuler</button>
            </form>
            </form>
        </div>
        </div>
    </main>
    <?php
    include "footer.php";

    ?>

    <script>
        function confirmerPaiement() {
            var confirmer = confirm("Confirmez-vous le paiement ?");
            if (confirmer) {
                // Si l'utilisateur clique sur OK, le formulaire sera soumis
                document.getElementById("paymentForm").submit();
            }
        }
    </script>
