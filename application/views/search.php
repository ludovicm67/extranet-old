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
<ul>
  <?php foreach ($results->clients as $client): ?>
  <li>
    <a href="/client/<?php echo $client->id; ?>"><?php echo $client->fullName; ?></a>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->projects)): ?>
<h2>Projets</h2>
<ul>
  <?php foreach ($results->projects as $project): ?>
  <li>
    <a href="/project/<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($results->contacts)): ?>
<h2>Contacts</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results->contacts as $contact): ?>
    <tr>
      <td><a href="/contact/<?php echo $contact->id; ?>"><?php echo $contact->name; ?></a></td>
      <td>
        <a href="/contact/edit/<?php echo $contact->id; ?>">Modifier</a>
        -
        <a href="/contact/delete/<?php echo $contact->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php if (!empty($results->users)): ?>
<h2>Utilisateurs</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Adresse mail</th>
      <th scope="col">Rôle</th>
      <th scope="col">Administrateur ?</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results->users as $user): ?>
    <tr>
      <td><a href="/user/<?php echo $user->id; ?>"><?php echo $user->firstname; ?> <?php echo $user->lastname; ?></a></td>
      <td><a href="mailto:<?php echo htmlspecialchars($user->mail); ?>"><?php echo $user->mail; ?></a></td>
      <td><?php echo ($user->role) ? $user->role : 'Aucun rôle'; ?></td>
      <td><?php echo ($user->is_admin) ? 'Oui' : 'Non'; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php if (!empty($results->tags)): ?>
<h2>Tags</h2>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results->tags as $tag): ?>
    <tr>
      <td><a href="/tag/<?php echo $tag->id; ?>"><?php echo $tag->name; ?></a></td>
      <td>
        <a href="/tag/edit/<?php echo $tag->id; ?>">Modifier</a>
        -
        <a href="/tag/delete/<?php echo $tag->id; ?>">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php endif; ?>


<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
