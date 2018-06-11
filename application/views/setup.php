<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Initialisation</h1>
<p class="lead">Aucun utilisateur pour le moment; créons-en un !</p>

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
    <label for="userMail" class="col-sm-2 col-form-label">Adresse mail (requis)</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="mail" id="userMail" placeholder="Adresse mail...">
    </div>
  </div>
  <div class="form-group row">
    <label for="userPassword" class="col-sm-2 col-form-label">Mot de passe (requis)</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password" id="userPassword" placeholder="Mot de passe...">
    </div>
  </div>
  <input type="hidden" name="role" value="0">
  <input type="hidden" name="is_admin" value="1">
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php if ($js_exec_request): ?>
<script>
fetch('<?php echo htmlspecialchars($js_request_url); ?>', {
  credentials: 'omit'
});
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
