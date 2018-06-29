<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\Guzzle;
use Dompdf\Dompdf;

defined('BASEPATH') or exit('No direct script access allowed');



class DateObject {
  private $date;

  public function __construct($date = null) {
    $this->date = $this->initDate($date);
  }

  private function initDate($date) {
    if (is_null($date)) {
      return new DateTime();
    }
    if (is_string($date)) {
      return new DateTime($date);
    }
    if (is_object($date)) {
      return $date;
    }
    return new DateTime();
  }

  public function format() {
    return $this->date->format('Y-m-d');
  }

  public function allDaysTo($date) {
    $otherDate = $this->initDate($date);

    $period = new DatePeriod(
      new DateTime($this->format()),
      new DateInterval('P1D'),
      new DateTime($otherDate->format('Y-m-d') . ' 23:59:59')
    );

    $r = array_map(function ($d) {
      return new self($d);
    }, iterator_to_array($period));
    return $r;
  }

  public function isWeekDay() {
    return !$this->isWeekEnd();
  }

  public function isWeekEnd() {
    return $this->date->format('N') >= 6;
  }

  public function isHoliday() {
    $year = intval($this->date->format('Y'));
    $month = intval($this->date->format('m'));
    $day = intval($this->date->format('d'));

    $formatted = $this->format();

    // easter
    $base = new DateTime("$year-03-21");
    $days = easter_days($year);
    $paques = $base->add(new DateInterval("P{$days}D"));
    $easter = $paques->format('Y-m-d');

    // days based on easter
    $vendrediSaint = (new DateTime($easter))->sub(new DateInterval("P2D"));
    $lundiPaques = (new DateTime($easter))->add(new DateInterval("P1D"));
    $ascension = (new DateTime($easter))->add(new DateInterval("P39D"));
    $pentecote = (new DateTime($easter))->add(new DateInterval("P49D"));
    $lundiPentecote = (new DateTime($easter))->add(new DateInterval("P50D"));

    if ($paques->format('Y-m-d') == $formatted) {
      return true;
    }

    if ($vendrediSaint->format('Y-m-d') == $formatted) {
      return true;
    }

    if ($lundiPaques->format('Y-m-d') == $formatted) {
      return true;
    }

    if ($ascension->format('Y-m-d') == $formatted) {
      return true;
    }

    if ($pentecote->format('Y-m-d') == $formatted) {
      return true;
    }

    if ($lundiPentecote->format('Y-m-d') == $formatted) {
      return true;
    }


    // Nouvel an
    if ($month == 1 && $day == 1) {
      return true;
    }

    // Fête du travail
    if ($month == 5 && $day == 1) {
      return true;
    }

    // Victoire des alliés
    if ($month == 5 && $day == 8) {
      return true;
    }

    // Fête nationale
    if ($month == 7 && $day == 14) {
      return true;
    }

    // Assomption
    if ($month == 8 && $day == 15) {
      return true;
    }

    // Toussaint
    if ($month == 11 && $day == 1) {
      return true;
    }

    // Armistice
    if ($month == 11 && $day == 11) {
      return true;
    }

    // Noël
    if ($month == 12 && $day == 25) {
      return true;
    }

    // Saint-Etienne
    if ($month == 12 && $day == 26) {
      return true;
    }

    return false;
  }

}





class Pdf extends MY_AuthController
{
  public function index()
  {
    redirect('/');
  }

  public function document()
  {
    $getDoctype = $this->input->get('doctype'); // ex. 'order'
    $getDocid = $this->input->get('docid');

    if (empty($getDoctype) || empty($getDocid)) {
      redirect('/');
      return;
    }

    $guzzleClient = new Client();
    $transportBridge = new Guzzle($guzzleClient);

    $sellsy = new Sellsy(
      'https://apifeed.sellsy.com/0/',
      $this->db->dc->getConfValueDefault('access_token', 'sellsy'),
      $this->db->dc->getConfValueDefault('access_token_secret', 'sellsy'),
      $this->db->dc->getConfValueDefault('consumer_token', 'sellsy'),
      $this->db->dc->getConfValueDefault('consumer_token_secret', 'sellsy')
    );

    $sellsy->setTransport($transportBridge);

    $res = $sellsy
      ->Document()
      ->getPublicLink(['doctype' => $getDoctype, 'docid' => $getDocid])
      ->getResponse();

    var_dump($res);
  }

  public function test()
  {
    $guzzleClient = new Client();
    $transportBridge = new Guzzle($guzzleClient);

    $sellsy = new Sellsy(
      'https://apifeed.sellsy.com/0/',
      $this->db->dc->getConfValueDefault('access_token', 'sellsy'),
      $this->db->dc->getConfValueDefault('access_token_secret', 'sellsy'),
      $this->db->dc->getConfValueDefault('consumer_token', 'sellsy'),
      $this->db->dc->getConfValueDefault('consumer_token_secret', 'sellsy')
    );

    $sellsy->setTransport($transportBridge);

    $res = $sellsy
      ->AccountPrefs()
      ->getAbo([])
      ->getResponse();

    var_dump($res);
  }

  private function getNbDays($year = null, $month = null, $contractStart = null, $contractEnd = null, $includeHours = false) {
    if (is_null($year)) {
      $year = intval(date('Y'));
    }
    if (is_null($month)) {
      $month = intval(date('n'));
    }

    $start = new DateTime($year . '-' . $month . '-01');
    $end = new DateTime($start->format('Y-m-t') . ' 23:59:59');

    if (!is_null($contractStart)) {
      if (is_string($contractStart)) {
        $startDate = new DateTime($contractStart);
      }
      if (is_object($contractStart)) {
        $startDate = $contractStart;
      }
      if ($startDate > $start) {
        $start = $startDate;
      }
    }

    if (!is_null($contractEnd) && !empty($contractEnd)) {
      if (is_string($contractEnd)) {
        $endDate = new DateTime($contractEnd);
      }
      if (is_object($contractEnd)) {
        $endDate = $contractEnd;
      }
      if ($endDate < $end) {
        $end = $endDate;
      }
    }

    $d = new DateObject($start->format('Y-m-d'));
    $period = $d->allDaysTo($end->format('Y-m-d'));

    $workingDays = array_filter($period, function ($d) {
      return $d->isWeekDay() && !$d->isHoliday();
    });

    $nbDays = count($workingDays);
    if ($includeHours && $start->format('H') > 9) {
      $nbDays -= .5;
    }
    if ($includeHours && $end->format('H') < 18) {
      $nbDays -= .5;
    }

    return $nbDays;
  }

  // thing = congé, maladie, CDD, ...
  private function writeDates($thing, $month, $year, $from, $to = null, $onlyWhenNeeded = false, $includeHours = false) {
    $content = $thing;

    // month period
    $start = new DateTime($year . '-' . $month . '-01');
    $end = new DateTime($start->format('Y-m-t') . ' 23:59:59');

    $startDate = new DateTime($from);
    $startPeriod = '';
    $endPeriod = '';
    if ($includeHours && $startDate->format('H') > 9) {
      $startPeriod = ' après-midi';
    }
    $needBefore = (!$onlyWhenNeeded || $startDate > $start);
    if (!is_null($to)) {
      $endDate = new DateTime($to);
      if ($includeHours && $endDate->format('H') < 18) {
        $endPeriod = ' midi';
      }
      $needAfter = (!$onlyWhenNeeded || $endDate < $end);
    } else {
      $needAfter = false;
    }

    if ($needBefore && $needAfter) {
      // start
      if ($startDate->format('Y') != $year) {
        $s = $startDate->format('d/m/Y');
      } else if ($startDate->format('n') != $month) {
        $s = $startDate->format('d/m');
      } else {
        $s = $startDate->format('j');
      }

      // end
      if ($endDate->format('Y') != $year) {
        $e = $endDate->format('d/m/Y');
      } else if ($endDate->format('n') != $month) {
        $e = $endDate->format('d/m');
      } else {
        $e = $endDate->format('j');
      }

      $content .= ' du ' . $s . $startPeriod . ' au ' . $e . $endPeriod;
    } else if ($needBefore && !$needAfter) {
      // start
      if ($startDate->format('Y') != $year) {
        $s = $startDate->format('d/m/Y');
      } else if ($startDate->format('n') != $month) {
        $s = $startDate->format('d/m');
      } else {
        $s = $startDate->format('j');
      }

      $content .= ' depuis le ' . $s . $startPeriod;
    } else if (!$needBefore && $needAfter) {
      // end
      if ($endDate->format('Y') != $year) {
        $e = $endDate->format('d/m/Y');
      } else if ($endDate->format('n') != $month) {
        $e = $endDate->format('d/m');
      } else {
        $e = $endDate->format('j');
      }

      $content .= " jusqu'au " . $e . $endPeriod;
    }

    return $content;
  }

  private function getLines() {
    $getYear = intval($this->input->get('year'));
    $getMonth = intval($this->input->get('month'));
    $year = !empty($getYear) ? $getYear : date('Y');
    $month = !empty($getMonth) ? $getMonth : date('n');

    // month period
    $start = new DateTime($year . '-' . $month . '-01');
    $end = new DateTime($start->format('Y-m-t') . ' 23:59:59');

    $req = $this->db
      ->distinct()
      ->select(
        '*, users.id AS user_id, contracts.type AS contract_type'
      )
      ->order_by('contracts.start_at', 'asc')
      ->join('users', 'users.id = contracts.user_id')
      ->join('overtime', 'overtime.user_id = contracts.user_id AND overtime.month = ' . $month . ' AND overtime.year = ' . $year, 'left')
      ->where('contracts.start_at <=', $end->format('Y-m-d H:i:s'))
      ->where(
        '(contracts.end_at >= "' .
          $start->format('Y-m-d H:i:s') .
          '" OR contracts.end_at IS NULL)'
      )
      ->get('contracts')
      ->result();

    $res = [];

    // group by user
    foreach ($req as $r) {
      if (!isset($res[$r->user_id])) {
        $res[$r->user_id] = [];
      }
      $res[$r->user_id][] = $r;
    }


    $ids = array_keys($res);
    $leave = [];
    $expenses = [];

    if (!empty($ids)) {
      $this->db->where_in('user_id', $ids);
      $this->db->select('*, leave.id AS id');
      $this->db->order_by('leave.id', 'desc');
      $this->db->where('start <=', $end->format('Y-m-d H:i:s'));
      $this->db->where('end >=', $start->format('Y-m-d H:i:s'));
      $this->db->where('accepted', 1);
      $leave = $this->db->get('leave')->result();

      $this->db->where_in('user_id', $ids);
      $this->db->select('*, expenses.id AS id');
      $this->db->where('month', $month);
      $this->db->where('year', $year);
      $this->db->order_by('expenses.id', 'desc');
      $this->db->where('accepted', 1);
      $expenses = $this->db->get('expenses')->result();
    }

    $lines = [];
    // generate a line for each user
    foreach ($res as $uId => $r) {
      $firstLine = $r[0];
      $details = '';
      $contract = implode(' et ', array_map(function ($c) use ($year, $month) {
        return $this->writeDates($c->contract_type, $month, $year, $c->start_at, $c->end_at, true, false);
      }, $r));

      $stages = array_filter($r, function ($c) {
        return mb_strtolower($c->contract_type) == 'stage';
      });
      $presence = 0;
      $stagiaire = !empty($stages);
      if ($stagiaire) {
        $presence = array_reduce($stages, function ($sum, $o) use ($year, $month) {
          return $sum + $this->getNbDays($year, $month, $o->start_at, $o->end_at, false);
        }, $presence);
      }

      $overtime = intval($firstLine->volume);
      if ($overtime <= 0) $overtime = 0;


      $userLeave = array_filter($leave, function ($l) use ($uId) {
        return $l->user_id == $uId;
      });

      $userExpenses = array_filter($expenses, function ($e) use ($uId) {
        return $e->user_id == $uId;
      });

      $conges = array_filter($userLeave, function ($l) {
        return mb_strtolower($l->reason) == 'congé';
      });

      $maladie = array_filter($userLeave, function ($l) {
        return mb_strtolower($l->reason) == 'maladie';
      });

      $autre = array_filter($userLeave, function ($l) {
        return mb_strtolower($l->reason) == 'autre';
      });

      if (!empty($details) && !empty($conges)) $details .= ', ';
      $details .= implode(', ', array_map(function ($c) use ($year, $month) {
        return $this->writeDates('congés', $month, $year, $c->start, $c->end, false, true);
      }, $conges));

      if (!empty($details) && !empty($maladie)) $details .= ', ';
      $details .= implode(', ', array_map(function ($c) use ($year, $month) {
        return $this->writeDates('maladie', $month, $year, $c->start, $c->end, false, true);
      }, $maladie));

      if (!empty($details) && !empty($autre)) $details .= ', ';
      $details .= implode(', ', array_map(function ($c) use ($year, $month) {
        return $this->writeDates('autre', $month, $year, $c->start, $c->end, false, true);
      }, $autre));

      $conges = array_reduce($conges, function ($sum, $o) use ($year, $month) {
        return $sum + $this->getNbDays($year, $month, $o->start, $o->end, true);
      }, .0);
      $maladie = array_reduce($maladie, function ($sum, $o) use ($year, $month) {
        return $sum + $this->getNbDays($year, $month, $o->start, $o->end, true);
      }, .0);
      $autre = array_reduce($autre, function ($sum, $o) use ($year, $month) {
        return $sum + $this->getNbDays($year, $month, $o->start, $o->end, true);
      }, .0);

      $transports = array_reduce(array_filter($userExpenses, function ($e) {
        return mb_strtolower($e->type) == 'transports';
      }), function ($sum, $o) {
        return $sum + $o->amount;
      }, .0);
      $expenses = array_reduce(array_filter($userExpenses, function ($e) {
        return mb_strtolower($e->type) == 'dépense';
      }), function ($sum, $o) {
        return $sum + $o->amount;
      }, .0);

      if ($stagiaire) {
        $presence -+ $conges;
        $presence -+ $maladie;
        $presence -+ $autre;
        if (!empty($details)) $details .= ', ';
        $s = '';
        if ($presence > 1) $s = 's';
        $details .= $presence . ' jour' . $s . ' de présence (stage)';
      }

      $lines[] = (object) [
        'name' => $firstLine->firstname . ' ' . $firstLine->lastname,
        'contract' => $contract,
        'overtime' => $overtime,
        'conges' => $conges,
        'maladie' => $maladie,
        'autre' => $autre,
        'transports' => $transports,
        'expenses' => $expenses,
        'details' => $details
      ];
    }

    return $lines;
  }

  public function compta()
  {
    ob_start();
    $this->view('pdf/compta', [
      'lines' => $this->getLines()
    ]);
    $content = ob_get_clean();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('compta.pdf', ['compress' => 1, 'Attachment' => 0]);
  }
}
