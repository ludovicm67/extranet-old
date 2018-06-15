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

  public function index()
  {
    $this->view('leave/list');
  }
}
