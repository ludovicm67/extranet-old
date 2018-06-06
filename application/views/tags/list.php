<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des tags <a class="btn btn-outline-primary" href="/tags/new" role="button">Ajouter</a></h1>
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
    <tr>
      <td><a href="/tag/<?php echo $tag->id; ?>"><?php echo $tag->name; ?></a></td>
      <td>
        <a href="/tag/edit/<?php echo $tag->id; ?>">Modifier</a>
        -
        <a href="/tag/delete/<?php echo $tag->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
