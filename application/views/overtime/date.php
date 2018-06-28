<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Sélectionnez une période</h1>
<p class="lead">Choisissez une période pour ajouter des heures supplémentaires</p>

<form action="/overtime" method="get">
  <div class="form-group row">
    <label for="overtimeMonth" class="col-sm-2 col-form-label">Mois</label>
    <div class="col-sm-10">
      <select class="form-control" name="month" id="overtimeMonth">
        <?php foreach ($months as $k => $m): ?>
          <option value="<?php echo $k; ?>"<?php echo (date('n') == $k) ? ' selected="selected"' : ''; ?>>
            <?php echo $m; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="overtimeYear" class="col-sm-2 col-form-label">Année</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="year" id="overtimeYear" placeholder="Année..." value="<?php
                                                                                                            echo date(
                                                                                                              'Y'
                                                                                                            );
                                                                                                            ?>">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Suivant »</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
