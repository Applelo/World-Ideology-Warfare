<?php
if (!$_SESSION["censure"])
  $factions = array("NAZI","URSS","USA");//Version non censurée
else
  $factions = array("LAA-LAA","DIPSY","PO");//Version censurée

 ?>

<section class="container">
<div class="row">
  <div class="col-md-12">
    <h2 class="text-center">Joueur <?php echo $_SESSION['joueur']; ?> alias <?php echo $_SESSION["pseudo"][$_SESSION['joueur'] - 1]; ?>, choisissez votre faction !</h2>
  </div>
  <?php
  for ($i=0; $i<3; $i++) {
  ?>
  <div class="col-md-4">
    <a href="?player=<?php echo $_SESSION['joueur']; ?>&faction=<?php echo $i; ?>" class="thumbnail">
      <img src="images/factions/<?php echo strtolower($factions[$i]); ?>.png" alt="<?php echo $factions[$i] ?>">
    </a>
    <p class="text-center"><strong><?php echo $factions[$i] ?></strong></p>
  </div>
  <?php
  }
 ?>
</div>
</section>
