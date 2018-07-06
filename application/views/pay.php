<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Déposer une fiche de paie</h1>
<p class="lead">Importez une fiche de paie pour un utilisateur</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="payUser" class="col-sm-2 col-form-label">Utilisateurs</label>
    <div class="col-sm-10">
      <select class="form-control" name="user_id" id="payUser">
        <?php foreach ($users as $user): ?>
        <option value="<?php echo $user->id; ?>"><?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->email . ')'; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="payYear" class="col-sm-2 col-form-label">Année</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="year" id="payYear" value="<?php
                                                                                     echo date(
                                                                                       'Y'
                                                                                     );
                                                                                     ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="payMonth" class="col-sm-2 col-form-label">Mois</label>
    <div class="col-sm-10">
      <select class="form-control" name="month" id="payMonth" required>
        <?php foreach ($months as $k => $month): ?>
        <option value="<?php echo $k; ?>"<?php echo ($k == date('n')) ? ' selected="selected"' : ''; ?>>
          <?php echo $month; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="payFile" class="col-sm-2 col-form-label">Fiche de paie</label>
    <div class="col-sm-10">
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" name="file" id="payFile" required>
          <label class="custom-file-label" for="payFile">Choisir un fichier...</label>
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Déposer</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
