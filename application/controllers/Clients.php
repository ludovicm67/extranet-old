<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('clients', 'show');

    $clients = $this->db->get('sellsy_clients')->result();
    $this->view('clients/list', ['clients' => $clients]);
  }

  public function show($id)
  {
    $this->checkPermission('clients_details', 'show');

    $clientDB = $this->db
      ->get_where('sellsy_clients', ['id' => $id], 1, 0)
      ->result();
    if (count($clientDB) !== 1) {
      redirect('/clients');
    }
    $client = $clientDB[0];
    $client->contacts = $this->db
      ->get_where('sellsy_contacts', ['thirdid' => $client->sellsy_id])
      ->result();

    $orders = [];
    if ($this->hasPermission('orders', 'show')) {
      $ordersDB = $this->db
        ->get_where('sellsy_orders', ['thirdid' => $client->sellsy_id])
        ->result();
      foreach ($ordersDB as $o) {
        $orders[$o->sellsy_id] = $o;
        $orders[$o->sellsy_id]->invoices = [];
        $orders[$o->sellsy_id]->remainingOrderAmount = floatval(
          $o->totalAmountTaxesFree
        );
        $orders[$o->sellsy_id]->remainingDueAmount = 0;
      }
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
        if ($this->hasPermission('invoices', 'show')) {
          $orders[$invoice->parentid]->invoices[] = $invoice;
        }
        if ($invoice->isDeposit != 'Y') {
          $orders[$invoice->parentid]->remainingOrderAmount -= floatval(
            $invoice->totalAmountTaxesFree
          );
        }
        $orders[$invoice->parentid]->remainingDueAmount += floatval(
          $invoice->dueAmount
        );
      }
    }

    $client->orders = $orders;

    $client->projects = ($this->hasPermission('projects', 'show'))
      ? $this->db->get_where('projects', ['client_id' => $client->id])->result()
      : [];

    $client->subs = $this->db
      ->select('sellsy_invoices.*')
      ->join(
        'sellsy_orders',
        'sellsy_orders.sellsy_id = sellsy_invoices.parentid',
        'left'
      )
      ->get_where('sellsy_invoices', [
        'sellsy_orders.sellsy_id' => null,
        'sellsy_invoices.thirdid' => $client->sellsy_id
      ])
      ->result();

    $this->view('clients/show', ['client' => $client]);
  }
}
