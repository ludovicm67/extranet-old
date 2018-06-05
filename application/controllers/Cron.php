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
          'thirdid' => isset($client['thirdid']) ? $client['thirdid'] : null,
          'capital' => isset($client['capital']) ? $client['capital'] : '',
          'logo' => isset($client['logo']) ? $client['logo'] : '',
          'joindate' => isset($client['joindate']) ? $client['joindate'] : '',
          'auxCode' => isset($client['auxCode']) ? $client['auxCode'] : '',
          'accountingCode' =>
            isset($client['accountingCode']) ? $client['accountingCode'] : '',
          'stickyNote' =>
            isset($client['stickyNote']) ? $client['stickyNote'] : '',
          'ident' => isset($client['ident']) ? $client['ident'] : '',
          'rateCategory' =>
            isset($client['rateCategory']) ? $client['rateCategory'] : '',
          'massmailingUnsubscribed' =>
            isset($client['massmailingUnsubscribed'])
              ? $client['massmailingUnsubscribed']
              : '',
          'massmailingUnsubscribedSMS' =>
            isset($client['massmailingUnsubscribedSMS'])
              ? $client['massmailingUnsubscribedSMS']
              : '',
          'phoningUnsubscribed' =>
            isset($client['phoningUnsubscribed'])
              ? $client['phoningUnsubscribed']
              : '',
          'massmailingUnsubscribedMail' =>
            isset($client['massmailingUnsubscribedMail'])
              ? $client['massmailingUnsubscribedMail']
              : '',
          'massmailingUnsubscribedCustom' =>
            isset($client['massmailingUnsubscribedCustom'])
              ? $client['massmailingUnsubscribedCustom']
              : '',
          'lastactivity' =>
            isset($client['lastactivity']) ? $client['lastactivity'] : '',
          'ownerid' => isset($client['ownerid']) ? $client['ownerid'] : null,
          'type' => isset($client['type']) ? $client['type'] : '',
          'maincontactid' =>
            isset($client['maincontactid']) ? $client['maincontactid'] : null,
          'relationType' =>
            isset($client['relationType']) ? $client['relationType'] : '',
          'actif' => isset($client['actif']) ? $client['actif'] : '',
          'pic' => isset($client['pic']) ? $client['pic'] : '',
          'dateTransformProspect' =>
            isset($client['dateTransformProspect'])
              ? $client['dateTransformProspect']
              : '',
          'mainContactName' =>
            isset($client['mainContactName']) ? $client['mainContactName'] : '',
          'name' => isset($client['name']) ? $client['name'] : '',
          'tel' => isset($client['tel']) ? $client['tel'] : '',
          'fax' => isset($client['fax']) ? $client['fax'] : '',
          'email' => isset($client['email']) ? $client['email'] : '',
          'mobile' => isset($client['mobile']) ? $client['mobile'] : '',
          'apenaf' => isset($client['apenaf']) ? $client['apenaf'] : '',
          'rcs' => isset($client['rcs']) ? $client['rcs'] : '',
          'siret' => isset($client['siret']) ? $client['siret'] : '',
          'siren' => isset($client['siren']) ? $client['siren'] : '',
          'vat' => isset($client['vat']) ? $client['vat'] : '',
          'mainaddressid' =>
            isset($client['mainaddressid']) ? $client['mainaddressid'] : '',
          'maindelivaddressid' =>
            isset($client['maindelivaddressid'])
              ? $client['maindelivaddressid']
              : '',
          'web' => isset($client['web']) ? $client['web'] : '',
          'corpType' => isset($client['corpType']) ? $client['corpType'] : '',
          'addr_name' =>
            isset($client['addr_name']) ? $client['addr_name'] : '',
          'addr_part1' =>
            isset($client['addr_part1']) ? $client['addr_part1'] : '',
          'addr_part2' =>
            isset($client['addr_part2']) ? $client['addr_part2'] : '',
          'addr_zip' => isset($client['addr_zip']) ? $client['addr_zip'] : '',
          'addr_town' =>
            isset($client['addr_town']) ? $client['addr_town'] : '',
          'addr_state' =>
            isset($client['addr_state']) ? $client['addr_state'] : '',
          'addr_lat' => isset($client['addr_lat']) ? $client['addr_lat'] : '',
          'addr_lng' => isset($client['addr_lng']) ? $client['addr_lng'] : '',
          'addr_countrycode' =>
            isset($client['addr_countrycode'])
              ? $client['addr_countrycode']
              : '',
          'delivaddr_name' =>
            isset($client['delivaddr_name']) ? $client['delivaddr_name'] : '',
          'delivaddr_part1' =>
            isset($client['delivaddr_part1']) ? $client['delivaddr_part1'] : '',
          'delivaddr_part2' =>
            isset($client['delivaddr_part2']) ? $client['delivaddr_part2'] : '',
          'delivaddr_zip' =>
            isset($client['delivaddr_zip']) ? $client['delivaddr_zip'] : '',
          'delivaddr_town' =>
            isset($client['delivaddr_town']) ? $client['delivaddr_town'] : '',
          'delivaddr_state' =>
            isset($client['delivaddr_state']) ? $client['delivaddr_state'] : '',
          'delivaddr_lat' =>
            isset($client['delivaddr_lat']) ? $client['delivaddr_lat'] : '',
          'delivaddr_lng' =>
            isset($client['delivaddr_lng']) ? $client['delivaddr_lng'] : '',
          'delivaddr_countrycode' =>
            isset($client['delivaddr_countrycode'])
              ? $client['delivaddr_countrycode']
              : '',
          'formated_joindate' =>
            isset($client['formated_joindate'])
              ? $client['formated_joindate']
              : '',
          'formated_transformprospectdate' =>
            isset($client['formated_transformprospectdate'])
              ? $client['formated_transformprospectdate']
              : '',
          'corpid' => isset($client['corpid']) ? $client['corpid'] : null,
          'lastactivity_formatted' =>
            isset($client['lastactivity_formatted'])
              ? $client['lastactivity_formatted']
              : '',
          'addr_countryname' =>
            isset($client['addr_countryname'])
              ? $client['addr_countryname']
              : '',
          'mainAddress' =>
            isset($client['mainAddress']) ? $client['mainAddress'] : '',
          'addr_geocode' =>
            isset($client['addr_geocode']) ? $client['addr_geocode'] : '',
          'delivaddr_countryname' =>
            isset($client['delivaddr_countryname'])
              ? $client['delivaddr_countryname']
              : '',
          'delivAddress' =>
            isset($client['delivAddress']) ? $client['delivAddress'] : '',
          'delivaddr_geocode' =>
            isset($client['delivaddr_geocode'])
              ? $client['delivaddr_geocode']
              : '',
          'fullName' => isset($client['fullName']) ? $client['fullName'] : '',
          'contactId' =>
            isset($client['contactId']) ? $client['contactId'] : '',
          'contactDetails' =>
            isset($client['contactDetails']) ? $client['contactDetails'] : '',
          'formatted_tel' =>
            isset($client['formatted_tel']) ? $client['formatted_tel'] : '',
          'formatted_mobile' =>
            isset($client['formatted_mobile'])
              ? $client['formatted_mobile']
              : '',
          'formatted_fax' =>
            isset($client['formatted_fax']) ? $client['formatted_fax'] : '',
          'owner' => isset($client['owner']) ? $client['owner'] : '',
          'webUrl' => isset($client['webUrl']) ? $client['webUrl'] : ''
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
          'pic' => isset($contact['pic']) ? $contact['pic'] : '',
          'name' => isset($contact['name']) ? $contact['name'] : '',
          'forename' => isset($contact['forename']) ? $contact['forename'] : '',
          'tel' => isset($contact['tel']) ? $contact['tel'] : '',
          'email' => isset($contact['email']) ? $contact['email'] : '',
          'mobile' => isset($contact['mobile']) ? $contact['mobile'] : '',
          'civil' => isset($contact['civil']) ? $contact['civil'] : '',
          'position' => isset($contact['position']) ? $contact['position'] : '',
          'birthdate' =>
            isset($contact['birthdate']) ? $contact['birthdate'] : '',
          'thirdid' => isset($contact['thirdid']) ? $contact['thirdid'] : null,
          'peopleid' =>
            isset($contact['peopleid']) ? $contact['peopleid'] : null,
          'fullName' => isset($contact['fullName']) ? $contact['fullName'] : '',
          'corpid' => isset($contact['corpid']) ? $contact['corpid'] : null,
          'formatted_tel' =>
            isset($contact['formatted_tel']) ? $contact['formatted_tel'] : '',
          'formatted_mobile' =>
            isset($contact['formatted_mobile'])
              ? $contact['formatted_mobile']
              : '',
          'formatted_fax' =>
            isset($contact['formatted_fax']) ? $contact['formatted_fax'] : '',
          'formatted_birthdate' =>
            isset($contact['formatted_birthdate'])
              ? $contact['formatted_birthdate']
              : ''
        ]);
      }
      var_dump($contactsRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }
}
