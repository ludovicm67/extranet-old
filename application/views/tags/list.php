<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Liste des tags
  <?php if ($controller->hasPermission('tags', 'add')): ?>
    <a class="btn btn-outline-primary" href="/tags/new" role="button">Ajouter</a>
  <?php endif; ?>
</h1>
<p class="lead">Page listant les différents tags</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tags as $tag): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($tag->name)); ?>">
      <td><a href="/tag/<?php echo $tag->id; ?>"><?php echo $tag->name; ?></a></td>
      <td>
        <?php if ($controller->hasPermission('tags', 'edit')): ?>
          <a href="/tag/edit/<?php echo $tag->id; ?>">Modifier</a>
        <?php endif; ?>
        <?php if ($controller->hasPermission('tags', 'edit') && $controller->hasPermission('tags', 'delete')): ?>
          -
        <?php endif; ?>
        <?php if ($controller->hasPermission('tags', 'delete')): ?>
          <a href="/tag/delete/<?php echo $tag->id; ?>">Supprimer</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
