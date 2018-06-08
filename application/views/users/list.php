<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des utilisateurs <a class="btn btn-outline-primary" href="/users/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents utilisateurs</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Adresse mail</th>
      <th scope="col">Rôle</th>
      <th scope="col">Administrateur ?</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($user->firstname . ' ' . $user->lastname . ' ' . $user->firstname . ' ' . $user->mail)); ?>">
      <td><?php echo $user->firstname; ?> <?php echo $user->lastname; ?></td>
      <td><?php echo $user->mail; ?></td>
      <td>ROLE</td>
      <td><?php echo ($user->is_admin) ? 'Oui' : 'Non'; ?></td>
      <td>
        <a href="/user/edit/<?php echo $user->id; ?>">Modifier</a>
        -
        <a href="/user/delete/<?php echo $user->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
