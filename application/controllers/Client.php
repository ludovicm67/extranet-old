<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
  }

  public function index()
  {
    redirect('/clients', 'refresh');
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

    $this->load->view('client', ['client' => $client]);
  }
}
