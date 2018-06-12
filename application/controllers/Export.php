<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export extends MY_AuthController
{
  // export?tag=X&value=xxxxxxx&type=X
  public function index()
  {
    $this->db->order_by('name');
    $contacts = $this->db->get('contacts')->result();

    foreach ($contacts as $contact) {
      $out = fopen('php://output', 'w');
      fputcsv($out, [
        $contact->name,
        $contact->mail,
        $contact->phone,
        $contact->address
      ]);
      fclose($out);
    }
    die('okok');
  }
}
