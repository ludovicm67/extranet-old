<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Modification d'une demande de congés</h1>
<p class="lead">
  Modifiez une période de congés
  <?php if (!$controller->hasPermission('request_management', 'edit')): ?>
    <em>(nécessitera une nouvelle validation)</em>
  <?php endif; ?>
</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="leaveStart" class="col-sm-2 col-form-label">Début</label>
    <div class="col-sm-7">
      <input type="date" class="form-control" name="start" id="leaveStart" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php
                                                                                                                                echo (new DateTime(
                                                                                                                                  $leave->start
                                                                                                                                ))->format(
                                                                                                                                  'Y-m-d'
                                                                                                                                );
                                                                                                                                ?>">
    </div>
    <div class="col-sm-3">
      <select name="start_time" id="leaveStartTime">
        <option value="09"<?php
                          echo ($leave->start_time == 9)
                            ? ' selected="selected"'
                            : '';
                          ?>>9h</option>
        <option value="14"<?php
                          echo ($leave->start_time == 14)
                            ? ' selected="selected"'
                            : '';
                          ?>>14h</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveEnd" class="col-sm-2 col-form-label">Date de fin</label>
    <div class="col-sm-7">
      <input type="date" class="form-control" name="end" id="leaveEnd" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php
                                                                                                                            echo (new DateTime(
                                                                                                                              $leave->end
                                                                                                                            ))->format(
                                                                                                                              'Y-m-d'
                                                                                                                            );
                                                                                                                            ?>">
    </div>
    <div class="col-sm-3">
      <select name="end_time" id="leaveEndTime">
        <option value="18"<?php
                          echo ($leave->end_time == 18)
                            ? ' selected="selected"'
                            : '';
                          ?>>18h</option>
        <option value="12"<?php
                          echo ($leave->end_time == 12)
                            ? ' selected="selected"'
                            : '';
                          ?>>12h</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveDays" class="col-sm-2 col-form-label">Nombre de jours</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input id="leaveDays" type="number" class="form-control" required pattern="[0-9]+([\.,][0-9]+)?" step="0.5" min="0" value="<?php
                                                                                                                                   echo $leave->days;
                                                                                                                                   ?>" name="days">
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
        <option value="leave"<?php
                             echo ($leave->reason == 'Congé')
                               ? ' selected="selected"'
                               : '';
                             ?>>Congé</option>
        <option value="disease"<?php
                               echo ($leave->reason == 'Maladie')
                                 ? ' selected="selected"'
                                 : '';
                               ?>>Maladie (pensez à joindre un justificatif)</option>
        <option value="other"<?php
                             echo ($leave->reason == 'Autre')
                               ? ' selected="selected"'
                               : '';
                             ?>>Autre (précisez en commentaire)</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="leaveFile" class="col-sm-2 col-form-label">Justificatif (si maladie)<br><em>Laisser vide pour ne pas changer</em></label>
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
      <textarea class="form-control" name="details" id="leaveDetails" placeholder="Commentaire..."><?php
                                                                                                   echo $leave->details;
                                                                                                   ?></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
