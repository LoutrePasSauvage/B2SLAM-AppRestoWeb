
# AppRestoWeb

![Logo](design/logoRestoNoBg.png)

Projet de seconde année de BTS SIO en B2 SLAM Atelier Professionnel. 

Travail de groupe : 

Ludovic BOUZAC, Hector CAILLEBOTTE et Sébastien VERCHERE.

### Liens :

#### Lien Trello :

https://trello.com/b/XABrlGy9/projet-restoweb

### Projet pour l'Epreuve E5

Les deux applications serviront pour les situations professionnelles à présenter à l'épreuve E5 du BTS. Chaque candidat apportera ses propres situations même si les applications ont été développées collectivement. Les applications et la documentation seront à fournir pour l'épreuve (préparation et déroulement).


## === PRESENTATION ===

AppResto est une application de gestion de commandes dans un restaurant. Elle est découpée en deux parties :

"Restoweb" pour le front-office client "RestoSwing" pour le back-office restaurateur Le principe est de remplacer le service traditionnel (serveur, service à table, commande papier,...) par un système informatisé.

### RestoWeb

C'est une application Web utilisée par les clients du restaurant. Elle permet de voir le menu et de passer une commande. Une fois la commande prête, le client est notifié et il va la chercher au comptoir. Restoweb doit être responsive pour pouvoir être utilisée sur un smartphone. Les technologies sont : PHP, HTML, CSS, MariaDB, API REST, VSCode.

### RestoSwing

C'est un client lourd en Java/Swing qui permet au restaurateur de gérer les commandes. Celui-ci peut les afficher, les accepter, les refuser et indiquer quand elles sont prêtes. Les technologies sont : Java, Swing, API REST, Netbeans.


## === INSTALLATION DE L'APPLICATION ===
## > RestoWeb

### 1/ Installer l'intégralité du projet

Installer la dernière "release" du projet

### 2/ Emplacement de l'application pour xampp :
   
Placer le projet selon le chemin ci-dessous

```
  ./xampp/htdocs/projets/B2SLAM-AppRestoWeb
```

**NB :** *selon la version de la "release", le nom de domaine sera différent.*

**NB :** *"projets" est un dossier qui n'est pas par défaut. Il sera nécessaire de le créer.*


### 3/ Mise en place de la base de donnée 

Une fois le module **MySQL** démarré avec le panel de contrôle de Xampp. Rendez-vous à l'URL suivante :

```
  http://localhost/phpmyadmin/index.php
```

Création d'une nouvelle base de donnée nommée **db_restoweb** avec l'encodage **utf8_general_ci**.


Dans la nouvelle base de donnée :
Exécuter le code SQL contenu dans

```
    B2SLAM-AppRestoWeb/data_db/db_restoweb.sql
```


| **Installation terminée !** |

## > RestoSwing

Bientôt disponible !


## === UTILISATION DE L'APPLICATION ===
## > RestoWeb

### 1/ Accès à l'application

#### 1.1/ Connexion

Par défaut, vous pouvez vous connecter, depuis la page d'accueil, avec le compte de test avec les identifiants suivants :

| Login      | Password   |
| ---------- | ---------- |
| a          | a          |

*Ou à l'URL suivante :*

```
    http://localhost/projets/B2SLAM-AppRestoWeb/connexion.php
```

#### 1.2 / Inscription

Pour accéder au site en tant que nouveau client, Référez-vous au bouton d'inscription de la page d'accueil et suivez les instructions.

*Ou à l'URL suivante :*

```
    http://localhost/projets/B2SLAM-AppRestoWeb/inscription.php
```

#### 2/ Commander & Payer

Une fois connecté, vous serez dirigé sur l'URL suivante :

```
    http://localhost/projets/B2SLAM-AppRestoWeb/list.php
```

Sur cette page vous avez 2 listes :

- **La liste des produits**
- **La liste des commandes**

Pour choisir un produit, cliquez sur le bouton **ajouter** du produit en question.

Une fois la commande terminée :

- Choisissez le type de consommation :
  - **Sur place**
  - **À emporter**
- Confirmez la commande en cliquant sur le bouton **commander**

Une fois la commande confirmé, vous serez dirigé vers l'URL suivante :

```
    http://localhost/projets/B2SLAM-AppRestoWeb/pay.php
```

Vous saisissez les informations demandées.

Une fois les informations saisies, confirmez le paiement en cliquant sur le bouton **payer**.

**NB :** La carte de crédit ne peut pas être vérifiée par un protocole spécialisé.
Pour essayer la situation de redirection vers la page d'erreur de paiement, vous devrez saisir le numéro de carte de crédit suivant : 00000000000000000000

Une fois le paiement confirmé, vous serez dirigé vers l'URL suivante :

```
    http://localhost/projets/B2SLAM-AppRestoWeb/payConf.php
```

Cette page affiche les informations de votre commande.
Vous pouvez ensuite revenir sur la page d'accueil en cliquant sur le bouton **Revenir à la page d'accueil**.

#### 3/ Déconnexion

Vous pouvez vous déconnecter en cliquant sur le bouton **Se déconnecter** qui est dans le menu déroulant en haut à droite d'une page.


## > RestoSwing

#### 1/

Bientôt disponible !



## === DOCUMENTATION TECHNIQUE ===

### 4 états d'une commande :

| Libellé de l'état | Valeur    | API correspondante       |
| ----------------- | --------- | ------------------------ |
| En attente        | 1         | commandes_en_attente.php |
| En préparation    | 2         | commande_accepter.php    |
| Abandonnée        | 3         | commande_refuser.php     |
| Prête             | 4         | commande_terminer.php    |


--> Ces valeurs sont attribuées au champ **id_etat** dans la table **commande**.

**NB :** Pour éviter les ambiguïtés :

 - **Abandonnée** ou **refusée** sont considérés comme le **même état** donc la **même valeur** : **3**.
 - **En préparation** ou **acceptée** sont considérés comme le **même état** donc la **même valeur** : **2**.
 - **Terminéee** ou **prête** sont considérés comme le **même état** donc la **même valeur** : **4**.


### Explication Echanges entre le Client et le Serveur :

Le client envoie une URL au serveur.

Si cette URL spécifie une commande en particulier (par son ID), elle envoie la valeur que doit contenir le champ **id_commande** de la table **commande**.
À l'issu de la réception, le serveur fournit un fichier json ciblant la commande ou les commandes en question.

Par exemple, dans commandes_en_attente.php, le serveur génère un fichier json qui liste les commandes en attente.
Le fichier json : **fichier_exemple.json** est l'exemple d'un résultat de la création d'un fichier json par le programme **commande_en_attente.php**.

Pour accepter, refuser ou terminer : le serveur mettra à jour l'état de la commande pour fournir un fichier **json** à jour de ce changement d'état.



### Description fichiers json (API) :

Vous trouverez les fichiers suivants dans le dossier **API**.

#### **commandes_en_attente.php :**

L'url pour accéder au fichier :
```
    http://localhost/projets/B2SLAM-AppRestoWeb/API/commandes_en_attente.php
```

Ce programme génèrera un fichier **json** listant toutes les commandes en attentes.
C'est-à-dire l'état en "attente", donc le champ **id_etat = 1** dans la table **commande**. 

#### **commande_accepter.php :**

L'url pour accéder au fichier : 

```
    http://localhost/B2SLAM-AppRestoWeb/API/commande_accepter.php?id_commande=X
```

**X** : id de la commande en question.

Pour tester la fonctionnalité **"accepter"** du programme : Attribuez à **X** la valeur de l'id d'une commande ayant un état **"en attente"**, ici ça sera **id_etat = 1**.

Voici la commande SQL pour trouver des commandes en attentes :
```
    SELECT * FROM commande WHERE id_etat = 1;
```

Le programme va sélectionner une commande **X** qui était en préparation pour lui mettre à jour l'état **"accepter"**.
Ce programme généra un fichier **json** de la commande **X** avec le nouvel état (ici acceptée).


#### **commande_refuser.php :**

L'url pour accéder au fichier : 
```
    http://localhost/B2SLAM-AppRestoWeb/API/commande_refuser.php?id_commande=X
```

**X** : id de la commande en question.

Pour tester la fonctionnalité **"refuser"** du programme : Attribuez à **X** la valeur de l'id d'une commande ayant un état **"en attente"**, ici ça sera **id_etat = 1**.

Voici la commande SQL pour trouver des commandes en attentes :
```
    SELECT * FROM commande WHERE id_etat = 1;
```

Le programme va sélectionner une commande **X** qui était en préparation pour lui mettre à jour l'état **"refuser"**.
Ce programme généra un fichier **json** de la commande **X** avec le nouvel état (ici refusée ou abandonnée).


#### **commande_terminer.php :**

L'url pour accéder au fichier :
```
    http://localhost/projets/B2SLAM-AppRestoWeb/API/commande_terminer.php?id_commande=X
```

**X :** id de la commande en question.

Pour tester la fonctionnalité **"terminer"** du programme : Attribuez à **X** la valeur de l'id d'une commande ayant un état **"en préparation"**, ici ça sera **id_etat = 2**.

Voici la commande SQL pour trouver des commandes en préparation :
```
    SELECT * FROM commande WHERE id_etat = 2;
```
Sinon mettez à jour une commande à l'état "en préparation" (acceptée) grâce à la fonctionnalité **"accepter"** du programme **commande_accepter.php**.

Le programme va sélectionner une commande **X** qui était en préparation pour lui mettre à jour l'état en "terminée" (soit "prête").
Ce programme généra un fichier **json** de la commande **X** avec le nouvel état (ici terminée ou prête).



