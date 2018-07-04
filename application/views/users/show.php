<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  <a class="btn btn-outline-dark" title="Retour à la liste" href="/users" role="button">
    <i class="fas fa-list"></i>
  </a>
  <?php
  echo $user->firstname;
  ?> <?php echo $user->lastname; ?>
  <small>(<?php
          echo $user->mail;
          ?>)</small>
  <?php if ($controller->hasPermission('users', 'edit')): ?>
    <a class="btn btn-outline-primary" href="/user/edit/<?php echo $user->id; ?>" role="button">Modifier</a>
  <?php endif; ?>
  <?php if ($this->session->id != $user->id && $controller->hasPermission('users', 'delete')): ?>
    <a data-confirm-delete-url class="btn btn-outline-danger" href="/user/delete/<?php echo $user->id; ?>" role="button">Supprimer</a>
  <?php endif; ?>
</h1>
<p class="lead">Affichage des informations à propos de cet utilisateur</p>

<ul>
  <li><strong>Nom complet :</strong> <?php
                                     echo $user->firstname;
                                     ?> <?php
                                        echo $user->lastname;
                                        ?></li>
  <li><strong>Adresse mail :</strong> <a href="mailto:<?php
                                                      echo htmlspecialchars(
                                                        $user->mail
                                                      );
                                                      ?>"><?php
                                                          echo $user->mail;
                                                          ?></a></li>
  <li>
    <strong>Rôle :</strong>
    <?php if ($user->is_admin): ?>
      <strong>Super administrateur</strong>
    <?php else: ?>
      <?php echo ($user->role) ? $user->role : 'Aucun rôle'; ?>
    <?php endif; ?>
  </li>
</ul>

<?php if (!empty($user->projects)): ?>
<h2>Projets assignés à cet utilisateur</h2>
<ul class="list-upgraded">
  <?php foreach ($user->projects as $project): ?>
    <li>
      <a href="/project/<?php echo $project->id; ?>">
        <?php echo $project->name; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
