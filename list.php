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





$deleteID = isset($_POST['deleteID']) ? $_POST['deleteID'] : "";
$productID = isset($_POST['productID']) ? $_POST['productID'] : "";

if (!$user) {
    echo "<p> Vous n'êtes pas connecté ! </p>";
}

if ($user) {
    $produits = $db->SelectDb("SELECT * FROM produit;", NULL);
    $commandes = $db->SelectDb("SELECT * FROM commande WHERE id_user=:idUser;", [":idUser" => $user['id_user']]);
    $id_produit =  $db->SelectDb("SELECT id_produit FROM ligne, commande, user WHERE ligne.id_commande = commande.id_commande and commande.id_user = user.id_user AND user.id_user = :id_user;", [":id_user" => $user['id_user']]);
   
   
    $totalHT = $db->SelectDb("SELECT SUM(total_commande) as totalHT FROM commande WHERE id_user=:id_user;", [":id_user" => $user['id_user']]);
    $_SESSION["totalHT"] = $totalHT[0]['totalHT'];
}
//récupération du formulaire pour un INSERT dans commande 


if ($supprimer) {
    $db->DeleteDb("DELETE FROM ligne WHERE id_commande=:deleteID", [":deleteID" => $deleteID]);
    $db->DeleteDb("DELETE FROM commande WHERE id_commande=:deleteID", [":deleteID" => $deleteID]);
    header("Refresh:0");
}


if ($ajouter) {

    // INSERT command
    
    $db->InsertDb(
        "INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES (NULL, :id_user, :id_etat, :date, :total_commande, :type_conso);",
        [":id_etat" => "1", ":date" => date('Y-m-d'), ":total_commande" => '20.2', ":type_conso" => 'extérieur', ":id_user" => $user['id_user']]
    );

    // INSERT ligne 

    foreach ($commandes as $value) {
        $db->InsertDb(
            "INSERT INTO `ligne` (`id_ligne`, `id_commande`, `id_produit`, `qte`, `total_ligne_ht`) VALUES (NULL, :id_commande, :id_produit, :qte, :total_ligne_ht);",
            [":id_commande" => $value["id_commande"],  ":id_produit" => $productID, ":qte" => '1', ":total_ligne_ht" => $_SESSION['totalHT']]
        );
    }
    header("Refresh:0");
}


?>

<!doctype html>
<html lang="fr">

<head>
    <title>Liste des produits</title>

    <?php
    include('header.php');
    ?>
    <div class="container">

        <div class="row">

            <div class="col align-self-start">
                <div class="text-white">
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
                        <?php foreach ($commandes as $row) {
                            echo ' 
                            <form method="POST">
                            <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                <div class="card-body" style="width: 350px;">
                                <h5 class="card-title"> Commande N°' . $row['id_commande'] . '</h5>';

                            if ($row['id_etat'] == 0) {
                                echo '<h5 class="card-title"> extérieur </h5>';
                            } else {
                                echo '<h5 class="card-title"> sur place </h5>';
                            }

                            echo '     
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                            <input type="hidden"  name="deleteID" value="' . $row['id_commande'] . '">
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
                <button type="button" class="btn btn-success">Commander</button>
                <button type="button" class="btn btn-warning">Annuler</button>
                <div class="text-white">
                    <h1>Prix Total HT : <?php echo $_SESSION["totalHT"]; ?> $</h1>
                    <h1>Prix Total TVA : 9.8 $</h1>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

</html>