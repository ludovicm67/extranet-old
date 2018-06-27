<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  Liste des types de contacts
  <?php if ($controller->hasPermission('types', 'add')): ?>
    <a class="btn btn-outline-primary" href="/types/new" role="button">Ajouter</a>
  <?php endif; ?>
</h1>
<p class="lead">Page listant les différents types de contacts</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($types as $type): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($type->name)); ?>">
      <td><?php echo $type->name; ?></td>
      <td>
        <?php if ($controller->hasPermission('types', 'edit')): ?>
        <a href="/type/edit/<?php echo $type->id; ?>">Modifier</a>
        <?php endif; ?>
        <?php if ($controller->hasPermission('types', 'edit') && $controller->hasPermission('types', 'delete')): ?>
          -
        <?php endif; ?>
        <?php if ($controller->hasPermission('types', 'delete')): ?>
          <a data-confirm-delete-url href="/type/delete/<?php echo $type->id; ?>">Supprimer</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
