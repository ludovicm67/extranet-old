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

    $this->view('calendar', [
      'now' => $now,
      'prev' => $prev,
      'next' => $next,
      'o' => (object) ['calendarTranslator' => new CalendarTranslator()]
    ]);
  }
}
