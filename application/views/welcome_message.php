<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Petite page de test.</p>

<h2>Pages pour tester</h2>
<ul>
  <li><a href="/clients" target="_blank">/clients</a> : liste les différents clients et leurs contacts associés</li>
</ul>

<h2>Pages à appeler depuis des tâches cron</h2>
<ul>
  <li><a href="/cron/reset_database" target="_blank">/cron/reset_database</a> : à appeler pour vider complètement la base de données (pour toutes les tables ayant des informations de Sellsy) et reconstruire l'architecture des différentes tables</li>
  <li><a href="/cron/sellsy_clients" target="_blank">/cron/sellsy_clients</a> : à appeler pour récupérer l'ensemble des clients avec leurs contacts associés</li>
  <li><a href="/cron/sellsy_contacts" target="_blank">/cron/sellsy_contacts</a> : à appeler pour mettre à jour les informations sur l'ensemble des contacts</li>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
