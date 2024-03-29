<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_AuthController
{
  private $months = [
    1 => 'Janvier',
    2 => 'Février',
    3 => 'Mars',
    4 => 'Avril',
    5 => 'Mai',
    6 => 'Juin',
    7 => 'Juillet',
    8 => 'Août',
    9 => 'Septembre',
    10 => 'Octobre',
    11 => 'Novembre',
    12 => 'Décembre'
  ];

  private $pages = [
    '/' => "Page d'accueil",
    '/clients' => 'Clients',
    '/projects' => 'Projets',
    '/contacts' => 'Interlocuteurs',
    '/users' => 'Utilisateurs',
    '/roles' => 'Rôles',
    '/calendar' => 'Calendrier',
    '/calendar?me=1' => 'Mon calendrier',
    '/leave' => 'Congés',
    '/expenses' => 'Notes de frais',
    '/contracts' => 'Contrats'
  ];

  public function index()
  {
    $this->checkPermission('users', 'show');

    $this->db->select('*, roles.name AS role, users.id AS id');
    $this->db->order_by('users.id', 'desc');
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $users = $this->db->get('users')->result();
    $this->view('users/list', ['users' => $users]);
  }

  public function show($id)
  {
    $this->checkPermission('users', 'show');

    $this->db->select('*, roles.name AS role, users.id AS id');
    $this->db->order_by('users.id', 'desc');
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $this->db->where('users.id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() <= 0) {
      redirect('/users');
    }
    $user = $q->result()[0];

    $user->projects = [];
    if ($this->hasPermission('projects', 'show')) {
      $this->db->select('*');
      $this->db->from('project_users');
      $this->db->join('projects', 'projects.id = project_users.project_id');
      $this->db->where('user_id', $user->id);
      $user->projects = $this->db->get()->result();
    }

    $pay = [];
    if ($id == $this->session->id || $this->hasPermission('pay', 'show')) {
      $this->db->where('user_id', $id);
      $this->db->order_by('year', 'desc');
      $this->db->order_by('month', 'desc');
      $this->db->order_by('id', 'desc');
      $pay = $this->db->get('pay')->result();
    }

    $this->view('users/show', [
      'user' => $user,
      'pay' => $pay,
      'months' => $this->months
    ]);
  }

  public function delete($id)
  {
    $this->checkPermission('users', 'delete');

    // disallow user to delete himself
    if ($this->session->id == $id) {
      $this->session->set_flashdata(
        'error',
        'Vous ne pouvez pas supprimer votre compte vous-même'
      );
      redirect('/users');
    }

    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() > 0) {
      $this->db->delete('users', ['id' => $id]);
      $this->writeLog('delete', 'users', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        "L'utilisateur a bien été supprimé !"
      );
    } else {
      $this->session->set_flashdata('error', "L'utilisateur n'existe pas.");
    }
    redirect('/users');
  }

  public function new()
  {
    $this->checkPermission('users', 'add');

    if (isset($_POST['mail'])) {
      if (empty(trim($this->input->post('password')))) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer un mot de passe !'
        );
        redirect('/users/new');
      }

      $role = $this->input->post('role');

      $userFirstname = strip_tags(trim($this->input->post('firstname')));
      $userLastname = strip_tags(trim($this->input->post('lastname')));
      $userPassword = password_hash(
        trim($this->input->post('password')),
        PASSWORD_DEFAULT
      );
      $userMail = strip_tags(trim($this->input->post('mail')));
      $userRole = ($role <= 0) ? null : intval($role);
      $userAdmin = ($role == -1) ? 1 : 0;
      $userDefaultpage = strip_tags(trim($this->input->post('default_page')));
      if (empty($userDefaultpage)) {
        $userDefaultpage = '/';
      }

      if (empty($userMail)) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer une adresse mail !'
        );
        redirect('/users/new');
      }
      $this->db->where('email', $userMail);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Un utilisateur existe déjà avec cette adresse mail !"
        );
      } else {
        $content = [
          'firstname' => $userFirstname,
          'lastname' => $userLastname,
          'password' => $userPassword,
          'email' => $userMail,
          'role_id' => $userRole,
          'is_admin' => $userAdmin,
          'default_page' => $userDefaultpage
        ];
        $this->db->insert('users', $content);
        $content['id'] = $this->db->insert_id();
        $this->writeLog('insert', 'users', $content, $content['id']);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été créé avec succès !"
        );
        redirect('/users');
      }
    }

    $this->db->select(['id', 'name']);
    $roles = $this->db->get('roles')->result();

    $this->view('users/new', ['roles' => $roles, 'pages' => $this->pages]);
  }

  public function edit($id)
  {
    $this->checkPermission('users', 'edit');

    // check if user exists
    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() <= 0) {
      redirect('/users');
    }
    $user = $q->result()[0];

    // if form was submitted
    if (isset($_POST['mail'])) {
      if (empty(trim($this->input->post('password')))) {
        $userPassword = $user->password;
      } else {
        $userPassword = password_hash(
          trim($this->input->post('password')),
          PASSWORD_DEFAULT
        );
      }

      $role = $this->input->post('role');

      $userFirstname = strip_tags(trim($this->input->post('firstname')));
      $userLastname = strip_tags(trim($this->input->post('lastname')));
      $userMail = strip_tags(trim($this->input->post('mail')));
      $userRole = ($role <= 0) ? null : intval($role);
      $userAdmin = ($role == -1) ? 1 : 0;
      $userDefaultpage = strip_tags(trim($this->input->post('default_page')));
      if (empty($userDefaultpage)) {
        $userDefaultpage = '/';
      }

      if ($this->session->id == $id) {
        $userAdminMe = ($this->session->is_admin) ? 1 : 0;
        if ($user->is_admin == 1 && $userAdmin == 0) {
          $this->session->set_flashdata(
            'error',
            'Vous ne pouvez pas vous retirer les droits administrateurs vous-même'
          );
          $userAdmin = $user->is_admin;
        } else {
          $this->session->set_userdata('is_admin', $userAdmin);
        }
      }

      if (empty($userMail)) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer une adresse mail !'
        );
        redirect('/users/new');
      }

      $this->db->where('id !=', $id);
      $this->db->where('email', $userMail);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Un utilisateur existe déjà avec cette adresse mail !"
        );
      } else {
        $content = [
          'firstname' => $userFirstname,
          'lastname' => $userLastname,
          'password' => $userPassword,
          'email' => $userMail,
          'role_id' => $userRole,
          'is_admin' => $userAdmin,
          'default_page' => $userDefaultpage
        ];
        $this->db->where('id', $id);
        $this->db->update('users', $content);
        $content['id'] = $id;
        $this->writeLog('update', 'users', $content, $id);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été modifié avec succès !"
        );
        redirect('/user/' . $id);
      }
    }

    $this->db->select(['id', 'name']);
    $roles = $this->db->get('roles')->result();

    $this->view('users/edit', [
      'user' => $user,
      'roles' => $roles,
      'pages' => $this->pages
    ]);
  }

  public function me()
  {
    $id = $this->session->id;

    // check if user exists
    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() <= 0) {
      redirect('/users');
    }
    $user = $q->result()[0];

    // if form was submitted
    if (isset($_POST['mail'])) {
      if (empty(trim($this->input->post('password')))) {
        $userPassword = $user->password;
      } else {
        $userPassword = password_hash(
          trim($this->input->post('password')),
          PASSWORD_DEFAULT
        );
      }

      $userFirstname = strip_tags(trim($this->input->post('firstname')));
      $userLastname = strip_tags(trim($this->input->post('lastname')));
      $userMail = strip_tags(trim($this->input->post('mail')));
      $userDefaultpage = strip_tags(trim($this->input->post('default_page')));
      if (empty($userDefaultpage)) {
        $userDefaultpage = '/';
      }

      if (empty($userMail)) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer une adresse mail !'
        );
        redirect('/users/new');
      }

      $this->db->where('id !=', $id);
      $this->db->where('email', $userMail);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Un utilisateur existe déjà avec cette adresse mail !"
        );
      } else {
        $content = [
          'firstname' => $userFirstname,
          'lastname' => $userLastname,
          'password' => $userPassword,
          'email' => $userMail,
          'default_page' => $userDefaultpage
        ];
        $this->db->where('id', $id);
        $this->db->update('users', $content);
        $content['id'] = $id;
        $this->writeLog('update', 'users', $content, $id);
        $this->session->set_flashdata(
          'success',
          "Votre compte utilisateur a bien été modifié avec succès !"
        );
        redirect('/');
      }
    }

    $this->db->where('user_id', $id);
    $this->db->order_by('leave.id', 'desc');
    $leave = $this->db->get('leave')->result();

    $this->db->where('user_id', $id);
    $this->db->order_by('expenses.id', 'desc');
    $expenses = $this->db->get('expenses')->result();

    $this->db->where('user_id', $id);
    $this->db->order_by('year', 'desc');
    $this->db->order_by('month', 'desc');
    $this->db->order_by('id', 'desc');
    $pay = $this->db->get('pay')->result();

    $this->view('users/me', [
      'user' => $user,
      'pages' => $this->pages,
      'pay' => $pay,
      'months' => $this->months
    ]);
  }
}
