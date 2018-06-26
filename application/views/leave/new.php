<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Nouvelle demande de congés</h1>
<p class="lead">Demandez une nouvelle période de congés</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="leaveStart" class="col-sm-2 col-form-label">Début</label>
    <div class="col-sm-7">
      <input type="date" class="form-control" name="start" id="leaveStart" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div class="col-sm-3">
      <select name="start_time" id="leaveStartTime">
        <option value="09">9h</option>
        <option value="14">14h</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveEnd" class="col-sm-2 col-form-label">Date de fin</label>
    <div class="col-sm-7">
      <input type="date" class="form-control" name="end" id="leaveEnd" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div class="col-sm-3">
      <select name="end_time" id="leaveEndTime">
        <option value="18">18h</option>
        <option value="12">12h</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveDays" class="col-sm-2 col-form-label">Nombre de jours</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input id="leaveDays" type="number" class="form-control" required pattern="[0-9]+([\.,][0-9]+)?" step="0.5" min="0" value="1" name="days">
        <div class="input-group-append">
          <span class="input-group-text">jours</span>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveReason" class="col-sm-2 col-form-label">Motif du congé</label>
    <div class="col-sm-10">
      <select name="reason" id="leaveReason">
        <option value="leave">Congé</option>
        <option value="disease">Maladie (pensez à joindre un justificatif)</option>
        <option value="other">Autre (précisez en commentaire)</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveFile" class="col-sm-2 col-form-label">Justificatif (si maladie)</label>
    <div class="col-sm-10">
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" name="file" id="leaveFile">
          <label id="leaveFileLabel" class="custom-file-label" for="leaveFile">Choisir un fichier...</label>
        </div>
      </div>
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
