<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $project->name; ?>
  <a class="btn btn-outline-primary" href="/project/edit/<?php echo $project->id; ?>" role="button">Modifier</a>
  <a class="btn btn-outline-danger" href="/project/delete/<?php echo $project->id; ?>" role="button">Supprimer</a>
</h1>
<p class="lead">Affichages d'informations concernant le projet</p>
<p>
  <?php foreach ($project->tags as $tag): ?>
    <a href="/tag/<?php echo $tag->id; echo (!empty($tag->value)) ? '?value=' . urlencode($tag->value) : ''; ?>" class="badge badge-secondary">
      <?php echo $tag->name; ?>
      <?php echo (!empty($tag->value)) ? ': ' . $tag->value : ''; ?>
    </a>
  <?php endforeach; ?>
</p>

<h2>Client principal</h2>
<?php if (!empty($project->client)): ?>
  <p>Le client principal de ce projet est <a href="/client/<?php echo $project->client->id; ?>"><?php echo $project->client->fullName; ?></a>.</p>
<?php else: ?>
  <p>Le projet n'est assigné à aucun client principal.</p>
<?php endif; ?>

<h2>Contacts pour ce projet</h2>
<?php if (!empty($project->contacts)): ?>
  <ul>
    <?php foreach ($project->contacts as $contact): ?>
    <li>
      <ul>
        <?php if (!empty($contact->fullName)): ?>
          <li><strong>Nom complet :</strong> <?php echo $contact->fullName; ?></li>
        <?php endif; ?>
        <?php if (!empty($contact->email)): ?>
          <li><strong>Email :</strong> <?php echo $contact->email; ?></li>
        <?php endif; ?>
        <?php if (!empty($contact->tel)): ?>
          <li><strong>Téléphone :</strong> <?php echo $contact->tel; ?></li>
        <?php endif; ?>
        <?php if (!empty($contact->mobile)): ?>
          <li><strong>Mobile :</strong> <?php echo $contact->mobile; ?></li>
        <?php endif; ?>
        <?php if (!empty($contact->position)): ?>
          <li><strong>Position :</strong> <?php echo $contact->position; ?></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Le projet n'est assigné à aucun contacts.</p>
<?php endif; ?>

<h2>Commandes pour ce projet</h2>
<?php if (!empty($project->orders)): ?>
  <ul>
    <?php foreach ($project->orders as $order): ?>
    <li>
      <ul>
        <li><strong>Client :</strong> <?php echo $order->thirdname; ?></li>
        <li><strong>Sujet :</strong> <?php echo $order->subject; ?></li>
        <li><strong>Statut :</strong> <span style="color: <?php echo $order->step_hex; ?>;"><?php echo $order->step_label; ?></span></li>
        <li><strong>Montant total :</strong> <?php echo $order->formatted_totalAmount; ?></li>
        <li><strong>Contact :</strong> <?php echo $order->contactName; ?></li>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Le projet n'est assigné à aucune commande.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
