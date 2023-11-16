<?php  
    try
    {
        include_once "../class/database.class.php";
        include "../db_connect.php";


        $objetConnexion = db_connect();
        $db = new Database($objetConnexion);

        $sql_commande_attente = "SELECT * FROM commande WHERE id_etat = :id_etat";

        $commande_attente = $db->SelectDb($sql_commande_attente, [":id_etat"=>1]);
        /*
            En attente      -> 1
            En préparation  -> 2
            abandonnée      -> 3
            prête           -> 4
        */


        /*
            echo "<pre>";
            print_r($commande_attente);
            echo "</pre>";
        */

        $commandes_attente_json = json_encode($commande_attente, JSON_PRETTY_PRINT);

        echo $commandes_attente_json;
    } 
    catch (Exception $ex) 
    {
        echo "<p>Erreur : ".$ex->getMessage()."</p>";
    }
?>
