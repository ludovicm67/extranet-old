<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Liste des utilisateurs
  <?php if ($controller->hasPermission('users', 'add')): ?>
    <a class="btn btn-outline-primary" href="/users/new" role="button">Ajouter</a>
  <?php endif; ?>
</h1>
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
      <td><a href="/user/<?php echo $user->id; ?>"><?php echo (empty($user->firstname . $user->lastname)) ? 'Utilisateur#' . $user->id : $user->firstname . ' ' . $user->lastname; ?></a></td>
      <td><a href="mailto:<?php echo htmlspecialchars($user->mail); ?>"><?php echo $user->mail; ?></a></td>
      <td><?php echo ($user->role) ? $user->role : 'Aucun rôle'; ?></td>
      <td><?php echo ($user->is_admin) ? 'Oui' : 'Non'; ?></td>
      <td>
        <?php if ($controller->hasPermission('users', 'edit')): ?>
          <a href="/user/edit/<?php echo $user->id; ?>">Modifier</a>
        <?php endif; ?>
        <?php if ($controller->hasPermission('users', 'edit') && $this->session->id != $user->id && $controller->hasPermission('users', 'delete')): ?>
          -
        <?php endif; ?>
        <?php if ($this->session->id != $user->id && $controller->hasPermission('users', 'delete')): ?>
          <a data-confirm-delete-url href="/user/delete/<?php echo $user->id; ?>">Supprimer</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
