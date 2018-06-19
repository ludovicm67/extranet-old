<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Transports extends MY_AuthController
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

  public function new()
  {
    $this->checkPermission('transports', 'add');

    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $year = intval($this->input->post('year'));
      $month = intval($this->input->post('month'));
      $amount = floatval($this->input->post('amount'));
      $details = htmlspecialchars(trim($this->input->post('details')));
      if (empty($year) || $year == 0 || empty($month) || $month == 0) {
        $this->session->set_flashdata(
          'error',
          "Veuillez rensigner le mois et l'année !"
        );
        redirect('/transports/new');
      }
      if ($amount < 0) {
        $this->session->set_flashdata(
          'error',
          'Le montant ne peut être négatif !'
        );
        redirect('/transports/new');
      }

      $this->load->library('upload', [
        'upload_path' => ROOTPATH . 'public/uploads/',
        'allowed_types' => 'gif|jpg|png|jpeg|pdf'
      ]);

      $file = null;
      if (
        isset($_FILES['file']) &&
        !empty($_FILES['file']) &&
        !empty($_FILES['file']['name'])
      ) {
        if (!$this->upload->do_upload('file')) {
          $this->session->set_flashdata(
            'error',
            "Le justificatif n'a pas pu être uploadé.."
          );
          redirect('/transports/new');
        } else {
          $upload_data = $this->upload->data();
          $newName =
            $upload_data['file_path'] .
            $this->session->id .
            '_' .
            Uuid::uuid4()->toString() .
            '_' .
            base64_encode($upload_data['orig_name']) .
            $upload_data['file_ext'];
          rename($upload_data['full_path'], $newName);
          $file = str_replace(ROOTPATH . 'public', '', $newName);
        }
      }

      $content = [
        'user_id' => $this->session->id,
        'year' => $year,
        'month' => $month,
        'amount' => $amount,
        'details' => $details,
        'file' => $file
      ];
      $this->db->insert('transports', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'transports', $content, $content['id']);

      $this->session->set_flashdata('success', 'La demande a bien été crée !');

      redirect('/transports');
    }

    $this->view('transports/new', ['months' => $this->months]);
  }

  public function accept($id)
  {
    $this->checkPermission('transports', 'edit');

    $this->db->where('id', $id);
    $q = $this->db->get('transports');
    if ($q->num_rows() <= 0) {
      redirect('/transports');
    }

    $content = ['accepted' => 1];
    $this->db->where('id', $id);
    $this->db->update('transports', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'transports', $content, $content['id']);
    $this->session->set_flashdata(
      'success',
      'La demande a bien été acceptée !'
    );
    redirect('/transports');
  }

  public function delete($id)
  {
    $this->checkPermission('transports', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('transports');
    if ($q->num_rows() > 0) {
      $this->db->delete('transports', ['id' => $id]);
      $this->writeLog('delete', 'transports', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        'La demande a bien été supprimée !'
      );
    } else {
      $this->session->set_flashdata('error', "La demande n'existe pas.");
    }
    redirect('/transports');
  }

  public function index()
  {
    if (!$this->hasPermissions('transports', 'show')) {
      $this->db->where('users.id', $this->session->id);
    }
    $this->db->select('*, transports.id AS id');
    $this->db->order_by('transports.accepted', 'asc');
    $this->db->order_by('transports.id', 'desc');
    $this->db->join('users', 'users.id = transports.user_id', 'left');
    $content = $this->db->get('transports')->result();
    $this->view('transports/list', ['content' => $content]);
  }
}
