<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;

class Ics extends MY_Controller
{
  public function index()
  {
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="cal.ics"');

    $this->db->select('*, leave.id AS id');
    $this->db->order_by('leave.accepted', 'asc');
    $this->db->order_by('leave.id', 'desc');
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $leaves = $this->db->get('leave')->result();

    $calName = $this->db->dc->getConfValueDefault('site_name', null, 'Gestion');
    $vCalendar = new Calendar($calName);
    $vCalendar->setName($calName);
    $vCalendar->setPublishedTTL('PT1H');

    foreach ($leaves as $leave) {
      $flags = [];
      if ($leave->accepted != 1) {
        $flags[] = '?';
      }
      if (empty($flags)) {
        $flags = '';
      } else {
        $flags = '[' . implode('][', $flags) . '] ';
      }

      $vEvent = (new Event())
        ->setDtStart(new \DateTime($leave->start))
        ->setDtEnd(new \DateTime($leave->end))
        ->setSummary(
          $flags .
            $leave->reason .
            ' de ' .
            $leave->firstname .
            ' ' .
            $leave->lastname
        );
      $vCalendar->addComponent($vEvent);
    }

    echo $vCalendar->render();
  }
}
