<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestionProduit</title>

    <?php
        include('header.php');
    ?>

    <h1>Gestion du Produit</h1>
    <br>
    <p>Retour à la page de la gestion de <a href="./GestionList.php">liste des produits</a></p>
    <br>
    <?php
        include_once "class/database.class.php";
        include "db_connect.php";

        session_start();

        $user = $_SESSION["user"];
        //Si l'Utilisateur n'est pas connecter alors il est redirigé vers la page de connexion
        if (!$user) {
            header("Location: connexion.php");
            die;
        }

        
        if ($_SESSION['user']['admin'] == 0)
        {
            header("Location: connexion.php");
            die;
        }
        

        $objetConnexion = db_connect();
        $db = new Database($objetConnexion);

        $idProduit = isset($_GET['idProduit']) ? $_GET['idProduit'] : "";

        $Produit = $db->SelectDb("SELECT * FROM produit WHERE id_produit=:id_produit;", [":id_produit" => $idProduit]);

        foreach ($Produit as $row) 
        {
            if($row['Rupture'] == 1)
            {
                $rupture_indice = "Oui";
            }
            else
            {
                $rupture_indice = "Non";
            }
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
                                <p class="card-text">Rupture ? ' . $rupture_indice . '</p>
                                <input type="hidden" name="Rupture" value="0"/>
                                <input type="checkbox" name="Rupture" value="1" 
                                ';
                                if($row['Rupture'] == 1)
                                {
                                    echo 'checked';
                                }
            echo '/> 
                                <p class="card-text">Motif de rupture : </p>
                                <input type="text" name="MotifRupture" value="'.$row['MotifRupture'].'"/>
                                <p> Id produit : '.$row['id_produit'].'</p>

                                <input type="submit" value="Envoyer" name="Envoyer" class="btn btn-success"/>

                            </div>
                        </div>
                    </div>
                </div>
            </form>'; 
        }

        if(isset($_POST['Envoyer']))
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            {
                $rupture = isset($_POST['Rupture']) ? $_POST['Rupture'] : 0;
                $rupture = intval($rupture);
                //echo "Rupture: " . $rupture;
                //print_r($rupture);
                //echo gettype($rupture);
            }
            $motifRupture = isset($_POST['MotifRupture']) ? $_POST['MotifRupture'] : "";

            if($rupture == 1 && $motifRupture == NULL)
            {
                echo "Veuillez saisir un motif de rupture";
            }
            else
            {
                if($rupture == 0)
                {
                    $motifRupture = "";
                }
                //echo $motifRupture;
                
                $db->UpdateDb(
                    "UPDATE produit SET Rupture=:Rupture, MotifRupture=:MotifRupture WHERE id_produit=:id_produit",[":Rupture" => $rupture, ":MotifRupture" => $motifRupture, ":id_produit" => $Produit[0]['id_produit']]
                );

                header("Location: GestionList.php");
            }


    
        }
        
    ?>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    </head>

</html>