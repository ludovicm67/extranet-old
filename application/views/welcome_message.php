<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Petite page de test.</p>

<h2>Pages pour tester</h2>
<ul>
  <li><a href="/clients" target="_blank">/clients</a></li>
</ul>

<h2>Pages à appeler depuis des tâches cron</h2>
<ul>
  <li><a href="/cron/sellsy_clients" target="_blank">/cron/sellsy_clients</a></li>
  <li><a href="/cron/sellsy_contacts" target="_blank">/cron/sellsy_contacts</a></li>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
