<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
var_dump($lines);
 ?>

<h1 class="mt-5">Modifications d'un pdf</h1>
<p class="lead">Éditez un pdf pour effectuer quelques ajustement</p>

<form method="post">
  <div class="form-group row">
    <label for="pdfName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="pdfName" value="<?php echo $name; ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="pdfPeriod" class="col-sm-2 col-form-label">Période</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="period" id="pdfPeriod" value="<?php echo $period; ?>" required>
    </div>
  </div>

<?php foreach ($lines as $line): ?>

<?php endforeach; ?>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
