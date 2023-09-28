<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inscription</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <nav>
        <ul class="menu">
            <li class="menu-item">
                <a href="index.php">
                    <img class="logoResto" src="img/logoResto.png" width=20%/>
                </a>
            </li>
            <li class="menu-item a-menu"><a href="index.php">Accueil</a></li>
            <li class="menu-item a-menu"><a href="connexion.php">Connexion</a></li>
        </ul>
    </nav>

    <section class="space">

    </section>


    <h1>Inscription :</h1>

    <section class="space">

    </section>

    <form method="post">
    
    <p> Nom :</p>
    <input type='text' name='nom'>
    <br>
    <p> Prénom :</p>
    <input type='text' name='prenom'>
    <br>
    <p> Identifiant :</p>
    <input type='text' name='id'>
    <br>
    <p> Mot de passe :</p>
    <input type='password' name='password'>
    <br>
    <p> Confirmer mot de passe :</p>
    <input type='password' name='password_confirm'>
    <br>
    <p> e-mail :</p>
    <input type='email' name='email'>
    <br>
    <p> Téléphone :</p>
    <input type='tel' name='phone'>
    <br>
    <p><input type='submit' name='submit' value='Envoyer' />&nbsp;<input type='reset' value='Réinitialiser' /></p>   
         
        
    <section class="spaceback">

    </section>

    <footer>
        &copy Restaurant de qualité
    </footer>
    


</body>
</html>

<!--Js for boot strap-->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
