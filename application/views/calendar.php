<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  <a href="/calendar?year=<?php
                          echo $prev->year;
                          ?>&amp;month=<?php echo $prev->month;
echo ($this->input->get('me') == 1) ? '&amp;me=1' : '';
 ?>" class="btn btn-dark"><i class="fas fa-arrow-left"></i></a>
  <?php
  echo $o->calendarTranslator->month($now->month) . ' ' . $now->year;
  ?>
  <a href="/calendar?year=<?php
                          echo $next->year;
                          ?>&amp;month=<?php echo $next->month;
echo ($this->input->get('me') == 1) ? '&amp;me=1' : '';
 ?>" class="btn btn-dark"><i class="fas fa-arrow-right"></i></a>
  <?php if ($controller->hasPermission('leave', 'add')): ?>
    <a class="btn btn-outline-primary" href="/leave/new" role="button">Demande de congés</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('expenses', 'add')): ?>
    <a class="btn btn-outline-primary" href="/expenses/new" role="button">Nouvelle note de frais</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('overtime', 'add')): ?>
    <a class="btn btn-outline-primary" href="/overtime?month=<?php echo $now->month; ?>&amp;year=<?php echo $now->year; ?>" role="button">Heure sup</a>
  <?php endif; ?>



<div class="btn-group">
  <a class="btn btn-outline-primary" href="/pdf/compta?month=<?php echo $now->month; ?>&amp;year=<?php echo $now->year; ?>" role="button" target="_blank">PDF compta</a>
  <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" target="_blank" href="/pdf/compta?month=<?php echo $now->month; ?>&amp;year=<?php echo $now->year; ?>">Générer le PDF</a>
    <a class="dropdown-item" target="_blank" href="/pdf/form?month=<?php echo $now->month; ?>&amp;year=<?php echo $now->year; ?>">Éditer le PDF</a>
  </div>
</div>




</h1>
<p class="lead">Calendrier permettant d'afficher les congés et les notes de frais.</p>


    <?php if (empty($leave)): ?>
      <p><em>Aucune demande de congés ce moi-ci.</em></p>
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
      <p><em>Aucune demande de remboursement de frais ce mois-ci.</em></p>
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


<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
