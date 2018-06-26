<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\Guzzle;

defined('BASEPATH') or exit('No direct script access allowed');

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
      ->getPublicLink([
        'doctype' => $getDoctype,
        'docid' => $getDocid
      ])
      ->getResponse();

    var_dump($res);

  }
}
