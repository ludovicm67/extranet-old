<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
  private $sellsy;

  public function __construct()
  {
    parent::__construct();

    $guzzleClient = new Client();
    $transportBridge = new Guzzle($guzzleClient);

    $sellsy = new Sellsy(
      'https://apifeed.sellsy.com/0/',
      $this->config->item('api')['ACCESS_TOKEN'],
      $this->config->item('api')['ACCESS_TOKEN_SECRET'],
      $this->config->item('api')['API_SELLSY_CONSUMER_TOKEN'],
      $this->config->item('api')['API_SELLSY_CONSUMER_SECRET']
    );

    $sellsy->setTransport($transportBridge);
    $this->sellsy = $sellsy;
  }

  // useful function to add or update a data in the database
  private function addOrUpdateDB($table, $id, $data)
  {
    $this->db->where('id', $id);
    $q = $this->db->get($table);
    if ($q->num_rows() > 0) {
      $this->db->where('id', $id);
      $this->db->update($table, (object) $data);
    } else {
      $this->db->set('id', $id);
      $this->db->insert($table, (object) $data);
    }
  }

  public function sellsy_clients()
  {
    $pagenum = 1;
    $nbpages = $pagenum + 1;
    do {
      $clientsRequest = $this->sellsy
        ->Client()
        ->getList(['pagination' => ['nbperpage' => 100, 'pagenum' => $pagenum]])
        ->getResponse();
      $nbpages = $clientsRequest['infos']['nbpages'];

      $clients = $clientsRequest['result'];
      foreach ($clients as $clientId => $client) {
        $this->addOrUpdateDB('sellsy_clients', $clientId, [
          'fullname' => $client['fullName']
        ]);
      }
      var_dump($clientsRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }

  public function sellsy_contacts()
  {
    $pagenum = 1;
    $nbpages = $pagenum + 1;
    do {
      $contactsRequest = $this->sellsy
        ->Peoples()
        ->getList(['pagination' => ['nbperpage' => 100, 'pagenum' => $pagenum]])
        ->getResponse();
      $nbpages = $contactsRequest['infos']['nbpages'];

      $contacts = $contactsRequest['result'];
      foreach ($contacts as $contactId => $contact) {
        $this->addOrUpdateDB('sellsy_contacts', $contactId, [
          'fullname' => $contact['fullName'],
          'email' => $contact['email'],
          'tel' => $contact['tel'],
          'mobile' => $contact['mobile'],
          'position' => $contact['position'],
          'thirdid' => $contact['thirdid']
        ]);
      }
      var_dump($contactsRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }
}
