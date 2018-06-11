<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  protected $isLogged = false;

  public function __construct()
  {
    parent::__construct();

    if ($this->session->has_userdata('logged')) {
      if ($this->session->userdata('logged') === true) {
        $this->isLogged = true;
      } else {
        $this->session->unset_userdata('logged');
      }
    }

    if (
      !$this->db->table_exists('users') ||
      $this->db->count_all('users') <= 0
    ) {
      redirect('/setup', 'refresh');
    }
  }

  public function isLoggedIn()
  {
    return $this->isLogged;
  }

  public function userLogout()
  {
    $this->isLogged = false;
    $this->session->unset_userdata('logged');
    redirect('/login', 'refresh');
  }
}

class MY_AuthController extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();

    if (!$this->isLogged && $this->router->class !== 'login') {
      redirect('/login', 'refresh');
    }
  }
}
