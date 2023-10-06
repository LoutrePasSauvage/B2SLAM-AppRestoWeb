<?php
include_once "class/database.class.php";
include "db_connect.php";
session_start();
$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits 

$user = $_SESSION["user"];

$produits = $db->SelectDb("SELECT * FROM produit;");

//récupération du formulaire pour un INSERT dans commande 


$submit = isset($_POST['submit']) ? $_POST['submit'] : "";
$productID = isset($_POST['productID']) ? $_POST['productID'] : "";
if($submit) {

$db->InsertDb("INSERT INTO `commande` (`idCommande`, `idEtat`, `_date`, `totalCommande`, `typeConso`, `idUser`) VALUES (NULL, idEtat=:idEtat, _date=:_date, totalCommande=:totalCommande, typeConso=:typeConso, idUser=:idUser);", 
[":idEtat" => "0", ":_date" => "2023-10-06", ":totalCommande" => "20", ":typeConso" => "extérieur", ":idUser" => $user['idUser']]);
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
         
                foreach($produits as $row) { echo 
                    '
                    <form method="POST">
                    <div class="card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="logoResto.png" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">'.$row['libelle'].'</h5>
                                <p class="card-text">'.$row['descProduit'].'</p>
                                <p class="font-weight-bold">'.$row['prixHT'].' €</p>
                                <input type="hidden"  name="productID" value="'.$row['idProduit'].'">
                                <input type="submit" value="Ajouter" name="submit" class="btn btn-success"/>
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
                        <?php foreach($commandes as $row) {
                            echo ' <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body" style="width: 350px;">
                                        <h5 class="card-title">'.$row[''].'</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        } ?>
                        
                    </div>
                </div>
                <button type="button" class="btn btn-success">Commander</button>
                <button type="button" class="btn btn-warning">Annuler</button>
                <div class="text-white">
                <h1>Prix Total HT : 8.8 $</h1>
                <h1>Prix Total TVA : 9.8 $</h1>
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