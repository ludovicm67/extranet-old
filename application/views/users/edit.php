<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Modifier un utilisateur</h1>
<p class="lead">Entrez ici les informations concernant l'utilisateur</p>

<form method="post">
  <div class="form-group row">
    <label for="userLastname" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="lastname" id="userLastname" value="<?php
                                                                                       echo htmlspecialchars(
                                                                                         $user->lastname
                                                                                       );
                                                                                       ?>" placeholder="Nom...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userFirstame" class="col-sm-2 col-form-label">Prénom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="firstname" id="userFirstame" value="<?php
                                                                                        echo htmlspecialchars(
                                                                                          $user->firstname
                                                                                        );
                                                                                        ?>" placeholder="Prénom...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userRole" class="col-sm-2 col-form-label">Rôle</label>
    <div class="col-sm-10">
      <select class="form-control" name="role" id="userRole">
        <option value="0">Aucun rôle</option>
        <?php foreach ($roles as $role): ?>
        <option value="<?php echo $role->id; ?>"<?php echo ($user->role_id === $role->id) ? ' selected="selected"' : ''; ?>>
          <?php echo $role->name; ?>
        </option>
        <?php endforeach; ?>
        <option value="-1"<?php
                          echo ($user->is_admin == 1)
                            ? ' selected="selected"'
                            : '';
                          ?>>Super administrateur</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="userMail" class="col-sm-2 col-form-label">Adresse mail</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="mail" id="userMail" value="<?php
                                                                                echo htmlspecialchars(
                                                                                  $user->email
                                                                                );
                                                                                ?>" placeholder="Adresse mail...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userPassword" class="col-sm-2 col-form-label">Mot de passe</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password" id="userPassword" placeholder="Mot de passe (laisser vide pour ne pas changer)...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userDefaultpage" class="col-sm-2 col-form-label">Page par défaut</label>
    <div class="col-sm-10">
      <select class="form-control" name="default_page" id="userDefaultpage">
        <?php foreach ($pages as $u => $p): ?>
          <option<?php echo ($user->default_page == $u) ? ' selected="selected"' : ''; ?> value="<?php echo htmlspecialchars($u); ?>"><?php echo $p; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
