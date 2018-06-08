<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends MY_Controller
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
    $ordersDB = $this->db
      ->get_where('sellsy_orders', ['thirdid' => $client->sellsy_id])
      ->result();

    $orders = [];
    foreach ($ordersDB as $o) {
      $orders[$o->sellsy_id] = $o;
      $orders[$o->sellsy_id]->invoices = [];
      $orders[$o->sellsy_id]->remainingOrderAmount = floatval($o->totalAmount);
      $orders[$o->sellsy_id]->remainingDueAmount = 0;
    }

    $ordersIds = array_keys($orders);

    if (count($ordersIds) > 0) {
      $invoices = $this->db
        ->where_in('parentid', $ordersIds)
        ->get('sellsy_invoices')
        ->result();
      foreach ($invoices as $invoice) {
        if (!isset($orders[$invoice->parentid])) {
          continue;
        }
        $orders[$invoice->parentid]->invoices[] = $invoice;
        $orders[$invoice->parentid]->remainingOrderAmount -= floatval(
          $invoice->totalAmount
        );
        $orders[$invoice->parentid]->remainingDueAmount += floatval(
          $invoice->dueAmount
        );
      }
    }

    $client->orders = $orders;

    $client->projects = $this->db
      ->get_where('projects', ['client_id' => $client->id])
      ->result();

    $this->load->view('clients/show', ['client' => $client]);
  }
}
