    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>


<body style="background-color: #141f25;">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img width='30%' height='30%' src="img/logoRestoNoBg.png">
            </a>
    
            <button class="navbar-toggler" style="margin-right:5px !important;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon">

                </span>
            </button>

            <div class="collapse navbar-collapse top_nav" id="navbarSupportedContent">
                <ul class="navbar-nav" style="padding-left: 90%;">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Accueil</a>    
                    </li>
                    <li class="nav-item ">
                        <a href="connexion.php" class="nav-link">Se connecter</a>
                    </li>

                    <?php  

                    if(isset($_SESSION['user']))
                    echo '<li class="nav-item ">
                        <a href="list.php" class="nav-link">Liste des produits</a>
                    </li>';

                    ?>
                    <li class="nav-item ">
                        <a href="deconnexion.php" class="nav-link">Se déconnecter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> 