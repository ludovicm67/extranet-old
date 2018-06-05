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

    $this->load->view('clients/list', ['clients' => $clients]);
  }

  public function show($id)
  {
    $clientDB = $this->db
      ->get_where('sellsy_clients', ['id' => $id], 1, 0)
      ->result();
    if (count($clientDB) !== 1) {
      redirect('/clients', 'refresh');
    }
    $client = $clientDB[0];
    $client->contacts = $this->db
      ->get_where('sellsy_contacts', ['thirdid' => $client->sellsy_id])
      ->result();
    $client->orders = $this->db
      ->get_where('sellsy_orders', ['thirdid' => $client->sellsy_id])
      ->result();
    $client->invoices = $this->db
      ->get_where('sellsy_invoices', ['thirdid' => $client->sellsy_id])
      ->result();

    $this->load->view('clients/show', ['client' => $client]);
  }
}
