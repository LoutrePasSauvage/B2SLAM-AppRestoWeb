<?php

class Database
{
    private PDO $dbh;

    function __construct(PDO $pDO)
    {
        $this->dbh = $pDO;
    }

    public function SelectDb(string $sql, ?array $champs)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($champs);
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC); // Utilisation de PDO::FETCH_ASSOC
            return $rows;
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête SELECT SQL : " . $e->getMessage() . "</p>");
        }
    }

    public function InsertDb(string $sql, array $champs)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($champs);
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête INSERT SQL : " . $e->getMessage() . "</p>");
        }

    }
    public function UpdateDb(string $sql, ?array $champs)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($champs);
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête UPDATE SQL : " . $e->getMessage() . "</p>");
        }
    }

    public function DeleteDb(string $sql, ?array $champs)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($champs);
            $nb = $sth->rowcount();
        } catch (PDOException $e) {
            die("<p>Erreur lors de la requête DELETE SQL : " . $e->getMessage() . "</p>");
        }
    }

}

?>
