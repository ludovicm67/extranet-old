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

  public function d() {
    $getYear = intval($this->input->get('year'));
    $getMonth = intval($this->input->get('month'));
    $year = !empty($getYear) ? $getYear : date('Y');
    $month = !empty($getMonth) ? $getMonth : date('n');

    // contract
    $startDate = '2016-04-21 00:00:00';
    $endDate = '2019-04-21 00:00:00';

    $start = new DateTime($year . '-' . $month . '-01');
    $end = new DateTime($start->format('Y-m-t') . ' 23:59:59');

    $startDate = new DateTime($startDate);
    if ($startDate > $start) {
      $start = $startDate;
    }

    if (!empty($endDate)) {
      $endDate = new DateTime($endDate);
      if ($endDate < $end) {
        $end = $endDate;
      }
    }

    $d = new DateObject($start->format('Y-m-d'));
    $year = $d->allDaysTo($end->format('Y-m-d'));

    $workingDays = array_filter($year, function ($d) {
      return $d->isWeekDay() && !$d->isHoliday();
    });

    $workingDays = array_map(function ($d) {
      return $d->format();
    }, $workingDays);

    var_dump($workingDays);
  }

  public function compta()
  {
    ob_start();
    $this->view('pdf/compta');
    $content = ob_get_clean();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('compta.pdf', ['compress' => 1, 'Attachment' => 0]);
  }
}
