<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <a href="/calendar?year=<?php echo $prev->year; ?>&amp;month=<?php
echo $prev->month;
echo ($this->input->get('me') == 1) ? '&amp;me=1' : '';
?>" class="btn btn-dark"><i class="fas fa-arrow-left"></i></a>
  <?php echo $o->calendarTranslator->month($now->month) . ' ' . $now->year; ?>
  <a href="/calendar?year=<?php echo $next->year; ?>&amp;month=<?php
echo $next->month;
echo ($this->input->get('me') == 1) ? '&amp;me=1' : '';
?>" class="btn btn-dark"><i class="fas fa-arrow-right"></i></a>
  <?php if ($controller->hasPermission('leave', 'add')): ?>
    <a class="btn btn-outline-primary" href="/leave/new" role="button">Demande de congés</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('expenses', 'add')): ?>
    <a class="btn btn-outline-primary" href="/expenses/new" role="button">Nouvelle note de frais</a>
  <?php endif; ?>
</h1>
<p class="lead">Calendrier permettant d'afficher les congés et les notes de frais.</p>

<h2>Congés</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Utilisateur</th>
      <th scope="col">Début</th>
      <th scope="col">Fin</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($leave as $c): ?>
    <tr>
      <td><a href="/user/<?php echo $c->user_id; ?>"><?php echo $c->firstname; ?> <?php echo $c->lastname; ?></a></td>
      <td><?php echo (new DateTime($c->start))->format('d/m/Y'); ?></td>
      <td><?php echo (new DateTime($c->end))->format('d/m/Y'); ?></td>
      <td><?php echo nl2br($c->details); ?></td>
      <td>
        <?php if ($c->accepted == 0 && ($c->user_id == $this->session->id || $controller->hasPermissions('leave', 'edit'))): ?>
          <a class="btn btn-success" href="/leave/accept/<?php echo $c->id; ?>">
            <i class="fas fa-check"></i>
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

<h2>Liste des notes de frais</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Utilisateur</th>
      <th scope="col">Date</th>
      <th scope="col">Montant</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($expenses as $c): ?>
    <tr>
      <td><a href="/user/<?php echo $c->user_id; ?>"><?php echo $c->firstname; ?> <?php echo $c->lastname; ?></a></td>
      <td><?php echo $c->month . '/' . $c->year; ?></td>
      <td><?php echo number_format($c->amount, 2, ',', ' '); ?>€</td>
      <td><?php echo nl2br($c->details); ?></td>
      <td>
        <?php if (!empty($c->file)): ?>
          <a class="btn btn-dark" href="<?php echo $c->file; ?>" target="_blank" title="Ouvrir le justificatif">
            <i class="far fa-file"></i>
          </a>
        <?php endif; ?>

        <?php if ($c->accepted == 0 && ($c->user_id == $this->session->id || $controller->hasPermissions('expenses', 'edit'))): ?>
          <a class="btn btn-success" href="/expenses/accept/<?php echo $c->id; ?>">
            <i class="fas fa-check"></i>
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


<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
