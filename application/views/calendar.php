<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">
  <a href="/calendar?year=<?php echo $prev->year; ?>&amp;month=<?php echo $prev->month; ?>" class="btn btn-dark"><i class="fas fa-arrow-left"></i></a>
  <?php echo $o->calendarTranslator->month($now->month) . ' ' . $now->year; ?>
  <a href="/calendar?year=<?php echo $next->year; ?>&amp;month=<?php echo $next->month; ?>" class="btn btn-dark"><i class="fas fa-arrow-right"></i></a>
  <a class="btn btn-outline-primary" href="/leave/new" role="button">Demande de congés</a>
  <a class="btn btn-outline-primary" href="/transports/new" role="button">Remboursement frais de transport</a>
</h1>
<p class="lead">Calendrier permettant d'afficher les congés et les demandes de remboursements de frais de transport.</p>



<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
