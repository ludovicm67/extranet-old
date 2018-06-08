<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends CI_Controller
{
  public function index()
  {
    $this->db->order_by('name');
    $roles = $this->db->get('roles')->result();
    $this->load->view('roles/list', ['roles' => $roles]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('roles');
    if ($q->num_rows() > 0) {
      $this->db->delete('roles', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        "Le rôle a bien été supprimé !"
      );
    } else {
      $this->session->set_flashdata(
        'error',
        "Le rôle n'existe pas."
      );
    }
    redirect('/roles', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $roleName = strip_tags(trim($this->input->post('name')));

      if (empty($roleName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/roles/new', 'refresh');
      }
      $this->db->where('name', $roleName);
      $q = $this->db->get('roles');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le rôle existe déjà !"
        );
      } else {
        $this->db->insert('roles', ['name' => $roleName]);
        $this->session->set_flashdata(
          'success',
          "Le rôle a bien été créé avec succès !"
        );
        redirect('/roles', 'refresh');
      }
    }

    $this->load->view('roles/new');
  }

  public function edit($id)
  {
    // check if role exists
    $this->db->where('id', $id);
    $q = $this->db->get('roles');
    if ($q->num_rows() <= 0) {
      redirect('/roles', 'refresh');
    }
    $role = $q->result()[0];

    if (isset($_POST['name'])) {
      $roleName = strip_tags(trim($this->input->post('name')));

      if (empty($roleName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/roles/new', 'refresh');
      }
      $this->db->where('id !=', $id);
      $this->db->where('name', $roleName);
      $q = $this->db->get('roles');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le rôle n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('roles', ['name' => $roleName]);
        $this->session->set_flashdata(
          'success',
          "Le rôle a bien été modifié avec succès !"
        );
        redirect('/roles', 'refresh');
      }
    }

    $this->load->view('roles/edit', ['role' => $role]);
  }
}
