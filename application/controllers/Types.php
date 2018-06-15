<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Types extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('types', 'show');

    $this->db->order_by('name');
    $types = $this->db->get('types')->result();
    $this->view('types/list', ['types' => $types]);
  }

  public function delete($id)
  {
    $this->checkPermission('types', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('types');
    if ($q->num_rows() > 0) {
      $this->db->delete('types', ['id' => $id]);
      $this->writeLog('delete', 'types', $q->result()[0]);
      $this->session->set_flashdata('success', 'Le type a bien été supprimé !');
    } else {
      $this->session->set_flashdata('error', "Le type n'existe pas.");
    }
    redirect('/types');
  }

  public function new()
  {
    $this->checkPermission('types', 'add');

    if (isset($_POST['name'])) {
      $typeName = strip_tags(trim($this->input->post('name')));

      if (empty($typeName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/types/new');
      }
      $this->db->where('name', $typeName);
      $q = $this->db->get('types');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata('error', 'Le type existe déjà !');
      } else {
        $this->db->insert('types', ['name' => $typeName]);
        $this->writeLog('insert', 'types', [
          'name' => $typeName,
          'id' => $this->db->insert_id()
        ]);
        $this->session->set_flashdata(
          'success',
          'Le type a bien été créé avec succès !'
        );
        redirect('/types');
      }
    }

    $this->view('types/new');
  }

  public function edit($id)
  {
    $this->checkPermission('types', 'edit');

    // check if type exists
    $this->db->where('id', $id);
    $q = $this->db->get('types');
    if ($q->num_rows() <= 0) {
      redirect('/types');
    }
    $type = $q->result()[0];

    if (isset($_POST['name'])) {
      $typeName = strip_tags(trim($this->input->post('name')));

      if (empty($typeName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/types/new');
      }
      $this->db->where('id !=', $id);
      $this->db->where('name', $typeName);
      $q = $this->db->get('types');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le type n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('types', ['name' => $typeName]);
        $this->writeLog('update', 'types', ['name' => $typeName, 'id' => $id]);
        $this->session->set_flashdata(
          'success',
          'Le type a bien été modifié avec succès !'
        );
        redirect('/types');
      }
    }

    $this->view('types/edit', ['type' => $type]);
  }
}
