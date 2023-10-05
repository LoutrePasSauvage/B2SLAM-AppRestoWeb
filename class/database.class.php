<?php

class Database
{
    private PDO $dbh;
    private string $sql = "";
    private array $balises = array();
    private int $method = 0;

    function __construct(PDO $pDO, int $method, string $sql, array $balises)
    {
        $this->dbh = $pDO;
        $this->sql = $sql;
        $this->method = $method;
        $this->balises = $balises;

        if ($method == 1)
            $this->SelectDb();
        if ($method == 2)
            $this->InsertDb();
        if ($method == 3)
            $this->UpdateDb();
        if ($method == 4)
            $this->DeleteDb();
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
            die("<p>Erreur lors de la requête DELETE SQL : " . $e->getMessage() . "</p>");
        }
    }

}

?>