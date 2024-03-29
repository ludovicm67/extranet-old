<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('roles', 'show');

    $this->db->order_by('name');
    $roles = $this->db->get('roles')->result();
    $this->view('roles/list', ['roles' => $roles]);
  }

  public function delete($id)
  {
    $this->checkPermission('roles', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('roles');
    if ($q->num_rows() > 0) {
      $this->db->delete('roles', ['id' => $id]);
      $this->writeLog('delete', 'roles', $q->result()[0], $id);
      $this->session->set_flashdata('success', "Le rôle a bien été supprimé !");
    } else {
      $this->session->set_flashdata('error', "Le rôle n'existe pas.");
    }
    redirect('/roles', 'refresh');
  }

  public function new()
  {
    $this->checkPermission('roles', 'add');

    if (isset($_POST['name'])) {
      $roleName = strip_tags(trim($this->input->post('name')));

      if (empty($roleName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/roles/new', 'refresh');
      }
      $this->db->where('name', $roleName);
      $q = $this->db->get('roles');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata('error', "Le rôle existe déjà !");
      } else {
        $this->db->insert('roles', ['name' => $roleName]);
        $newId = $this->db->insert_id();
        $this->writeLog(
          'insert',
          'roles',
          ['name' => $roleName, 'id' => $newId],
          $newId
        );
        $this->session->set_flashdata(
          'success',
          "Le rôle a bien été créé avec succès !"
        );
        redirect('/roles', 'refresh');
      }
    }

    $this->view('roles/new');
  }

  public function edit($id)
  {
    $this->checkPermission('roles', 'edit');

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
        $this->writeLog(
          'update',
          'roles',
          ['name' => $roleName, 'id' => $id],
          $id
        );
        $this->session->set_flashdata(
          'success',
          "Le rôle a bien été modifié avec succès !"
        );
        redirect('/roles', 'refresh');
      }
    }

    $this->view('roles/edit', ['role' => $role]);
  }
}
