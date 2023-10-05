<?php

class Database
{
    private PDO $dbh;
    private string $sql = "";
    private array $balises = array();


    function __construct(PDO $pDO, string $method, string $sql, array $balises)
    {
        $this->dbh = $pDO;
        $this->sql = $sql;
        $this->balises = $balises;

        switch (strtolower($method)) {
            case "select":
                $this->SelectDb();
            case "insert":
                $this->InsertDb();
            case "update":
                $this->UpdateDb();
            case "delete":
                $this->DeleteDb();
            default:
                die("Erreur: Type de Requête inexistante !");

        }
    }

    public function SelectDb()
    {
        try {
            $sth = $this->dbh->prepare($this->sql);
            $sth->execute();
            $row = $sth->fetchAll();
            return $row;
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête SELECT SQL : " . $e->getMessage() . "</p>");
        }
    }

    public function InsertDb()
    {
        try {
            $sth = $this->dbh->prepare($this->sql);
            print_r($this->balises);
            $sth->execute($this->balises);
            
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête INSERT SQL : " . $e->getMessage() . "</p>");
        }

    }
    public function UpdateDb()
    {
        try {
            $sth = $this->dbh->prepare($this->sql);
            $sth->execute($this->balises);
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête UPDATE SQL : " . $e->getMessage() . "</p>");
        }
    }

    public function DeleteDb()
    {
        try {
            $sth = $this->dbh->prepare($this->sql);
            $sth->execute();
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête UPDATE SQL : " . $e->getMessage() . "</p>");
        }
    }

}

?>