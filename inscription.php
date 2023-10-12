<?php
    include('db_connect.php');
    include('ini.php');
    $submit = isset($_POST['submit']);    

    if($submit) 
    {
        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        try 
        {
            if($password == $password_confirm)
            {                   
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
            else
            {
                echo "<p class ='w'>Les mots de passes ne correspondent pas</p>";
            }
        } 
        catch (PDOException $error) 
        {
            die("<p class ='w u'>Erreur inscription (SQL) : ".$error->getMessage()."</p>");
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

    <section class="space">

    </section>


    <h1 class='u w'>Inscription :</h1>

    <section class="space">

    </section>

    <form method="post">
    
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
    <p><input type='submit' name='submit' value='Envoyer' />&nbsp;<input type='reset' value='Réinitialiser' /></p>   
         
    <section class="spaceback">

    </section>

<?php
    include('footer.php');
?>
