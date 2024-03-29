<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  <a class="btn btn-outline-dark" title="Retour à la liste" href="/contacts" role="button">
    <i class="fas fa-list"></i>
  </a>
  <?php
  echo $contact->name;
  ?>
  <?php if ($controller->hasPermission('contacts', 'edit')): ?>
    <a class="btn btn-outline-primary" href="/contact/edit/<?php echo $contact->id; ?>" role="button">Modifier</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('contacts', 'delete')): ?>
    <a data-confirm-delete-url class="btn btn-outline-danger" href="/contact/delete/<?php echo $contact->id; ?>" role="button">Supprimer</a>
  <?php endif; ?>
</h1>
<p class="lead">Affichage des informations à propos de ce contact</p>

<h2>Informations générales</h2>
<ul>
  <?php if (!empty($contact->type)): ?>
    <li><strong>Type :</strong> <?php echo $contact->type; ?></li>
  <?php endif; ?>
  <?php if (!empty($contact->mail)): ?>
    <li>
      <strong>Adresse mail :</strong>
      <a href="mailto:<?php echo htmlspecialchars($contact->mail); ?>">
        <?php echo $contact->mail; ?>
      </a>
    </li>
  <?php endif; ?>
  <?php if (!empty($contact->phone)): ?>
    <li>
      <strong>Téléphone :</strong>
      <a href="tel:<?php echo htmlspecialchars($contact->phone); ?>">
        <?php echo $contact->phone; ?>
      </a>
    </li>
  <?php endif; ?>
  <?php if (!empty($contact->address)): ?>
    <li><strong>Adresse :</strong> <?php echo $contact->address; ?></li>
  <?php endif; ?>
  <?php if (!empty($contact->other)): ?>
    <li>
      <p><strong>Autres informations :</strong></p>
      <p><?php echo nl2br($contact->other); ?></p>
    </li>
  <?php endif; ?>
</ul>

<h2>Projets dans lequel ce contact est impliqué</h2>
<ul class="list-upgraded">
  <?php foreach ($contact->projects as $project): ?>
  <li>
    <a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
  </li>
<?php endforeach; ?>
</ul>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
