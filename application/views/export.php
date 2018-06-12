<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Exporter des contacts <a class="btn btn-outline-primary" href="<?php echo $download_url; ?>" role="button" target="_blank">Télécharger CSV</a></h1>
<p class="lead">Exportez des contacts en filtrant sur le type et par tags de projets sur lesquels ils sont affectés.</p>


<div class="container">
  <div class="row">
    <div class="col-sm-3">
      <form method="get">
        <div class="form-group">
        <label for="contactType" class="form-label">Type de contact</label>
          <select class="form-control" name="type" id="contactType">
            <option value="0">Tous les types</option>
            <?php foreach ($types as $type): ?>
            <option value="<?php echo $type->id; ?>"<?php echo ($this->input->get('type') === $type->id) ? ' selected="selected"' : ''; ?>>
              <?php echo $type->name; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
        <label for="projectTags" class="form-label">Tags</label>
          <select class="form-control" name="tag" id="projectTags">
            <option value="0">Tous les tags</option>
            <?php foreach ($tags as $tag): ?>
            <option value="<?php echo $tag->id; ?>"<?php echo ($this->input->get('tag') === $tag->id) ? ' selected="selected"' : ''; ?>>
              <?php echo $tag->name; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="tagValue" class="form-label">Valeur du tag</label>
          <input type="text" class="form-control" name="value" value="<?php echo htmlspecialchars($this->input->get('value')); ?>" id="tagValue" placeholder="Filtrer sur la valeur du tag...">
        </div>

        <button type="submit" class="btn btn-primary">Filtrer</button>
      </form>
    </div>
    <div class="col-sm">

      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Type</th>
            <th scope="col">Adresse mail</th>
            <th scope="col">Téléphone</th>
            <th scope="col">Projet</th>
            <th scope="col">Domaine</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $contact): ?>
          <tr>
            <td><a href="/contact/<?php echo $contact->contact_id; ?>"><?php echo $contact->contact_name; ?></a></td>
            <td><?php echo $contact->type; ?></td>
            <td>
              <a href="mailto:<?php echo htmlspecialchars($contact->mail); ?>">
                <?php echo $contact->mail; ?>
              </a>
            </td>
            <td>
              <a href="tel:<?php echo htmlspecialchars($contact->phone); ?>">
                <?php echo $contact->phone; ?>
              </a>
            </td>
            <td>
              <?php if (!empty($contact->project_id)): ?>
                <a href="/project/<?php echo $contact->project_id; ?>"><?php echo $contact->project_name; ?></a>
              <?php endif; ?>
            </td>
            <td><?php echo $contact->domain; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>


<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
