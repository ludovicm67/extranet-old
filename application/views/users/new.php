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
    <label for="userRole" class="col-sm-2 col-form-label">Rôle</label>
    <div class="col-sm-10">
      <select class="form-control" name="role" id="userRole">
        <option value="0">Aucun rôle</option>
        <?php foreach ($roles as $role): ?>
        <option value="<?php echo $role->id; ?>">
          <?php echo $role->name; ?>
        </option>
        <?php endforeach; ?>
        <option value="-1">Super administrateur</option>
      </select>
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
      <input type="password" class="form-control" name="password" id="userPassword" placeholder="Mot de passe...">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
