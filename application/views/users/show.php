<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $user->firstname; ?> <?php echo $user->lastname; ?>
  <small>(<?php echo $user->mail; ?>)</small>
  <a class="btn btn-outline-primary" href="/user/edit/<?php echo $user->id; ?>" role="button">Modifier</a>
  <a class="btn btn-outline-danger" href="/user/delete/<?php echo $user->id; ?>" role="button">Supprimer</a>
</h1>
<p class="lead">Affichage des informations à propos de cet utilisateur</p>

<ul>
  <li><strong>Nom complet :</strong> <?php echo $user->firstname; ?> <?php echo $user->lastname; ?></li>
  <li><strong>Adresse mail :</strong> <a href="mailto:<?php echo htmlspecialchars($user->mail); ?>"><?php echo $user->mail; ?></a></li>
  <li><strong>Rôle :</strong> <?php echo ($user->role) ? $user->role : 'Aucun rôle'; ?></li>
  <?php if ($user->is_admin): ?>
  <li><strong>Cet utilisateur est un administrateur</strong></li>
  <?php endif; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
