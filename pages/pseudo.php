<section class="container">
<div class="row">
  <div class="col-md-12">
    <h2 class="text-center">Choisissez vos pseudos</h2>
    <p class="text-center">Tu peux laisser vide, comme ça tu auras un pseudo rigolo lol</p>
  </div>
  <div class="col-md-6 col-md-offset-3">
    <form method="post">
      <?php for($i = 1;$i <= 3; $i++) { // Affiche les champs grâce à une boucle for ?>
      <div class="form-group">
        <label for="joueur<?php echo $i; ?>">Joueur <?php echo $i; ?></label>
        <input type="email" class="form-control" name="joueur<?php echo $i; ?>" placeholder="Pseudo">
      </div>
      <?php } ?>
      <button type="submit" class="btn btn-default">Confirmer</button>
    </form>
  </div>
</div>
</section>
