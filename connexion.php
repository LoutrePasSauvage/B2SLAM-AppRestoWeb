<?php
include "db_connect.php";
// Connexion à la base de données
$dbh = db_connect();

$messages = array();  // Message d'erreur
$messagesid = array();  // Message d'erreur Identifiant
$messagesMDP = array();  // Message d'erreur MDP

$submit = isset($_POST['submit']);

// Vérifie le user
if ($submit) {

    // Récupère le contenu du formulaire
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    //verifie si le login est vide
    if (empty($login)) {
        $messagesid[] = "Le login est obligatoire";
    }
    //verifie si le mdp est vide
    if (empty($password)) {
        $messagesMDP[] = "Le mot de passe est obligatoire";
    }

    // Vérifie si le login et le mot de passe sont valides
    if (empty($login) || empty($password)) {
        $messages[] = "Le login ou le mot de passe n'est pas valide : $login";
    }

    $sql = "SELECT * FROM user WHERE login=:login";
    try {
        $params = array(':login' => $login);
        $sth = $dbh->prepare($sql);
        $sth->execute($params);
        $user = $sth->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifie le mot de passe hashé
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                header("Location: list.php");
                exit();
            } else {
                $messages[] = "Login ou mot de passe incorrect";
            }
        } else {
            $messages[] = "Login ou mot de passe incorrect";
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
</head>
<body>
<h1 class='w'>Connexion :</h1>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <?php
    if (count($messages) > 0) {
        foreach ($messages as $message) {
            echo "<h6 class='alert alert-danger'>" . htmlspecialchars($message) . "</h6>";
        }
    }
    ?>
    <p>Login<br />
        <?php
        if (count($messagesid) > 0) {
            foreach ($messagesid as $message) {
                echo "<p class='btn alert-warning'>" . htmlspecialchars($message) . "</p> <br>";
            }
        }
        ?><input type="text" name="login" id="login"></p>
    <p>Mot de passe<br />
        <?php
        if (count($messagesMDP) > 0) {
            foreach ($messagesMDP as $message) {
                echo "<p class='btn alert-warning'>" . htmlspecialchars($message) . "</p> <br>";
            }
        }
        ?><input type="password" name="password" id="password"></p>
    <button class="btn btn-default btn-lg active" type="submit" name="submit" value="submit">Connexion</button>
</form>

<?php
include "footer.php";
?>
</body>
</html>
