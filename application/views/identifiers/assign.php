<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Ajout d'un identifiant pour <?php echo $project->name; ?>
</h1>
<p class="lead">Renseignez les différents champs pour ajouter un nouvel identifiant pour ce projet</p>

<?php var_dump($identifiers); ?>

<form method="post">
  <div class="form-group row">
    <label for="identifierType" class="col-sm-2 col-form-label">Type d'identifiant</label>
    <div class="col-sm-10">
      <select class="form-control" name="type" id="identifierType">
        <?php foreach ($identifiers as $identifier): ?>
        <option value="<?php echo $identifier->id; ?>">
          <?php echo $identifier->name; ?>
        </option>
        <?php endforeach; ?>
        <option value="0">Autre ?</option>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="identifierValue" class="col-sm-2 col-form-label">Valeur</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="value" id="identifierValue" placeholder="Entrez ici les identifiants"></textarea>
    </div>
  </div>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
