<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CalendarTranslator
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

  public function month($month = 1)
  {
    $m = intval($month);
    if ($m < 1) {
      $m = 1;
    } elseif ($m > 12) {
      $m = 12;
    }
    return $this->months[$m];
  }
}

class Calendar extends MY_AuthController
{
  public function index()
  {
    $getYear = intval($this->input->get('year'));
    $getMonth = intval($this->input->get('month'));
    $nowYear = !empty($getYear) ? $getYear : date('Y');
    $nowMonth = !empty($getMonth) ? $getMonth : date('n');

    $now = (object) ['year' => $nowYear, 'month' => $nowMonth];
    $prev = (object) [
      'year' => ($nowMonth == 1) ? $nowYear - 1 : $nowYear,
      'month' => ($nowMonth == 1) ? 12 : $nowMonth - 1
    ];
    $next = (object) [
      'year' => ($nowMonth == 12) ? $nowYear + 1 : $nowYear,
      'month' => ($nowMonth == 12) ? 1 : $nowMonth + 1
    ];

    $dtStart = new DateTime("$nowYear-$nowMonth-1");
    $dtEnd = new DateTime($dtStart->format('Y-m-t 23:59:59'));

    if (!$this->hasPermissions('leave', 'show')) {
      $this->db->where('users.id', $this->session->id);
    }
    $this->db->select('*, leave.id AS id');
    $this->db->order_by(
      '(CASE leave.accepted WHEN 0 THEN 1 WHEN 1 THEN 2 WHEN -1 THEN 3 END)',
      'asc'
    );
    $this->db->order_by('leave.id', 'desc');
    $this->db->where('start <=', $dtEnd->format('Y-m-d H:i:s'));
    $this->db->where('end >=', $dtStart->format('Y-m-d H:i:s'));
    if ($this->input->get('me') == 1) {
      $this->db->where('user_id', $this->session->id);
    }
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $leave = $this->db->get('leave')->result();

    if (!$this->hasPermissions('expenses', 'show')) {
      $this->db->where('users.id', $this->session->id);
    }
    $this->db->select('*, expenses.id AS id');
    $this->db->where('month', $nowMonth);
    $this->db->where('year', $nowYear);
    if ($this->input->get('me') == 1) {
      $this->db->where('user_id', $this->session->id);
    }
    $this->db->order_by(
      '(CASE expenses.accepted WHEN 0 THEN 1 WHEN 1 THEN 2 WHEN -1 THEN 3 END)',
      'asc'
    );
    $this->db->order_by('expenses.id', 'desc');
    $this->db->join('users', 'users.id = expenses.user_id', 'left');
    $expenses = $this->db->get('expenses')->result();

    $this->view('calendar', [
      'now' => $now,
      'prev' => $prev,
      'next' => $next,
      'leave' => $leave,
      'expenses' => $expenses,
      'o' => (object) ['calendarTranslator' => new CalendarTranslator()]
    ]);
  }
}
