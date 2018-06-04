<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Accueil</h1>
<p class="lead">Petite page de test.</p>
<p>
  <ul>
<?php
  // var_dump(
  //   $sellsy
  //     ->Infos()
  //     ->getInfos()
  //     ->getResponse()
  // );
  // var_dump(
  //   $sellsy
  //     ->Infos()
  //     ->getInfos()
  //     ->getResponse()['consumerdatas']['id']
  // );
  // var_dump(
  //   $sellsy
  //     ->AccountPrefs()
  //     ->getCorpInfos()
  //     ->getResponse()['email']
  // );
  $clientsRequest = $sellsy
      ->Client()
      ->getList()
      ->getResponse();

  // @TODO: check for other pages
  $clients = $clientsRequest['result'];
  var_dump($clients);
?>
  <?php foreach ($clients as $client): ?>
    <li><?= $client['fullName']; ?></li>
  <?php endforeach; ?>
  </ul>
</p>


<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
