<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Créer un nouveau type de contact</h1>
<p class="lead">Entrez ici les informations concernant le type de contact</p>

<form method="post">
  <div class="form-group row">
    <label for="typeName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="typeName" placeholder="Nom du type...">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
