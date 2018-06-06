<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <?php echo $contact->name; ?>
  <?php if (!empty($this->input->get('value'))): ?>
    <small><?php echo strip_tags($this->input->get('value')); ?></small>
    <a class="btn btn-outline-secondary" href="/contact/<?php echo $contact->id; ?>" role="button">Enlever le filtre</a>
  <?php endif; ?>
  <a class="btn btn-outline-primary" href="/contact/edit/<?php echo $contact->id; ?>" role="button">Modifier</a>
  <a class="btn btn-outline-danger" href="/contact/delete/<?php echo $contact->id; ?>" role="button">Supprimer</a>
</h1>
<p class="lead">Affichage des projets dans lequel est impliqué ce contact</p>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
