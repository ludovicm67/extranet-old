<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends CI_Controller
{
  public function index()
  {
    $tagsDB = $this->db->get('tags')->result();
    $this->load->view('tags/list', ['tags' => $tags]);
  }
}
