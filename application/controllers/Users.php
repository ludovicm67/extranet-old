<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_AuthController
{
  public function index()
  {
    $this->db->select('*, roles.name AS role, users.id AS id');
    $this->db->order_by('users.id', 'desc');
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $users = $this->db->get('users')->result();
    $this->load->view('users/list', ['users' => $users]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() > 0) {
      $this->db->delete('users', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        "L'utilisateur a bien été supprimé !"
      );
    } else {
      $this->session->set_flashdata('error', "L'utilisateur n'existe pas.");
    }
    redirect('/users', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['mail'])) {
      if (empty(trim($this->input->post('password')))) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer un mot de passe !'
        );
        redirect('/users/new', 'refresh');
      }

      $userFirstname = strip_tags(trim($this->input->post('firstname')));
      $userLastname = strip_tags(trim($this->input->post('lastname')));
      $userPassword = password_hash(
        trim($this->input->post('password')),
        PASSWORD_DEFAULT
      );
      $userMail = strip_tags(trim($this->input->post('mail')));
      $userRole = ($this->input->post('role') == 0)
        ? null
        : $this->input->post('role');
      $userAdmin = (empty($this->input->post('is_admin'))) ? 0 : 1;

      if (empty($userMail)) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer une adresse mail !'
        );
        redirect('/users/new', 'refresh');
      }
      $this->db->where('mail', $userMail);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Un utilisateur existe déjà avec cette adresse mail !"
        );
      } else {
        $this->db->insert('users', [
          'firstname' => $userFirstname,
          'lastname' => $userLastname,
          'password' => $userPassword,
          'mail' => $userMail,
          'role_id' => $userRole,
          'is_admin' => $userAdmin
        ]);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été créé avec succès !"
        );
        redirect('/users', 'refresh');
      }
    }

    $this->db->select(['id', 'name']);
    $roles = $this->db->get('roles')->result();

    $this->load->view('users/new', ['roles' => $roles]);
  }

  public function edit($id)
  {
    // check if user exists
    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() <= 0) {
      redirect('/users', 'refresh');
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
      $userRole = ($this->input->post('role') == 0)
        ? null
        : $this->input->post('role');
      $userAdmin = (empty($this->input->post('is_admin'))) ? 0 : 1;

      if (empty($userMail)) {
        $this->session->set_flashdata(
          'error',
          'Veuillez insérer une adresse mail !'
        );
        redirect('/users/new', 'refresh');
      }

      $this->db->where('id !=', $id);
      $this->db->where('mail', $userMail);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Un utilisateur existe déjà avec cette adresse mail !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('users', [
          'firstname' => $userFirstname,
          'lastname' => $userLastname,
          'password' => $userPassword,
          'mail' => $userMail,
          'role_id' => $userRole,
          'is_admin' => $userAdmin
        ]);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été modifié avec succès !"
        );
        redirect('/users', 'refresh');
      }
    }

    $this->db->select(['id', 'name']);
    $roles = $this->db->get('roles')->result();

    $this->load->view('users/edit', ['user' => $user, 'roles' => $roles]);
  }
}
