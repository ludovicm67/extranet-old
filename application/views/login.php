<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Connexion</h1>
<p class="lead">Pour accéder à cette ressource, il est impératif de se connecter</p>

<form method="post">
  <div class="form-group row">
    <label for="loginMail" class="col-sm-2 col-form-label">Adresse mail</label>
    <div class="col-sm-10">
      <input type="email" name="mail" class="form-control" id="loginMail" placeholder="Adresse mail">
    </div>
  </div>
  <div class="form-group row">
    <label for="loginPassword" class="col-sm-2 col-form-label">Mot de passe</label>
    <div class="col-sm-10">
      <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Mot de passe">
    </div>
  </div>
  <a href="/password/reset">Mot de passe perdu ?</a>
  <div class="form-group row">
    <div class="col-sm-10">
      <button type="submit" class="btn btn-primary">Se connecter</button>
    </div>
  </div>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
