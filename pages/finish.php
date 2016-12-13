<?php
$joueurs = array(new Faction($dbCon, $_SESSION["censure"]), new Faction($dbCon, $_SESSION["censure"]), new Faction($dbCon, $_SESSION["censure"]));

for ($i=0; $i<3; $i++)
  $joueurs[$i]->getDataFaction($i+1);

?>
<section class="container">
  <div class="row">
    <?php for($i=0; $i<3; $i++) { ?>
    <div class="col-md-4">
      <h2 class="text-center"><?php echo $joueurs[$i]->getNamePlayer(); ?> - Joueur <?php echo $i+1; ?></h2>
      <p class="text-center">Population : <?php echo $joueurs[$i]->getPopulation(); ?></p>
      <p class="text-center">Production : <?php echo $joueurs[$i]->getProduction(); ?></p>
      <p class="text-center">Puissance de feu : <?php echo $joueurs[$i]->getPuissance(); ?></p>
    </div>
    <?php } ?>
    <div class="col-md-12">
      <p class="text-center"><a href="?exit=true" class="btn btn-default">Quitter</a></p>
    </div>
  </div>
</section>
