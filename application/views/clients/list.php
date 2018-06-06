<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Liste des clients</h1>
<p class="lead">Page listant les différents clients</p>

<ul>
  <?php foreach ($clients as $client): ?>
  <li>
    <a href="/client/<?php echo $client->id; ?>"><?php echo $client->fullName; ?></a>
  </li>
<?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
