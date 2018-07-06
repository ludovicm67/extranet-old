<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  Liste des contrats
  <?php if ($controller->hasPermission('contracts', 'add')): ?>
    <a class="btn btn-outline-primary" href="/contracts/new" role="button">Ajouter</a>
  <?php endif; ?>
</h1>
<p class="lead">Page listant les différents contrats</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Personne</th>
      <th scope="col">Type</th>
      <th scope="col">Début</th>
      <th scope="col">Fin</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contracts as $contract): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($contract->type . ' ' . $contract->full_name . ' (' . $contract->email . ')')); ?>">
      <td><a href="/user/<?php echo $contract->user_id; ?>"><?php echo $contract->full_name . ' (' . $contract->email . ')'; ?></a></td>
      <td>
        <?php echo $contract->type; ?>
        <?php if ($contract->type == 'Stage' && !empty($contract->days)): ?>
          <em>(<?php echo $contract->days; ?> jours de présence)</em>
        <?php endif; ?>
      </td>
      <td><?php echo (new DateTime($contract->start_at))->format('d/m/Y'); ?></td>
      <td>
        <?php if (!empty($contract->end_at)): ?>
          <?php echo (new DateTime($contract->end_at))->format('d/m/Y'); ?>
        <?php else: ?>
          - - - - - - - - - -
        <?php endif; ?>
      </td>
      <td>
        <?php if ($controller->hasPermission('contracts', 'edit')): ?>
        <a href="/contracts/edit/<?php echo $contract->id; ?>">Modifier</a>
        <?php endif; ?>
        <?php if ($controller->hasPermission('contracts', 'edit') && $controller->hasPermission('contracts', 'delete')): ?>
          -
        <?php endif; ?>
        <?php if ($controller->hasPermission('contracts', 'delete')): ?>
          <a data-confirm-delete-url href="/contracts/delete/<?php echo $contract->id; ?>">Supprimer</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
