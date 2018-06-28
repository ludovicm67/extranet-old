<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contacts extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('contacts', 'show');

    $this->db->order_by('name');
    $contacts = $this->db->get('contacts')->result();
    $this->view('contacts/list', ['contacts' => $contacts]);
  }

  public function show($id)
  {
    $this->checkPermission('contacts', 'show');

    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() <= 0) {
      redirect('/contacts');
    }
    $contact = $q->result()[0];

    $this->db->select(
      '*, contacts.id AS id, contacts.name AS name, types.name AS type'
    );
    $this->db->from('contacts');
    $this->db->join('types', 'types.id = contacts.type_id', 'left');
    $this->db->where('contacts.id', $id);
    $contact = $this->db->get()->result()[0];

    $this->db->select('*');
    $this->db->from('project_contacts');
    $this->db->join('projects', 'projects.id = project_contacts.project_id');
    $this->db->where('contact_id', $contact->id);
    $contact->projects = $this->db->get()->result();

    $this->view('contacts/show', ['contact' => $contact]);
  }

  public function delete($id)
  {
    $this->checkPermission('contacts', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() > 0) {
      $this->db->delete('contacts', ['id' => $id]);
      $this->writeLog('delete', 'contacts', $q->result()[0], $id);
      $this->session->set_flashdata(
        'success',
        'Le contact a bien été supprimé !'
      );
    } else {
      $this->session->set_flashdata('error', "Le contact n'existe pas.");
    }
    redirect('/contacts');
  }

  private function createTypeOnThFly($id)
  {
    if (!is_numeric($id) && !empty($id)) {
      $this->db->where('name', $id);
      $q = $this->db->get('types');
      if ($q->num_rows() > 0) {
        return $q->result()[0]->id;
      } else {
        $content = ['name' => $id];
        $this->db->insert('types', $content);
        $content['id'] = $this->db->insert_id();
        $this->writeLog('insert', 'types', $content, $content['id']);
        return $content['id'];
      }
    } else {
      return ($id == 0) ? null : $id;
    }
  }

  public function new()
  {
    $this->checkPermission('contacts', 'add');

    if (isset($_POST['name'])) {
      $contactName = strip_tags(trim($this->input->post('name')));
      $contactType = $this->createTypeOnThFly($this->input->post('type'));
      $contactMail = strip_tags(trim($this->input->post('mail')));
      $contactPhone = strip_tags(trim($this->input->post('phone')));
      $contactAddress = strip_tags(trim($this->input->post('address')));
      $contactOther = strip_tags(trim($this->input->post('other')));

      if (empty($contactName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/contacts/new');
      }

      $content = [
        'name' => $contactName,
        'type_id' => $contactType,
        'mail' => $contactMail,
        'phone' => $contactPhone,
        'address' => $contactAddress,
        'other' => $contactOther
      ];
      $this->db->insert('contacts', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'contacts', $content, $content['id']);

      $this->session->set_flashdata(
        'success',
        'Le contact a bien été créé avec succès !'
      );
      redirect('/contacts');
    }

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->view('contacts/new', ['types' => $types]);
  }

  public function newAjax()
  {
    header('Content-Type: application/json');

    if (!$this->hasPermission('contacts', 'add')) {
      echo json_encode([
        'success' => false,
        'message' => 'no permission to create a contact...'
      ]);
    }

    if (isset($_POST['name'])) {
      $contactName = strip_tags(trim($this->input->post('name')));
      $contactType = $this->createTypeOnThFly($this->input->post('type'));
      $contactMail = strip_tags(trim($this->input->post('mail')));
      $contactPhone = strip_tags(trim($this->input->post('phone')));
      $contactAddress = strip_tags(trim($this->input->post('address')));
      $contactOther = strip_tags(trim($this->input->post('other')));

      if (empty($contactName)) {
        echo json_encode([
          'success' => false,
          'message' => 'no name specified...'
        ]);
        return;
      }

      $content = [
        'name' => $contactName,
        'type_id' => $contactType,
        'mail' => $contactMail,
        'phone' => $contactPhone,
        'address' => $contactAddress,
        'other' => $contactOther
      ];
      $this->db->insert('contacts', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog('insert', 'contacts', $content, $content['id']);

      echo json_encode([
        'success' => true,
        'id' => $content['id'],
        'name' => $contactName
      ]);
      return;
    }

    echo json_encode(['success' => false, 'message' => 'empty fields']);
  }

  public function edit($id)
  {
    $this->checkPermission('contacts', 'edit');

    // check if contact exists
    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() <= 0) {
      redirect('/contacts');
    }
    $contact = $q->result()[0];

    if (isset($_POST['name'])) {
      $contactName = strip_tags(trim($this->input->post('name')));
      $contactType = $this->createTypeOnThFly($this->input->post('type'));
      $contactMail = strip_tags(trim($this->input->post('mail')));
      $contactPhone = strip_tags(trim($this->input->post('phone')));
      $contactAddress = strip_tags(trim($this->input->post('address')));
      $contactOther = strip_tags(trim($this->input->post('other')));

      if (empty($contactName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/contacts/new');
      }

      $content = [
        'name' => $contactName,
        'type_id' => $contactType,
        'mail' => $contactMail,
        'phone' => $contactPhone,
        'address' => $contactAddress,
        'other' => $contactOther
      ];
      $this->db->where('id', $id);
      $this->db->update('contacts', $content);
      $content['id'] = $id;
      $this->writeLog('update', 'contacts', $content, $content['id']);

      $this->session->set_flashdata(
        'success',
        'Le contact a bien été modifié avec succès !'
      );

      $urlAfter = ($this->urlAfter == '/') ? '/contacts' : $this->urlAfter;
      redirect($urlAfter);
    }

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->view('contacts/edit', ['contact' => $contact, 'types' => $types]);
  }
}
