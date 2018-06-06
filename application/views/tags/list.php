<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des tags <a class="btn btn-outline-primary" href="/tags/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents tags</p>

<ul>
  <?php foreach ($tags as $tag): ?>
  <li>
    <a href="/tag/<?php echo $tag->id; ?>"><?php echo $tag->name; ?></a>
    &middot;
    (
      <a href="/tag/edit/<?php echo $tag->id; ?>">Modifier</a>
      -
      <a href="/tag/delete/<?php echo $tag->id; ?>">Supprimer</a>
    )
  </li>
<?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
