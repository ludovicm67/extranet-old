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

    <?php if ($controller->hasPermission('request_management', 'edit')): ?>
    <h2>Demandes en attente</h2>
    <?php if (empty($leave)): ?>
      <p><em>Les demandes de congés ont toutes été traitées !</em></p>
    <?php else: ?>
    <h3>Congés</h3>
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Utilisateur</th>
          <th scope="col">Motif</th>
          <th scope="col">Début</th>
          <th scope="col">Fin</th>
          <th scope="col">Nombre de jours</th>
          <th scope="col">Statut</th>
          <th scope="col">Commentaire</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leave as $c): ?>
        <tr<?php echo ($c->accepted == -1) ? ' class="text-danger font-italic"' : ''; echo ($c->accepted == 1) ? ' class="text-success font-italic"' : ''; ?>>
          <td><a href="/user/<?php echo $c->user_id; ?>"><?php echo $c->firstname; ?> <?php echo $c->lastname; ?></a></td>
          <td><?php echo $c->reason; ?></td>
          <td><?php echo (new DateTime($c->start))->format('d/m/Y'); ?></td>
          <td><?php echo (new DateTime($c->end))->format('d/m/Y'); ?></td>
          <td><?php echo $c->days; ?></td>
          <td>
    <?php switch ($c->accepted) { case -1: echo 'Refusée'; break; case 1: echo 'Acceptée'; break; default: echo 'En attente...'; break; } ?>
          </td>
          <td><?php echo nl2br($c->details); ?></td>
          <td>
            <?php if (!empty($c->file)): ?>
              <a class="btn btn-dark" href="<?php echo $c->file; ?>" target="_blank" title="Ouvrir le justificatif">
                <i class="far fa-file"></i>
              </a>
            <?php endif; ?>

            <?php if ($controller->hasPermissions('request_management', 'edit')): ?>
              <?php if ($c->accepted != 1): ?>
                <a class="btn btn-success" href="/leave/accept/<?php echo $c->id; ?>">
                  <i class="fas fa-check"></i>
                </a>
              <?php endif; ?>
              <?php if ($c->accepted != -1): ?>
                <a class="btn btn-danger" href="/leave/reject/<?php echo $c->id; ?>">
                  <i class="fas fa-times"></i>
                </a>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($c->user_id == $this->session->id || $controller->hasPermissions('leave', 'edit')): ?>
              <a class="btn btn-dark" href="/leave/edit/<?php echo $c->id; ?>">
                <i class="fas fa-edit"></i>
              </a>
            <?php endif; ?>

            <?php if ($c->user_id == $this->session->id || $controller->hasPermissions('leave', 'delete')): ?>
              <a data-confirm-delete-url class="btn btn-danger" href="/leave/delete/<?php echo $c->id; ?>">
                <i class="far fa-trash-alt"></i>
              </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <?php if (empty($expenses)): ?>
      <p><em>Les demandes de remboursement de frais ont toutes été traitées !</em></p>
    <?php else: ?>
    <h3>Frais</h3>
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Utilisateur</th>
          <th scope="col">Type</th>
          <th scope="col">Date</th>
          <th scope="col">Montant</th>
          <th scope="col">Statut</th>
          <th scope="col">Commentaire</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($expenses as $c): ?>
        <tr<?php echo ($c->accepted == -1) ? ' class="text-danger font-italic"' : ''; echo ($c->accepted == 1) ? ' class="text-success font-italic"' : ''; ?>>
          <td><a href="/user/<?php echo $c->user_id; ?>"><?php echo $c->firstname; ?> <?php echo $c->lastname; ?></a></td>
          <td><?php echo $c->type; ?></td>
          <td><?php echo $c->month . '/' . $c->year; ?></td>
          <td><?php echo number_format($c->amount, 2, ',', ' '); ?>€</td>
          <td>
    <?php switch ($c->accepted) { case -1: echo 'Refusée'; break; case 1: echo 'Acceptée'; break; default: echo 'En attente...'; break; } ?>
          </td>
          <td><?php echo nl2br($c->details); ?></td>
          <td>
            <?php if (!empty($c->file)): ?>
              <a class="btn btn-dark" href="<?php echo $c->file; ?>" target="_blank" title="Ouvrir le justificatif">
                <i class="far fa-file"></i>
              </a>
            <?php endif; ?>

            <?php if ($controller->hasPermissions('request_management', 'edit')): ?>
              <?php if ($c->accepted != 1): ?>
                <a class="btn btn-success" href="/expenses/accept/<?php echo $c->id; ?>">
                  <i class="fas fa-check"></i>
                </a>
              <?php endif; ?>
              <?php if ($c->accepted != -1): ?>
                <a class="btn btn-danger" href="/expenses/reject/<?php echo $c->id; ?>">
                  <i class="fas fa-times"></i>
                </a>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($c->user_id == $this->session->id || $controller->hasPermissions('expenses', 'edit')): ?>
              <a class="btn btn-dark" href="/expenses/edit/<?php echo $c->id; ?>">
                <i class="fas fa-edit"></i>
              </a>
            <?php endif; ?>

            <?php if ($c->user_id == $this->session->id || $controller->hasPermissions('expenses', 'delete')): ?>
              <a data-confirm-delete-url class="btn btn-danger" href="/expenses/delete/<?php echo $c->id; ?>">
                <i class="far fa-trash-alt"></i>
              </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</div>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
