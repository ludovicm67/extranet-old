<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des clients</h1>
<p class="lead">Page listant les différents clients</p>

<ul>
  <?php foreach ($clients as $client): ?>
  <li>
    <ul>
      <li><strong>Nom complet :</strong> <?php echo $client->fullname; ?></li>
      <li>
        <strong>Contacts :</strong>
        <ul>
          <?php foreach ($client->contacts as $contact): ?>
          <li>
            <ul>
              <?php if (!empty($contact->fullname)): ?>
                <li><strong>Nom complet :</strong> <?php echo $contact->fullname; ?></li>
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
    </ul>
  </li>
<?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
