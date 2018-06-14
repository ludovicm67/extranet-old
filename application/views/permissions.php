<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Permissions pour <em><?php echo $role->name; ?></em></h1>
<p class="lead">Gérez ici les différentes permissions pour ce rôle</p>

<form method="post">
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Afficher</th>
      <th scope="col">Ajouter</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
      <th scope="col">Tous</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($permissions as $key => $permission): ?>
    <tr>
      <td><?php echo $permission->name; ?></td>
      <td>
        <?php if ($permission->show): ?>
          <input type="checkbox" value="1" name="permissions[<?php echo $key; ?>][show]"<?php echo (in_array('show', $permission->checked)) ? ' checked="checked"' : ''; ?>>
        <?php else: ?>
          <input type="checkbox" value="0" name="permissions[<?php echo $key; ?>][show]" disabled="disabled">
        <?php endif; ?>
      </td>
      <td>
        <?php if ($permission->add): ?>
          <input type="checkbox" value="1" name="permissions[<?php echo $key; ?>][add]"<?php echo (in_array('add', $permission->checked)) ? ' checked="checked"' : ''; ?>>
        <?php else: ?>
          <input type="checkbox" value="0" name="permissions[<?php echo $key; ?>][add]" disabled="disabled">
        <?php endif; ?>
      </td>
      <td>
        <?php if ($permission->edit): ?>
          <input type="checkbox" value="1" name="permissions[<?php echo $key; ?>][edit]"<?php echo (in_array('edit', $permission->checked)) ? ' checked="checked"' : ''; ?>>
        <?php else: ?>
          <input type="checkbox" value="0" name="permissions[<?php echo $key; ?>][edit]" disabled="disabled">
        <?php endif; ?>
      </td>
      <td>
        <?php if ($permission->delete): ?>
          <input type="checkbox" value="1" name="permissions[<?php echo $key; ?>][delete]"<?php echo (in_array('delete', $permission->checked)) ? ' checked="checked"' : ''; ?>>
        <?php else: ?>
          <input type="checkbox" value="0" name="permissions[<?php echo $key; ?>][delete]" disabled="disabled">
        <?php endif; ?>
      </td>
      <td><input class="row-select-all-checkbox" type="checkbox"<?php echo (
        (($permission->show && in_array('show', $permission->checked)) || !$permission->show)
        && (($permission->add && in_array('add', $permission->checked)) || !$permission->add)
        && (($permission->edit && in_array('edit', $permission->checked)) || !$permission->edit)
        && (($permission->delete && in_array('delete', $permission->checked)) || !$permission->delete)
      ) ? ' checked="checked"' : ''; ?>></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
