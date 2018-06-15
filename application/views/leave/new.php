<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Nouvelle demande de congés</h1>
<p class="lead">Demandez une nouvelle période de congés</p>

<form method="post">
  <div class="form-group row">
    <label for="leaveStart" class="col-sm-2 col-form-label">Début</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="start" id="leaveStart" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveEnd" class="col-sm-2 col-form-label">Fin</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="end" id="leaveEnd" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveDetails" class="col-sm-2 col-form-label">Commentaire</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="details" id="leaveDetails" placeholder="Commentaire..."></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
