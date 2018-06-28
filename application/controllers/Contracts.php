<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contracts extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('contracts', 'show');

    $this->db->select(
      'CONCAT(users.firstname, " ", users.lastname) AS full_name, users.mail, contracts.*'
    );
    $this->db->join('users', 'users.id = contracts.user_id');
    $this->db->order_by('start_at', 'desc');
    $contracts = $this->db->get('contracts')->result();
    $this->view('contracts/list', ['contracts' => $contracts]);
  }

  public function delete($id)
  {
    $this->checkPermission('contracts', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('contracts');
    if ($q->num_rows() > 0) {
      $this->db->delete('contracts', ['id' => $id]);
      $this->writeLog('delete', 'contracts', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        "Le contrat a bien été supprimé !"
      );
    } else {
      $this->session->set_flashdata('error', "Le contrat n'existe pas.");
    }
    redirect('/contracts');
  }

  public function new()
  {
    $this->checkPermission('contracts', 'add');

    if (isset($_POST['type'])) {
      $contractUser = strip_tags(trim($this->input->post('user_id')));
      $contractType = strip_tags(trim($this->input->post('type')));
      $contractStart = strip_tags(trim($this->input->post('start_at')));
      $contractEnd = strip_tags(trim($this->input->post('end_at')));
      $contractDays = strip_tags(trim($this->input->post('days')));

      if (empty($contractEnd)) {
        $contractEnd = null;
      }

      $content = [
        'user_id' => $contractUser,
        'type' => $contractType,
        'start_at' => $contractStart,
        'end_at' => $contractEnd,
        'days' => $contractDays
      ];
      $this->db->insert('contracts', $content);
      $newId = $this->db->insert_id();

      $content['id'] = $newId;
      $this->writeLog('insert', 'contracts', $content, $newId);
      $this->session->set_flashdata(
        'success',
        "Le contrat a bien été créé avec succès !"
      );
      redirect('/contracts');
    }

    $users = $this->db->get('users')->result();
    $this->view('contracts/new', ['users' => $users]);
  }

  public function edit($id)
  {
    $this->checkPermission('contracts', 'edit');

    // check if contract exists
    $this->db->where('id', $id);
    $q = $this->db->get('contracts');
    if ($q->num_rows() <= 0) {
      redirect('/contracts');
    }
    $contract = $q->result()[0];

    if (isset($_POST['type'])) {
      $contractUser = strip_tags(trim($this->input->post('user_id')));
      $contractType = strip_tags(trim($this->input->post('type')));
      $contractStart = strip_tags(trim($this->input->post('start_at')));
      $contractEnd = strip_tags(trim($this->input->post('end_at')));
      $contractDays = strip_tags(trim($this->input->post('days')));

      if (empty($contractEnd)) {
        $contractEnd = null;
      }

      $content = [
        'user_id' => $contractUser,
        'type' => $contractType,
        'start_at' => $contractStart,
        'end_at' => $contractEnd,
        'days' => $contractDays
      ];
      $this->db->where('id', $id);
      $this->db->update('contracts', $content);

      $content['id'] = $id;
      $this->writeLog('update', 'contracts', $content, $id);
      $this->session->set_flashdata(
        'success',
        "Le contrat a bien été modifié avec succès !"
      );
      redirect('/contracts');
    }

    $users = $this->db->get('users')->result();
    $this->view('contracts/edit', ['contract' => $contract, 'users' => $users]);
  }
}
