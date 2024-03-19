<?php
    include_once "class/database.class.php";
    include "db_connect.php";

    session_start();

    $objetConnexion = db_connect();
    $db = new Database($objetConnexion);

    //récupération des produits 

    $user = $_SESSION["user"];
    $first_commande = false;
    $commande_vide = false;
    $ajouter = isset($_POST['ajouter']) ? $_POST['ajouter'] : "";
    $supprimer = isset($_POST['supprimer']) ? $_POST['supprimer'] : "";
    $commander = isset($_POST['commander']) ? $_POST['commander'] : "";
    $annuler = isset($_POST['annuler']) ? $_POST['annuler'] : "";
    $deleteID = isset($_POST['deleteID']) ? $_POST['deleteID'] : "";
    $productID = isset($_POST['productID']) ? $_POST['productID'] : "";

    $typeConso = isset($_POST["typeconsommation"]) ? $_POST["typeconsommation"] : "0";

    //Si l'Utilisateur n'est pas connecter alors il est redirigé vers la page de connexion
    if (!$user) {
        header("Location: connexion.php");
    }

    if ($user) {

        //Type Conso si 1 alors c'està emporter autrement c'est sur place 
        if ($typeConso == 1) 
        {
            $_SESSION['typeConso'] = "à emporter";
            $valeur_tva = 0.055;
        } 
        else 
        {
            $_SESSION['typeConso'] = "sur place";
            $valeur_tva = 0.1;
        }
        //récupération de la liste de tous les produits 
        $produits = $db->SelectDb("SELECT * FROM produit;", NULL);
        //récupération des commande à partir de l'ID du l'utilisateur connectés 
        $commandes = $db->SelectDb("SELECT * FROM commande, user WHERE user.id_user = commande.id_user AND commande.id_user=:idUser;", [":idUser" => $user['id_user']]);

        if (!empty($commandes)) {
            $lignes = $db->SelectDb("SELECT id_ligne,id_commande,id_produit,qte,total_ligne_ht FROM `ligne`, user WHERE user.id_user = :id_user AND ligne.id_commande = :id_commande", [":id_user" => $user['id_user'], ":id_commande" => $commandes[0]["id_commande"]]);
            $_SESSION['id_commande'] = $commandes[0]["id_commande"];

            //Recup total ht de la commande grace au TRIGGER lors du SELECT
            $total_lignes = $db->SelectDb("SELECT total_ligne_ht FROM ligne, user WHERE user.id_user=:id_user AND ligne.id_commande = :id_commande", [":id_user" => $user['id_user'], ":id_commande" => $commandes[0]["id_commande"]]);
        }
        $total_ht = 0.0; //comme l'utilisateur peut avoir plusieurs ligne de commandes je lui fait un total

        if (!empty($total_lignes)) {
            foreach ($total_lignes as $value) {
                $total_ht += $value['total_ligne_ht'];
            }
        }
        $_SESSION["total_commande"] = $total_ht;
    }
    //récupération du formulaire pour un INSERT dans commande 


    if ($supprimer) {
        $db->DeleteDb("DELETE FROM ligne WHERE id_ligne=:deleteID", [":deleteID" => $deleteID]);
        $delete_c = $db->SelectDb("SELECT * FROM `ligne`, user WHERE user.id_user = :id_user AND ligne.id_ligne = :id_ligne", [":id_user" => $user['id_user'], ":id_ligne" => $deleteID + 1]);
        header("Refresh: 0");
    }

    if ($commander) {
        
        if (!empty($lignes) && $first_commande == false) {

            $db->InsertDb(
                "INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES (NULL, :id_user, :id_etat, NOW(), :total_commande, :type_conso);",
                [":id_etat" => "1", ":total_commande" => $_SESSION['totalTVA'], ":type_conso" => $typeConso, ":id_user" => $user['id_user']]
            );

            header("Location: pay.php");
        } 
        
        
        else {
            $commande_vide = true;
        }
    }

    if ($annuler) {
        if (!empty($lignes)) {
            $db->DeleteDb(
                "DELETE FROM ligne WHERE :id_commande",
                [":id_commande" => $lignes[0]['id_commande']]
            );
        }

        header("Refresh: 0");
    }

    if ($ajouter) {

        // INSERT ligne
        if (!empty($commandes)) {
            $db->InsertDb(
                "INSERT INTO `ligne` (`id_ligne`, `id_commande`, `id_produit`, `qte`, `total_ligne_ht`) VALUES (NULL, :id_commande, :id_produit, :qte, NULL);",
                [":id_commande" => $commandes[0]["id_commande"], ":id_produit" => $productID, ":qte" => '1']
            );
        } else {
            $db->InsertDb(
                "INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES (NULL, :id_user, :id_etat, NOW(), :total_commande, :type_conso);",
                [":id_etat" => "1", ":total_commande" => $_SESSION['totalTVA'], ":type_conso" => $typeConso, ":id_user" => $user['id_user']]
            );
            $first_commande = true;
        }

        header("Refresh: 0");
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>List</title>

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
                            <img src="images/' . $row['libelle'] . '.jpg" class="card-img" alt="pizza">
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
                <div class="text-dark">
                    <h1> Liste des commandes</h1>
                </div>
                <div class="box">
                    <div class="col align-self-start">

                        <?php

                        $show = false;
                        $previousID = null;

                        $liste_ids[] = array();
                        $array_id_produits = array();
                        $the_product[] = array();
                        $typeconso[] = array();

                        $_SESSION["liste_ids"] = $liste_ids;

                        if (!empty($lignes)) {
                            foreach ($lignes as $row) {
                                $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [':id_produit' => $row['id_produit']]);

                                $array_id_produits[] = $the_product[0]['libelle'];
                            }

                            $array_id_produits = array_replace($array_id_produits, array_fill_keys(array_keys($array_id_produits, null), ''));

                            $array_id_produits = array_count_values($array_id_produits);
                            foreach ($lignes as $row) {
                                $liste_ids[] = $row['id_produit'];
                                if (!empty($liste_ids)) {
                                    if (!in_array($row['id_produit'], $_SESSION["liste_ids"])) {
                                        $show = true;
                                    } elseif ($row['id_produit'] == $previousID) {
                                        $show = false;
                                    }  elseif (in_array($row['id_produit'], $_SESSION["liste_ids"])) {
                                        $show = false;
                                    }
                                }
                                $the_product = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit", [':id_produit' => $row['id_produit']]);

                                $typeconso = $db->SelectDb(
                                    "SELECT type_conso FROM `commande`, user WHERE commande.id_commande = :id_commande AND user.id_user = :id_user;",
                                    [":id_commande" => $row["id_commande"], ":id_user" => $user["id_user"]]
                                );
                                if ($show) {
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
                                    <h5 class="card-title"> Nombre produits ' . $array_id_produits[$the_product[0]['libelle']] . '</h5>
                                    <h5 class="card-title"> Commande N°' . $row['id_commande'] . '</h5>
                                    
                                     <p class="card-text">' . $the_product[0]['libelle'] . '</p>
                                                <input type="hidden"  name="deleteID" value="' . $row['id_ligne'] . '">
                                            <input type="submit" value="Supprimer" name="supprimer" class="btn btn-danger"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            ';

                                }
                                $_SESSION["liste_ids"] = $liste_ids;
                                $previousID = $row['id_produit'];

                            }
                        }
                    
                        ?>

                    </div>
                </div>
                <form method="POST">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="typeconsommation"
                            id="typeconsommation" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Sur place
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="typeconsommation"
                            id="typeconsommation">
                        <label class="form-check-label" for="flexRadioDefault2">
                            à emporter
                        </label>
                    </div>
                    <input type="submit" name="commander" class="btn btn-success" value="Commander">
                    <input type="submit" name="annuler" class="btn btn-warning" value="Annuler">
                </form>
                <div class="text-dark">
                    <h1>Prix Total HT :
                        <?php echo $_SESSION["total_commande"]."€"; 
                        if($commande_vide == true) {
                           echo" <div class='form-check'>
                           <div class='alert alert-danger' role='alert'>
                             Commande Vide :(
                        </div>
                       </div>";   
                             }
                        ?> 
                    </h1>
                  
                    <?php                   
                    
                        $_SESSION['totalTVA'] = $_SESSION["total_commande"] + $_SESSION["total_commande"] * $valeur_tva; 
                    ?>
                 
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    </body>

</html>
