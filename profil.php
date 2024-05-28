<?php
include_once "class/database.class.php";
include "db_connect.php";

session_start();

$objetConnexion = db_connect();
$db = new Database($objetConnexion);

//récupération des produits
$user = $_SESSION["user"];

$userId = $user['id_user'];

$submit = isset($_POST['submit']) ? $_POST['submit'] : "";

$messagesMail = array();
$messagesNumTel = array();
$messagesDate = array();
$messagesSexe = array();
$messageNumRue = array();
$messageCodePostal = array();
$messageVille = array();
$erreur = false;
$success = false;

if ($submit) {
    $email = $_POST['email'];
    $NumTel = $_POST['NumTel'];
    $DateNaissance = $_POST['DateNaissance'];
    $Sexe = $_POST['Sexe'];
    $NumRue = $_POST['NumRue'];
    $nomRue = $_POST['nomRue'];
    $codePostal = $_POST['codePostal'];
    $ville = $_POST['ville'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messagesMail[] = "Adresse mail invalide";
        $erreur = true;
    }
    if (empty($email)) {
        $messagesMail[] = "Email vide";
        $erreur = true;
    }

    if (!preg_match('/^[0-9]*$/', $NumTel)) {
        $messagesNumTel[] = "le numéro de téléphone doit être composé de chiffres";
        $erreur = true;
    }
    if (strlen($NumTel) != 10) {
        $messagesNumTel[] = "le numéro de téléphone doit être composé de 10 chiffres";
        $erreur = true;
    }

    if ($DateNaissance > date("Y-m-d")) {
        $messagesDate[] = "Date de naissance invalide";
        $erreur = true;
    }


    if ($Sexe != "0" && $Sexe != "1") {
        $messagesSexe[] = "Choisissez un sexe valide";
        $erreur = true;
    }

    if (!preg_match('/^[0-9]*$/', $NumRue)) {
        $messageNumRue[] = "le numéro doit être composé de chiffres";
        $erreur = true;
    }

    if (!preg_match('/^[0-9]*$/', $codePostal)) {
        $messageCodePostal[] = "le code postal doit être composé de chiffres";
        $erreur = true;
    }

    if (!preg_match('/^[a-zA-Z]*$/', $ville)) {
        $messageVille[] = "la ville doit être composée de lettres";
        $erreur = true;
    }

    if (!empty($NumRue) && (empty($codePostal) || empty($ville))) {
        $messageNumRue[] = "le numéro de la rue doit être rempli si le code Postal et la ville sont remplis";
        $erreur = true;
    }

    if (!empty($codePostal) && (empty($NumRue) || empty($ville))) {
        $messageCodePostal[] = "le code postal doit être rempli si le numéro de rue et la ville sont remplis";
        $erreur = true;
    }

    if (!empty($ville) && (empty($NumRue) || empty($codePostal))) {
        $messageVille[] = "la ville doit être rempli si le numéro de rue et le code postal sont remplis";
        $erreur = true;
    }


    if ($erreur == false) {

        $user = $db->SelectDb("SELECT * FROM user WHERE id_user = :id_user", [":id_user" => $userId])[0];

        $db->UpdateDb("UPDATE user SET email = :email, NumTel = :NumTel, DateNaissance = :DateNaissance, Sexe = :Sexe, NumRue = :NumRue, nomRue = :nomRue, codePostal = :codePostal, ville = :ville WHERE id_user = :id_user", [
            ":email" => $email,
            ":NumTel" => $NumTel,
            ":DateNaissance" => $DateNaissance,
            ":Sexe" => $Sexe,
            ":NumRue" => $NumRue,
            ":nomRue" => $nomRue,
            ":codePostal" => $codePostal,
            ":ville" => $ville,
            ":id_user" => $userId
        ]);

        $user = $db->SelectDb("SELECT * FROM user WHERE id_user = :id_user", [":id_user" => $userId])[0];
        $success = true;
    }
    if ($erreur == true) {
        echo "<p class='alert alert-danger'>Erreur lors de la modification du profil</p>";
    }


}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Profil</title>

    <?php
    include "header.php";
    ?>

</head>
<body>


<div class="container">
    <h1>Profil</h1>

    <?php if ($success) {
    echo "<p class='alert alert-success'>Profil modifié avec succès</p>";
    }?>
    <form method="post" action="profil.php">
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" name="login" disabled
                   value="<?php echo $user['login']; ?>">

        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
            <?php
            if (count($messagesMail) > 0) {
                echo "<p class='btn alert-warning' >";
                foreach ($messagesMail as $message) {
                    echo $message . "<br>";
                }
                echo "</p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="NumTel">Numéro de téléphone</label>
            <input type="number" class="form-control" id="NumTel" name="NumTel" value="<?php echo $user['NumTel']; ?>">
            <?php
            if (count($messagesNumTel) > 0) {
                echo "<p class='btn alert-warning' >";
                foreach ($messagesNumTel as $message) {
                    echo $message . "<br>";
                }
                echo "</p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="DateNaissance">Date de naissance</label>
            <input type="date" class="form-control" id="DateNaissance" name="DateNaissance"
                   value="<?php echo $user['DateNaissance']; ?>">
            <?php
            if (count($messagesDate) > 0) {
                echo "<p class='btn alert-warning' >";
                foreach ($messagesDate as $message) {
                    echo $message . "<br>";
                }
                echo "</p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="Sexe">Sexe</label>
            <input type="radio" class="form-control" id="Sexe" name="Sexe"
                   value="0" <?php echo $user['Sexe'] == "0" ? "checked" : ""; ?>> Garçon
            <input type="radio" class="form-control" id="Sexe" name="Sexe"
                   value="1" <?php echo $user['Sexe'] == "1" ? "checked" : ""; ?>> Fille
            <?php
            if (count($messagesSexe) > 0) {
                echo "alert alert-warning";
            }
            ?>

        </div>
        <div class="form-group">
            <label for="NumRue">Numéro de rue</label>
            <input type="number" class="form-control" id="NumRue" name="NumRue" value="<?php echo $user['NumRue']; ?>">
        </div>
        <div class="form-group">
            <label for="nomRue">Nom de rue</label>
            <input type="text" class="form-control" id="nomRue" name="nomRue" value="<?php echo $user['nomRue']; ?>">
        </div>
        <div class="form-group">
            <label for="codePostal">Code postal</label>
            <input type="number" class="form-control" id="codePostal" name="codePostal"
                   value="<?php echo $user['codePostal']; ?>">
        </div>
        <div class="form-group">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $user['ville']; ?>">
        </div>

        <p><input class="btn btn-default btn-lg active" type='submit' name='submit' value='submit'/>&nbsp;&nbsp;<input
                    class="btn btn-danger btn-lg active" type='reset' value='Réinitialiser'/></p>
    </form>
</div>


<?php
include "footer.php";
?>
</body>
</html>
