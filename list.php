<?php
include_once "class/database.class.php";
include_once "class/ligneProduit.class.php";
include "db_connect.php";

session_start();

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits 

$user = $_SESSION["user"];

$commande_vide = false;

$ajouter = isset($_POST['ajouter']) ? $_POST['ajouter'] : "";
$supprimer = isset($_POST['supprimer']) ? $_POST['supprimer'] : "";
$commander = isset($_POST['commander']) ? $_POST['commander'] : "";
$annuler = isset($_POST['annuler']) ? $_POST['annuler'] : "";

$prixProduit = isset($_POST['prixProduit']) ? $_POST['prixProduit'] : "";
$productID = isset($_POST['productID']) ? $_POST['productID'] : "";
$deleteID = isset($_POST['deleteID']) ? $_POST['deleteID'] : "";

$surplace = isset($_POST['surplace']) ? $_POST['surplace'] : "";
$emporter = isset($_POST['emporter']) ? $_POST['emporter'] : "";

$produits = $db->SelectDb("SELECT * FROM produit;", NULL);


$SelectionCommandes = array();
$facteurConso = array();

if(isset($_SESSION['facteurConso'])) {
    $facteurConso = $_SESSION['facteurConso'];
}

if (isset($_SESSION['SelectionCommandes'])) {
    $SelectionCommandes = $_SESSION['SelectionCommandes'];
}

if (!isset($_SESSION["total_commande"])) {
    $_SESSION["total_commande"] = 0;
}

if (isset($_SESSION["typeConso"])) {
    $typeConso = $_SESSION["typeConso"];
}
if(isset($_SESSION["facteurConso"])) {
    $facteurConso = $_SESSION["facteurConso"];
}

//Si l'Utilisateur n'est pas connecter alors il est redirigé vers la page de connexion
if (!$user) {
    header("Location: connexion.php");
    die;
}

//Type Conso si 1 alors c'est à emporter autrement c'est sur place 
if($surplace) {
    $facteurConso = array("facteur" => 1.1, "typeConso" => "Sur place");
    $_SESSION['facteurConso'] = $facteurConso;
}
if($emporter) {
    $facteurConso = array("facteur" => 1.055, "typeConso" => "à emporter");
    $_SESSION['facteurConso'] = $facteurConso;
}


if ($ajouter) {
    $found = false;
    $_SESSION["total_commande"] += $prixProduit;
    foreach ($SelectionCommandes as $row) {
        if ($row->get_id_produit() == $productID) {
            $found = true;
            $row->set_qte($row->get_qte() + 1);
            break;
        }
    }

    if (!$found) {
        $SelectionCommandes[] = new LigneProduit(0, $productID, 1, $prixProduit);
    }

    $_SESSION['SelectionCommandes'] = $SelectionCommandes;
    header("Refresh: 0");
}


if ($supprimer) {

    foreach ($_SESSION['SelectionCommandes'] as $key => $value) {
        if ($value->get_id_produit() == $deleteID) {
            if ($value->get_qte() > 1) {
                $value->set_qte($value->get_qte() - 1);
                $_SESSION["total_commande"] -= $value->get_prix();
            } else {
                unset($SelectionCommandes[$key]);
                $_SESSION["total_commande"] -= $value->get_prix();
            }
        }
    }

    $_SESSION['SelectionCommandes'] = $SelectionCommandes;

    header("Refresh: 0");
}

if ($commander) {

    if (!empty($SelectionCommandes)) {
        $_SESSION['SelectionCommandes'] = $SelectionCommandes;
        header("Location: pay.php");
    } else {
        $commande_vide = true;
    }
}

if ($annuler) {
    //set total commande to 0
    $_SESSION["total_commande"] = 0;
    //unset all selected products
    unset($_SESSION['SelectionCommandes']);
    if (!empty($lignes)) {
        $db->DeleteDb(
            "DELETE FROM ligne WHERE :id_commande",
            [":id_commande" => $lignes[0]['id_commande']]
        );
    }

    header("Refresh: 0");
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>List</title>

    <?php
    include('header.php');
    ?>


    <div class="container">

        <div class="row">

            <div class="col-md-6">
                <div class="text-dark">
                    <h1>Liste des produits</h1>
                </div>
                <div class="row row-cols-6">

                    <?php

                    foreach ($produits as $row) {

                        echo
                        '
                    <form method="POST">
                    <div class="col card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="images/' . $row['libelle'] . '.jpg" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">' . $row['libelle'] . '</h5>
                                <p class="card-text">' . $row['descProduit'] . '</p>
                                <p class="font-weight-bold">' . $row['prix_ht'] . ' €</p>    
                                <input type="hidden"  name="productID" value="' . $row['id_produit'] . '">
                                <input type="hidden" name="prixProduit" value="' . $row['prix_ht'] . '">
                                <input type="submit" value="Ajouter" name="ajouter" class="btn btn-success"/>
                            </div>
                        </div>
                    </div>
                  </div>
                </form>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-dark">
                    <h1> Liste des commandes</h1>
                </div>
                <div class="box">
                    <div class="col align-self-start">

                        <?php


                        //store already added products ids

                        foreach ($SelectionCommandes as $row) {
                            $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [":id_produit" => $row->get_id_produit()]);
                            $row->set_prix($the_product[0]['prix_ht']);
                            echo '
                                <form method="POST">
                                <div class="card mb-2" style="max-width: 640px;">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="images/' . $the_product[0]['libelle'] . '.jpg" class="card-img" alt="pizza">
                                    </div>
    
                                    <div class="col-md-8">
                                    <div class="card-body" style="width: 350px;">
                                    <h5 class="card-title"> ' . $the_product[0]['libelle'] . '</h5>
                                    <p class="font-weight-bold"> </p>
                                    <h5 class="card-title"> Nombre produits ' . $row->get_qte() . '</h5>
                                
                                     <p class="card-text">' . $the_product[0]['libelle'] . '</p>
                                            <input type="hidden"  name="deleteID" value="' . $the_product[0]['id_produit'] . '">
                                            <input type="submit" value="Supprimer" name="supprimer" class="btn btn-danger"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            ';}
                        ?>

                    </div>
                </div>
                <form method="POST">

                    <div class="form-check">
                        <input type="submit" name="surplace" class="btn btn-success" value="Sur place">
                        <input type="submit" name="emporter" class="btn btn-success" value="à emporter">

                        <input type="submit" name="commander" class="btn btn-success" value="Commander">
                        <input type="submit" name="annuler" class="btn btn-warning" value="Annuler">
                </form>
                <div class="text-dark">
                    <h1>Prix Total HT :
                        <?php echo $_SESSION["total_commande"] . "€";
                        if ($commande_vide == true) {
                            echo " <div class='form-check'>
                           <div class='alert alert-danger' role='alert'>
                             Commande Vide :(
                        </div>
                       </div>";
                        }
                        ?>
                    
                    </h1>

                    <h3> <?php 
                    if(isset($facteurConso["facteur"]) && isset($facteurConso["typeConso"])) {
                        echo("Prix Total TVA ". $facteurConso["typeConso"]. " : ");
                        $_SESSION['totalTVA'] = $_SESSION["total_commande"]  * $facteurConso["facteur"]; 
                        echo $_SESSION['totalTVA']."€"; 
                    }
                    ?></h3>
                </div>
            </div>
        </div>
    </div>
    </div>
    </body>

</html>