<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  Liste des remboursements de frais de transport
  <a class="btn btn-outline-primary" href="/transports/new" role="button">Faire une demande</a>
</h1>
<p class="lead">Passez en revue les demandes de remboursements de frais de transport</p>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
