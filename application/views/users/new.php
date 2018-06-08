<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Créer un nouvel utilisateur</h1>
<p class="lead">Entrez ici les informations concernant l'utilisateur</p>

<form method="post">
  <div class="form-group row">
    <label for="userLastname" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="lastname" id="userLastname" placeholder="Nom...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userFirstame" class="col-sm-2 col-form-label">Prénom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="firstname" id="userFirstame" placeholder="Prénom...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userMail" class="col-sm-2 col-form-label">Adresse mail</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="mail" id="userMail" placeholder="Adresse mail...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userPassword" class="col-sm-2 col-form-label">Mot de passe</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="mail" id="userPassword" placeholder="Mot de passe...">
    </div>
  </div>
  <div class="form-group row">
    <span class="col-sm-2 col-form-label">Administrateur</span>
    <div class="col-sm-10">
      <label for="userIsAdmin" value="1">
        <input type="checkbox" name="is_admin" id="userIsAdmin">
        Marquer cet utilisateur comme étant un super-adinistrateur ?
      </label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
