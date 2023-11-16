<?php

include_once "../db_connect.php";
include_once "../class/database.class.php";

try {
    $json = "";
    $dbh = db_connect();
    
    // Pour Firefox et le formatage
    header("Content-Type: application/json");

    $sql = "SELECT * FROM commande WHERE id_etat = 2 AND id_commande = :id_commande";
    $db = new Database($dbh);
    $row = $db->SelectDb($sql, [":id_commande" => $_GET['id_commande']]);

    $commandeUpdate = $dbh->prepare(
        "UPDATE commande SET id_etat = :id_Etat WHERE id_commande = :id_Commande"
    );

    $commandeUpdate->execute([
        ":id_Etat" => 4,
        ":id_Commande" => $_GET['id_commande']
    ]);

    $json = json_encode($row, JSON_PRETTY_PRINT);
    echo $json;
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL : " . $ex->getMessage());
}
?>
