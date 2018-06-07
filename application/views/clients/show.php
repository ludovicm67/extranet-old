<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5"><?php echo $client->fullName; ?> <a class="btn btn-outline-primary" href="/projects/new?client_id=<?php echo $client->id; ?>" role="button">Ajouter un projet</a></h1>
<p class="lead">Affichage de quelques informations à propos de ce client</p>

<h2>Commandes</h2>
<?php if (!empty($client->orders)): ?>
<div id="accordion">
  <?php foreach ($client->orders as $order): ?>
  <div class="card">
    <div class="card-header" id="heading<?php echo $order->id; ?>">
      <h5 class="mb-0">
        <button class="btn btn-light" data-toggle="collapse" data-target="#collapse<?php echo $order->id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $order->id; ?>">
          <?php echo $order->subject; ?>
        </button>
        <span class="float-right" style="color: <?php echo $order->step_hex; ?>;">
          <?php echo number_format($order->remainingOrderAmount, 2, ',', ' ') . ' ' . $order->currencysymbol; ?>
          <?php if ($order->remainingDueAmount > 0): ?>
            + <?php echo number_format($order->remainingDueAmount, 2, ',', ' ') . ' ' . $order->currencysymbol; ?> en attente de paiement
          <?php endif; ?>
        </span>
      </h5>
    </div>

    <div id="collapse<?php echo $order->id; ?>" class="collapse" aria-labelledby="heading<?php echo $order->id; ?>" data-parent="#accordion">
      <div class="card-body">
        <ul>
        <li><strong>Client :</strong> <?php echo $order->thirdname; ?></li>
        <li><strong>Sujet :</strong> <?php echo $order->subject; ?></li>
        <li><strong>Statut :</strong> <span style="color: <?php echo $order->step_hex; ?>;"><?php echo $order->step_label; ?></span></li>
        <li><strong>Montant total :</strong> <?php echo $order->formatted_totalAmount; ?></li>
        <li><strong>Contact :</strong> <?php echo $order->contactName; ?></li>
        <li>
          <p><strong>Factures associées :</strong></p>
          <ul>
            <?php foreach ($order->invoices as $invoice): ?>
            <li>
              <ul>
                <li><strong>Sujet :</strong> <?php echo $invoice->subject; ?></li>
                <li><strong>Statut :</strong> <span style="color: <?php echo $invoice->step_hex; ?>;"><?php echo $invoice->step_label; ?></span></li>
                <li><strong>Montant total :</strong> <?php echo $invoice->formatted_totalAmount; ?></li>
                <li><strong>Reste à payer :</strong> <?php echo $invoice->formatted_dueAmount; ?></li>
                <li><strong>Contact :</strong> <?php echo $invoice->contactName; ?></li>
              </ul>
            </li>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

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

<?php if (count($client->projects)): ?>
<h2>Projets</h2>
<ul>
  <?php foreach ($client->projects as $project): ?>
    <li><a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
