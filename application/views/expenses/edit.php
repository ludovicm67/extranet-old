<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Modifier note de frais</h1>
<p class="lead">
  Effectuer des modifications sur une note de frais
  <?php if (!$controller->hasPermission('request_management', 'edit')): ?>
    <em>(nécessitera une nouvelle validation)</em>
  <?php endif; ?>
</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="expensesYear" class="col-sm-2 col-form-label">Année</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="year" id="expensesYear" value="<?php
                                                                                     echo $expense->year;
                                                                                     ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="expensesMonth" class="col-sm-2 col-form-label">Mois</label>
    <div class="col-sm-10">
      <select class="form-control" name="month" id="expensesMonth">
        <?php foreach ($months as $k => $month): ?>
        <option value="<?php echo $k; ?>"<?php echo ($k == $expense->month) ? ' selected="selected"' : ''; ?>>
          <?php echo $month; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="expensesType" class="col-sm-2 col-form-label">Type</label>
    <div class="col-sm-10">
      <select class="form-control" name="type" id="expensesType">
        <option value="Transports"<?php
                                  echo ($expense->type == 'Transports')
                                    ? ' selected="selected"'
                                    : '';
                                  ?>>Transports</option>
        <option value="Dépense"<?php
                               echo ($expense->type == 'Dépense')
                                 ? ' selected="selected"'
                                 : '';
                               ?>>Dépense</option>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="expensesAmount" class="col-sm-2 col-form-label">Montant</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input id="expensesAmount" type="number" class="form-control" required pattern="[0-9]+([\.,][0-9]+)?" step="0.01" min="0" value="<?php
                                                                                                                                         echo $expense->amount;
                                                                                                                                         ?>" name="amount">
        <div class="input-group-append">
          <span class="input-group-text">€</span>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="expensesFile" class="col-sm-2 col-form-label">Justificatif<br><em>Laisser vide pour ne pas changer</em></label>
    <div class="col-sm-10">
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" name="file" id="expensesFile">
          <label class="custom-file-label" for="expensesFile">Choisir un fichier...</label>
        </div>
      </div>
      <?php if (!empty($expense->file)): ?>
      <label>
        <input type="checkbox" name="delete_file" value="1">
        Supprimer le justificatif ?
      </label>
      <?php endif; ?>
    </div>
  </div>
  <div class="form-group row">
    <label for="expensesDetails" class="col-sm-2 col-form-label">Commentaire</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="details" id="expensesDetails" placeholder="Commentaire..."><?php
                                                                                                      echo $expense->details;
                                                                                                      ?></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
