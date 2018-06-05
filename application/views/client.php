<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5"><?php echo $client->fullName; ?></h1>
<p class="lead">Affichage de quelques informations à propos de ce client</p>

<?php if (count($client->contacts)): ?>
<h2>Contacts</h2>
<ul>
  <?php foreach ($client->contacts as $contact): ?>
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
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
