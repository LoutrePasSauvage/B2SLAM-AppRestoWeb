<?php  
    try
    {
        include_once "../class/database.class.php";
        include_once "../db_connect.php";

        $objetConnexion = db_connect();
        $db = new Database($objetConnexion);
    if ($_GET['id_commande'] != null) {

        $sql_select_commande_prep = "SELECT * FROM commande WHERE id_etat = 2 AND id_commande = :id_commande";

        $commande_prep = $db->SelectDb($sql_select_commande_prep, [":id_commande" => $_GET['id_commande']]);
    } else {
        echo "Erreur lors de la récupération de l'id de la commande";
    }
        /*
            En terminer      -> 1
            En préparation  -> 2
            abandonnée      -> 3
            prête           -> 4
        */
        
        if (!empty($commande_prep) && isset($commande_prep[0]['id_etat']) && $commande_prep[0]['id_etat'] == 2) 
        {
            $sql_update_commande_terminer = "UPDATE commande SET id_etat = 4 WHERE id_commande = :id_commande";

            $commande_terminer = $db->UpdateDb($sql_update_commande_terminer, [":id_commande" => $_GET['id_commande']]);

            $sql_select_commande_terminer = "SELECT * FROM commande WHERE id_etat = 4 AND id_commande = :id_commande";
            
            $commande_terminer_end = $db->SelectDb($sql_select_commande_terminer, [":id_commande" => $_GET['id_commande']]);
                
            //Pour voir le résultat de la commande :
            /*
                echo "<pre>";
                print_r($commande_terminer_end);
                echo "</pre>";
            */
            $commandes_terminer_json = json_encode($commande_terminer_end, JSON_PRETTY_PRINT);

            //Pour voir le résultat du fichier json de la commande

            header("Content-type: application/json; charset=utf-8");
            
            echo $commandes_terminer_json;
        }
    } 
    catch (Exception $ex) 
    {
        echo "<p>Erreur : ".$ex->getMessage()."</p>";
    }
?>
