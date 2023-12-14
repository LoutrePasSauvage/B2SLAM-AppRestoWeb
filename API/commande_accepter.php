<?php
    include_once "../db_connect.php";
    include_once "../class/database.class.php";
        /*
            En attente      -> 1
            En préparation  -> 2
            refusé          -> 3
            prête           -> 4
        */

    try 
    {
        $dbh = db_connect();
        // Pour Firefox et le formatage
        //header("Content-Type: application/json");
        $db = new Database($dbh);
        
        $sql_select_attente = "SELECT * FROM commande WHERE id_etat = 1 AND id_commande = :id_commande";
        
        $command_attente = $db->SelectDb($sql_select_attente, [":id_commande" => $_GET['id_commande']]);

        // Check if $row is not empty before accessing its elements
        if (!empty($command_attente) && isset($command_attente[0]['id_etat']) && $command_attente[0]['id_etat'] == 1) 
        {
            // Utilisation d'une requête préparée pour l'UPDATE
            $sql_update_commande_refuser = "UPDATE commande SET id_etat = 2 WHERE id_commande = :id_commande";

            $commande_refuser = $db->UpdateDb($sql_update_commande_refuser, [":id_commande" => $_GET['id_commande']]);

            $sql_select_commande_refuser = "SELECT * FROM commande WHERE id_etat = 2 AND id_commande = :id_commande";
            
            $commande_refuser_end = $db->SelectDb($sql_select_commande_refuser, [":id_commande" => $_GET['id_commande']]);
                
            /*
                echo "<pre>";
                print_r($commande_refuser_end);
                echo "</pre>";
            */
            
            $commande_refuser_json = json_encode($commande_refuser_end, JSON_PRETTY_PRINT);

            echo $commande_refuser_json;
        }
    } 
    catch (PDOException $ex) 
    {
        // Capture les erreurs liées à la base de données
        die("Erreur lors de la requete SQL : " . $ex->getMessage());
    }
?>
