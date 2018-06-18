<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $project->name; ?>
  <?php if ($isMyProject || $controller->hasPermission('project_identifiers', 'show')): ?>
    <a class="btn btn-outline-primary" href="/identifiers/show/<?php echo $project->id; ?>" role="button">Identifiants</a>
  <?php endif; ?>
  <?php if ($isMyProject || $controller->hasPermission('projects', 'edit')): ?>
    <a class="btn btn-outline-primary" href="/project/edit/<?php echo $project->id; ?>" role="button">Modifier</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('projects', 'delete')): ?>
    <a data-confirm-delete-url class="btn btn-outline-danger" href="/project/delete/<?php echo $project->id; ?>" role="button">Supprimer</a>
  <?php endif; ?>
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

<?php if ($project->domain): ?>
<p><strong>Nom de domaine principal :</strong> <?php echo $project->domain; ?></p>
<?php endif; ?>

<h2>Client principal</h2>
<?php if (!empty($project->client)): ?>
  <p>Le client principal de ce projet est <a href="/client/<?php echo $project->client->id; ?>"><?php echo $project->client->fullName; ?></a>.</p>
<?php else: ?>
  <p>Le projet n'est assigné à aucun client principal.</p>
<?php endif; ?>

<h2>Contacts pour ce projet</h2>
<?php if (!empty($project->contacts)): ?>
  <ul class="list-upgraded">
    <?php foreach ($project->contacts as $contact): ?>
      <li>
        <a href="/contact/<?php echo $contact->id; ?>">
          <?php echo $contact->name; ?>
          <?php echo (!empty($contact->type)) ? ' (' . $contact->type . ')' : ''; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Le projet n'est assigné à aucun contacts.</p>
<?php endif; ?>

<h2>Utilisateurs assignés à ce projet</h2>
<?php if (!empty($project->users)): ?>
  <ul class="list-upgraded">
    <?php foreach ($project->users as $user): ?>
      <li>
        <a href="/user/<?php echo $user->id; ?>">
          <?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->mail . ')'; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Le projet n'est assigné à aucun utilisateurs.</p>
<?php endif; ?>

<?php if (!empty($project->next_action)): ?>
  <h2>Prochaine action à effectuer</h2>
  <p><?php echo nl2br($project->next_action); ?></p>
<?php endif; ?>

<p<?php echo (!empty($project->end_at) && new DateTime($project->end_at) < new DateTime('now')) ? ' class="text-warning"' : ''; ?>>
  <strong>Date de fin :</strong> <?php echo (!empty($project->end_at)) ? (new DateTime($project->end_at))->format('d/m/Y') : ''; ?>
</p>

<h2>Commandes pour ce projet</h2>
<?php if (!empty($project->orders)): ?>
<div id="accordion">
  <?php foreach ($project->orders as $order): ?>
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
        <li><strong>Montant total HT :</strong> <?php echo $order->formatted_totalAmountTaxesFree; ?></li>
        <li><strong>Montant total TTC :</strong> <?php echo $order->formatted_totalAmount; ?></li>
        <li><strong>Contact :</strong> <?php echo $order->contactName; ?></li>
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
<?php else: ?>
  <p>Le projet n'est assigné à aucune commande.</p>
<?php endif; ?>

<h2>URLs pour ce projet</h2>
<?php if (!empty($project->urls)): ?>
  <ul>
    <?php foreach ($project->urls as $url): ?>
      <li>
        <?php echo (!empty($url->name)) ? $url->name . ' :' : ''; ?>
        <?php if (strpos($url->value, '://') === false): ?>
          <a href="http://<?php echo htmlspecialchars($url->value); ?>" target="_blank">
            http://<?php echo $url->value; ?>
          </a>
        <?php else: ?>
          <a href="<?php echo htmlspecialchars($url->value); ?>" target="_blank">
            <?php echo $url->value; ?>
          </a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Aucune URL spécifiée pour ce projet.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
