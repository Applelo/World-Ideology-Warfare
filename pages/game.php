<?php
if (!$_SESSION['joueur']) {
  $_SESSION['joueur'] = 1;
  $_SESSION['tour'] = 1;
}
if (isset($_POST["choix"])) {
  //Do action
  $attack = new Faction($dbCon, $_SESSION["censure"]);
  $attack->getDataFaction($_SESSION['joueur']);


  if (isset($_POST["ennemi"])) {//ATTAQUE
    $ennemi = new Faction($dbCon, $_SESSION["censure"]);
    $ennemi->getDataFaction($_POST["ennemi"]);

    $avant = $attack->attackEnnemi($ennemi, $_POST["choix"]);





  }
  else {//OU ON PATIENTE
    $avant = $attack->updateDataFaction($_POST["choix"]);
  }

  $_SESSION['joueur']++;

  if ($_SESSION['joueur'] > 3) {
    $_SESSION['tour']++;
    $_SESSION['joueur'] = 1;
  }
}
$joueur = new Faction($dbCon, $_SESSION["censure"]);
$joueur->getDataFaction($_SESSION['joueur']);
$ennemis = $joueur->getEnnemis($_SESSION['joueur']);

if ($joueur->getPopulation() != 0) {
?>

<section class="container">
<div class="row">

  <?php
if (isset($_POST["choix"])) {
  if (isset($_POST["ennemi"])) { ?>
  <div class="col-md-2">
    <div class="thumbnail text-center">
      <img src="images/factions/<?php echo strtolower($attack->getFactionName()); ?>.png" alt="<?php echo $attack->getFactionName(); ?>">
    </div>
  </div>
  <div class="col-md-3 text-center">
    <p><strong><?php echo $attack->getNamePlayer(); ?></strong></p>
    <p>Population : <?php echo $avant["attack"][0]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getPopulation(); ?></p>
    <p>Production : <?php echo $avant["attack"][1]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getProduction(); ?></p>
    <p>Puissance de feu : <?php echo $avant["attack"][2]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getPuissance(); ?></p>
  </div>
  <div class="col-md-2 text-center">
    <h2 class="glyphicon glyphicon-arrow-right"></h2>
  </div>
  <div class="col-md-2">
    <div class="thumbnail text-center">
      <img src="images/factions/<?php echo strtolower($ennemi->getFactionName()); ?>.png" alt="<?php echo $ennemi->getFactionName(); ?>">
    </div>
  </div>
  <div class="col-md-3 text-center">
    <p><strong><?php echo $ennemi->getNamePlayer(); ?></strong></p>
    <p>Population : <?php echo $avant["ennemi"][0]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $ennemi->getPopulation(); ?></p>
    <p>Production : <?php echo $avant["ennemi"][1]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $ennemi->getProduction(); ?></p>
    <p>Puissance de feu : <?php echo $avant["ennemi"][2]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $ennemi->getPuissance(); ?></p>
  </div>
  <?php }
  else {
  ?>

  <div class="col-md-2 col-md-offset-4">
    <div class="thumbnail text-center">
      <img src="images/factions/<?php echo strtolower($attack->getFactionName()); ?>.png" alt="<?php echo $attack->getFactionName(); ?>">
    </div>
  </div>
  <div class="col-md-4">
    <p><strong><?php echo $attack->getNamePlayer(); ?></strong></p>
    <p>Population : <?php echo $avant["attack"][0]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getPopulation(); ?></p>
    <p>Production : <?php echo $avant["attack"][1]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getProduction(); ?></p>
    <p>Puissance de feu : <?php echo $avant["attack"][2]; ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $attack->getPuissance(); ?></p>
  </div>

<?php }
} ?>
  <div class="col-md-12">
    <h2 class="text-center">Tour <?php echo $_SESSION['tour']; ?>  - <?php echo $joueur->getNamePlayer(); ?> - Joueur <?php echo $_SESSION["joueur"]; ?></h2>
  </div>
  <div class="col-md-offset-4 col-md-4">
    <div class="thumbnail">
      <img src="images/factions/<?php echo strtolower($joueur->getFactionName()); ?>.png" alt="<?php echo $joueur->getFactionName(); ?>">
    </div>
    <p class="text-center"><strong><?php echo $joueur->getFactionName(); ?></strong></p>
  </div>


  <div class="col-md-2 col-md-offset-3">
    <form method="post">
      <div class="form-group">
      <select name="choix" class="form-control">
        <option value="0" class="text-center">Population</option>
        <option value="1" class="text-center">Production</option>
        <option value="2" class="text-center">Puissance de feu</option>
      </select>
    </div>
    <div class="form-group">
      <select name="ennemi" class="form-control">
        <?php for($i=0;$i<count($ennemis["info"]);$i++) { ?>
        <option value="<?php echo $ennemis['number'][$i]; ?>" class="text-center"><?php echo $ennemis['info'][$i]; ?></option>
        <?php } ?>
      </select>
    </div>
      <div class="text-center">
        <button type="submit" class="btn btn-default">Attaquer</button>
      </div>
    </form>
  </div>

  <div class="col-md-2 col-md-offset- text-center">
    <p>Population : <?php echo $joueur->getPopulation(); ?></p>
    <p>Production : <?php echo $joueur->getProduction(); ?></p>
    <p>Puissance de feu : <?php echo $joueur->getPuissance(); ?></p>
  </div>

  <div class="col-md-2">
    <form method="post">
      <div class="form-group">
      <select name="choix" class="form-control">
        <option value="0" class="text-center">Population</option>
        <option value="1" class="text-center">Production</option>
        <option value="2" class="text-center">Puissance de feu</option>
      </select>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-default">Augmenter et Patienter</button>
      </div>
    </form>
  </div>

</div>
</section>
<?php }
else {
  echo '<meta http-equiv="refresh" content="4; URL=#">';
  ?>
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="text-center">Tour <?php echo $_SESSION['tour']; ?>  - <?php echo $joueur->getNamePlayer(); ?> - Joueur <?php echo $_SESSION["joueur"] ?></h2>
    </div>
    <div class="col-md-offset-4 col-md-4">
      <div class="thumbnail">
        <img src="images/factions/<?php echo strtolower($joueur->getFactionName()); ?>.png" alt="<?php echo $joueur->getFactionName(); ?>">
      </div>
      <p class="text-center"><strong><?php echo $joueur->getFactionName(); ?></strong></p>
    </div>
    <div class="col-md-12">
      <p class="text-center">C'est triste, tu as perdu, passe ton tour.</p>
    </div>
  </div>
</section>
<?php
$_SESSION['joueur']++;

if ($_SESSION['joueur'] > 3) {
  $_SESSION['tour']++;
  $_SESSION['joueur'] = 1;
}

} ?>
