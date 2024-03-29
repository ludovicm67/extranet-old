<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends MY_AuthController
{
  public function index()
  {
    $query = htmlspecialchars(strip_tags(trim($this->input->get('q'))));
    $res = (object) [
      'projects' => [],
      'users' => [],
      'contacts' => [],
      'sellsy_contacts' => [],
      'clients' => [],
      'tags' => [],
      'has_query' => false,
      'query' => null,
      'results' => 0
    ];
    if (!empty($query)) {
      $res->query = $query;
      $res->has_query = true;

      // clients
      if ($this->hasPermission('clients', 'show')) {
        $this->db->like('name', $query);
        $res->clients = $this->db->get('sellsy_clients')->result();
      }

      // projects
      if ($this->hasPermission('projects', 'show')) {
        $this->db->order_by('updated_at', 'desc');
        $this->db->order_by('id', 'desc');
        $this->db->like('name', $query);
        $res->projects = $this->db->get('projects')->result();
      }

      // contacts
      if ($this->hasPermission('contacts', 'show')) {
        $this->db->like('name', $query);
        $res->contacts = $this->db->get('contacts')->result();
      }

      // users
      if ($this->hasPermission('users', 'show')) {
        $this->db->select('*, roles.name AS role, users.id AS id');
        $this->db->order_by('users.id', 'desc');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->like("CONCAT(firstname, ' ', lastname)", $query);
        $this->db->or_like("CONCAT(lastname, ' ', firstname)", $query);
        $this->db->or_like('email', $query);
        $res->users = $this->db->get('users')->result();
      }

      // sellsy_contacts
      if ($this->hasPermission('clients', 'show')) {
        $this->db->select(
          'sellsy_clients.id AS client_id, sellsy_clients.name AS client_name, sellsy_contacts.fullName'
        );
        $this->db->order_by('sellsy_clients.sellsy_id', 'desc');
        $this->db->join(
          'sellsy_clients',
          'sellsy_clients.sellsy_id = sellsy_contacts.thirdid'
        );
        $this->db->like('sellsy_contacts.fullName', $query);
        $res->sellsy_contacts = $this->db->get('sellsy_contacts')->result();
      }

      // tags
      if ($this->hasPermission('tags', 'show')) {
        $this->db->order_by('name');
        $this->db->like('name', $query);
        $res->tags = $this->db->get('tags')->result();
      }

      $res->results += count($res->clients);
      $res->results += count($res->projects);
      $res->results += count($res->contacts);
      $res->results += count($res->users);
      $res->results += count($res->tags);
    }
    $this->view('search', ['results' => $res]);
  }
}
