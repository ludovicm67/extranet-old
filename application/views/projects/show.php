<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5"><?php echo $project->name; ?></h1>
<p class="lead">Affichages d'informations concernant le projet</p>

<h2>Client principal</h2>
<?php if (!empty($project->client)): ?>
  <p>Le client principal de ce projet est <a href="/client/<?php echo $project->client->id; ?>"><?php echo $project->client->fullName; ?></a>.</p>
<?php else: ?>
  <p>Le projet n'est assigné à aucun client principal.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
