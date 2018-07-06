<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Créer un nouveau contrat</h1>
<p class="lead">Entrez ici les informations concernant le contrat</p>

<form method="post">
  <div class="form-group row">
    <label for="contractUser" class="col-sm-2 col-form-label">Personne</label>
    <div class="col-sm-10">
      <select class="form-control" name="user_id" id="contractUser" required>
        <?php foreach ($users as $user): ?>
        <option value="<?php echo $user->id; ?>">
          <?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->email . ')'; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="contractType" class="col-sm-2 col-form-label">Type de contrat</label>
    <div class="col-sm-10">
      <select class="form-control" name="type" id="contractType" required>
        <option value="CDI">CDI</option>
        <option value="CDD">CDD</option>
        <option value="Stage">Stage</option>
        <option value="Apprentissage">Apprentissage</option>
        <option value="Contrat pro">Contrat pro</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="contractStart" class="col-sm-2 col-form-label">Date de début</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="start_at" id="contractStart" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php
                                                                                                                                      echo date(
                                                                                                                                        'Y-m-d'
                                                                                                                                      );
                                                                                                                                      ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="contractEnd" class="col-sm-2 col-form-label">Date de fin (optionnel)</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="end_at" id="contractEnd" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
    </div>
  </div>
  <div class="form-group row" id="contractDaysGroup" style="display: none;">
    <label for="contractDays" class="col-sm-2 col-form-label">Jours de présence (stage)</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="days" id="contractDays" min="0">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
