<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  Liste des congés
  <?php if ($controller->hasPermission('leave', 'add')): ?>
    <a class="btn btn-outline-primary" href="/leave/new" role="button">Faire une demande</a>
  <?php endif; ?>
</h1>
<p class="lead">Passez en revue les demandes de congés</p>

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
    <?php foreach ($content as $c): ?>
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

        <?php if ($controller->hasPermissions('leave', 'edit')): ?>
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

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
