<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Petite page de test.</p>

<h2>Pages pour tester</h2>
<ul>
  <li><a href="/clients">/clients</a> : liste les différents clients et leurs contacts associés</li>
  <li><a href="/projects">/projects</a> : liste les différents projets</li>
  <li><a href="/contacts">/contacts</a> : liste les différents contacts</li>
  <li><a href="/tags">/tags</a> : liste les différents tags</li>
  <li><a href="/types">/types</a> : liste les différents types de contacts</li>
</ul>

<h2>Pages à appeler depuis des tâches cron</h2>
<ul>
  <li><a href="/cron/all" target="_blank">/cron/all</a> : à appeler pour lancer toutes les tâches suivantes en une fois</li>
  <li><a href="/cron/init_database" target="_blank">/cron/init_database</a> : à appeler pour générer la base de données (créer l'ensemble des tables requises; ne supprime rien)</li>
  <li><a href="/cron/sellsy_clients" target="_blank">/cron/sellsy_clients</a> : à appeler pour récupérer l'ensemble des clients avec leurs contacts associés</li>
  <li><a href="/cron/sellsy_contacts" target="_blank">/cron/sellsy_contacts</a> : à appeler pour mettre à jour les informations sur l'ensemble des contacts</li>
  <li><a href="/cron/sellsy_orders" target="_blank">/cron/sellsy_orders</a> : à appeler pour mettre à jour les informations sur l'ensemble des commandes</li>
  <li><a href="/cron/sellsy_invoices" target="_blank">/cron/sellsy_invoices</a> : à appeler pour mettre à jour les informations sur l'ensemble des factures</li>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
