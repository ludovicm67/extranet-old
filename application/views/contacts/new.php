<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<h1 class="mt-5">Créer un nouveau contact</h1>
<p class="lead">Entrez ici les informations concernant le contact</p>

<form method="post">
  <div class="form-group row">
    <label for="contactName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="contactName" placeholder="Nom du contact...">
    </div>
  </div>
  <div class="form-group row">
    <label for="contactType" class="col-sm-2 col-form-label">Type de contact</label>
    <div class="col-sm-10">
      <select data-tags="true" class="form-control" name="type" id="contactType">
        <option value="0">Aucun type</option>
        <?php foreach ($types as $type): ?>
        <option value="<?php echo $type->id; ?>">
          <?php echo $type->name; ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="contactMail" class="col-sm-2 col-form-label">Adresse mail</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="mail" id="contactMail" placeholder="Adresse mail du contact...">
    </div>
  </div>
  <div class="form-group row">
    <label for="contactPhone" class="col-sm-2 col-form-label">Téléphone</label>
    <div class="col-sm-10">
      <input type="tel" class="form-control" name="phone" id="contactPhone" placeholder="Téléphone du contact...">
    </div>
  </div>
  <div class="form-group row">
    <label for="contactAddress" class="col-sm-2 col-form-label">Adresse</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="address" id="contactAddress" placeholder="Adresse du contact...">
    </div>
  </div>
  <div class="form-group row">
    <label for="contactOther" class="col-sm-2 col-form-label">Autres informations</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="other" id="contactOther" placeholder="Autres informations sur le contact..."></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Créer</button>
</form>

<?php
$content = ob_get_clean();
require_once VIEWPATH . 'template.php';
