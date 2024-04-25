<?php  
    try 
    {
        include_once "../class/database.class.php";
        include "../db_connect.php";

        $objetConnexion = db_connect();
        $db = new Database($objetConnexion);

        $sql_commande_attente = "SELECT commande.id_commande, commande.id_user, commande.id_etat, commande.date, commande.total_commande, commande.type_conso, ligne.id_ligne, ligne.id_produit, ligne.qte, ligne.total_ligne_ht, produit.libelle
FROM commande, ligne, produit
WHERE commande.id_commande = ligne.id_commande
AND ligne.id_produit = produit.id_produit
AND (commande.id_etat = :id_etat1 OR commande.id_etat = :id_etat2)";

        $commandes_attente = $db->SelectDb($sql_commande_attente, [":id_etat1"=>1, ":id_etat2"=>2]);
        /*
            En attente      -> 1
            En préparation  -> 2
            abandonnée      -> 3
            prête           -> 4
        */

        $result = array();

        foreach ($commandes_attente as $commande) {
            $id_commande = $commande['id_commande'];
            if (!isset($result[$id_commande])) {
                $result[$id_commande] = array(
                    "id_commande" => $commande['id_commande'],
                    "id_user" => $commande['id_user'],
                    "id_etat" => $commande['id_etat'],
                    "date" => $commande['date'],
                    "total_commande" => $commande['total_commande'],
                    "type_conso" => $commande['type_conso'],
                    "lignes" => array()
                );
            }
            $result[$id_commande]['lignes'][] = array(
                "id_ligne" => $commande['id_ligne'],
                "id_produit" => $commande['id_produit'],
                "qte" => $commande['qte'],
                "total_ligne_ht" => $commande['total_ligne_ht'],
                "libelle" => $commande['libelle']
            );
        }

        $commandes_attente_json = json_encode(array_values($result), JSON_PRETTY_PRINT);

        header("Content-type: application/json; charset=utf-8");

        echo $commandes_attente_json;
    } 
    catch (Exception $ex) 
    {
        echo "<p>Erreur : ".$ex->getMessage()."</p>";
    }
?>
