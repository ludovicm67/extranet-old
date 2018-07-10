<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Expenses extends MY_AuthController
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
    $this->checkPermission('expenses', 'add');

    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $year = intval($this->input->post('year'));
      $month = intval($this->input->post('month'));
      $amount = floatval($this->input->post('amount'));
      $type = htmlspecialchars(trim($this->input->post('type')));
      $details = htmlspecialchars(trim($this->input->post('details')));
      if (empty($year) || $year == 0 || empty($month) || $month == 0) {
        $this->session->set_flashdata(
          'error',
          "Veuillez rensigner le mois et l'année !"
        );
        redirect('/expenses/new');
      }
      if ($amount < 0) {
        $this->session->set_flashdata(
          'error',
          'Le montant ne peut être négatif !'
        );
        redirect('/expenses/new');
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
          redirect('/expenses/new');
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
        'file' => $file,
        'type' => $type
      ];
      $this->db->insert('expenses', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'expenses', $content, $content['id']);

      $this->session->set_flashdata('success', 'La demande a bien été crée !');

      redirect('/expenses');
    }

    $this->view('expenses/new', ['months' => $this->months]);
  }

  public function edit($id)
  {
    // check if expense exists
    $this->db->where('id', $id);
    $q = $this->db->get('expenses');
    if ($q->num_rows() <= 0) {
      redirect('/expenses');
    }
    $expense = $q->result()[0];

    if (
      $expense->user_id != $this->session->id &&
      !$this->hasPermission('expenses', 'edit')
    ) {
      redirect('/expenses');
    }

    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $year = intval($this->input->post('year'));
      $month = intval($this->input->post('month'));
      $amount = floatval($this->input->post('amount'));
      $type = htmlspecialchars(trim($this->input->post('type')));
      $details = htmlspecialchars(trim($this->input->post('details')));
      $deleteFile = isset($_POST['delete_file']);
      if (empty($year) || $year == 0 || empty($month) || $month == 0) {
        $this->session->set_flashdata(
          'error',
          "Veuillez rensigner le mois et l'année !"
        );
        redirect('/expenses/edit/' . $id);
      }
      if ($amount < 0) {
        $this->session->set_flashdata(
          'error',
          'Le montant ne peut être négatif !'
        );
        redirect('/expenses/edit/' . $id);
      }

      $this->load->library('upload', [
        'upload_path' => ROOTPATH . 'public/uploads/',
        'allowed_types' => 'gif|jpg|png|jpeg|pdf'
      ]);

      $file = $expense->file;
      if ($deleteFile && !is_null($file)) {
        unlink(ROOTPATH . 'public' . $expense->file);
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
          redirect('/expenses/edit/' . $id);
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
          if (!$deleteFile && !is_null($expense->file)) {
            unlink(ROOTPATH . 'public' . $expense->file);
          }
          $file = str_replace(ROOTPATH . 'public', '', $newName);
        }
      }

      $accepted = 0;
      if ($this->hasPermission('request_management', 'edit')) {
        $accepted = $expense->accepted;
      }

      $content = [
        'year' => $year,
        'month' => $month,
        'amount' => $amount,
        'details' => $details,
        'file' => $file,
        'type' => $type,
        'accepted' => $accepted
      ];
      $this->db->where('id', $id);
      $this->db->update('expenses', $content);
      $content['id'] = $id;
      $this->writeLog('update', 'expenses', $content, $content['id']);

      $this->session->set_flashdata(
        'success',
        'La demande a bien été modifiée !'
      );

      redirect('/expenses');
    }

    $this->view('expenses/edit', [
      'months' => $this->months,
      'expense' => $expense
    ]);
  }

  public function accept($id)
  {
    $this->checkPermission('request_management', 'edit');

    $this->db->where('id', $id);
    $q = $this->db->get('expenses');
    if ($q->num_rows() <= 0) {
      redirect('/expenses');
    }
    $c = $q->result()[0];

    $content = ['accepted' => 1];
    $this->db->where('id', $id);
    $this->db->update('expenses', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'expenses', $content, $content['id']);
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
      $this->email->subject('[EXTRANET] Demande de remboursement de frais acceptée !');
      $this->email->message(
        "Votre demande de remboursement de frais pour la période " . $c->month . "/" . $c->year . " a bien été acceptée. Rendez-vous sur l'extranet pour plus de détails."
      );
      $this->email->send();
    }

    redirect('/expenses');
  }

  public function reject($id)
  {
    $this->checkPermission('request_management', 'edit');

    $this->db->where('id', $id);
    $q = $this->db->get('expenses');
    if ($q->num_rows() <= 0) {
      redirect('/expenses');
    }

    $content = ['accepted' => -1];
    $this->db->where('id', $id);
    $this->db->update('expenses', $content);
    $content['id'] = $id;
    $this->writeLog('update', 'expenses', $content, $content['id']);
    $this->session->set_flashdata('success', 'La demande a bien été refusée !');
    redirect('/expenses');
  }

  public function delete($id)
  {
    $this->checkPermission('expenses', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('expenses');
    if ($q->num_rows() > 0) {
      $res = $q->result()[0];
      if ($res->file) {
        unlink(ROOTPATH . 'public' . $res->file);
      }

      $this->db->delete('expenses', ['id' => $id]);
      $this->writeLog('delete', 'expenses', $res, $id);
      $this->session->set_flashdata(
        'success',
        'La demande a bien été supprimée !'
      );
    } else {
      $this->session->set_flashdata('error', "La demande n'existe pas.");
    }
    redirect('/expenses');
  }

  public function index()
  {
    if (!$this->hasPermissions('expenses', 'show')) {
      $this->db->where('users.id', $this->session->id);
    }
    $this->db->select('*, expenses.id AS id');
    $this->db->order_by(
      '(CASE expenses.accepted WHEN 0 THEN 1 WHEN 1 THEN 2 WHEN -1 THEN 3 END)',
      'asc'
    );
    $this->db->order_by('expenses.id', 'desc');
    $this->db->join('users', 'users.id = expenses.user_id', 'left');
    $content = $this->db->get('expenses')->result();
    $this->view('expenses/list', ['content' => $content]);
  }
}
