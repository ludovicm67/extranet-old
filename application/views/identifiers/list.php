<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des types d'identifiants <a class="btn btn-outline-primary" href="/identifiers/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents types d'identifiants</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($identifiers as $identifier): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($identifier->name)); ?>">
      <td><a href="/identifier/<?php echo $identifier->id; ?>"><?php echo $identifier->name; ?></a></td>
      <td>
        <a href="/identifier/edit/<?php echo $identifier->id; ?>">Modifier</a>
        -
        <a href="/identifier/delete/<?php echo $identifier->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
