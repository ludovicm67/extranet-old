<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5"><?php echo $tag->name; ?></h1>
<p class="lead">Affichage des projets utilisant ce tag</p>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
