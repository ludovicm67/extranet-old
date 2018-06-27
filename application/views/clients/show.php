<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  <a class="btn btn-outline-dark" title="Retour à la liste" href="/clients" role="button">
    <i class="fas fa-list"></i>
  </a>
  <?php
  echo $client->fullName;
  ?>
  <?php if ($controller->hasPermission('projects', 'add')): ?>
    <a class="btn btn-outline-primary" href="/projects/new?client_id=<?php echo $client->id; ?>" role="button">Ajouter un projet</a>
  <?php endif; ?>
</h1>
<p class="lead">Affichage de quelques informations à propos de ce client</p>

<?php if (count($client->contacts)): ?>
<h2>Contacts</h2>
<div class="row">
  <?php foreach ($client->contacts as $contact): ?>
    <div class="card bg-light col-md-4">
      <div class="card-body">
        <h5 class="card-title"><?php echo $contact->fullName; ?></h5>
        <p class="card-text">
          <?php if (!empty($contact->position)): ?>
            <?php echo $contact->position; ?><br>
          <?php endif; ?>
          <?php if (!empty($contact->email)): ?>
            <a href="mailto:<?php echo htmlspecialchars($contact->email); ?>"><?php echo $contact->email; ?></a><br>
          <?php endif; ?>
          <?php if (!empty($contact->tel)): ?>
            <a href="tel:<?php echo htmlspecialchars($contact->tel); ?>"><?php echo $contact->tel; ?></a><br>
          <?php endif; ?>
          <?php if (!empty($contact->mobile)): ?>
            <a href="tel:<?php echo htmlspecialchars($contact->mobile); ?>"><?php echo $contact->mobile; ?></a><br>
          <?php endif; ?>
        </p>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (count($client->projects)): ?>
<h2>Projets</h2>
<ul class="list-upgraded">
  <?php foreach ($client->projects as $project): ?>
    <li><a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($client->orders)): ?>
<h2>Commandes</h2>
<div id="accordion">
  <?php foreach ($client->orders as $order): ?>
  <div class="card">
    <div class="card-header" id="heading<?php echo $order->id; ?>">
      <h5 class="mb-0">
        <button class="btn btn-light" data-toggle="collapse" data-target="#collapse<?php echo $order->id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $order->id; ?>">
          <?php echo $order->subject; ?>
        </button>
        <span class="float-right" style="color: <?php echo $order->step_hex; ?>;">
          <?php echo number_format($order->remainingOrderAmount, 2, ',', ' ') . ' ' . $order->currencysymbol; ?> HT
          <?php if ($order->remainingDueAmount > 0): ?>
            + <?php echo number_format($order->remainingDueAmount, 2, ',', ' ') . ' ' . $order->currencysymbol; ?> TTC en attente de paiement
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
        <li><strong>Montant total HT :</strong> <?php echo $order->formatted_totalAmountTaxesFree; ?> HT</li>
        <li><strong>Montant total TTC :</strong> <?php echo $order->formatted_totalAmount; ?> TTC</li>
        <li><strong>Contact :</strong> <?php echo $order->contactName; ?></li>
        <?php if (!empty($order->publicLinkShort)): ?>
          <li>
            <strong>Lien vers le bon de commande :</strong>
            <a href="<?php echo $order->publicLinkShort; ?>" target="_blank">
              <?php echo $order->publicLinkShort; ?>
            </a>
          </li>
        <?php endif; ?>
        <li>
          <p><strong>Factures associées :</strong></p>
          <ul>
            <?php foreach ($order->invoices as $invoice): ?>
            <li>
              <ul>
                <li><strong>Sujet :</strong> <?php echo $invoice->subject; ?></li>
                <li><strong>Statut :</strong> <span style="color: <?php echo $invoice->step_hex; ?>;"><?php echo $invoice->step_label; ?></span></li>
                <li><strong>Montant total HT :</strong> <?php echo $invoice->formatted_totalAmountTaxesFree; ?> HT</li>
                <li><strong>Montant total TTC :</strong> <?php echo $invoice->formatted_totalAmount; ?> TTC</li>
                <li><strong>Reste à payer :</strong> <?php echo $invoice->formatted_dueAmount; ?> TTC</li>
                <li><strong>Contact :</strong> <?php echo $invoice->contactName; ?></li>
                <?php if (!empty($invoice->publicLinkShort)): ?>
                  <li>
                    <strong>Lien vers la facture :</strong>
                    <a href="<?php echo $invoice->publicLinkShort; ?>" target="_blank">
                      <?php echo $invoice->publicLinkShort; ?>
                    </a>
                  </li>
                <?php endif; ?>
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


<?php if (!empty($client->subs)): ?>
<h2>Factures liées aux abonnements</h2>
<div id="subsAccordion">
  <?php foreach ($client->subs as $sub): ?>
  <div class="card">
    <div class="card-header" id="heading-sub-<?php echo $sub->id; ?>">
      <h5 class="mb-0">
        <button class="btn btn-light" data-toggle="collapse" data-target="#collapse-sub-<?php echo $sub->id; ?>" aria-expanded="false">
          <?php echo $sub->subject; ?>
        </button>
        <span class="float-right" style="color: <?php echo $sub->step_hex; ?>;">
          <?php echo number_format($sub->dueAmount, 2, ',', ' ') . ' ' . $sub->currencysymbol; ?> TTC
        </span>
      </h5>
    </div>

    <div id="collapse-sub-<?php echo $sub->id; ?>" class="collapse" aria-labelledby="heading-sub-<?php echo $sub->id; ?>" data-parent="#subsAccordion">
      <div class="card-body">
        <ul>
          <li><strong>Sujet :</strong> <?php echo $sub->subject; ?></li>
          <li><strong>Statut :</strong> <span style="color: <?php echo $sub->step_hex; ?>;"><?php echo $sub->step_label; ?></span></li>
          <li><strong>Montant total HT :</strong> <?php echo $sub->formatted_totalAmountTaxesFree; ?> HT</li>
          <li><strong>Montant total TTC :</strong> <?php echo $sub->formatted_totalAmount; ?> TTC</li>
          <li><strong>Reste à payer :</strong> <?php echo $sub->formatted_dueAmount; ?> TTC</li>
          <li><strong>Contact :</strong> <?php echo $sub->contactName; ?></li>
          <?php if (!empty($sub->publicLinkShort)): ?>
            <li>
              <strong>Lien vers la facture :</strong>
              <a href="<?php echo $sub->publicLinkShort; ?>" target="_blank">
                <?php echo $sub->publicLinkShort; ?>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
