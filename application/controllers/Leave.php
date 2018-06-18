<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leave extends MY_AuthController
{
  public function new()
  {
    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $startDate = date('Y-m-d', strtotime($this->input->post('start')));
      $endDate = date('Y-m-d', strtotime($this->input->post('end')));
      $details = htmlspecialchars(trim($this->input->post('details')));
      if (!$startDate || !$endDate) {
        $this->session->set_flashdata('error', 'Mauvais format de dates !');
        redirect('/leave/new');
      }
      if ($startDate > $endDate) {
        $this->session->set_flashdata(
          'error',
          'La date de début doit être antérieure à celle de fin.'
        );
        redirect('/leave/new');
      }

      $content = [
        'user_id' => $this->session->id,
        'start' => $startDate,
        'end' => $endDate,
        'details' => $details
      ];
      $this->db->insert('leave', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'leave', $content, $content['id']);

      $this->session->set_flashdata('success', 'La demande a bien été crée !');

      redirect('/leave');
    }

    $this->view('leave/new');
  }

  public function accept($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() <= 0) {
      redirect('/leave');
    }

    $content = ['accepted' => 1];
    $this->db->where('id', $id);
    $this->db->update('leave', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'leave', $content, $content['id']);
    $this->session->set_flashdata(
      'success',
      'La demande a bien été acceptée !'
    );
    redirect('/leave');
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() > 0) {
      $this->db->delete('leave', ['id' => $id]);
      $this->writeLog('delete', 'leave', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        'La demande a bien été supprimée !'
      );
    } else {
      $this->session->set_flashdata('error', "La demande n'existe pas.");
    }
    redirect('/leave');
  }

  public function index()
  {
    $this->db->select('*, leave.id AS id');
    $this->db->order_by('leave.accepted', 'asc');
    $this->db->order_by('leave.id', 'desc');
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $content = $this->db->get('leave')->result();
    $this->view('leave/list', ['content' => $content]);
  }
}
