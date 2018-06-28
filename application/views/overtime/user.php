<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Sélectionnez une personne</h1>
<p class="lead">Choisissez une personne à qui ajouter des heures supplémentaires pour le mois de <?php
                                                                                                 echo $monthName;
                                                                                                 ?> <?php
                                                                                                    echo $year;
                                                                                                    ?></p>

<ul class="list-upgraded">
  <?php foreach ($overtime as $o): ?>
    <li>
      <a href="/overtime?month=<?php echo $month; ?>&amp;year=<?php echo $year; ?>&amp;user=<?php echo $o->user_id; ?>">
        <?php echo $o->full_name; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>


<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
