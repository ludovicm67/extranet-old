<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Rechercher</h1>
<p class="lead">Recherchez ce dont vous avez besoin !</p>

<?php if (empty($results->has_query)): ?>
<p><em>Effectuez une recherche en utilisant le champ de recherche. Les résultats apparaîtront ici.</em></p>
<?php else: ?>
<p><strong><?php echo $results->results; ?> résultats pour la requête "<em><?php echo $results->query; ?></em>".</strong></p>
<?php if (!empty($results->clients)): ?>
<h2>Clients</h2>
<ul class="list-upgraded">
  <?php foreach ($results->clients as $client): ?>
  <li>
    <a href="/client/<?php echo $client->id; ?>"><?php echo $client->fullName; ?></a>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->projects)): ?>
<h2>Projets</h2>
<ul class="list-upgraded">
  <?php foreach ($results->projects as $project): ?>
  <li>
    <a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->contacts)): ?>
<h2>Interlocuteurs</h2>
<ul class="list-upgraded">
  <?php foreach ($results->contacts as $contact): ?>
    <li>
      <a href="/contact/<?php echo $contact->id; ?>"><?php echo $contact->name; ?></a>
    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->sellsy_contacts)): ?>
<h2>Contacts</h2>
<ul class="list-upgraded">
  <?php foreach ($results->sellsy_contacts as $contact): ?>
  <li>
    <a href="/client/<?php echo $contact->client_id; ?>"><?php echo $contact->fullName; ?> de <?php echo $contact->client_name; ?></a>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->users)): ?>
<h2>Utilisateurs</h2>
<ul class="list-upgraded">
  <?php foreach ($results->users as $user): ?>
  <li>
    <a href="/user/<?php echo $user->id; ?>"><?php echo $user->firstname; ?> <?php echo $user->lastname; ?> (<?php echo $user->mail; ?>)</a>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->tags)): ?>
<h2>Tags</h2>
<ul class="list-upgraded">
  <?php foreach ($results->tags as $tag): ?>
  <li>
    <a href="/tag/<?php echo $tag->id; ?>"><?php echo $tag->name; ?></a>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
