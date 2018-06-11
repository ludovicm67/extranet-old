<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Créer un nouveau projet</h1>
<p class="lead">Entrez ici les informations concernant le projet</p>

<form method="post">
  <div class="form-group row">
    <label for="projectName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="projectName" placeholder="Nom du projet...">
    </div>
  </div>
  <div class="form-group row">
    <label for="projectClient" class="col-sm-2 col-form-label">Client principal</label>
    <div class="col-sm-10">
      <select class="form-control" name="client" id="projectClient">
        <option value="0">Aucun client</option>
        <?php foreach ($clients as $client): ?>
        <option value="<?php echo $client->id; ?>"<?php echo ($project->client_id == $client->id) ? ' selected="selected"' : ''; ?>>
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
        <option value="<?php echo $contact->id; ?>"><?php echo $contact->name; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="projectOrder" class="col-sm-2 col-form-label">Commandes</label>
    <div class="col-sm-10">
      <select class="form-control" name="orders[]" id="projectOrder" multiple="multiple">
        <?php foreach ($orders as $order): ?>
        <option value="<?php echo $order->id; ?>"><?php echo $order->thirdname . ' :: ' . $order->subject; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="projectUsers" class="col-sm-2 col-form-label">Utilisateurs affectés</label>
    <div class="col-sm-10">
      <select class="form-control" name="users[]" id="projectUsers" multiple="multiple">
        <?php foreach ($users as $user): ?>
        <option value="<?php echo $user->id; ?>"><?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->mail . ')'; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Tags</label>
    <div class="col-sm-10">
      <div>

        <div class="row dupplicate-item">
          <div class="col-sm-4">
            <select class="form-control" name="tagName[]">
              <option value="">Aucun tag</option>
              <?php foreach ($tags as $tag): ?>
              <option value="<?php echo $tag->id; ?>">
                <?php echo $tag->name; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="tagValue[]" placeholder="Valeur du tag (si nécessaire)...">
          </div>
        </div>

        <button class="btn btn-outline-primary dupplicate-action" type="button">Ajouter un nouveau tag</button>

      </div>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">URLs</label>
    <div class="col-sm-10">
      <div>

        <div class="row move-item dupplicate-item">
          <div class="col-sm-4">
            <input type="text" class="form-control" name="urlName[]" placeholder="Nom de l'url">
          </div>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="urlValue[]" placeholder="Url...">
          </div>
          <div class="col-sm-2">
            <button class="badge move-up" type="button">↑</button>
            <button class="badge move-down" type="button">↓</button>
          </div>
        </div>

        <button class="btn btn-outline-primary dupplicate-action" type="button">Ajouter une nouvelle URL</button>

      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
