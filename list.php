<?php
include_once "class/database.class.php";
include "db_connect.php";

session_start();

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits 

$user = $_SESSION["user"];

$ajouter = isset($_POST['ajouter']) ? $_POST['ajouter'] : "";
$supprimer = isset($_POST['supprimer']) ? $_POST['supprimer'] : "";
$commander = isset($_POST['commander']) ? $_POST['commander'] : "";
$annuler = isset($_POST['annuler']) ? $_POST['annuler'] : "";




$deleteID = isset($_POST['deleteID']) ? $_POST['deleteID'] : "";
$productID = isset($_POST['productID']) ? $_POST['productID'] : "";
echo $deleteID;
if (!$user) {
    header("Location: connexion.php");
}

if ($user) {
    $produits = $db->SelectDb("SELECT * FROM produit;", NULL);
    $commandes = $db->SelectDb("SELECT * FROM commande WHERE id_user=:idUser;", [":idUser" => $user['id_user']]);
    $lignes =  $db->SelectDb("SELECT * FROM `ligne`, user WHERE user.id_user = :id_user;", [":id_user" => $user['id_user']]);
    
    //Recup total ht de la commande grace au TRIGGER lors du SELECT
    $total_lignes = $db->SelectDb("SELECT total_ligne_ht FROM ligne, user WHERE user.id_user=:id_user;", [":id_user" => $user['id_user']]);
    
    $total_ht = 0; //comme l'utilisateur peut avoir plusieurs ligne de commandes je lui fait un total
    foreach($total_lignes as $value) {
        $total_ht += $value['total_ligne_ht'];

    }
    $_SESSION["total_commande"] = $total_ht;
} 
//récupération du formulaire pour un INSERT dans commande 


if ($supprimer) {
    $db->DeleteDb("DELETE FROM ligne WHERE id_ligne=:deleteID", [":deleteID" => $deleteID]);
    $delete_c = $db->SelectDb("SELECT * FROM `ligne`, user WHERE user.id_user = :id_user AND ligne.id_ligne = :id_ligne", [":id_user" => $user['id_user'], ":id_ligne" => $deleteID+1]);
    header("Refresh:0");
}

if($commander) {

    header("Location: pay.php");

}

if($annuler) {

    //$db->DeleteDb("")

}

if ($ajouter) {

    // INSERT command
    
    $db->InsertDb(
        "INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES (NULL, :id_user, :id_etat, :date, :total_commande, :type_conso);",
        [":id_etat" => "1", ":date" => date('Y-m-d'), ":total_commande" => $_SESSION['totalHT'], ":type_conso" => 'extérieur', ":id_user" => $user['id_user']]
    );
   // INSERT ligne 
        $db->InsertDb(
            "INSERT INTO `ligne` (`id_ligne`, `id_commande`, `id_produit`, `qte`, `total_ligne_ht`) VALUES (NULL, :id_commande, :id_produit, :qte, NULL);",
            [":id_commande" => $commandes[0]["id_commande"],  ":id_produit" => $productID, ":qte" => '1']
        );
    
    header("Refresh:0");
}


?>

<!doctype html>
<html lang="fr">

<head>
<div class="text-dark">
    <title>Liste des produits</title>
</div>
    <?php
    include('header.php');
    ?>
    <div class="container">

        <div class="row">

            <div class="col align-self-start">
                <div class="text-dark">
                    <h1>Liste des produits</h1>
                </div>

                <?php

                foreach ($produits as $row) {
                    echo
                    '
                    <form method="POST">
                    <div class="card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="logoResto.png" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">' . $row['libelle'] . '</h5>
                                <p class="card-text">' . $row['descProduit'] . '</p>
                                <p class="font-weight-bold">' . $row['prix_ht'] . ' €</p>
                                <input type="hidden"  name="productID" value="' . $row['id_produit'] . '">
                                <input type="submit" value="Ajouter" name="ajouter" class="btn btn-success"/>
                            </div>
                        </div>
                    </div>
                  </div>
                </form>';
                }
                ?>

            </div>
            <div class="col align-self-start">
                <div class="text-white">
                    <h1> Liste des comandes</h1>
                </div>
                <div class="box">
                    <div class="col align-self-start">
                        <?php foreach ($lignes as $row) {

                            $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [':id_produit' => $row['id_produit']]);
                            echo ' 
                            <form method="POST">
                            <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                <div class="card-body" style="width: 350px;">
                                <h5 class="card-title"> ' . $the_product[0]['libelle'] . '</h5>
                                
                                <h5 class="card-title"> Commande N°' . $row['id_commande'] . '</h5>
                                
                                 <p class="card-text">'.$the_product[0]['libelle'].'</p>
                                            <input type="hidden"  name="deleteID" value="' . $row['id_ligne'] . '">
                                        <input type="submit" value="Supprimer" name="supprimer" class="btn btn-danger"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        ';
                        } ?>


                    </div>
                </div>
                <form method="POST">
                <input type="submit" name="commander" class="btn btn-success" value="Commander">
                <input type="submit" name="annuler" class="btn btn-warning" value="Annuler">
                </form>
                <div class="text-dark">
                    <h1>Prix Total HT : <?php echo $_SESSION["total_commande"]; ?> $</h1>
                    <h1>Prix Total TVA : <?php $_SESSION['totalTVA'] = $_SESSION["total_commande"] + $_SESSION["total_commande"]*0.05; echo $_SESSION['totalTVA']?> $</h1>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

</html>