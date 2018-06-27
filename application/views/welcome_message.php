<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Bienvenue sur l'interface de gestion de <?php
                                                        echo $this->db->dc->getConfValueDefault(
                                                          'site_name',
                                                          null,
                                                          'Gestion'
                                                        );
                                                        ?>.</p>

<div class="row">
  <div class="col-md-3">
    <div class="card mb-3">
      <div class="card-header">
        Mon compte
      </div>
      <div class="card-body">
        <?php if (!empty($this->session->firstname)): ?>
          <p>Bienvenue <?php echo htmlspecialchars($this->session->firstname); ?> !</p>
        <?php endif; ?>
        <ul>
          <li><a href="/users/me">Modifier mon compte</a></li>
          <li><a href="/logout">Se déconnecter</a></li>
        </ul>
      </div>
    </div>

    <?php if (!empty($favProjects)): ?>
    <div class="card mb-3">
      <div class="card-header">
        Projets favoris
      </div>
      <div class="card-body">
        <ul>
          <?php foreach ($favProjects as $project): ?>
            <li>
              <a href="/project/<?php echo $project->id; ?>">
                <?php echo $project->name; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

    <div class="card mb-3">
      <div class="card-header">
        Mettre à jour
      </div>
      <div class="card-body">
        <p>
          <button class="btn btn-primary request-runner" data-request="/cron/init_database" type="button">Base de données</button>
        </p>
        <p>
          <button class="btn btn-primary request-runner" data-request="/cron/sellsy_clients" type="button">Clients</button>
        </p>
        <p>
          <button class="btn btn-primary request-runner" data-request="/cron/sellsy_contacts" type="button">Contacts</button>
        </p>
        <p>
          <button class="btn btn-primary request-runner" data-request="/cron/sellsy_orders" type="button">Commandes</button>
        </p>
        <p>
          <button class="btn btn-primary request-runner" data-request="/cron/sellsy_invoices" type="button">Factures</button>
        </p>
      </div>
    </div>
  </div>

  <div class="col-md-9">
    <?php if (!empty($projects)): ?>
    <h2>Mes projets</h2>
    <ul class="list-upgraded">
      <?php foreach ($projects as $project): ?>
        <li>
          <a href="/project/<?php echo $project->id; ?>">
            <?php echo $project->name; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <h2>Demandes en attente</h2>

    <h3>Congés</h3>
    <p><em>Prochainnement...</em></p>

    <h3>Frais</h3>
    <p><em>Prochainnement...</em></p>
  </div>
</div>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
