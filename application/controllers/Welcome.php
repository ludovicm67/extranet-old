<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
  public function index()
  {
    $this->load->view('welcome_message');
  }

  public function test()
  {
    $this->load->view('template', [
      'content' =>
        '<h1 class="mt-5">Accueil</h1><p class="lead">Petite page de test.</p>'
    ]);
  }
}
