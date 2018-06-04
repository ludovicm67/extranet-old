<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{
  public function index()
  {
    $clientsDB = $this->db->get('sellsy_clients')->result();
    $clients = [];
    foreach ($clientsDB as $k => $c) {
      $clientsDB[$k]->contacts = [];
      $clients[$c->id] = $clientsDB[$k];
    }
    $contacts = $this->db->get('sellsy_contacts')->result();
    foreach ($contacts as $k => $c) {
      if (isset($clients[$c->thirdid])) {
        $clients[$c->thirdid]->contacts[] = $c;
      }
    }

    $this->load->view('clients', ['clients' => $clients]);
  }
}
