<?php
    include('db_connect.php');
    include('ini.php');
    $submit = isset($_POST['submit']);

    $messageslogin = array();  // Message d'erreur login
    $messagesMail = array();  // Message d'erreur e-mail
    $messagesMDP = array();  // Message d'erreur MDP


    if($submit) {
        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        // Filtrage
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // le nom est obligatoire
        if (empty(trim($login))) 
        {
            $messageslogin[] = "le login est obligatoire";
        }
        // l'email est obligatoire
        if (empty(trim($email))) 
        {
            $messagesMail[] = "l'email est obligatoire";
        }
        // l'email est valide
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
        {
            $messagesMail[] = "l'email n'est pas valide : $email";
        }
        // le mdp est obligatoire
        if (empty(trim($password))) 
        {
            $messagesMDP[] = "le mot de passe est obligatoire";
        }
        if (empty(trim($password_confirm))) 
        {
            $messagesMDP[] = "le mot de passe est obligatoire";
        }
        // le mdp est doit avoir plus de 12 caractères
        if (strlen($password) < 12) {
            $messagesMDP[] = "le mot de passe doit avoir plus de 12 caractères";
        }
        // Le mot de passe doit avoir un caractère spécial
        if (!preg_match('/\W/', $password)) 
        {
            $messagesMDP[] = "Le mot de passe doit avoir un caractère spécial.";
        }
        // le mdp doit avoir un chiffre
        if (!preg_match('/[0-9]/', $password)) 
        {
            $messagesMDP[] = "le mot de passe doit avoir un chiffre";
        }
        // le mdp doit avoir une minuscule
        if (!preg_match('/[a-z]/', $password)) 
        {
            $messagesMDP[] = "le mot de passe doit avoir une minuscule";
        }

        // le mdp doit avoir une majuscule
        if (!preg_match('/[A-Z]/', $password)) 
        {
            $messagesMDP[] = "le mot de passe doit avoir une minuscule";
        }

        if ($password == $password_confirm) 
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } 
        else 
        {
            $messagesMDP[] = "les mots de passe ne sont pas identiques";
        }

// Pas de message : inscrit !
        if (empty($messageslogin) && empty($messagesMail) && empty($messagesMDP)) {
            try {
                    //$new_user = "INSERT INTO _user(login, password, email) VALUES (:login, :password, :email)";
                    $objetConnexion = db_connect();
                    $db = new Database($objetConnexion);

                    $db->InsertDb("INSERT INTO user(login, password, email) VALUES (:login, :password, :email)", [
                        ":login" => $login,
                        ":password" => $password,
                        ":email" => $email
                    ]);
                    header("Location: connexion.php");
            } catch (Exception $error) {
                die("<p class ='w u'>Erreur inscription (SQL) : " . $error->getMessage() . "</p>");
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>inscription</title>

<?php
    include('header.php');

?>


    <h1 class='u w'>Inscription :</h1>


    <form method="post">
    <p class='w'> Identifiant :</p>
    <input type='text' name='login' id='login'><br>
        <?php
        if (count($messageslogin) > 0) {
            echo "<p class='btn alert-warning' >";
            foreach ($messageslogin as $message) {
                echo $message . "<br>";
            }
            echo "</p>";
        }
        ?>

    <p class='w'> Mot de passe :</p>
    <input type='password' name='password' id='password'>
    <br>
        <?php
        if (count($messagesMDP) > 0) {
            echo "<p class='btn alert-warning' >";
            foreach ($messagesMDP as $message) {
                echo $message . "<br>";
            }
            echo "</p>";
        }
        ?>
    <p class='w'> Confirmer mot de passe :</p>
    <input type='password' name='password_confirm' id='password_confirm'>
    <br>
    <p class='w'> e-mail :</p>
    <input type='email' name='email' id='email'>
        <br>
        <?php
        if (count($messagesMail) > 0) {
            echo "<p class='btn alert-warning' >";
            foreach ($messagesMail as $message) {
                echo $message . "<br>";
            }
            echo "</p>";
        }
        ?>
        <br>
        <p><input class="btn btn-default btn-lg active" type='submit' name='submit' value='Envoyer' />&nbsp;&nbsp;<input class="btn btn-danger btn-lg active" type='reset' value='Réinitialiser' /></p>


<?php
    include('footer.php');
?>
