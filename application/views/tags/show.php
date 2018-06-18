<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $tag->name; ?>
  <?php if (!empty($this->input->get('value'))): ?>
    <small><?php echo strip_tags($this->input->get('value')); ?></small>
    <a class="btn btn-outline-secondary" href="/tag/<?php echo $tag->id; ?>" role="button">Enlever le filtre</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('export_contacts', 'show')): ?>
  <a
  class="btn btn-outline-secondary"
  href="/export?tag=<?php echo $tag->id; echo (!empty($this->input->get('value'))) ? '&value=' . urlencode(trim($this->input->get('value'))) : ''; ?>"
    role="button">
    Exporter
  </a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('tags', 'edit')): ?>
    <a class="btn btn-outline-primary" href="/tag/edit/<?php echo $tag->id; ?>" role="button">Modifier</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('tags', 'delete')): ?>
    <a data-confirm-delete-url class="btn btn-outline-danger" href="/tag/delete/<?php echo $tag->id; ?>" role="button">Supprimer</a>
  <?php endif; ?>
</h1>
<p class="lead">Affichage des projets utilisant ce tag</p>

<ul class="list-upgraded">
  <?php foreach ($tag->projects as $project): ?>
  <li>
    <a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
  </li>
<?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
