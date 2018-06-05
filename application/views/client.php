<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des clients</h1>
<p class="lead">Page listant les différents clients</p>

<ul>
  <li><strong>Nom complet :</strong> <?php echo $client->fullName; ?></li>
  <?php if (count($client->contacts)): ?>
  <li>
    <strong>Contacts :</strong>
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
  </li>
  <?php endif; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
