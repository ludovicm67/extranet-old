<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Leave extends MY_AuthController
{
  public function new()
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

  public function edit($id)
  {
    // check if leave exists
    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() <= 0) {
      redirect('/leave');
    }
    $leave = $q->result()[0];

    if (
      $leave->user_id != $this->session->id &&
      !$this->hasPermission('leave', 'edit')
    ) {
      redirect('/leave');
    }

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
      $deleteFile = isset($_POST['delete_file']);
      if (!$startDate || !$endDate) {
        $this->session->set_flashdata('error', 'Mauvais format de date !');
        redirect('/leave/edit/' . $id);
      }
      if ($startDate >= $endDate) {
        $this->session->set_flashdata(
          'error',
          'La date de début doit être antérieure à celle de fin.'
        );
        redirect('/leave/edit/' . $id);
      }

      $this->load->library('upload', [
        'upload_path' => ROOTPATH . 'public/uploads/',
        'allowed_types' => 'gif|jpg|png|jpeg|pdf'
      ]);

      $file = $leave->file;
      if ($deleteFile && !is_null($file)) {
        unlink(ROOTPATH . 'public' . $leave->file);
        $file = null;
      }

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
          redirect('/leave/edit/' . $id);
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
          if (!$deleteFile && !is_null($leave->file)) {
            unlink(ROOTPATH . 'public' . $leave->file);
          }
          $file = str_replace(ROOTPATH . 'public', '', $newName);
        }
      }

      $accepted = 0;
      if ($this->hasPermission('request_management', 'edit')) {
        $accepted = $leave->accepted;
      }

      $content = [
        'start' => $startDate,
        'end' => $endDate,
        'details' => $details,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'days' => $days,
        'reason' => $reason,
        'file' => $file,
        'accepted' => $accepted
      ];
      $this->db->where('id', $id);
      $this->db->update('leave', $content);
      $content['id'] = $id;
      $this->writeLog('update', 'leave', $content, $content['id']);

      $this->session->set_flashdata(
        'success',
        'La demande a bien été modifiée !'
      );

      redirect('/leave');
    }

    $this->view('leave/edit', ['leave' => $leave]);
  }

  public function accept($id)
  {
    $this->checkPermission('request_management', 'edit');

    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() <= 0) {
      redirect('/leave');
    }
    $c = $q->result()[0];

    $content = ['accepted' => 1];
    $this->db->where('id', $id);
    $this->db->update('leave', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'leave', $content, $content['id']);
    $this->session->set_flashdata(
      'success',
      'La demande a bien été acceptée !'
    );

    $this->db->where('id', $c->user_id);
    $q = $this->db->get('users');
    if ($q->num_rows() > 0) {
      $user = $q->result()[0];
      $this->load->library('email');
      $this->email->from(
        $this->db->dc->getConfValueDefault(
          'email_from',
          null,
          'noreply@example.com'
        ),
        $this->db->dc->getConfValueDefault('site_name', null, 'Gestion')
      );
      $this->email->to($user->email);
      $this->email->subject('[EXTRANET] Demande de congés acceptée !');
      $this->email->message(
        "Votre demande de congés du " . (new DateTime($c->start))->format('d/m/Y H\hi') . " au " . (new DateTime($c->end))->format('d/m/Y H\hi') . " a bien été acceptée. Rendez-vous sur l'extranet pour plus de détails."
      );
      $this->email->send();
    }

    redirect('/leave');
  }

  public function reject($id)
  {
    $this->checkPermission('request_management', 'edit');

    $this->db->where('id', $id);
    $q = $this->db->get('leave');
    if ($q->num_rows() <= 0) {
      redirect('/leave');
    }

    $content = ['accepted' => -1];
    $this->db->where('id', $id);
    $this->db->update('leave', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'leave', $content, $content['id']);
    $this->session->set_flashdata('success', 'La demande a bien été refusée !');
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
    $this->db->order_by(
      '(CASE leave.accepted WHEN 0 THEN 1 WHEN 1 THEN 2 WHEN -1 THEN 3 END)',
      'asc'
    );
    $this->db->order_by('leave.start', 'desc');
    $this->db->order_by('leave.id', 'desc');
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $content = $this->db->get('leave')->result();
    $this->view('leave/list', ['content' => $content]);
  }
}
