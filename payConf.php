<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
        <li class="menu-item a-menu"><a href="deconnexion.php">Déconnexion</a></li>
    </ul>
</nav>

<h1>Payement</h1>

<!-- formulaire carte bleu -->

<div class="card" style="width: 18rem;">
    <img class="card-img-top" src="img/logoResto.png" alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title">Commande n°8451</h5>
        <p class="card-text">Total payer : 8.5€</p>
    </div>
    <form method="post">
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><p>Merci Pour Votre Commande</p></li>
    </ul>

        <p><button type="submit" class="btn btn-primary" href=".php" >Confirmer</button></p>

</div>

<footer>
    &copy Restaurant de qualité
</footer>
</body>
</html>

<!--Js for boot strap-->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
