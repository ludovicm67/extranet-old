<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_AuthController
{
  public function index()
  {
    $this->view('welcome_message');
  }

  public function logout()
  {
    $this->userLogout();
  }
}
