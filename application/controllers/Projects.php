<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends MY_AuthController
{
  private $isMyProject = false;
  private $myProjects = null;

  public function index()
  {
    $this->checkPermission('projects', 'show');

    $myId = $this->session->id;
    if (empty($myId)) {
      $myId = null;
    }
    $this->db->order_by('favorite', 'desc');
    $this->db->order_by('updated_at', 'desc');
    $this->db->order_by('id', 'desc');

    if (is_null($myId)) {
      $this->db->select('*, 0 AS favorite');
    } else {
      $this->db->select(
        '*, COALESCE(project_favorites.user_id, 0) AS favorite'
      );
      $this->db->join(
        'project_favorites',
        'projects.id = project_favorites.project_id AND user_id = ' . $myId,
        'left'
      );
    }

    $projects = $this->db->get('projects')->result();

    $this->view('projects/list', [
      'projects' => $projects,
      'myProjects' => $this->getMyProjects()
    ]);
  }

  private function getMyProjects()
  {
    if (is_null($this->myProjects)) {
      $this->db->select('project_id');
      $this->db->from('project_users');
      $this->db->where('user_id', $this->session->id);
      $this->myProjects = array_map(function ($p) {
        return $p->project_id;
      }, $this->db->get()->result());
    }
    return $this->myProjects;
  }

  private function imAssigned($id)
  {
    if (!empty($this->session->id)) {
      return in_array($id, $this->getMyProjects());
    }
    return false;
  }

  public function show($id)
  {
    $this->isMyProject = $this->imAssigned($id);
    if (!$this->isMyProject) {
      $this->checkPermission('projects', 'show');
    }

    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects');
    }
    $project = $q->result()[0];
    $project->client = null;

    $clientDB = $this->db
      ->get_where('sellsy_clients', ['id' => $project->client_id], 1, 0)
      ->result();
    if (
      count($clientDB) === 1 &&
      ($this->isMyProject || $this->hasPermission('clients', 'show'))
    ) {
      $project->client = $clientDB[0];
      $project->client->contacts = $this->db
        ->get_where('sellsy_contacts', [
          'thirdid' => $project->client->sellsy_id
        ])
        ->result();
    }

    $project->contacts = [];
    if ($this->isMyProject || $this->hasPermission('contacts', 'show')) {
      $this->db->select(
        '*, contacts.id AS id, contacts.name AS name, types.name AS type'
      );
      $this->db->from('project_contacts');
      $this->db->join('contacts', 'contacts.id = project_contacts.contact_id');
      $this->db->join('types', 'types.id = contacts.type_id', 'left');
      $this->db->where('project_id', $project->id);
      $project->contacts = $this->db->get()->result();
    }

    $orders = [];
    if ($this->isMyProject || $this->hasPermission('orders', 'show')) {
      $this->db->select('*');
      $this->db->from('project_orders');
      $this->db->join(
        'sellsy_orders',
        'sellsy_orders.id = project_orders.order_id'
      );
      $this->db->where('project_id', $project->id);
      $ordersDB = $this->db->get()->result();
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
        if ($this->isMyProject || $this->hasPermission('invoices', 'show')) {
          $orders[$invoice->parentid]->invoices[] = $invoice;
        }
        $orders[$invoice->parentid]->remainingOrderAmount -= floatval(
          $invoice->totalAmountTaxesFree
        );
        $orders[$invoice->parentid]->remainingDueAmount += floatval(
          $invoice->dueAmount
        );
      }
    }

    $project->orders = $orders;

    $project->tags = [];
    if ($this->isMyProject || $this->hasPermission('tags', 'show')) {
      $this->db->select('*');
      $this->db->from('project_tags');
      $this->db->join('tags', 'tags.id = project_tags.tag_id');
      $this->db->where('project_id', $project->id);
      $project->tags = $this->db->get()->result();
    }

    $project->users = [];
    if ($this->isMyProject || $this->hasPermission('users', 'show')) {
      $this->db->select('*');
      $this->db->from('project_users');
      $this->db->join('users', 'users.id = project_users.user_id');
      $this->db->where('project_id', $project->id);
      $project->users = $this->db->get()->result();
    }

    $project->urls = [];
    if ($this->isMyProject || $this->hasPermission('project_urls', 'show')) {
      $this->db->order_by('order', 'asc');
      $this->db->select(['name', 'value']);
      $project->urls = $this->db
        ->get_where('project_urls', ['project_id' => $project->id])
        ->result();
    }

    $this->view('projects/show', [
      'project' => $project,
      'isMyProject' => $this->isMyProject
    ]);
  }

  public function delete($id)
  {
    $this->checkPermission('projects', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() > 0) {
      $this->db->delete('projects', ['id' => $id]);
      $this->writeLog('delete', 'projects', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        'Le projet a bien été supprimé !'
      );
    } else {
      $this->session->set_flashdata('error', "Le projet n'existe pas.");
    }
    redirect('/projects');
  }

  private function createTagsOnThFly($id)
  {
    $tagName = strtolower(
      str_replace(' ', '_', preg_replace("/[^A-Za-z0-9 ]/", '', $id))
    );

    if (!is_numeric($id) && !empty($tagName)) {
      $this->db->where('name', $id);
      $q = $this->db->get('tags');
      if ($q->num_rows() > 0) {
        return $q->result()[0]->id;
      } else {
        $content = ['name' => $tagName];
        $this->db->insert('tags', $content);
        $content['id'] = $this->db->insert_id();
        $this->writeLog('insert', 'tags', $content, $content['id']);
        return $content['id'];
      }
    } else {
      return ($id == 0) ? null : $id;
    }
  }

  public function new()
  {
    $this->checkPermission('projects', 'add');

    if (isset($_POST['name'])) {
      $projectName = strip_tags(trim($this->input->post('name')));
      $projectDomain = strip_tags(trim($this->input->post('domain')));
      $projectClient = strip_tags(trim($this->input->post('client')));
      $projectNextAction = htmlspecialchars(
        trim($this->input->post('next_action'))
      );
      $projectEndAt = $this->input->post('end_at')
        ? date('Y-m-d', strtotime($this->input->post('end_at')))
        : null;

      if (!empty($projectName)) {
        $projectContent = [
          'name' => $projectName,
          'client_id' => ($projectClient == 0) ? null : $projectClient,
          'domain' => empty($projectDomain) ? null : $projectDomain,
          'next_action' => $projectNextAction,
          'end_at' => $projectEndAt
        ];
        $this->db->insert('projects', $projectContent);
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

        if (isset($_POST['users'])) {
          foreach (array_unique($this->input->post('users')) as $user) {
            $userId = intval($user, 10);
            $this->db->insert('project_users', [
              'project_id' => $projectId,
              'user_id' => $userId
            ]);
          }
        }

        $contentToLog = [
          'project_id' => $projectId,
          'project' => $projectContent,
          'contacts' => $this->input->post('contacts'),
          'orders' => $this->input->post('orders'),
          'users' => $this->input->post('users'),
          'urls' => [],
          'tags' => []
        ];

        if (
          isset($_POST['tagName']) &&
          isset($_POST['tagName']) &&
          is_array($_POST['tagName']) == is_array($_POST['tagValue']) &&
          count($_POST['tagName']) &&
          count($_POST['tagValue'])
        ) {
          for ($i = 0; $i < count($_POST['tagName']); $i++) {
            $tagId = $this->createTagsOnThFly(
              $this->input->post('tagName')[$i]
            );
            $tagVal = strip_tags(trim($this->input->post('tagValue')[$i]));
            if (!empty($tagId)) {
              $this->db->insert('project_tags', [
                'project_id' => $projectId,
                'tag_id' => $tagId,
                'value' => $tagVal
              ]);
              $contentToLog['tags'][] = [
                'tag_id' => $tagId,
                'value' => $tagVal
              ];
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
              $contentToLog['urls'][] = [
                'name' => $urlName,
                'value' => $urlValue,
                'order' => $i
              ];
            }
          }
        }

        $this->writeLog('insert', 'projects', $contentToLog, $projectId);

        $this->session->set_flashdata(
          'success',
          'Le projet a bien été créé avec succès !'
        );
        redirect('/project/' . $projectId);
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

    $users = $this->db->get('users')->result();

    $clientId = (isset($_GET['client_id']) && intval($_GET['client_id']) != 0)
      ? intval($_GET['client_id'])
      : null;
    $project = (object) ['client_id' => $clientId];

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->view('projects/new', [
      'project' => $project,
      'clients' => $clients,
      'contacts' => $contacts,
      'orders' => $orders,
      'tags' => $tags,
      'users' => $users,
      'types' => $types
    ]);
  }

  public function edit($id)
  {
    $this->isMyProject = $this->imAssigned($id);
    if (!$this->isMyProject) {
      $this->checkPermission('projects', 'edit');
    }

    // check if project exists
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects');
    }

    // if form was submitted
    if (isset($_POST['name'])) {
      $projectName = strip_tags(trim($this->input->post('name')));
      $projectDomain = strip_tags(trim($this->input->post('domain')));
      $projectClient = strip_tags(trim($this->input->post('client')));
      $projectNextAction = htmlspecialchars(
        trim($this->input->post('next_action'))
      );
      $projectEndAt = $this->input->post('end_at')
        ? date('Y-m-d', strtotime($this->input->post('end_at')))
        : null;

      if (!empty($projectName)) {
        $projectContent = [
          'name' => $projectName,
          'client_id' => ($projectClient == 0) ? null : $projectClient,
          'domain' => empty($projectDomain) ? null : $projectDomain,
          'next_action' => $projectNextAction,
          'end_at' => $projectEndAt
        ];
        $this->db->where('id', $id);
        $this->db->update('projects', $projectContent);
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

        $this->db->delete('project_users', ['project_id' => $id]);
        if (isset($_POST['users'])) {
          foreach (array_unique($this->input->post('users')) as $user) {
            $userId = intval($user, 10);
            $this->db->insert('project_users', [
              'project_id' => $projectId,
              'user_id' => $userId
            ]);
          }
        }

        $contentToLog = [
          'project_id' => $projectId,
          'project' => $projectContent,
          'contacts' => $this->input->post('contacts'),
          'orders' => $this->input->post('orders'),
          'users' => $this->input->post('users'),
          'urls' => [],
          'tags' => []
        ];

        $this->db->delete('project_tags', ['project_id' => $id]);
        if (
          isset($_POST['tagName']) &&
          isset($_POST['tagName']) &&
          is_array($_POST['tagName']) == is_array($_POST['tagValue']) &&
          count($_POST['tagName']) &&
          count($_POST['tagValue'])
        ) {
          for ($i = 0; $i < count($_POST['tagName']); $i++) {
            $tagId = $this->createTagsOnThFly(
              $this->input->post('tagName')[$i]
            );
            $tagVal = strip_tags(trim($this->input->post('tagValue')[$i]));
            if (!empty($tagId)) {
              $this->db->insert('project_tags', [
                'project_id' => $projectId,
                'tag_id' => $tagId,
                'value' => $tagVal
              ]);
              $contentToLog['tags'][] = [
                'tag_id' => $tagId,
                'value' => $tagVal
              ];
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
              $contentToLog['urls'][] = [
                'name' => $urlName,
                'value' => $urlValue,
                'order' => $i
              ];
            }
          }
        }

        $this->writeLog('update', 'projects', $contentToLog, $projectId);

        $this->session->set_flashdata(
          'success',
          'Le projet a bien été modifié avec succès !'
        );
        redirect('/project/' . $projectId);
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

    $this->db->select('user_id');
    $usersDB = $this->db
      ->get_where('project_users', ['project_id' => $project->id])
      ->result();
    $project->users = array_map(function ($c) {
      return $c->user_id;
    }, $usersDB);

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

    $users = $this->db->get('users')->result();

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->view('projects/edit', [
      'project' => $project,
      'clients' => $clients,
      'contacts' => $contacts,
      'orders' => $orders,
      'tags' => $tags,
      'users' => $users,
      'types' => $types
    ]);
  }

  public function fav($id)
  {
    // check if project exists
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      echo json_encode(['success' => false, 'message' => 'projet inexistant']);
      die();
    }

    if (empty($this->session->id)) {
      echo json_encode([
        'success' => false,
        'message' => 'utilisateur non connecté'
      ]);
      die();
    }

    $this->db->delete('project_favorites', [
      'project_id' => $id,
      'user_id' => $this->session->id
    ]);

    $this->db->insert('project_favorites', [
      'project_id' => $id,
      'user_id' => $this->session->id
    ]);

    echo json_encode(['success' => true]);
  }

  public function unfav($id)
  {
    // check if project exists
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      echo json_encode(['success' => false, 'message' => 'projet inexistant']);
      die();
    }

    if (empty($this->session->id)) {
      echo json_encode([
        'success' => false,
        'message' => 'utilisateur non connecté'
      ]);
      die();
    }

    $this->db->delete('project_favorites', [
      'project_id' => $id,
      'user_id' => $this->session->id
    ]);

    echo json_encode(['success' => true]);
  }
}
