<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Nouvelle demande de remboursement de frais de transport</h1>
<p class="lead">Demandez un remboursement de frais de transport</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="transportsYear" class="col-sm-2 col-form-label">Année</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="year" id="transportsYear" value="<?php echo date('Y'); ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="transportsMonth" class="col-sm-2 col-form-label">Mois</label>
    <div class="col-sm-10">
      <select class="form-control" name="month" id="transportsMonth">
        <?php foreach ($months as $k => $month): ?>
        <option value="<?php echo $k; ?>"<?php echo ($k == date('n')) ? ' selected="selected"' : ''; ?>>
          <?php echo $month; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="transportsMonth" class="col-sm-2 col-form-label">Montant</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input type="number" class="form-control" required pattern="[0-9]+([\.,][0-9]+)?" step="0.01" min="0" value="0" name="amount">
        <div class="input-group-append">
          <span class="input-group-text">€</span>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="transportsMonth" class="col-sm-2 col-form-label">Justificatif</label>
    <div class="col-sm-10">
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" name="file" id="transportsFile">
          <label class="custom-file-label" for="transportsFile">Choisir un fichier...</label>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="transportsDetails" class="col-sm-2 col-form-label">Commentaire</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="details" id="transportsDetails" placeholder="Commentaire..."></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
