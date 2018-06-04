<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<title>Adipso</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header>
      <!-- navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Adipso</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Accueil <span class="sr-only">(courrant)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Lien</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <!-- page content -->
    <main role="main" class="container">
      <?php echo (isset($content)) ? $content : ''; ?>
    </main>

    <!-- footer -->
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Page rendue en <strong>{elapsed_time}</strong> secondes. <?php echo (ENVIRONMENT === 'development') ? 'Version de CodeIgniter <strong>' . CI_VERSION . '</strong>' : ''; ?></p>
      </div>
    </footer>
  <script src="/js/app.js"></script>
</body>
</html>
