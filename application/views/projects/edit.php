<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Modifier le projet <em><?php echo $project->name; ?></em>
  <a class="btn btn-outline-primary" href="/project/<?php echo $project->id; ?>" role="button">Retourner au projet sans sauvegarder</a>
</h1>
<p class="lead">Ici vous pouvez modifier les informations concernant le projet</p>

<form method="post">
  <div class="form-group row">
    <label for="projectName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($project->name); ?>" id="projectName" placeholder="Nom du projet...">
    </div>
  </div>
  <div class="form-group row">
    <label for="projectDomain" class="col-sm-2 col-form-label">Domaine principal</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="domain" value="<?php echo htmlspecialchars($project->domain); ?>" id="projectDomain" placeholder="Domaine principal du projet (facultatif)...">
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
    <label for="projectContact" class="col-sm-2 col-form-label">Contacts <a data-create-contact-modal href="/contacts/new">(Créer)</a></label>
    <div class="col-sm-10">
      <select class="form-control" name="contacts[]" id="projectContact" multiple="multiple">
        <?php foreach ($contacts as $contact): ?>
        <option value="<?php echo $contact->id; ?>"<?php echo (in_array($contact->id, $project->contacts)) ? ' selected="selected"' : ''; ?>>
          <?php echo $contact->name; ?>
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
    <label for="projectUsers" class="col-sm-2 col-form-label">Utilisateurs affectés</label>
    <div class="col-sm-10">
      <select class="form-control" name="users[]" id="projectUsers" multiple="multiple">
        <?php foreach ($users as $user): ?>
        <option value="<?php echo $user->id; ?>"<?php echo (in_array($user->id, $project->users)) ? ' selected="selected"' : ''; ?>>
          <?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->mail . ')'; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="projectNextAction" class="col-sm-2 col-form-label">Prochaine action à effectuer</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="next_action" id="projectNextAction" placeholder="Prochaine action à effectuer sur le projet..."><?php echo $project->next_action; ?></textarea>
    </div>
  </div>

  <div class="form-group row">
    <span class="col-sm-2 col-form-label">Maintenance</span>
    <div class="col-sm-10">
      <label for="projectMaintenance" value="1">
        <input type="checkbox" name="maintenance" id="projectMaintenance"<?php echo ($project->maintenance == 1) ? ' checked="checked"' : ''; ?>>
        La maintenance est incluse
      </label>
    </div>
  </div>

  <div class="form-group row">
    <label for="projectEndAt" class="col-sm-2 col-form-label">Fin du projet souhaité</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="end_at" id="projectEndAt" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo date('Y-m-d', strtotime($project->end_at)); ?>">
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Tags</label>
    <div class="col-sm-10">
      <div>

        <div class="row dupplicate-item">
          <div class="col-sm-4">
            <select data-tags="true" class="form-control" name="tagName[]">
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

        <?php foreach ($project->tags as $pTag): ?>
          <div class="row">
            <div class="col-sm-4">
              <select data-tags="true" class="form-control" name="tagName[]">
                <option value="">Aucun tag</option>
                <?php foreach ($tags as $tag): ?>
                <option value="<?php echo $tag->id; ?>"<?php echo ($tag->id == $pTag->tag_id) ? ' selected="selected"' : ''; ?>>
                  <?php echo $tag->name; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="tagValue[]" value="<?php echo htmlspecialchars($pTag->value); ?>" placeholder="Valeur du tag (si nécessaire)...">
            </div>
          </div>
        <?php endforeach; ?>

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

        <?php foreach ($project->urls as $url): ?>
          <div class="row move-item">
            <div class="col-sm-4">
              <input type="text" class="form-control" name="urlName[]" value="<?php echo htmlspecialchars($url->name); ?>" placeholder="Nom de l'url">
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="urlValue[]" value="<?php echo htmlspecialchars($url->value); ?>" placeholder="Url...">
            </div>
            <div class="col-sm-2">
              <button class="badge move-up" type="button">↑</button>
              <button class="badge move-down" type="button">↓</button>
            </div>
          </div>
        <?php endforeach; ?>

        <button class="btn btn-outline-primary dupplicate-action" type="button">Ajouter une nouvelle URL</button>

      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<div class="modal fade" id="createContactModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Création d'un contact</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="createContactModalForm">
          <div class="form-group row">
            <label for="contactName" class="col-sm-4 col-form-label">Nom</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="name" id="contactName" placeholder="Nom du contact..." required>
            </div>
          </div>
          <div class="form-group row">
            <label for="contactType" class="col-sm-4 col-form-label">Type de contact</label>
            <div class="col-sm-8">
              <select data-tags="true" class="form-control" name="type" id="contactType">
                <option value="0">Aucun type</option>
                <?php foreach ($types as $type): ?>
                <option value="<?php echo $type->id; ?>">
                  <?php echo $type->name; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="contactMail" class="col-sm-4 col-form-label">Adresse mail</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" name="mail" id="contactMail" placeholder="Adresse mail du contact...">
            </div>
          </div>
          <div class="form-group row">
            <label for="contactPhone" class="col-sm-4 col-form-label">Téléphone</label>
            <div class="col-sm-8">
              <input type="tel" class="form-control" name="phone" id="contactPhone" placeholder="Téléphone du contact...">
            </div>
          </div>
          <div class="form-group row">
            <label for="contactAddress" class="col-sm-4 col-form-label">Adresse</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="address" id="contactAddress" placeholder="Adresse du contact...">
            </div>
          </div>
          <div class="form-group row">
            <label for="contactOther" class="col-sm-4 col-form-label">Autres informations</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="other" id="contactOther" placeholder="Autres informations sur le contact..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="createContactModalBtn">Créer</a>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
