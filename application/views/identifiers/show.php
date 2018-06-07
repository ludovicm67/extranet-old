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


<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
