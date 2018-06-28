<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Modifier un nouveau contrat</h1>
<p class="lead">Entrez ici les informations concernant le contrat</p>

<form method="post">
  <div class="form-group row">
    <label for="contractUser" class="col-sm-2 col-form-label">Personne</label>
    <div class="col-sm-10">
      <select class="form-control" name="user_id" id="contractUser" required>
        <?php foreach ($users as $user): ?>
        <option<?php echo ($user->id == $contract->user_id) ? ' selected="selected"' : ''; ?> value="<?php echo $user->id; ?>">
          <?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->mail . ')'; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="contractType" class="col-sm-2 col-form-label">Type de contrat</label>
    <div class="col-sm-10">
      <select class="form-control" name="type" id="contractType" required>
        <option<?php
               echo ($contract->type == 'CDI') ? ' selected="selected"' : '';
               ?> value="CDI">CDI</option>
        <option<?php
               echo ($contract->type == 'CDD') ? ' selected="selected"' : '';
               ?> value="CDD">CDD</option>
        <option<?php
               echo ($contract->type == 'Stage') ? ' selected="selected"' : '';
               ?> value="Stage">Stage</option>
        <option<?php
               echo ($contract->type == 'Apprentissage')
                 ? ' selected="selected"'
                 : '';
               ?> value="Apprentissage">Apprentissage</option>
        <option<?php
               echo ($contract->type == 'Contrat pro')
                 ? ' selected="selected"'
                 : '';
               ?> value="Contrat pro">Contrat pro</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="contractStart" class="col-sm-2 col-form-label">Date de début</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="start_at" id="contractStart" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php
                                                                                                                                      echo (new DateTime(
                                                                                                                                        $contract->start_at
                                                                                                                                      ))->format(
                                                                                                                                        'Y-m-d'
                                                                                                                                      );
                                                                                                                                      ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="contractEnd" class="col-sm-2 col-form-label">Date de fin (optionnel)</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="end_at" id="contractEnd"<?php
                                                                            echo (
                                                                              !empty(
                                                                                $contract->end_at
                                                                              )
                                                                            )
                                                                              ? ' value="' .
                                                                              (new DateTime(
                                                                                $contract->end_at
                                                                              ))->format(
                                                                                'Y-m-d'
                                                                              ) .
                                                                              '"'
                                                                              : '';
                                                                            ?> pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
    </div>
  </div>
  <div class="form-group row" id="contractDaysGroup"<?php
                                                    echo (
                                                      empty($contract->days)
                                                    )
                                                      ? ' style="display: none;"'
                                                      : '';
                                                    ?>>
    <label for="contractDays" class="col-sm-2 col-form-label">Jours de présence (stage)</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" name="days" id="contractDays" min="0"<?php
                                                                                     echo (
                                                                                       !empty(
                                                                                         $contract->days
                                                                                       )
                                                                                     )
                                                                                       ? ' value="' .
                                                                                       $contract->days .
                                                                                       '"'
                                                                                       : '';
                                                                                     ?>>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
