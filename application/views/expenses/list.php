<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  Liste des notes de frais
  <?php if ($controller->hasPermission('expenses', 'add')): ?>
    <a class="btn btn-outline-primary" href="/expenses/new" role="button">Faire une demande</a>
  <?php endif; ?>
</h1>
<p class="lead">Passez en revue les notes de frais</p>

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
    <?php foreach ($content as $c): ?>
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

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
