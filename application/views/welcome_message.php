<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Bienvenue sur l'interface de gestion de <?php echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion'); ?>.</p>

<h2>Mettre à jour les informations de la base de données</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Description</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr class="searcher-item" data-searcher="init_database">
      <td><a href="/cron/init_database" target="_blank">/cron/init_database</a></td>
      <td>générer la base de données (créer l'ensemble des tables requises; ne supprime rien)</td>
      <td><button class="btn btn-primary request-runner" data-request="/cron/init_database" type="button">Éxcuter</button></td>
    </tr>
    <tr class="searcher-item" data-searcher="sellsy_clients">
      <td><a href="/cron/sellsy_clients" target="_blank">/cron/sellsy_clients</a></td>
      <td>récupérer l'ensemble des clients avec leurs contacts associés</td>
      <td><button class="btn btn-primary request-runner" data-request="/cron/sellsy_clients" type="button">Éxcuter</button></td>
    </tr>
    <tr class="searcher-item" data-searcher="sellsy_contacts">
      <td><a href="/cron/sellsy_contacts" target="_blank">/cron/sellsy_contacts</a></td>
      <td>mettre à jour les informations sur l'ensemble des contacts</td>
      <td><button class="btn btn-primary request-runner" data-request="/cron/sellsy_contacts" type="button">Éxcuter</button></td>
    </tr>
    <tr class="searcher-item" data-searcher="sellsy_orders">
      <td><a href="/cron/sellsy_orders" target="_blank">/cron/sellsy_orders</a></td>
      <td>mettre à jour les informations sur l'ensemble des commandes</td>
      <td><button class="btn btn-primary request-runner" data-request="/cron/sellsy_orders" type="button">Éxcuter</button></td>
    </tr>
    <tr class="searcher-item" data-searcher="sellsy_invoices">
      <td><a href="/cron/sellsy_invoices" target="_blank">/cron/sellsy_invoices</a></td>
      <td>mettre à jour les informations sur l'ensemble des factures</td>
      <td><button class="btn btn-primary request-runner" data-request="/cron/sellsy_invoices" type="button">Éxcuter</button></td>
    </tr>
  </tbody>
</table>

<ul>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
