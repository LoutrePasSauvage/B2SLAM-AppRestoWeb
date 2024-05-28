<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GestionList</title>

    <?php
        include('header.php');
    ?>

    <h1>Gestion liste produits</h1>

    <p>Retour à la page des <a href="./list.php">commandes</a></p>

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

        $produits = $db->SelectDb("SELECT * FROM produit;", NULL);


        foreach ($produits as $row) {
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
                                <p class="card-text">' . $row['MotifRupture'] . '</p>
                                <p> Id produit : '.$row['id_produit'].'</p>

                                <a href = "GestionProduit.php?idProduit= '. $row['id_produit'] . '">Modifier</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>'; //<a href="GestionProduit.php?idProduit='.$row['id_produit'].'">Modifier</a>
        }
    ?>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    </head>

</html>