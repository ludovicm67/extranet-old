<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<title><?php echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion'); ?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header>
    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container">
        <a class="navbar-brand" href="/"><?php echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion'); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <?php if ($this->session->logged): ?>
            <li class="nav-item">
              <a class="nav-link" href="/clients">Clients</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Projets</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="/projects">Liste</a>
                <a class="dropdown-item" href="/tags">Tags</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/identifiers">Types d'identifiants</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Contacts</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="/contacts">Liste</a>
                <a class="dropdown-item" href="/types">Types</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/export">Exporter</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="/users">Liste</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/roles">Rôles</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Déconnexion</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">Connexion</a>
            </li>
            <?php endif; ?>
          </ul>
          <?php if ($this->session->logged): ?>
          <form class="form-inline" action="/search" method="get">
            <input id="searcher" class="form-control mr-sm-2" type="search" name="q" placeholder="Rechercher..." aria-label="Rechercher">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Rechercher</button>
          </form>
          <?php endif; ?>
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

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
  <script src="/js/app.js"></script>
</body>
</html>
