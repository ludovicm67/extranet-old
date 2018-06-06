<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des contacts <a class="btn btn-outline-primary" href="/contacts/new" role="button">Ajouter</a></h1>
<p class="lead">Page listant les différents contacts</p>

<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $contact): ?>
    <tr class="searcher-item" data-searcher="<?php echo strtolower(htmlspecialchars($contact->name)); ?>">
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

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
