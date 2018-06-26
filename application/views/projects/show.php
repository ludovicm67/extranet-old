<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <a class="btn btn-outline-dark" title="Retour à la liste" href="/projects" role="button">
    <i class="fas fa-list"></i>
  </a>
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

<p<?php echo (!empty($project->end_at) && new DateTime($project->end_at) < new DateTime('now')) ? ' class="text-warning"' : ''; ?>>
  <strong>Fin du projet souhaité :</strong> <?php echo (!empty($project->end_at)) ? (new DateTime($project->end_at))->format('d/m/Y') : ''; ?>
</p>

<?php if (!empty($project->client)): ?>
  <h2>Client principal</h2>
  <p>Le client principal de ce projet est <a href="/client/<?php echo $project->client->id; ?>"><?php echo $project->client->fullName; ?></a>.</p>
  <?php if (!empty($project->client->contacts)): ?>
    <h2>Contacts</h2>
    <div class="row">
    <?php foreach ($project->client->contacts as $contact): ?>
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
  <?php endif; endif; ?>

<?php if (!empty($project->contacts)): ?>
<h2>Interlocuteurs</h2>
<div class="row">
<?php foreach ($project->contacts as $contact): ?>
  <div class="card bg-light col-md-4">
    <div class="card-body">
      <h5 class="card-title">
        <a href="/contact/edit/<?php echo $contact->id; ?>" class="float-right">
          <i class="far fa-edit"></i>
        </a>
        <a href="/contact/<?php echo $contact->id; ?>"><?php echo $contact->name; ?></a>
      </h5>
      <p class="card-text">
        <?php if (!empty($contact->type)): ?>
          <?php echo $contact->type; ?><br>
        <?php endif; ?>
        <?php if (!empty($contact->mail)): ?>
          <a href="mailto:<?php echo htmlspecialchars($contact->mail); ?>"><?php echo $contact->mail; ?></a><br>
        <?php endif; ?>
        <?php if (!empty($contact->phone)): ?>
          <a href="tel:<?php echo htmlspecialchars($contact->phone); ?>"><?php echo $contact->phone; ?></a><br>
        <?php endif; ?>
        <?php if (!empty($contact->address)): ?>
          <?php echo $contact->address; ?><br>
        <?php endif; ?>
      </p>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($project->users)): ?>
<h2>Utilisateurs assignés</h2>
  <ul class="list-upgraded">
    <?php foreach ($project->users as $user): ?>
      <li>
        <a href="/user/<?php echo $user->id; ?>">
          <?php echo $user->firstname . ' ' . $user->lastname . ' (' . $user->mail . ')'; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php if (!empty($project->next_action)): ?>
  <h2>Prochaine action à effectuer</h2>
  <p><?php echo nl2br($project->next_action); ?></p>
<?php endif; ?>

<?php if (!empty($project->orders)): ?>
<h2>Commandes</h2>
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

<?php if (!empty($project->urls)): ?>
<h2>URLs</h2>
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
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
