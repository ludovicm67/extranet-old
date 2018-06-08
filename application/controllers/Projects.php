<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends MY_AuthController
{
  public function index()
  {
    $this->db->order_by('name');
    $projects = $this->db->get('projects')->result();
    $this->load->view('projects/list', ['projects' => $projects]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects', 'refresh');
    }
    $project = $q->result()[0];
    $project->client = null;

    $clientDB = $this->db
      ->get_where('sellsy_clients', ['id' => $project->client_id], 1, 0)
      ->result();
    if (count($clientDB) === 1) {
      $project->client = $clientDB[0];
    }

    $this->db->select(
      '*, contacts.id AS id, contacts.name AS name, types.name AS type'
    );
    $this->db->from('project_contacts');
    $this->db->join('contacts', 'contacts.id = project_contacts.contact_id');
    $this->db->join('types', 'types.id = contacts.type_id', 'left');
    $this->db->where('project_id', $project->id);
    $project->contacts = $this->db->get()->result();

    $this->db->select('*');
    $this->db->from('project_orders');
    $this->db->join(
      'sellsy_orders',
      'sellsy_orders.id = project_orders.order_id'
    );
    $this->db->where('project_id', $project->id);
    $ordersDB = $this->db->get()->result();
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

    $project->orders = $orders;

    $this->db->select('*');
    $this->db->from('project_tags');
    $this->db->join('tags', 'tags.id = project_tags.tag_id');
    $this->db->where('project_id', $project->id);
    $project->tags = $this->db->get()->result();

    $this->db->order_by('order', 'asc');
    $this->db->select(['name', 'value']);
    $project->urls = $this->db
      ->get_where('project_urls', ['project_id' => $project->id])
      ->result();

    $this->load->view('projects/show', ['project' => $project]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() > 0) {
      $this->db->delete('projects', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        'Le projet a bien été supprimé !'
      );
    } else {
      $this->session->set_flashdata('error', "Le projet n'existe pas.");
    }
    redirect('/projects', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $projectName = strip_tags(trim($this->input->post('name')));
      $projectClient = strip_tags(trim($this->input->post('client')));

      if (!empty($projectName)) {
        $this->db->insert('projects', [
          'name' => $projectName,
          'client_id' => ($projectClient == 0) ? null : $projectClient
        ]);
        $projectId = $this->db->insert_id();

        if (isset($_POST['contacts'])) {
          foreach (array_unique($this->input->post('contacts')) as $contact) {
            $contactId = intval($contact, 10);
            $this->db->insert('project_contacts', [
              'project_id' => $projectId,
              'contact_id' => $contactId
            ]);
          }
        }

        if (isset($_POST['orders'])) {
          foreach (array_unique($this->input->post('orders')) as $order) {
            $orderId = intval($order, 10);
            $this->db->insert('project_orders', [
              'project_id' => $projectId,
              'order_id' => $orderId
            ]);
          }
        }

        if (
          isset($_POST['tagName']) &&
          isset($_POST['tagName']) &&
          is_array($_POST['tagName']) == is_array($_POST['tagValue']) &&
          count($_POST['tagName']) &&
          count($_POST['tagValue'])
        ) {
          for ($i = 0; $i < count($_POST['tagName']); $i++) {
            $tagId = intval($this->input->post('tagName')[$i]);
            $tagVal = strip_tags(trim($this->input->post('tagValue')[$i]));
            if (!empty($tagId)) {
              $this->db->insert('project_tags', [
                'project_id' => $projectId,
                'tag_id' => $tagId,
                'value' => $tagVal
              ]);
            }
          }
        }

        if (
          isset($_POST['urlName']) &&
          isset($_POST['urlName']) &&
          is_array($_POST['urlName']) == is_array($_POST['urlValue']) &&
          count($_POST['urlName']) &&
          count($_POST['urlValue'])
        ) {
          for ($i = 0; $i < count($_POST['urlName']); $i++) {
            $urlName = strip_tags(trim($this->input->post('urlName')[$i]));
            $urlValue = strip_tags(trim($this->input->post('urlValue')[$i]));
            if (!empty($urlName) || !empty($urlValue)) {
              $this->db->insert('project_urls', [
                'project_id' => $projectId,
                'name' => $urlName,
                'value' => $urlValue,
                'order' => $i
              ]);
            }
          }
        }

        $this->session->set_flashdata(
          'success',
          'Le projet a bien été créé avec succès !'
        );
        redirect('/project/' . $projectId, 'refresh');
      }

      $this->session->set_flashdata(
        'error',
        'Veuillez donner un nom au projet.'
      );
    }

    $this->db->select(['id', 'fullName']);
    $clients = $this->db->get('sellsy_clients')->result();

    $this->db->select(['id', 'name']);
    $contacts = $this->db->get('contacts')->result();

    $this->db->select(['id', 'thirdname', 'subject']);
    $orders = $this->db->get('sellsy_orders')->result();

    $this->db->select(['id', 'name']);
    $tags = $this->db->get('tags')->result();

    $clientId = (isset($_GET['client_id']) && intval($_GET['client_id']) != 0)
      ? intval($_GET['client_id'])
      : null;
    $project = (object) ['client_id' => $clientId];

    $this->load->view('projects/new', [
      'project' => $project,
      'clients' => $clients,
      'contacts' => $contacts,
      'orders' => $orders,
      'tags' => $tags
    ]);
  }

  public function edit($id)
  {
    // check if project exists
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects', 'refresh');
    }

    // if form was submitted
    if (isset($_POST['name'])) {
      $projectName = strip_tags(trim($this->input->post('name')));
      $projectClient = strip_tags(trim($this->input->post('client')));

      if (!empty($projectName)) {
        $this->db->where('id', $id);
        $this->db->update('projects', [
          'name' => $projectName,
          'client_id' => ($projectClient == 0) ? null : $projectClient
        ]);
        $projectId = $id;

        $this->db->delete('project_contacts', ['project_id' => $id]);
        if (isset($_POST['contacts'])) {
          foreach (array_unique($this->input->post('contacts')) as $contact) {
            $contactId = intval($contact, 10);
            $this->db->insert('project_contacts', [
              'project_id' => $projectId,
              'contact_id' => $contactId
            ]);
          }
        }

        $this->db->delete('project_orders', ['project_id' => $id]);
        if (isset($_POST['orders'])) {
          foreach (array_unique($this->input->post('orders')) as $order) {
            $orderId = intval($order, 10);
            $this->db->insert('project_orders', [
              'project_id' => $projectId,
              'order_id' => $orderId
            ]);
          }
        }

        $this->db->delete('project_tags', ['project_id' => $id]);
        if (
          isset($_POST['tagName']) &&
          isset($_POST['tagName']) &&
          is_array($_POST['tagName']) == is_array($_POST['tagValue']) &&
          count($_POST['tagName']) &&
          count($_POST['tagValue'])
        ) {
          for ($i = 0; $i < count($_POST['tagName']); $i++) {
            $tagId = intval($this->input->post('tagName')[$i]);
            $tagVal = strip_tags(trim($this->input->post('tagValue')[$i]));
            if (!empty($tagId)) {
              $this->db->insert('project_tags', [
                'project_id' => $projectId,
                'tag_id' => $tagId,
                'value' => $tagVal
              ]);
            }
          }
        }

        $this->db->delete('project_urls', ['project_id' => $id]);
        if (
          isset($_POST['urlName']) &&
          isset($_POST['urlName']) &&
          is_array($_POST['urlName']) == is_array($_POST['urlValue']) &&
          count($_POST['urlName']) &&
          count($_POST['urlValue'])
        ) {
          for ($i = 0; $i < count($_POST['urlName']); $i++) {
            $urlName = strip_tags(trim($this->input->post('urlName')[$i]));
            $urlValue = strip_tags(trim($this->input->post('urlValue')[$i]));
            if (!empty($urlName) || !empty($urlValue)) {
              $this->db->insert('project_urls', [
                'project_id' => $projectId,
                'name' => $urlName,
                'value' => $urlValue,
                'order' => $i
              ]);
            }
          }
        }

        $this->session->set_flashdata(
          'success',
          'Le projet a bien été modifié avec succès !'
        );
        redirect('/project/' . $projectId, 'refresh');
      }

      $this->session->set_flashdata(
        'error',
        'Veuillez donner un nom au projet.'
      );
    }

    $project = $q->result()[0];

    $this->db->select('contact_id');
    $contactsDB = $this->db
      ->get_where('project_contacts', ['project_id' => $project->id])
      ->result();
    $project->contacts = array_map(function ($c) {
      return $c->contact_id;
    }, $contactsDB);

    $this->db->select('order_id');
    $ordersDB = $this->db
      ->get_where('project_orders', ['project_id' => $project->id])
      ->result();
    $project->orders = array_map(function ($c) {
      return $c->order_id;
    }, $ordersDB);

    $this->db->select(['tag_id', 'value']);
    $project->tags = $this->db
      ->get_where('project_tags', ['project_id' => $project->id])
      ->result();

    $this->db->order_by('order', 'asc');
    $this->db->select(['name', 'value']);
    $project->urls = $this->db
      ->get_where('project_urls', ['project_id' => $project->id])
      ->result();

    $this->db->select(['id', 'fullName']);
    $clients = $this->db->get('sellsy_clients')->result();

    $this->db->select(['id', 'name']);
    $contacts = $this->db->get('contacts')->result();

    $this->db->select(['id', 'thirdname', 'subject']);
    $orders = $this->db->get('sellsy_orders')->result();

    $this->db->select(['id', 'name']);
    $tags = $this->db->get('tags')->result();

    $this->load->view('projects/edit', [
      'project' => $project,
      'clients' => $clients,
      'contacts' => $contacts,
      'orders' => $orders,
      'tags' => $tags
    ]);
  }
}
