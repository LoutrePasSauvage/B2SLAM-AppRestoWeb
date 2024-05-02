<?php

class LigneProduit {
    private int $id_commande = 0;
    private int $id_produit = 0;
    private int $qte = 0;

    private float $prix = 0;

    function __construct( $id_commande, $id_produit, $qte, $prix) {
        $this->set_id_commande($id_commande);
        $this->set_id_produit($id_produit);
        $this->set_qte($qte);
        $this->set_prix($prix);

    }

    function get_prix() {
        return $this->prix;
    }

    // getter
    function get_id_commande() {
        return $this->id_commande;
    }

    function get_id_produit() {
        return $this->id_produit;
    }

    function get_qte() {
        return $this->qte;
    }


    function set_prix($prix) {
        $this->prix = $prix;
    }

    function set_id_commande($id_commande) {
        $this->id_commande = $id_commande;
    }

    function set_id_produit($id_produit) {
        $this->id_produit = $id_produit;
    }

    function set_qte($qte) {
        $this->qte = $qte;
    }

}

?>