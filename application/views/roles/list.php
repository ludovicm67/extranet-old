<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des rôles <a class="btn btn-outline-primary" href="/roles/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents rôles</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($roles as $role): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($role->name)); ?>">
      <td><?php echo $role->name; ?></td>
      <td>
        <a href="/role/edit/<?php echo $role->id; ?>">Modifier</a>
        -
        <a href="/role/delete/<?php echo $role->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
