<?php
include "db_connect.php";
// Connexion à la base de données
$dbh = db_connect();

// Récupère le contenu du formulaire
$login = isset($_POST['login']) ? $_POST['login'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$submit = isset($_POST['submit']);

// Vérifie le user
if ($submit) {
    $sql = "select * from _user where login= :login and password= :password";
    try {
        $params = array(
            ':login' => $login,
            ':password' => $password
        );
        $sth = $dbh->prepare($sql);
        $sth->execute($params);
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header("Location: list.php");
            exit();
        } else {
            $message = "Login ou mot de passe incorrect";
        }
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>connexion</title>

    <?php
    include "header.php";
    ?>



<h1>Connexion :</h1>

<section class="space">

</section>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>login<br /><input type="text" name="login" id="login" value="<?= $login ?>" required></p>
    <p>Mot De Passe<br /><input type="password" name="password" id="password" required></p>
    <button class="btn btn-default btn-lg active" type="submit" name="submit" value="submit">Connexion</button>
</form>

<?php

include "footer.php";
?>