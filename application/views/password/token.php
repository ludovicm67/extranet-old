<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Mot de passe perdu ?</h1>
<p class="lead">Définissez ici un nouveau mot de passe pour votre compte.</p>

<form method="post">
  <div class="form-group row">
    <label for="passwordPass" class="col-sm-2 col-form-label">Mot de passe</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password" id="passwordPass" placeholder="Entrez ici votre nouveau mot de passe...">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
