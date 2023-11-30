<?php

include_once "../db_connect.php";
include_once "../class/database.class.php";

try {
    $json = "";
    $dbh = db_connect();

    // Pour Firefox et le formatage
    header("Content-Type: application/json");

    $sql = "SELECT * FROM commande WHERE id_etat = 1 AND id_commande = :id_commande";
    $db = new Database($dbh);
    $row = $db->SelectDb($sql, [":id_commande" => $_GET['id_commande']]);

    // Check if $row is not empty before accessing its elements
    if (!empty($row) && isset($row[0]['id_etat']) && $row[0]['id_etat'] == 1) {
        // Utilisation d'une requête préparée pour l'UPDATE
        $commandeUpdate = $dbh->prepare("UPDATE commande SET id_etat = :id_Etat WHERE id_commande = :id_Commande");

        // Utilisation d'une vérification d'exécution de la requête UPDATE
        if ($commandeUpdate->execute([
            ":id_Etat" => 4,
            ":id_Commande" => $_GET['id_commande']
        ])) {
            $json = json_encode("Commande acceptée", JSON_PRETTY_PRINT);
        } else {
            // Fournit un message d'erreur en cas d'échec de la requête UPDATE
            $json = json_encode(["error" => "Erreur lors de la mise à jour de la commande."], JSON_PRETTY_PRINT);
        }
    } else {
        // Si l'état n'est pas 1 ou $row est vide, retourne un message d'erreur
        $json = json_encode(["error" => "La commande n'est pas dans l'etat approprie pour etre mise a jour."], JSON_PRETTY_PRINT);
    }

    echo $json;
} catch (PDOException $ex) {
    // Capture les erreurs liées à la base de données
    die("Erreur lors de la requête SQL : " . $ex->getMessage());
}
/*
            En attente      -> 1
            En préparation  -> 2
            abandonnée      -> 3
            prête           -> 4
        */
