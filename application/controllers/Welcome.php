<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
  public function index()
  {
    // $guzzleClient = new Client();
    // $transportBridge = new Guzzle($guzzleClient);

    // $sellsy = new Sellsy(
    //   'https://apifeed.sellsy.com/0/',
    //   $this->config->item('api')['ACCESS_TOKEN'],
    //   $this->config->item('api')['ACCESS_TOKEN_SECRET'],
    //   $this->config->item('api')['API_SELLSY_CONSUMER_TOKEN'],
    //   $this->config->item('api')['API_SELLSY_CONSUMER_SECRET']
    // );

    // $sellsy->setTransport($transportBridge);

    // var_dump(
    //   $sellsy
    //     ->Infos()
    //     ->getInfos()
    //     ->getResponse()['consumerdatas']['id']
    // );
    // var_dump(
    //   $sellsy
    //     ->AccountPrefs()
    //     ->getCorpInfos()
    //     ->getResponse()['email']
    // );

    $this->load->view('welcome_message');
  }
}
