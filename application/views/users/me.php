<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">
  Modifier mes informations
  <a class="btn btn-outline-primary" href="/calendar?me=1" role="button">Mon calendrier</a>
  <?php if ($controller->hasPermission('leave', 'add')): ?>
    <a class="btn btn-outline-primary" href="/leave/new" role="button">Demande de congés</a>
  <?php endif; ?>
  <?php if ($controller->hasPermission('expenses', 'add')): ?>
    <a class="btn btn-outline-primary" href="/expenses/new" role="button">Nouvelle note de frais</a>
  <?php endif; ?>
</h1>
<p class="lead">Modifiez ici les informations concernant votre compte utilisateur</p>

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

<?php if (!empty($pay)): ?>
<h2>Mes fiches de paie</h2>
<?php if (!empty($this->db->dc->getConfValueDefault('password', 'pay'))) : ?>
<p>
  <small>
    Le mot de passe pour lire les pdf est le suivant :
    <code><?php echo $this->db->dc->getConfValueDefault('password', 'pay'); ?></code>
  </small>
</p>
<?php endif; ?>
<ul class="list-upgraded">
  <?php foreach ($pay as $p): ?>
  <li>
    <a href="<?php echo htmlspecialchars($p->file); ?>" target="_blank">
      <?php echo $months[$p->month]; ?> <?php echo $p->year; ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
