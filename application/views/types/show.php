<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $type->name; ?>
  <a class="btn btn-outline-primary" href="/type/edit/<?php echo $type->id; ?>" role="button">Modifier</a>
  <a class="btn btn-outline-danger" href="/type/delete/<?php echo $type->id; ?>" role="button">Supprimer</a>
</h1>
<p class="lead">Affichage des contacts de ce type</p>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
