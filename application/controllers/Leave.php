<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Leave extends MY_AuthController
{
  function new()
  {
    $this->checkPermission('leave', 'add');

    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $startTime = intval($this->input->post('start_time'));
      $endTime = intval($this->input->post('end_time'));
      $startDate = date(
        'Y-m-d H:i:s',
        strtotime($this->input->post('start') . ' ' . $startTime . ':00:00')
      );
      $endDate = date(
        'Y-m-d H:i:s',
        strtotime($this->input->post('end') . ' ' . $endTime . ':00:00')
      );

      $days = floatval($this->input->post('days'));
      if ($days < 0) {
        $days *= -1;
      }

      // reason
      switch ($this->input->post('reason')) {
        case 'leave':
          $reason = 'Congé';
          break;
        case 'disease':
          $reason = 'Maladie';
          break;
        default:
          $reason = 'Autre';
          break;
      }

      $details = htmlspecialchars(trim($this->input->post('details')));
      if (!$startDate || !$endDate) {
        $this->session->set_flashdata('error', 'Mauvais format de date !');
        redirect('/leave/new');
      }
      if ($startDate >= $endDate) {
        $this->session->set_flashdata(
          'error',
          'La date de début doit être antérieure à celle de fin.'
        );
        redirect('/leave/new');
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
          redirect('/leave/new');
        } else {
          $upload_data = $this->upload->data();
          $newName =
            $upload_data['file_path'] .
            $this->session->id .
            '_leave_' .
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
        'start' => $startDate,
        'end' => $endDate,
        'details' => $details,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'days' => $days,
        'reason' => $reason,
        'file' => $file
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
    $this->checkPermission('leave', 'edit');

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
    $this->checkPermission('leave', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() > 0) {
      $res = $q->result()[0];
      if ($res->file) {
        unlink(ROOTPATH . 'public' . $res->file);
      }

      $this->db->delete('leave', ['id' => $id]);
      $this->writeLog('delete', 'leave', $res, $id);
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
    if (!$this->hasPermissions('leave', 'show')) {
      $this->db->where('leave.user_id', $this->session->id);
    }
    $this->db->select('*, leave.id AS id');
    $this->db->order_by('leave.accepted', 'asc');
    $this->db->order_by('leave.id', 'desc');
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $content = $this->db->get('leave')->result();
    $this->view('leave/list', ['content' => $content]);
  }
}
