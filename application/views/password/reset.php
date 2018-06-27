<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Mot de passe perdu ?</h1>
<p class="lead">Recevez par mail un lien permettant de définir un nouveau mot de passe.</p>

<form method="post">
  <div class="form-group row">
    <label for="passwordMail" class="col-sm-2 col-form-label">Adresse mail</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="mail" id="passwordMail" placeholder="Entrez ici votre adresse mail...">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
