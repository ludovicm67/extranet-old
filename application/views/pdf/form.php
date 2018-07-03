<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
$i = 0;
 ?>

<h1 class="mt-5">Modifications d'un pdf</h1>
<p class="lead">Éditez un pdf pour effectuer quelques ajustement</p>

<form method="post">
  <div class="form-group row">
    <label for="pdfName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="pdfName" value="<?php echo $name; ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="pdfPeriod" class="col-sm-2 col-form-label">Période</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="period" id="pdfPeriod" value="<?php echo $period; ?>" required>
    </div>
  </div>

  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Nom</th>
        <th scope="col">Contrat</th>
        <th scope="col">Heures sup (h)</th>
        <th scope="col">Congés (j)</th>
        <th scope="col">Maladie (j)</th>
        <th scope="col">Autres abs. (j)</th>
        <th scope="col">Transports (€)</th>
        <th scope="col">Dépenses (€)</th>
        <th scope="col">Observations</th>
      </tr>
    </thead>
    <tbody>

      <?php foreach ($lines as $line): ?>
      <tr>
        <td><input name="lines[<?php echo $i; ?>][name]" class="form-control" type="text" value="<?php echo htmlspecialchars($line->name); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][contract]" class="form-control" type="text" value="<?php echo htmlspecialchars($line->contract); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][overtime]" class="form-control" type="number" min="0" step="1" value="<?php echo htmlspecialchars($line->overtime); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][conges]" class="form-control" type="number" min="0" step="0.5" value="<?php echo htmlspecialchars($line->conges); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][maladie]" class="form-control" type="number" min="0" step="0.5" value="<?php echo htmlspecialchars($line->maladie); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][autre]" class="form-control" type="number" min="0" step="0.5" value="<?php echo htmlspecialchars($line->autre); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][transports]" class="form-control" type="number" min="0" step="0.01" value="<?php echo htmlspecialchars($line->transports); ?>"></td>
        <td><input name="lines[<?php echo $i; ?>][expenses]" class="form-control" type="number" min="0" step="0.01" value="<?php echo htmlspecialchars($line->expenses); ?>"></td>
        <td><textarea name="lines[<?php echo $i++; ?>][details]" class="form-control"><?php echo strip_tags($line->details); ?></textarea></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <button type="submit" class="btn btn-primary">Générer le PDF</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
