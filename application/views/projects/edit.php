<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Modifier un projet</h1>
<p class="lead">Ici vous pouvez modifier les informations concernant le projet</p>

<form method="post">
  <div class="form-group row">
    <label for="projectName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($project->name); ?>" id="projectName" placeholder="Nom du projet...">
    </div>
  </div>
  <div class="form-group row">
    <label for="projectClient" class="col-sm-2 col-form-label">Client principal</label>
    <div class="col-sm-10">
      <select class="form-control" name="client" id="projectClient">
        <option value="0">Aucun client</option>
        <?php foreach ($clients as $client): ?>
        <option value="<?php echo $client->id; ?>"<?php echo ($project->client_id === $client->id) ? ' selected="selected"' : ''; ?>>
          <?php echo $client->fullName; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="projectContact" class="col-sm-2 col-form-label">Contacts</label>
    <div class="col-sm-10">
      <select class="form-control" name="contacts[]" id="projectContact" multiple="multiple">
        <?php foreach ($contacts as $contact): ?>
        <option value="<?php echo $contact->id; ?>"<?php echo (in_array($contact->id, $project->contacts)) ? ' selected="selected"' : ''; ?>>
          <?php echo $contact->fullName; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="projectOrder" class="col-sm-2 col-form-label">Commandes</label>
    <div class="col-sm-10">
      <select class="form-control" name="orders[]" id="projectOrder" multiple="multiple">
        <?php foreach ($orders as $order): ?>
        <option value="<?php echo $order->id; ?>"<?php echo (in_array($order->id, $project->orders)) ? ' selected="selected"' : ''; ?>>
          <?php echo $order->thirdname . ' :: ' . $order->subject; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Tags</label>
    <div class="col-sm-10">
      <div>

        <div class="row dupplicate-item">
          <div class="col-sm-6">
            <select class="form-control" name="tagName[]">
              <option value="">Aucun tag</option>
              <?php foreach ($tags as $tag): ?>
              <option value="<?php echo $tag->id; ?>">
                <?php echo $tag->name; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="tagValue[]" placeholder="Valeur du tag (si nécessaire)...">
          </div>
        </div>

        <?php foreach ($project->tags as $pTag): ?>
          <div class="row">
            <div class="col-sm-6">
              <select class="form-control" name="tagName[]">
                <option value="">Aucun tag</option>
                <?php foreach ($tags as $tag): ?>
                <option value="<?php echo $tag->id; ?>"<?php echo ($tag->id == $pTag->tag_id) ? ' selected="selected"' : ''; ?>>
                  <?php echo $tag->name; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="tagValue[]" value="<?php echo htmlspecialchars($pTag->value); ?>" placeholder="Valeur du tag (si nécessaire)...">
            </div>
          </div>
        <?php endforeach; ?>

        <button class="btn btn-outline-primary dupplicate-action" type="button">Ajouter un nouveau tag</button>

      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
