<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des types de contacts <a class="btn btn-outline-primary" href="/types/new" role="button">Ajouter</a></h1>
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
      <td><a href="/type/<?php echo $type->id; ?>"><?php echo $type->name; ?></a></td>
      <td>
        <a href="/type/edit/<?php echo $type->id; ?>">Modifier</a>
        -
        <a href="/type/delete/<?php echo $type->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
