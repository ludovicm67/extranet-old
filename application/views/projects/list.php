<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des projets <a class="btn btn-outline-primary" href="/projects/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents projets</p>

<ul>
  <?php foreach ($projects as $project): ?>
  <li>
    <a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
    &middot;
    (<a href="/project/delete/<?php echo $project->id; ?>">Supprimer</a>)
  </li>
<?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
