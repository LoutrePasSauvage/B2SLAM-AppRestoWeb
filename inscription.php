<?php
    include('db_connect.php');
    include('ini.php');
    $submit = isset($_POST['submit']);

    $messages = array();  // Message d'erreur

    if($submit) {
        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        // Filtrage
        $login = filter_var($login, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // le nom est obligatoire
        if (empty(trim($login))) {
            $messages[] = "le login est obligatoire";
        }
        // l'email est obligatoire
        if (empty(trim($email))) {
            $messages[] = "l'email est obligatoire";
        }
        // l'email est valide
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $messages[] = "l'email n'est pas valide : $email";
        }
        // le mdp est obligatoire
        if (empty(trim($password))) {
            $messages[] = "le mot de passe est obligatoire";
        }
        if (empty(trim($password_confirm))) {
            $messages[] = "le mot de passe est obligatoire";
        }
        // le mdp est doit avoir plus de 8 caractères
        if (strlen($password) < 8) {
            $messages[] = "le mot de passe doit avoir plus de 8 caractères";
        }
        // le mdp doit avoir un caractère spécial
        if (!preg_match('/[^a-z0-9]+/i', $password)) {
            $messages[] = "le mot de passe doit avoir un caractère spécial";
        }
        // le mdp doit avoir un chiffre
        if (!preg_match('/[0-9]+/', $password)) {
            $messages[] = "le mot de passe doit avoir un chiffre";
        }
        // le mdp doit avoir une lettre
        if (!preg_match('/[a-z]+/i', $password)) {
            $messages[] = "le mot de passe doit avoir une lettre";
        }
    }
// Pas de message : inscrit !
        if (empty($messages)) {
            try {

                if ($password == $password_confirm) {
                    //$new_user = "INSERT INTO _user(login, password, email) VALUES (:login, :password, :email)";
                    $objetConnexion = db_connect();
                    $db = new Database($objetConnexion);

                    $db->InsertDb("INSERT INTO user(login, password, email) VALUES (:login, :password, :email)", [
                        ":login" => $login,
                        ":password" => $password,
                        ":email" => $email
                    ]);
                    header("Location: connexion.php");
                    //2 --> insert

                    /*
                    $db=db_connect();
                    $req = $db->prepare($new_user, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                    $req->execute([
                        ":login" => $login,
                        ":password" => $password,
                        ":email" => $email
                    ]);
                    $req->fetchAll();
                    */
                }
            } catch (Exception $error) {
                die("<p class ='w u'>Erreur inscription (SQL) : " . $error->getMessage() . "</p>");
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
        <?php
        if (count($messages) > 0) {

            foreach ($messages as $message) {
                echo "<h6 class='alert alert-danger' >" . $message . "</h6>";
            }
        }
        ?>
    
    <p class='w'> Identifiant :</p>
    <input type='text' name='login' id='login'>
    <br>
    <p class='w'> Mot de passe :</p>
    <input type='password' name='password' id='password'>
    <br>
    <p class='w'> Confirmer mot de passe :</p>
    <input type='password' name='password_confirm' id='password_confirm'>
    <br>
    <p class='w'> e-mail :</p>
    <input type='email' name='email' id='email'>
    <br><br>
    <p><input class="btn btn-default btn-lg active" type='submit' name='submit' value='Envoyer' />&nbsp;&nbsp;<input class="btn btn-danger btn-lg active" type='reset' value='Réinitialiser' /></p>


<?php
    include('footer.php');
?>
