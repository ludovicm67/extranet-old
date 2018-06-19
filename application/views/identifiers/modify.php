<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Modification d'un identifiant pour <?php echo $project->name; ?>
</h1>
<p class="lead">Renseignez les différents champs pour modifier l'identifiant pour ce projet</p>

<form method="post">
  <div class="form-group row">
    <label for="identifierType" class="col-sm-2 col-form-label">Type d'identifiant</label>
    <div class="col-sm-10">
      <select data-tags="true" class="form-control" name="type" id="identifierType">
        <option value="0">Autre ?</option>
        <?php foreach ($identifiers as $identifier): ?>
        <option value="<?php echo $identifier->id; ?>"<?php echo ($identifier->id === $values->identifier_id) ? ' selected="selected"' : ''; ?>>
          <?php echo $identifier->name; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="identifierValue" class="col-sm-2 col-form-label">Valeur</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="value" id="identifierValue" placeholder="Entrez ici les identifiants"><?php echo $values->value; ?></textarea>
    </div>
  </div>

  <div class="form-group row">
    <span class="col-sm-2 col-form-label">Confidentialité</span>
    <div class="col-sm-10">
      <label for="identifierConfidential" value="1">
        <input type="checkbox" name="confidential" id="identifierConfidential"<?php echo ($values->confidential == 1) ? ' checked="checked"' : ''; ?>>
        Marquer cet identifiant comme confidentiel ?
      </label>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
