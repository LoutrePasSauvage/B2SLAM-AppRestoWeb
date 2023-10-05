<?php
include_once "class/database.class.php";
include "db_connect.php";
$pizza = "pizza!";
$objetConnexion = db_connect();
$insert = new Database($objetConnexion, "insert", "INSERT INTO commande VALUES (idProduit=:idProduit, libelle=:libelle,prixHT=:prixHT,descProduit=:descProduit);", 
[':idProduit' => 1, ':libelle' => $pizza, ':prixHT' => 6.8, ':descProduit' => $pizza]);

?>

<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Liste des produits</title>
</head>

<body style=" height: 100vh;
width: auto;
background-color: #141f25; align-items: center; justify-content: center;">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img width='30%' height='30%' src="logoResto.png"></a>

            <button class="navbar-toggler" style="margin-right:5px !important;" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse top_nav" id="navbarSupportedContent">
                <ul class="navbar-nav" style="padding-left: 90%;">
                    <li class="nav-item"><a href="#" class="nav-link">Acceuil</a>    
                    </li>
                    <li class="nav-item "><a href="#" class="nav-link">Liste des produits</a>
                    </li>
                    <li class="nav-item "><a href="#" class="nav-link">Se connecter</a>
                    </li>
                    <li class="nav-item "><a href="#" class="nav-link">Se déconnecter</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <div class="container">

        <div class="row">

            <div class="col align-self-start">
                <div class="text-white">
                    <h1>Liste des produits</h1>
                </div>
                <div class="card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="logoResto.png" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural
                                    lead-in to
                                    additional content. This content is a little bit longer.</p>
                                <button type="button" class="btn btn-success">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="logoResto.png" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural
                                    lead-in to
                                    additional content. This content is a little bit longer.</p>
                                <button type="button" class="btn btn-success">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3" style="max-width: 640px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="logoResto.png" class="card-img" alt="pizza">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="width: 350px;">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural
                                    lead-in to
                                    additional content. This content is a little bit longer.</p>
                                <button type="button" class="btn btn-success">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col align-self-start">
                <div class="text-white">
                    <h1> Liste des comandes</h1>
                </div>


                <div class="box">
                    <div class="col align-self-start">
                        <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body" style="width: 350px;">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body" style="width: 350px;">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2" style="max-width: 640px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="logoResto.png" class="card-img" alt="pizza">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body" style="width: 350px;">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success">Commander</button>
                <button type="button" class="btn btn-warning">Annuler</button>
                <div class="text-white">
                <h1>Prix Total HT : 8.8 $</h1>
                <h1>Prix Total TVA : 9.8 $</h1>
                </div>
            </div>
        </div>
    </div>
    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>