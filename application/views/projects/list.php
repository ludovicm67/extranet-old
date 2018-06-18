<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Liste des projets
  <?php if ($controller->hasPermission('projects', 'add')): ?>
    <a class="btn btn-outline-primary" href="/projects/new" role="button">Ajouter</a>
  <?php endif; ?>
</h1>
<p class="lead">Page listant les différents projets</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col"></th>
      <th scope="col">Nom</th>
      <th scope="col">Prochaine action à effectuer</th>
      <th scope="col">Date de fin</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($projects as $project): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($project->name)); ?>">
      <td>
        <span data-project-fav="<?php echo $project->id; ?>" data-project-favorited="<?php echo ($project->favorite == 0) ? 0 : 1; ?>">
          <i class="<?php echo ($project->favorite == 0) ? 'far' : 'fas'; ?> fa-star"></i>
        </span>
      </td>
      <td><a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a></td>
      <td></td>
      <td class="text-warning">Une date passée (@TODO)</td>
      <td>
        <?php if (in_array($project->id, $myProjects) || $controller->hasPermission('projects', 'edit')): ?>
          <a href="/project/edit/<?php echo $project->id; ?>">Modifier</a>
        <?php endif; ?>
        <?php if (in_array($project->id, $myProjects) || ($controller->hasPermission('projects', 'edit') && $controller->hasPermission('projects', 'delete'))): ?>
          -
        <?php endif; ?>
        <?php if (in_array($project->id, $myProjects) || $controller->hasPermission('projects', 'delete')): ?>
          <a href="/project/delete/<?php echo $project->id; ?>">Supprimer</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
