<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Overtime extends MY_AuthController
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
    $this->checkPermission('overtime', 'add');

    $page = 'selectDate';

    $getMonth = intval($this->input->get('month'));
    $getYear = intval($this->input->get('year'));
    $getUser = intval($this->input->get('user'));

    if (
      (!empty($getMonth) && empty($getYear)) ||
      (empty($getMonth) && !empty($getYear))
    ) {
      redirect('/overtime');
    }

    if (!empty($getMonth) && !empty($getYear)) {
      if ($getMonth < 1 || $getMonth > 12) {
        redirect('/overtime');
      }
      $page = 'selectUser';
    }

    if ($page == 'selectUser') {
      $this->db->where('users.id', $getUser);
      $q = $this->db->get('users');
      if ($q->num_rows() > 0) {
        $page = 'selectValue';
        $user = $q->result()[0];
      }
    }

    if ($page == 'selectValue' && isset($_POST['volume'])) {
      $page = 'submitValue';
    }

    switch ($page) {
      case 'selectUser':
        $dtStart = new DateTime("$getYear-$getMonth-1");
        $dtEnd = new DateTime($dtStart->format('Y-m-t 23:59:59'));

        $overtime = $this->db
          ->distinct()
          ->select(
            'users.id as user_id, CONCAT(users.firstname, " ", users.lastname, " (", users.mail, ")") AS full_name'
          )
          ->join('users', 'users.id = contracts.user_id')
          ->join('overtime', 'overtime.user_id = contracts.user_id', 'left')
          ->where('contracts.start_at <=', $dtEnd->format('Y-m-d H:i:s'))
          ->where(
            '(contracts.end_at > "' .
              $dtStart->format('Y-m-d H:i:s') .
              '" OR contracts.end_at IS NULL)'
          )
          ->get('contracts')
          ->result();
        $this->view('overtime/user', [
          'monthName' => $this->months[$getMonth],
          'month' => $getMonth,
          'year' => $getYear,
          'overtime' => $overtime
        ]);
        break;
      case 'selectValue':
        $dtStart = new DateTime("$getYear-$getMonth-1");
        $dtEnd = new DateTime($dtStart->format('Y-m-t 23:59:59'));

        $overtime = $this->db
          ->distinct()
          ->select(
            'users.id as user_id, overtime.volume, overtime.details, CONCAT(users.firstname, " ", users.lastname, " (", users.mail, ")") AS full_name'
          )
          ->join('users', 'users.id = contracts.user_id')
          ->join('overtime', 'overtime.user_id = contracts.user_id')
          ->where('overtime.month', $getMonth)
          ->where('overtime.year', $getYear)
          ->where('contracts.start_at <=', $dtEnd->format('Y-m-d H:i:s'))
          ->where(
            '(contracts.end_at > "' .
              $dtStart->format('Y-m-d H:i:s') .
              '" OR contracts.end_at IS NULL)'
          )
          ->get('contracts')
          ->result();

        if (empty($overtime)) {
          $users = $this->db
            ->distinct()
            ->select(
              'users.id as user_id, CONCAT(users.firstname, " ", users.lastname, " (", users.mail, ")") AS full_name'
            )
            ->join('users', 'users.id = contracts.user_id')
            ->join('overtime', 'overtime.user_id = contracts.user_id', 'left')
            ->where('users.id', $getUser)
            ->where('contracts.start_at <=', $dtEnd->format('Y-m-d H:i:s'))
            ->where(
              '(contracts.end_at > "' .
                $dtStart->format('Y-m-d H:i:s') .
                '" OR contracts.end_at IS NULL)'
            )
            ->get('contracts')
            ->result();
          if (empty($users)) {
            $this->session->set_flashdata(
              'error',
              "L'utilisateur n'a pas de contrat pour la période choisie."
            );

            redirect('/overtime');
          }
          $volume = 0;
          $full_name = $users[0]->full_name;
          $details = '';
        } else {
          $volume = $overtime[0]->volume;
          $full_name = $overtime[0]->full_name;
          $details = $overtime[0]->details;
        }

        $this->view('overtime/value', [
          'monthName' => $this->months[$getMonth],
          'month' => $getMonth,
          'year' => $getYear,
          'volume' => $volume,
          'full_name' => $full_name,
          'details' => $details
        ]);
        break;
      case 'submitValue':
        $dtStart = new DateTime("$getYear-$getMonth-1");
        $dtEnd = new DateTime($dtStart->format('Y-m-t 23:59:59'));

        $overtime = $this->db
          ->distinct()
          ->select('overtime.id AS id')
          ->join('users', 'users.id = contracts.user_id')
          ->join('overtime', 'overtime.user_id = contracts.user_id')
          ->where('overtime.month', $getMonth)
          ->where('overtime.year', $getYear)
          ->where('contracts.start_at <=', $dtEnd->format('Y-m-d H:i:s'))
          ->where(
            '(contracts.end_at > "' .
              $dtStart->format('Y-m-d H:i:s') .
              '" OR contracts.end_at IS NULL)'
          )
          ->get('contracts')
          ->result();

        $content = [
          'user_id' => $getUser,
          'month' => $getMonth,
          'year' => $getYear,
          'volume' => abs(intval($this->input->post('volume'))),
          'details' => htmlspecialchars(trim($this->input->post('details')))
        ];

        if (empty($overtime)) {
          $this->db->insert('overtime', $content);
          $newId = $this->db->insert_id();
          $content['id'] = $newId;
          $this->writeLog('insert', 'overtime', $content, $newId);
        } else {
          $id = $overtime[0]->id;
          $this->db->where('id', $id);
          $this->db->update('overtime', $content);
          $content['id'] = $id;
          $this->writeLog('update', 'overtime', $content, $id);
        }

        $this->session->set_flashdata(
          'success',
          "Modification enregistrée avec succès !"
        );

        redirect('/overtime?month=' . $getMonth . '&year=' . $getYear);
        break;
      case 'selectDate':
      default:
        $this->view('overtime/date', ['months' => $this->months]);
        break;
    }
  }

  public function delete($id)
  {
    $this->checkPermission('overtime', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('overtime');
    if ($q->num_rows() > 0) {
      $this->db->delete('overtime', ['id' => $id]);
      $this->writeLog('delete', 'overtime', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        "L'heure supplémentaire a bien été supprimée !"
      );
    } else {
      $this->session->set_flashdata(
        'error',
        "L'heure supplémentaire n'existe pas."
      );
    }
    redirect('/overtime');
  }
}
