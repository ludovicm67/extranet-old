<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Créer un nouveau projet</h1>
<p class="lead">Entrez ici les informations concernant le projet</p>

<form method="post">
  <div class="form-group row">
    <label for="projectName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="projectName" placeholder="Nom du projet...">
    </div>
  </div>
  <div class="form-group row">
    <label for="projectClient" class="col-sm-2 col-form-label">Client principal</label>
    <div class="col-sm-10">
      <select class="form-control" name="client" id="projectClient">
        <option value="0">Aucun client</option>
        <?php foreach ($clients as $client): ?>
        <option value="<?php echo $client->id; ?>"><?php echo $client->fullName; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
