<?php
defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
 ?>

<h1 class="mt-5">Modifier un tag</h1>
<p class="lead">Modifiez ici les informations concernant le tag</p>

<form method="post">
  <div class="form-group row">
    <label for="tagName" class="col-sm-2 col-form-label">Nom</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="tagName" value="<?php
                                                                              echo htmlspecialchars(
                                                                                $tag->name
                                                                              );
                                                                              ?>" placeholder="Nom du tag...">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
</form>

<?php $content = ob_get_clean();
require_once VIEWPATH . 'template.php';
