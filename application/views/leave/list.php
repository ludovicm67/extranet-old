<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Liste des congés
  <a class="btn btn-outline-primary" href="/leave/new" role="button">Faire une demande</a>
</h1>
<p class="lead">Passez en revue les demandes de congés</p>

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
    <?php foreach ($content as $c): ?>
    <tr>
      <td><a href="/user/<?php echo $c->user_id; ?>"><?php echo $c->firstname; ?> <?php echo $c->lastname; ?></a></td>
      <td><?php echo (new DateTime($c->start))->format('d/m/Y'); ?></td>
      <td><?php echo (new DateTime($c->end))->format('d/m/Y'); ?></td>
      <td><?php echo nl2br($c->details); ?></td>
      <td>OK KO</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
