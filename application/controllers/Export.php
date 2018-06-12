<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export extends MY_AuthController
{
  // export?tag=X&value=xxxxxxx&type=X
  private function getResults()
  {
    $getType = intval(trim($this->input->get('type')));
    $getTag = intval(trim($this->input->get('tag')));
    $getValue = trim($this->input->get('value'));
    $this->db->order_by('contact_name');
    if (!empty($getType) && $getType != 0) {
      $this->db->where('type_id', $getType);
    }
    $this->db->select(
      'mail, contacts.name AS contact_name, phone, address, projects.name AS project_name, domain, types.name AS type, contacts.id AS contact_id, type_id, project_contacts.project_id'
    );
    $this->db->join('types', 'contacts.type_id = types.id', 'left');
    $this->db->join(
      'project_contacts',
      'contacts.id = project_contacts.contact_id',
      'left'
    );
    $this->db->join(
      'projects',
      'project_contacts.project_id = projects.id',
      'left'
    );
    if (!empty($getTag) && $getTag != 0) {
      $this->db->join('project_tags', 'projects.id = project_tags.project_id');
      $this->db->where('tag_id', $getTag);
      if (!empty($getValue)) {
        $this->db->where('project_tags.value', $getValue);
      }
    }
    $contacts = $this->db->get('contacts')->result();

    return $contacts;
  }

  public function index()
  {
    $contacts = $this->getResults();

    $this->db->order_by('name');
    $types = $this->db->get('types')->result();

    $this->db->order_by('name');
    $tags = $this->db->get('tags')->result();

    $this->load->view('export', [
      'contacts' => $contacts,
      'types' => $types,
      'tags' => $tags,
      'download_url' =>
        '/export/download?' . strip_tags(trim($_SERVER['QUERY_STRING']))
    ]);
  }

  public function download()
  {
    $contacts = $this->getResults();
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=contacts.csv");
    $out = fopen('php://output', 'w');
    foreach ($contacts as $contact) {
      fputcsv($out, [
        $contact->mail,
        $contact->contact_name,
        $contact->phone,
        $contact->address,
        $contact->project_name,
        $contact->domain,
        $contact->type
      ]);
    }
    fclose($out);
  }
}
