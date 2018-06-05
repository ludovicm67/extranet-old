<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{
  public function index()
  {
    $this->load->helper('url');
    redirect('/clients', 'refresh');
  }

  public function show($id)
  {
    echo $id;
  }
}
