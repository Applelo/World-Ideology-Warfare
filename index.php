<?php
session_start();//On démarre la session

include("class/db.class.php");//On inclue la class qui se connecte à la base de donnée
include("class/joueur.class.php");//On inclue la class joueur parent de
include("class/faction.class.php");//faction donc on l'inclue ensuite
include("class/reset.class.php");//C'est l'objet qui reset tout par défaut.

$db = new Db();//Connexion à la base de donnée
$dbCon = $db->get();//Récupère les données
$reset = new Reset($dbCon);//On crée un nouvel objet de la class de reset

if (isset($_GET["exit"])) {//Reset tout
  session_destroy();//DESTRUCTION DE LA SESSION
  $reset->all();//RESET DE LA BASE DE DONNEE
  header("location:index.php");//retour à la page principal
  exit();//On arrête l'exécution du code
}

if (!isset($_SESSION["page"]))//Si il n'y a pas de page d'initialisé, on met la page home par défaut
  $_SESSION["page"] = "home";//On met la page par défaut

if (isset($_POST["jouer"]) && $_POST["jouer"]=="true")//Si page du début renvoi le lancement du jeu
  $_SESSION["page"] = "pseudo";//on lance la page pseudo

if (isset($_POST["joueur1"]) && $_SESSION["page"] == "pseudo") {//
  $_SESSION["pseudo"] = array($_POST["joueur1"], $_POST["joueur2"], $_POST["joueur3"]);//Session avec les différents noms de joueurs
  if (!$_SESSION["censure"])//Si pas de censure
    $dictateur = array("Jean-Marie", "Hitler", "Staline", "Mélanchon", "Hillary", "Bush");//Dictateur
  else//Sinon
    $dictateur = array("Casimir", "Oui-Oui", "Bob le bricoleur", "Pikachu", "Elsa", "Chanchan Goya");//Bisounours

  for ($i=0; $i<3; $i++) {//Boucle for pour choisir les pseudos si l'utilisateur n'en n'a pas choisi.
    if (empty($_SESSION["pseudo"][$i]))
      $_SESSION["pseudo"][$i] = $dictateur[rand(0,5)];
  }
  $_SESSION["page"] = "start";//On lance la page start
}

if ($_SESSION["page"] == "start") {//Si page est = à start

  if (!isset($_SESSION['joueur']))//Si pas de session joueur
    $_SESSION['joueur'] = 0;//initialise à 0 puis sera 1 à cause de l'instruction qui suit
  $_SESSION['joueur']++;//Augmente à chaque fois qu'on lance

  if (isset($_GET["faction"]) && isset($_GET["player"])) {//Selon les deux paramètres (faction et player)
    $joueur = new Faction($dbCon, $_SESSION["censure"]);//Création du nouveau joueur
    $joueur->defaultDataFaction($_GET["faction"], $_SESSION["pseudo"][($_GET["player"] - 1)]);//Enregistrement des données de base du joueur dans la base de donéne
  }

  if ($_SESSION['joueur'] > 3) {//Si supérieur à 3 (seulement 3 joueurs dans ce jeu)
   $_SESSION['joueur'] = null;//Supprimer la donnée joueur dans le session
   $_SESSION["page"] = "game";//On passe à la page game
  }
}

if ($_SESSION["page"] == "game") {
  $coucou = new Faction($dbCon, $_SESSION["censure"]);
  $coucou->getDataFaction(1);
  $ennemis = $coucou->getEnnemis(1);
  if (count($ennemis["info"]) == 1)
    $_SESSION["page"] = "finish";
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>World Ideology Warfare</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <header>
      <h1 class="text-center">World Ideology Warfare</h1>
    </header>

    <?php
      include("pages/" . $_SESSION["page"] . ".php");
    ?>

    <footer class="navbar navbar-inverse navbar-fixed-bottom">
      <div class="container-fluid">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <?php if ($_SESSION["page"] == "home") {//Possibilité de changer la version du jeu seulement au menu principal
          if (isset($_GET["censure"]) && $_GET["censure"] == "false")
            $_SESSION["censure"] = false;
          else
            $_SESSION["censure"] = true;
        ?>
        <li>
          <?php
          if (isset($_SESSION["censure"]) && $_SESSION["censure"] == true)//Affiche le message selon la version qu'on utilise
            echo '<a href="?censure=false">Version Censuré</a>';
          else
            echo '<a href="?censure=true">Version Non-Censuré</a>';
          ?>
        </li>
        <?php }
        else {
          echo "<li><a href='?exit=true'>Quitter</a></li>";//Lance le reset et retourne au menu principal
        }
         ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
  </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
