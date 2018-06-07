<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
  private $sellsy;

  // init sellsy
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
  private function addOrUpdateDB($table, $id, $data, $idField = 'id')
  {
    $this->db->where($idField, $id);
    $q = $this->db->get($table);
    if ($q->num_rows() > 0) {
      $this->db->where($idField, $id);
      $this->db->update($table, $data);
    } else {
      $this->db->set($idField, $id);
      $this->db->insert($table, $data);
    }
  }

  // useful function to run each commands from a SQL file
  private function execSqlFile($filename)
  {
    if (file_exists($filename)) {
      $content = file_get_contents($filename);
      if (empty($content)) {
        return;
      }
      $queries = explode(';', $content);
      foreach ($queries as $query) {
        $q = trim($query);
        if (!empty($q)) {
          $this->db->query($q);
        }
      }
    }
  }

  // init database (create all required tables)
  public function init_database()
  {
    $this->execSqlFile(ROOTPATH . 'database/sellsy_clients.sql');
    $this->execSqlFile(ROOTPATH . 'database/sellsy_contacts.sql');
    $this->execSqlFile(ROOTPATH . 'database/sellsy_orders.sql');
    $this->execSqlFile(ROOTPATH . 'database/sellsy_invoices.sql');
    $this->execSqlFile(ROOTPATH . 'database/projects.sql');
    echo json_encode(['success' => true]);
  }

  // get all sellsy clients and their contacts
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
        $this->addOrUpdateDB(
          'sellsy_clients',
          $clientId,
          [
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
              isset($client['mainContactName'])
                ? $client['mainContactName']
                : '',
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
              isset($client['delivaddr_part1'])
                ? $client['delivaddr_part1']
                : '',
            'delivaddr_part2' =>
              isset($client['delivaddr_part2'])
                ? $client['delivaddr_part2']
                : '',
            'delivaddr_zip' =>
              isset($client['delivaddr_zip']) ? $client['delivaddr_zip'] : '',
            'delivaddr_town' =>
              isset($client['delivaddr_town']) ? $client['delivaddr_town'] : '',
            'delivaddr_state' =>
              isset($client['delivaddr_state'])
                ? $client['delivaddr_state']
                : '',
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
          ],
          'sellsy_id'
        );
        if (isset($client['contacts'])) {
          foreach ($client['contacts'] as $contact) {
            if (isset($contact['peopleid'])) {
              $this->addOrUpdateDB(
                'sellsy_contacts',
                $contact['peopleid'],
                [
                  'pic' => isset($contact['pic']) ? $contact['pic'] : '',
                  'name' => isset($contact['name']) ? $contact['name'] : '',
                  'forename' =>
                    isset($contact['forename']) ? $contact['forename'] : '',
                  'tel' => isset($contact['tel']) ? $contact['tel'] : '',
                  'email' => isset($contact['email']) ? $contact['email'] : '',
                  'mobile' =>
                    isset($contact['mobile']) ? $contact['mobile'] : '',
                  'civil' => isset($contact['civil']) ? $contact['civil'] : '',
                  'position' =>
                    isset($contact['position']) ? $contact['position'] : '',
                  'birthdate' =>
                    (
                      isset($contact['birthdate']) &&
                        $contact['birthdate'] !== 'NC.'
                    )
                      ? $contact['birthdate']
                      : '',
                  'thirdid' =>
                    isset($contact['thirdid']) ? $contact['thirdid'] : null,
                  'fullName' =>
                    isset($contact['fullName']) ? $contact['fullName'] : '',
                  'corpid' =>
                    isset($contact['corpid']) ? $contact['corpid'] : null,
                  'formatted_tel' =>
                    isset($contact['formatted_tel'])
                      ? $contact['formatted_tel']
                      : '',
                  'formatted_mobile' =>
                    isset($contact['formatted_mobile'])
                      ? $contact['formatted_mobile']
                      : '',
                  'formatted_fax' =>
                    (
                      isset($contact['formatted_fax']) &&
                        $contact['formatted_fax'] !== 'N/C'
                    )
                      ? $contact['formatted_fax']
                      : '',
                  'formatted_birthdate' =>
                    isset($contact['formatted_birthdate'])
                      ? $contact['formatted_birthdate']
                      : ''
                ],
                'sellsy_id'
              );
            }
          }
        }
      }
      // var_dump($clientsRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }

  // get all sellsy contacts
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
        $this->addOrUpdateDB(
          'sellsy_contacts',
          $contactId,
          [
            'pic' => isset($contact['pic']) ? $contact['pic'] : '',
            'name' => isset($contact['name']) ? $contact['name'] : '',
            'forename' =>
              isset($contact['forename']) ? $contact['forename'] : '',
            'tel' => isset($contact['tel']) ? $contact['tel'] : '',
            'email' => isset($contact['email']) ? $contact['email'] : '',
            'mobile' => isset($contact['mobile']) ? $contact['mobile'] : '',
            'civil' => isset($contact['civil']) ? $contact['civil'] : '',
            'position' =>
              isset($contact['position']) ? $contact['position'] : '',
            'birthdate' =>
              (isset($contact['birthdate']) && $contact['birthdate'] !== 'NC.')
                ? $contact['birthdate']
                : '',
            'thirdid' =>
              isset($contact['thirdid']) ? $contact['thirdid'] : null,
            'fullName' =>
              isset($contact['fullName']) ? $contact['fullName'] : '',
            'corpid' => isset($contact['corpid']) ? $contact['corpid'] : null,
            'formatted_tel' =>
              isset($contact['formatted_tel']) ? $contact['formatted_tel'] : '',
            'formatted_mobile' =>
              isset($contact['formatted_mobile'])
                ? $contact['formatted_mobile']
                : '',
            'formatted_fax' =>
              (
                isset($contact['formatted_fax']) &&
                  $contact['formatted_fax'] !== 'N/C'
              )
                ? $contact['formatted_fax']
                : '',
            'formatted_birthdate' =>
              isset($contact['formatted_birthdate'])
                ? $contact['formatted_birthdate']
                : ''
          ],
          'sellsy_id'
        );
      }
      // var_dump($contactsRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }

  // get all sellsy orders
  public function sellsy_orders()
  {
    $pagenum = 1;
    $nbpages = $pagenum + 1;
    do {
      $ordersRequest = $this->sellsy
        ->Document()
        ->getList([
          'doctype' => 'order',
          'pagination' => ['nbperpage' => 100, 'pagenum' => $pagenum]
        ])
        ->getResponse();
      $nbpages = $ordersRequest['infos']['nbpages'];

      $orders = $ordersRequest['result'];
      foreach ($orders as $orderId => $order) {
        $this->addOrUpdateDB(
          'sellsy_orders',
          $orderId,
          [
            'corpname' => isset($order['corpname']) ? $order['corpname'] : '',
            'ownerFullName' =>
              isset($order['ownerFullName']) ? $order['ownerFullName'] : '',
            'status' => isset($order['status']) ? $order['status'] : '',
            'filename' => isset($order['filename']) ? $order['filename'] : '',
            'fileid' => isset($order['fileid']) ? $order['fileid'] : '',
            'nbpages' => isset($order['nbpages']) ? $order['nbpages'] : '',
            'ident' => isset($order['ident']) ? $order['ident'] : '',
            'thirdident' =>
              isset($order['thirdident']) ? $order['thirdident'] : '',
            'thirdname' =>
              isset($order['thirdname']) ? $order['thirdname'] : '',
            'thirdid' => isset($order['thirdid']) ? $order['thirdid'] : '',
            'thirdvatnum' =>
              isset($order['thirdvatnum']) ? $order['thirdvatnum'] : '',
            'contactId' =>
              isset($order['contactId']) ? $order['contactId'] : '',
            'contactName' =>
              isset($order['contactName']) ? $order['contactName'] : '',
            'displayedDate' =>
              isset($order['displayedDate']) ? $order['displayedDate'] : '',
            'currencysymbol' =>
              isset($order['currencysymbol']) ? $order['currencysymbol'] : '',
            'subject' => isset($order['subject']) ? $order['subject'] : '',
            'docspeakerText' =>
              isset($order['docspeakerText']) ? $order['docspeakerText'] : '',
            'docspeakerStaffId' =>
              isset($order['docspeakerStaffId'])
                ? $order['docspeakerStaffId']
                : '',
            'docspeakerStaffFullName' =>
              isset($order['docspeakerStaffFullName'])
                ? $order['docspeakerStaffFullName']
                : '',
            'corpaddressid' =>
              isset($order['corpaddressid']) ? $order['corpaddressid'] : '',
            'thirdaddressid' =>
              isset($order['thirdaddressid']) ? $order['thirdaddressid'] : '',
            'shipaddressid' =>
              isset($order['shipaddressid']) ? $order['shipaddressid'] : '',
            'rowsAmount' =>
              isset($order['rowsAmount']) ? $order['rowsAmount'] : '',
            'discountPercent' =>
              isset($order['discountPercent']) ? $order['discountPercent'] : '',
            'discountAmount' =>
              isset($order['discountAmount']) ? $order['discountAmount'] : '',
            'rowsAmountDiscounted' =>
              isset($order['rowsAmountDiscounted'])
                ? $order['rowsAmountDiscounted']
                : '',
            'offerAmount' =>
              isset($order['offerAmount']) ? $order['offerAmount'] : '',
            'rowsAmountAllInc' =>
              isset($order['rowsAmountAllInc'])
                ? $order['rowsAmountAllInc']
                : '',
            'packagingsAmount' =>
              isset($order['packagingsAmount'])
                ? $order['packagingsAmount']
                : '',
            'shippingsAmount' =>
              isset($order['shippingsAmount']) ? $order['shippingsAmount'] : '',
            'totalAmountTaxesFree' =>
              isset($order['totalAmountTaxesFree'])
                ? $order['totalAmountTaxesFree']
                : '',
            'taxesAmountSum' =>
              isset($order['taxesAmountSum']) ? $order['taxesAmountSum'] : '',
            'taxesAmountDetails' =>
              isset($order['taxesAmountDetails'])
                ? $order['taxesAmountDetails']
                : '',
            'totalAmount' =>
              isset($order['totalAmount']) ? $order['totalAmount'] : '',
            'useEcotaxe' =>
              isset($order['useEcotaxe']) ? $order['useEcotaxe'] : '',
            'totalEcoTaxFree' =>
              isset($order['totalEcoTaxFree']) ? $order['totalEcoTaxFree'] : '',
            'totalEcoTaxInc' =>
              isset($order['totalEcoTaxInc']) ? $order['totalEcoTaxInc'] : '',
            'ecoTaxId' => isset($order['ecoTaxId']) ? $order['ecoTaxId'] : '',
            'taxBasis' => isset($order['taxBasis']) ? $order['taxBasis'] : '',
            'payDateText' =>
              isset($order['payDateText']) ? $order['payDateText'] : '',
            'payDateCustom' =>
              isset($order['payDateCustom']) ? $order['payDateCustom'] : '',
            'hasDeadlines' =>
              isset($order['hasDeadlines']) ? $order['hasDeadlines'] : '',
            'payMediumsText' =>
              isset($order['payMediumsText']) ? $order['payMediumsText'] : '',
            'payCheckOrderText' =>
              isset($order['payCheckOrderText'])
                ? $order['payCheckOrderText']
                : '',
            'payBankAccountText' =>
              isset($order['payBankAccountText'])
                ? $order['payBankAccountText']
                : '',
            'shippingNbParcels' =>
              isset($order['shippingNbParcels'])
                ? $order['shippingNbParcels']
                : '',
            'shippingWeight' =>
              isset($order['shippingWeight']) ? $order['shippingWeight'] : '',
            'shippingWeightUnit' =>
              isset($order['shippingWeightUnit'])
                ? $order['shippingWeightUnit']
                : '',
            'shippingVolume' =>
              isset($order['shippingVolume']) ? $order['shippingVolume'] : '',
            'shippingTrackingNumber' =>
              isset($order['shippingTrackingNumber'])
                ? $order['shippingTrackingNumber']
                : '',
            'shippingTrackingUrl' =>
              isset($order['shippingTrackingUrl'])
                ? $order['shippingTrackingUrl']
                : '',
            'shippingDate' =>
              isset($order['shippingDate']) ? $order['shippingDate'] : '',
            'saveThirdPrefs' =>
              isset($order['saveThirdPrefs']) ? $order['saveThirdPrefs'] : '',
            'displayShipAddress' =>
              isset($order['displayShipAddress'])
                ? $order['displayShipAddress']
                : '',
            'analyticsCode' =>
              isset($order['analyticsCode']) ? $order['analyticsCode'] : '',
            'recorded' => isset($order['recorded']) ? $order['recorded'] : '',
            'recordable' =>
              isset($order['recordable']) ? $order['recordable'] : '',
            'rateCategory' =>
              isset($order['rateCategory']) ? $order['rateCategory'] : '',
            'isTaxesInc' =>
              isset($order['isTaxesInc']) ? $order['isTaxesInc'] : '',
            'hasDoubleVat' =>
              isset($order['hasDoubleVat']) ? $order['hasDoubleVat'] : '',
            'stockImpact' =>
              isset($order['stockImpact']) ? $order['stockImpact'] : '',
            'isFromPresta' =>
              isset($order['isFromPresta']) ? $order['isFromPresta'] : '',
            'eCommerceShopId' =>
              isset($order['eCommerceShopId']) ? $order['eCommerceShopId'] : '',
            'signcoords' =>
              isset($order['signcoords']) ? $order['signcoords'] : '',
            'esignID' => isset($order['esignID']) ? $order['esignID'] : '',
            'promotionid' =>
              isset($order['promotionid']) ? $order['promotionid'] : '',
            'useServiceDates' =>
              isset($order['useServiceDates']) ? $order['useServiceDates'] : '',
            'serviceDateStart' =>
              isset($order['serviceDateStart'])
                ? $order['serviceDateStart']
                : '',
            'serviceDateStop' =>
              isset($order['serviceDateStop']) ? $order['serviceDateStop'] : '',
            'locked' => isset($order['locked']) ? $order['locked'] : '',
            'reconciledStatus' =>
              isset($order['reconciledStatus'])
                ? $order['reconciledStatus']
                : '',
            'corpid' => isset($order['corpid']) ? $order['corpid'] : '',
            'ownerid' => isset($order['ownerid']) ? $order['ownerid'] : '',
            'linkedtype' =>
              isset($order['linkedtype']) ? $order['linkedtype'] : '',
            'linkedid' => isset($order['linkedid']) ? $order['linkedid'] : '',
            'created' => isset($order['created']) ? $order['created'] : '',
            'prefsid' => isset($order['prefsid']) ? $order['prefsid'] : '',
            'parentid' => isset($order['parentid']) ? $order['parentid'] : '',
            'docmapid' => isset($order['docmapid']) ? $order['docmapid'] : '',
            'hasVat' => isset($order['hasVat']) ? $order['hasVat'] : '',
            'doctypeid' =>
              isset($order['doctypeid']) ? $order['doctypeid'] : '',
            'step' => isset($order['step']) ? $order['step'] : '',
            'doctypestep' =>
              isset($order['doctypestep']) ? $order['doctypestep'] : '',
            'expireDate' =>
              isset($order['expireDate']) ? $order['expireDate'] : '',
            'showSignAndStamp' =>
              isset($order['showSignAndStamp'])
                ? $order['showSignAndStamp']
                : '',
            'currencyid' =>
              isset($order['currencyid']) ? $order['currencyid'] : '',
            'currencyposition' =>
              isset($order['currencyposition'])
                ? $order['currencyposition']
                : '',
            'numberformat' =>
              isset($order['numberformat']) ? $order['numberformat'] : '',
            'numberdecimals' =>
              isset($order['numberdecimals']) ? $order['numberdecimals'] : '',
            'numberthousands' =>
              isset($order['numberthousands']) ? $order['numberthousands'] : '',
            'numberprecision' =>
              isset($order['numberprecision']) ? $order['numberprecision'] : '',
            'notes' => isset($order['notes']) ? $order['notes'] : '',
            'bankaccountid' =>
              isset($order['bankaccountid']) ? $order['bankaccountid'] : '',
            'thirdRelationType' =>
              isset($order['thirdRelationType'])
                ? $order['thirdRelationType']
                : '',
            'auxCode' => isset($order['auxCode']) ? $order['auxCode'] : '',
            'thirdemail' =>
              isset($order['thirdemail']) ? $order['thirdemail'] : '',
            'thirdtel' => isset($order['thirdtel']) ? $order['thirdtel'] : '',
            'thirdmobile' =>
              isset($order['thirdmobile']) ? $order['thirdmobile'] : '',
            'lastpayment' =>
              isset($order['lastpayment']) ? $order['lastpayment'] : '',
            'payDateCustomUnix' =>
              isset($order['payDateCustomUnix'])
                ? $order['payDateCustomUnix']
                : '',
            'third_addr_name' =>
              isset($order['third_addr_name']) ? $order['third_addr_name'] : '',
            'third_addr_part1' =>
              isset($order['third_addr_part1'])
                ? $order['third_addr_part1']
                : '',
            'third_addr_part2' =>
              isset($order['third_addr_part2'])
                ? $order['third_addr_part2']
                : '',
            'third_addr_part3' =>
              isset($order['third_addr_part3'])
                ? $order['third_addr_part3']
                : '',
            'third_addr_part4' =>
              isset($order['third_addr_part4'])
                ? $order['third_addr_part4']
                : '',
            'third_addr_zip' =>
              isset($order['third_addr_zip']) ? $order['third_addr_zip'] : '',
            'third_addr_town' =>
              isset($order['third_addr_town']) ? $order['third_addr_town'] : '',
            'third_addr_countrycode' =>
              isset($order['third_addr_countrycode'])
                ? $order['third_addr_countrycode']
                : '',
            'ship_addr_name' =>
              isset($order['ship_addr_name']) ? $order['ship_addr_name'] : '',
            'ship_addr_part1' =>
              isset($order['ship_addr_part1']) ? $order['ship_addr_part1'] : '',
            'ship_addr_part2' =>
              isset($order['ship_addr_part2']) ? $order['ship_addr_part2'] : '',
            'ship_addr_part3' =>
              isset($order['ship_addr_part3']) ? $order['ship_addr_part3'] : '',
            'ship_addr_part4' =>
              isset($order['ship_addr_part4']) ? $order['ship_addr_part4'] : '',
            'ship_addr_zip' =>
              isset($order['ship_addr_zip']) ? $order['ship_addr_zip'] : '',
            'ship_addr_town' =>
              isset($order['ship_addr_town']) ? $order['ship_addr_town'] : '',
            'ship_addr_countrycode' =>
              isset($order['ship_addr_countrycode'])
                ? $order['ship_addr_countrycode']
                : '',
            'note' => isset($order['note']) ? $order['note'] : '',
            'step_color' =>
              isset($order['step_color']) ? $order['step_color'] : '',
            'step_hex' => isset($order['step_hex']) ? $order['step_hex'] : '',
            'step_label' =>
              isset($order['step_label']) ? $order['step_label'] : '',
            'step_css' => isset($order['step_css']) ? $order['step_css'] : '',
            'step_banner' =>
              isset($order['step_banner']) ? $order['step_banner'] : '',
            'step_id' => isset($order['step_id']) ? $order['step_id'] : '',
            'doctypestep_color' =>
              isset($order['doctypestep_color'])
                ? $order['doctypestep_color']
                : '',
            'doctypestep_hex' =>
              isset($order['doctypestep_hex']) ? $order['doctypestep_hex'] : '',
            'doctypestep_label' =>
              isset($order['doctypestep_label'])
                ? $order['doctypestep_label']
                : '',
            'doctypestep_css' =>
              isset($order['doctypestep_css']) ? $order['doctypestep_css'] : '',
            'doctypestep_id' =>
              isset($order['doctypestep_id']) ? $order['doctypestep_id'] : '',
            'displayed_payMediumsText' =>
              isset($order['displayed_payMediumsText'])
                ? $order['displayed_payMediumsText']
                : '',
            'formatted_totalAmount' =>
              isset($order['formatted_totalAmount'])
                ? $order['formatted_totalAmount']
                : '',
            'formatted_totalAmountTaxesFree' =>
              isset($order['formatted_totalAmountTaxesFree'])
                ? $order['formatted_totalAmountTaxesFree']
                : '',
            'formatted_created' =>
              isset($order['formatted_created'])
                ? $order['formatted_created']
                : '',
            'formatted_displayedDate' =>
              isset($order['formatted_displayedDate'])
                ? $order['formatted_displayedDate']
                : '',
            'formatted_payDateCustom' =>
              isset($order['formatted_payDateCustom'])
                ? $order['formatted_payDateCustom']
                : '',
            'formatted_serviceDateStart' =>
              isset($order['formatted_serviceDateStart'])
                ? $order['formatted_serviceDateStart']
                : '',
            'formatted_serviceDateStop' =>
              isset($order['formatted_serviceDateStop'])
                ? $order['formatted_serviceDateStop']
                : '',
            'formatted_lastSepaExportDate' =>
              isset($order['formatted_lastSepaExportDate'])
                ? $order['formatted_lastSepaExportDate']
                : '',
            'formatted_lastpayment' =>
              isset($order['formatted_lastpayment'])
                ? $order['formatted_lastpayment']
                : '',
            'formatted_expireDate' =>
              isset($order['formatted_expireDate'])
                ? $order['formatted_expireDate']
                : '',
            'noedit' => isset($order['noedit']) ? $order['noedit'] : '',
            'publicLinkShort' =>
              isset($order['publicLinkShort']) ? $order['publicLinkShort'] : '',
            'address' => isset($order['address']) ? $order['address'] : '',
            'shippingAddress' =>
              isset($order['shippingAddress']) ? $order['shippingAddress'] : '',
            'weightFormatted' =>
              isset($order['weightFormatted']) ? $order['weightFormatted'] : '',
            'weightFormattedDisplayed' =>
              isset($order['weightFormattedDisplayed'])
                ? $order['weightFormattedDisplayed']
                : '',
            'thirdStatus' =>
              isset($order['thirdStatus']) ? $order['thirdStatus'] : ''
          ],
          'sellsy_id'
        );
      }
      // var_dump($ordersRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }

  // get all sellsy invoices
  public function sellsy_invoices()
  {
    $pagenum = 1;
    $nbpages = $pagenum + 1;
    do {
      $invoicesRequest = $this->sellsy
        ->Document()
        ->getList([
          'doctype' => 'invoice',
          'pagination' => ['nbperpage' => 100, 'pagenum' => $pagenum]
        ])
        ->getResponse();
      $nbpages = $invoicesRequest['infos']['nbpages'];

      $invoices = $invoicesRequest['result'];
      foreach ($invoices as $invoiceId => $invoice) {
        $this->addOrUpdateDB(
          'sellsy_invoices',
          $invoiceId,
          [
            'corpname' =>
              isset($invoice['corpname']) ? $invoice['corpname'] : '',
            'ownerFullName' =>
              isset($invoice['ownerFullName']) ? $invoice['ownerFullName'] : '',
            'status' => isset($invoice['status']) ? $invoice['status'] : '',
            'filename' =>
              isset($invoice['filename']) ? $invoice['filename'] : '',
            'fileid' => isset($invoice['fileid']) ? $invoice['fileid'] : '',
            'nbpages' => isset($invoice['nbpages']) ? $invoice['nbpages'] : '',
            'ident' => isset($invoice['ident']) ? $invoice['ident'] : '',
            'thirdident' =>
              isset($invoice['thirdident']) ? $invoice['thirdident'] : '',
            'thirdname' =>
              isset($invoice['thirdname']) ? $invoice['thirdname'] : '',
            'thirdid' => isset($invoice['thirdid']) ? $invoice['thirdid'] : '',
            'thirdvatnum' =>
              isset($invoice['thirdvatnum']) ? $invoice['thirdvatnum'] : '',
            'contactId' =>
              isset($invoice['contactId']) ? $invoice['contactId'] : '',
            'contactName' =>
              isset($invoice['contactName']) ? $invoice['contactName'] : '',
            'displayedDate' =>
              isset($invoice['displayedDate']) ? $invoice['displayedDate'] : '',
            'currencysymbol' =>
              isset($invoice['currencysymbol'])
                ? $invoice['currencysymbol']
                : '',
            'subject' => isset($invoice['subject']) ? $invoice['subject'] : '',
            'docspeakerText' =>
              isset($invoice['docspeakerText'])
                ? $invoice['docspeakerText']
                : '',
            'docspeakerStaffId' =>
              isset($invoice['docspeakerStaffId'])
                ? $invoice['docspeakerStaffId']
                : '',
            'docspeakerStaffFullName' =>
              isset($invoice['docspeakerStaffFullName'])
                ? $invoice['docspeakerStaffFullName']
                : '',
            'corpaddressid' =>
              isset($invoice['corpaddressid']) ? $invoice['corpaddressid'] : '',
            'thirdaddressid' =>
              isset($invoice['thirdaddressid'])
                ? $invoice['thirdaddressid']
                : '',
            'shipaddressid' =>
              isset($invoice['shipaddressid']) ? $invoice['shipaddressid'] : '',
            'rowsAmount' =>
              isset($invoice['rowsAmount']) ? $invoice['rowsAmount'] : '',
            'discountPercent' =>
              isset($invoice['discountPercent'])
                ? $invoice['discountPercent']
                : '',
            'discountAmount' =>
              isset($invoice['discountAmount'])
                ? $invoice['discountAmount']
                : '',
            'rowsAmountDiscounted' =>
              isset($invoice['rowsAmountDiscounted'])
                ? $invoice['rowsAmountDiscounted']
                : '',
            'offerAmount' =>
              isset($invoice['offerAmount']) ? $invoice['offerAmount'] : '',
            'rowsAmountAllInc' =>
              isset($invoice['rowsAmountAllInc'])
                ? $invoice['rowsAmountAllInc']
                : '',
            'packagingsAmount' =>
              isset($invoice['packagingsAmount'])
                ? $invoice['packagingsAmount']
                : '',
            'shippingsAmount' =>
              isset($invoice['shippingsAmount'])
                ? $invoice['shippingsAmount']
                : '',
            'totalAmountTaxesFree' =>
              isset($invoice['totalAmountTaxesFree'])
                ? $invoice['totalAmountTaxesFree']
                : '',
            'taxesAmountSum' =>
              isset($invoice['taxesAmountSum'])
                ? $invoice['taxesAmountSum']
                : '',
            'taxesAmountDetails' =>
              isset($invoice['taxesAmountDetails'])
                ? $invoice['taxesAmountDetails']
                : '',
            'totalAmount' =>
              isset($invoice['totalAmount']) ? $invoice['totalAmount'] : '',
            'useEcotaxe' =>
              isset($invoice['useEcotaxe']) ? $invoice['useEcotaxe'] : '',
            'totalEcoTaxFree' =>
              isset($invoice['totalEcoTaxFree'])
                ? $invoice['totalEcoTaxFree']
                : '',
            'totalEcoTaxInc' =>
              isset($invoice['totalEcoTaxInc'])
                ? $invoice['totalEcoTaxInc']
                : '',
            'ecoTaxId' =>
              isset($invoice['ecoTaxId']) ? $invoice['ecoTaxId'] : '',
            'taxBasis' =>
              isset($invoice['taxBasis']) ? $invoice['taxBasis'] : '',
            'payDateText' =>
              isset($invoice['payDateText']) ? $invoice['payDateText'] : '',
            'payDateCustom' =>
              isset($invoice['payDateCustom']) ? $invoice['payDateCustom'] : '',
            'hasDeadlines' =>
              isset($invoice['hasDeadlines']) ? $invoice['hasDeadlines'] : '',
            'payMediumsText' =>
              isset($invoice['payMediumsText'])
                ? $invoice['payMediumsText']
                : '',
            'payCheckOrderText' =>
              isset($invoice['payCheckOrderText'])
                ? $invoice['payCheckOrderText']
                : '',
            'payBankAccountText' =>
              isset($invoice['payBankAccountText'])
                ? $invoice['payBankAccountText']
                : '',
            'shippingNbParcels' =>
              isset($invoice['shippingNbParcels'])
                ? $invoice['shippingNbParcels']
                : '',
            'shippingWeight' =>
              isset($invoice['shippingWeight'])
                ? $invoice['shippingWeight']
                : '',
            'shippingWeightUnit' =>
              isset($invoice['shippingWeightUnit'])
                ? $invoice['shippingWeightUnit']
                : '',
            'shippingVolume' =>
              isset($invoice['shippingVolume'])
                ? $invoice['shippingVolume']
                : '',
            'shippingTrackingNumber' =>
              isset($invoice['shippingTrackingNumber'])
                ? $invoice['shippingTrackingNumber']
                : '',
            'shippingTrackingUrl' =>
              isset($invoice['shippingTrackingUrl'])
                ? $invoice['shippingTrackingUrl']
                : '',
            'shippingDate' =>
              isset($invoice['shippingDate']) ? $invoice['shippingDate'] : '',
            'saveThirdPrefs' =>
              isset($invoice['saveThirdPrefs'])
                ? $invoice['saveThirdPrefs']
                : '',
            'displayShipAddress' =>
              isset($invoice['displayShipAddress'])
                ? $invoice['displayShipAddress']
                : '',
            'analyticsCode' =>
              isset($invoice['analyticsCode']) ? $invoice['analyticsCode'] : '',
            'recorded' =>
              isset($invoice['recorded']) ? $invoice['recorded'] : '',
            'recordable' =>
              isset($invoice['recordable']) ? $invoice['recordable'] : '',
            'rateCategory' =>
              isset($invoice['rateCategory']) ? $invoice['rateCategory'] : '',
            'isTaxesInc' =>
              isset($invoice['isTaxesInc']) ? $invoice['isTaxesInc'] : '',
            'hasDoubleVat' =>
              isset($invoice['hasDoubleVat']) ? $invoice['hasDoubleVat'] : '',
            'stockImpact' =>
              isset($invoice['stockImpact']) ? $invoice['stockImpact'] : '',
            'isFromPresta' =>
              isset($invoice['isFromPresta']) ? $invoice['isFromPresta'] : '',
            'eCommerceShopId' =>
              isset($invoice['eCommerceShopId'])
                ? $invoice['eCommerceShopId']
                : '',
            'signcoords' =>
              isset($invoice['signcoords']) ? $invoice['signcoords'] : '',
            'esignID' => isset($invoice['esignID']) ? $invoice['esignID'] : '',
            'promotionid' =>
              isset($invoice['promotionid']) ? $invoice['promotionid'] : '',
            'useServiceDates' =>
              isset($invoice['useServiceDates'])
                ? $invoice['useServiceDates']
                : '',
            'serviceDateStart' =>
              isset($invoice['serviceDateStart'])
                ? $invoice['serviceDateStart']
                : '',
            'serviceDateStop' =>
              isset($invoice['serviceDateStop'])
                ? $invoice['serviceDateStop']
                : '',
            'locked' => isset($invoice['locked']) ? $invoice['locked'] : '',
            'reconciledStatus' =>
              isset($invoice['reconciledStatus'])
                ? $invoice['reconciledStatus']
                : '',
            'corpid' => isset($invoice['corpid']) ? $invoice['corpid'] : '',
            'ownerid' => isset($invoice['ownerid']) ? $invoice['ownerid'] : '',
            'linkedtype' =>
              isset($invoice['linkedtype']) ? $invoice['linkedtype'] : '',
            'linkedid' =>
              isset($invoice['linkedid']) ? $invoice['linkedid'] : '',
            'created' => isset($invoice['created']) ? $invoice['created'] : '',
            'prefsid' => isset($invoice['prefsid']) ? $invoice['prefsid'] : '',
            'parentid' =>
              isset($invoice['parentid']) ? $invoice['parentid'] : '',
            'docmapid' =>
              isset($invoice['docmapid']) ? $invoice['docmapid'] : '',
            'hasVat' => isset($invoice['hasVat']) ? $invoice['hasVat'] : '',
            'doctypeid' =>
              isset($invoice['doctypeid']) ? $invoice['doctypeid'] : '',
            'step' => isset($invoice['step']) ? $invoice['step'] : '',
            'isDeposit' =>
              isset($invoice['isDeposit']) ? $invoice['isDeposit'] : '',
            'posId' => isset($invoice['posId']) ? $invoice['posId'] : '',
            'dueAmount' =>
              isset($invoice['dueAmount']) ? $invoice['dueAmount'] : '',
            'isSepaExported' =>
              isset($invoice['isSepaExported'])
                ? $invoice['isSepaExported']
                : '',
            'lastSepaExportDate' =>
              isset($invoice['lastSepaExportDate'])
                ? $invoice['lastSepaExportDate']
                : '',
            'currencyid' =>
              isset($invoice['currencyid']) ? $invoice['currencyid'] : '',
            'currencyposition' =>
              isset($invoice['currencyposition'])
                ? $invoice['currencyposition']
                : '',
            'numberformat' =>
              isset($invoice['numberformat']) ? $invoice['numberformat'] : '',
            'numberdecimals' =>
              isset($invoice['numberdecimals'])
                ? $invoice['numberdecimals']
                : '',
            'numberthousands' =>
              isset($invoice['numberthousands'])
                ? $invoice['numberthousands']
                : '',
            'numberprecision' =>
              isset($invoice['numberprecision'])
                ? $invoice['numberprecision']
                : '',
            'notes' => isset($invoice['notes']) ? $invoice['notes'] : '',
            'bankaccountid' =>
              isset($invoice['bankaccountid']) ? $invoice['bankaccountid'] : '',
            'thirdRelationType' =>
              isset($invoice['thirdRelationType'])
                ? $invoice['thirdRelationType']
                : '',
            'auxCode' => isset($invoice['auxCode']) ? $invoice['auxCode'] : '',
            'thirdemail' =>
              isset($invoice['thirdemail']) ? $invoice['thirdemail'] : '',
            'thirdtel' =>
              isset($invoice['thirdtel']) ? $invoice['thirdtel'] : '',
            'thirdmobile' =>
              isset($invoice['thirdmobile']) ? $invoice['thirdmobile'] : '',
            'lastpayment' =>
              isset($invoice['lastpayment']) ? $invoice['lastpayment'] : '',
            'payDateCustomUnix' =>
              isset($invoice['payDateCustomUnix'])
                ? $invoice['payDateCustomUnix']
                : '',
            'formatted_dueAmount' =>
              isset($invoice['formatted_dueAmount'])
                ? $invoice['formatted_dueAmount']
                : '',
            'formatted_marge' =>
              isset($invoice['formatted_marge'])
                ? $invoice['formatted_marge']
                : '',
            'formatted_tauxMarque' =>
              isset($invoice['formatted_tauxMarque'])
                ? $invoice['formatted_tauxMarque']
                : '',
            'formatted_tauxMarge' =>
              isset($invoice['formatted_tauxMarge'])
                ? $invoice['formatted_tauxMarge']
                : '',
            'note' => isset($invoice['note']) ? $invoice['note'] : '',
            'step_color' =>
              isset($invoice['step_color']) ? $invoice['step_color'] : '',
            'step_hex' =>
              isset($invoice['step_hex']) ? $invoice['step_hex'] : '',
            'step_label' =>
              isset($invoice['step_label']) ? $invoice['step_label'] : '',
            'step_css' =>
              isset($invoice['step_css']) ? $invoice['step_css'] : '',
            'step_banner' =>
              isset($invoice['step_banner']) ? $invoice['step_banner'] : '',
            'step_id' => isset($invoice['step_id']) ? $invoice['step_id'] : '',
            'displayed_payMediumsText' =>
              isset($invoice['displayed_payMediumsText'])
                ? $invoice['displayed_payMediumsText']
                : '',
            'formatted_totalAmount' =>
              isset($invoice['formatted_totalAmount'])
                ? $invoice['formatted_totalAmount']
                : '',
            'formatted_totalAmountTaxesFree' =>
              isset($invoice['formatted_totalAmountTaxesFree'])
                ? $invoice['formatted_totalAmountTaxesFree']
                : '',
            'formatted_created' =>
              isset($invoice['formatted_created'])
                ? $invoice['formatted_created']
                : '',
            'formatted_displayedDate' =>
              isset($invoice['formatted_displayedDate'])
                ? $invoice['formatted_displayedDate']
                : '',
            'formatted_payDateCustom' =>
              isset($invoice['formatted_payDateCustom'])
                ? $invoice['formatted_payDateCustom']
                : '',
            'formatted_serviceDateStart' =>
              isset($invoice['formatted_serviceDateStart'])
                ? $invoice['formatted_serviceDateStart']
                : '',
            'formatted_serviceDateStop' =>
              isset($invoice['formatted_serviceDateStop'])
                ? $invoice['formatted_serviceDateStop']
                : '',
            'formatted_lastSepaExportDate' =>
              isset($invoice['formatted_lastSepaExportDate'])
                ? $invoice['formatted_lastSepaExportDate']
                : '',
            'formatted_lastpayment' =>
              isset($invoice['formatted_lastpayment'])
                ? $invoice['formatted_lastpayment']
                : '',
            'noedit' => isset($invoice['noedit']) ? $invoice['noedit'] : '',
            'rateCategoryFormated' =>
              isset($invoice['rateCategoryFormated'])
                ? $invoice['rateCategoryFormated']
                : '',
            'publicLinkShort' =>
              isset($invoice['publicLinkShort'])
                ? $invoice['publicLinkShort']
                : '',
            'thirdStatus' =>
              isset($invoice['thirdStatus']) ? $invoice['thirdStatus'] : ''
          ],
          'sellsy_id'
        );
      }
      // var_dump($invoicesRequest);
    } while ($pagenum++ < $nbpages);

    echo json_encode(['success' => true]);
  }

  public function all()
  {
    ob_start();
    $this->init_database();
    $this->sellsy_clients();
    $this->sellsy_contacts();
    $this->sellsy_orders();
    $this->sellsy_invoices();
    ob_end_clean();

    echo json_encode(['success' => true]);
  }
}
