<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $project->name; ?>
  <a class="btn btn-outline-primary" href="/project/<?php echo $project->id; ?>" role="button">Voir le projet</a>
  <a class="btn btn-outline-primary" href="/identifiers/<?php echo $project->id; ?>/new" role="button">Ajouter un identifiant</a>
</h1>
<p class="lead">Les différents identifiants pour le projet</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Type</th>
      <th scope="col">Valeur</th>
      <th scope="col">Confidentiel</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($identifiers as $identifier): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($identifier->name)); ?>">
      <td><?php echo (empty($identifier->type)) ? 'Autre' : $identifier->type; ?></td>
      <td><?php echo nl2br($identifier->value); ?></td>
      <td><?php echo ($identifier->confidential == 1) ? 'Oui' : 'Non'; ?></td>
      <td>
        <a href="/identifiers/project_edit/<?php echo $identifier->id; ?>">Modifier</a>
        -
        <a href="/identifiers/project_delete/<?php echo $identifier->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
