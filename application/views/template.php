<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<title><?php echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion'); ?></title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="stylesheet" href="/css/style.min.css">
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
              <?php if ($controller->hasPermissions('clients', 'show')): ?>
                <li class="nav-item">
                  <a class="nav-link" href="/clients">Clients</a>
                </li>
              <?php endif; ?>
              <?php if ($controller->hasPermissions('projects', 'show') || $controller->hasPermissions('tags', 'show') || $controller->hasPermissions('identifiers', 'show')): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Projets</a>
                  <div class="dropdown-menu">
                  <?php if ($controller->hasPermissions('projects', 'show')): ?>
                    <a class="dropdown-item" href="/projects">Liste</a>
                  <?php endif; ?>
                  <?php if ($controller->hasPermissions('tags', 'show')): ?>
                    <a class="dropdown-item" href="/tags">Tags</a>
                  <?php endif; ?>
                  <?php if ($controller->hasPermissions('projects', 'show')): ?>
                    <div class="dropdown-divider"></div>
                  <?php endif; ?>
                  <?php if ($controller->hasPermissions('identifiers', 'show')): ?>
                    <a class="dropdown-item" href="/identifiers">Types d'identifiants</a>
                  <?php endif; ?>
                  </div>
                </li>
              <?php endif; ?>
              <?php if ($controller->hasPermissions('contacts', 'show') || $controller->hasPermissions('types', 'show') || $controller->hasPermissions('export_contacts', 'show')): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Contacts</a>
                  <div class="dropdown-menu">
                    <?php if ($controller->hasPermissions('contacts', 'show')): ?>
                      <a class="dropdown-item" href="/contacts">Liste</a>
                    <?php endif; ?>
                    <?php if ($controller->hasPermissions('types', 'show')): ?>
                      <a class="dropdown-item" href="/types">Types</a>
                    <?php endif; ?>
                    <?php if ($controller->hasPermissions('contacts', 'show') && $controller->hasPermissions('export_contacts', 'show')): ?>
                      <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    <?php if ($controller->hasPermissions('export_contacts', 'show')): ?>
                      <a class="dropdown-item" href="/export">Exporter</a>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endif; ?>
              <?php if ($controller->hasPermissions('users', 'show') || $controller->hasPermissions('roles', 'show')): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs</a>
                  <div class="dropdown-menu">
                    <?php if ($controller->hasPermissions('users', 'show')): ?>
                      <a class="dropdown-item" href="/users">Liste</a>
                      <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    <?php if ($controller->hasPermissions('roles', 'show')): ?>
                      <a class="dropdown-item" href="/roles">Rôles</a>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endif; ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  Moi<?php echo (!empty($this->session->firstname)) ? ' (' . $this->session->firstname . ')' : ''; ?>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="/users/me">Mon compte</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/calendar">Calendrier</a>
                  <a class="dropdown-item" href="/leave">Congés</a>
                  <a class="dropdown-item" href="/expenses">Notes de frais</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/logout">Déconnexion</a>
                </div>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="/login">Connexion</a>
              </li>
            <?php endif; ?>
          </ul>
          <?php if ($this->session->logged): ?>
          <form class="form-inline d-md-none d-lg-block" action="/search" method="get">
            <input id="searcher" class="form-control mr-sm-2" type="search" name="q" placeholder="Rechercher..." aria-label="Rechercher">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
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

    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Supprimer cet élément ?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Attention, si vous confirmez la suppression, l'élément ainsi que tous les éléments associés seront supprimés de manière définitive.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <a href="" id="modalDeleteUrl" class="btn btn-danger">Confirmer la suppression</a>
          </div>
        </div>
      </div>
    </div>

    <?php echo (isset($content)) ? $content : ''; ?>
  </main>

  <!-- footer -->
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Page rendue en <strong>{elapsed_time}</strong> secondes. <?php echo (ENVIRONMENT === 'development') ? 'Version de CodeIgniter <strong>' . CI_VERSION . '</strong>' : ''; ?></p>
    </div>
  </footer>

  <script src="/js/app.min.js"></script>
</body>
</html>
