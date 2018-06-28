<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Sélectionnez un volume horaire</h1>
<p class="lead">Choisissez le nombre d'heures supplémentaires pour <?php
                                                                   echo $full_name;
                                                                   ?> pour le mois de <?php
                                                                                      echo $monthName;
                                                                                      ?> <?php
                                                                                         echo $year;
                                                                                         ?></p>

<form method="post">
  <div class="form-group row">
    <label for="overtimeVolume" class="col-sm-2 col-form-label">Volume horaire</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="volume" id="overtimeVolume" placeholder="Volume horaire..." min="0" value="<?php
                                                                                                                                 echo $volume;
                                                                                                                                 ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="overtimeDetails" class="col-sm-2 col-form-label">Commentaire</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="details" id="overtimeDetails" placeholder="Précisions, ..."><?php
                                                                                                                 echo $details;
                                                                                                                 ?></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Valider</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
