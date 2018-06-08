<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_AuthController
{
  public function index()
  {
    $this->db->order_by('id', 'desc');
    $users = $this->db->get('users')->result();
    $this->load->view('users/list', ['users' => $users]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('users');
    if ($q->num_rows() > 0) {
      $this->db->delete('users', ['id' => $id]);
      $this->session->set_flashdata('success', "L'utilisateur a bien été supprimé !");
    } else {
      $this->session->set_flashdata('error', "L'utilisateur n'existe pas.");
    }
    redirect('/users', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $userName = strip_tags(trim($this->input->post('name')));

      if (empty($userName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/users/new', 'refresh');
      }
      $this->db->where('name', $userName);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata('error', "L'utilisateur existe déjà !");
      } else {
        $this->db->insert('users', ['name' => $userName]);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été créé avec succès !"
        );
        redirect('/users', 'refresh');
      }
    }

    $this->load->view('users/new');
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

    if (isset($_POST['name'])) {
      $userName = strip_tags(trim($this->input->post('name')));

      if (empty($userName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/users/new', 'refresh');
      }
      $this->db->where('id !=', $id);
      $this->db->where('name', $userName);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "L'utilisateur n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('users', ['name' => $userName]);
        $this->session->set_flashdata(
          'success',
          "L'utilisateur a bien été modifié avec succès !"
        );
        redirect('/users', 'refresh');
      }
    }

    $this->load->view('users/edit', ['user' => $user]);
  }
}
