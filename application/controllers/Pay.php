<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Pay extends MY_AuthController
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

  public function index()
  {
    $this->checkPermission('pay', 'add');

    if ($this->input->server('REQUEST_METHOD') == 'POST') {
      $userId = intval($this->input->post('user_id'));
      $year = intval($this->input->post('year'));
      $month = intval($this->input->post('month'));

      if (empty($year) || $year == 0 || empty($month) || $month == 0) {
        $this->session->set_flashdata(
          'error',
          "Veuillez rensigner le mois et l'année !"
        );
        redirect('/pay');
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
            "Le document n'a pas pu être uploadé.."
          );
          redirect('/pay');
        } else {
          $upload_data = $this->upload->data();
          $newName =
            $upload_data['file_path'] .
            $userId .
            '_pay_' . $year . '_' . $month . '_' .
            Uuid::uuid4()->toString() .
            '_' .
            base64_encode($upload_data['orig_name']) .
            $upload_data['file_ext'];
          rename($upload_data['full_path'], $newName);
          $file = str_replace(ROOTPATH . 'public', '', $newName);
        }
      }

      if (is_null($file)) {
        $this->session->set_flashdata(
          'error',
          "Veuillez uploader un document."
        );
        redirect('/pay');
      }

      $content = [
        'user_id' => $userId,
        'year' => $year,
        'month' => $month,
        'file' => $file
      ];
      $this->db->insert('pay', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'pay', $content, $content['id']);

      $this->session->set_flashdata(
        'success',
        'La fiche de paie a bien été ajoutée !'
      );
      redirect('/pay');
    }

    $users = $this->db->get('users')->result();

    $this->view('pay', [
      'months' => $this->months, 'users' => $users
    ]);
  }

  public function delete($id)
  {
    $this->checkPermission('pay', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('pay');
    if ($q->num_rows() > 0) {
      $res = $q->result()[0];
      $this->db->delete('pay', ['id' => $id]);
      $this->writeLog('delete', 'pay', $res, $id);

      $filePath = ROOTPATH . 'public' . $res->file;
      if ($res->file && file_exists($filePath)) {
        unlink($filePath);
      }

      $this->session->set_flashdata(
        'success',
        "La fiche de paie a bien été supprimée !"
      );
      redirect('/user/' . $res->user_id);
    } else {
      $this->session->set_flashdata('error', "La fiche de paie n'existe pas.");
      redirect('/users');
    }
  }
}
