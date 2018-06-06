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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header>
    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container">
        <a class="navbar-brand" href="/">Adipso</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="/clients">Clients</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/projects">Projets</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/tags">Tags</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- page content -->
  <main role="main" class="container">

    <?php if (!empty($this->session->flashdata('success'))): ?>
    <div class="alert alert-success" role="alert">
      <?php echo $this->session->flashdata('success'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>

    <?php if (!empty($this->session->flashdata('error'))): ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $this->session->flashdata('error'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>


    <?php echo (isset($content)) ? $content : ''; ?>
  </main>

  <!-- footer -->
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Page rendue en <strong>{elapsed_time}</strong> secondes. <?php echo (ENVIRONMENT === 'development') ? 'Version de CodeIgniter <strong>' . CI_VERSION . '</strong>' : ''; ?></p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
  <script src="/js/app.js"></script>
</body>
</html>
