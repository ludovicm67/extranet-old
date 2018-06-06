<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contacts extends CI_Controller
{
  public function index()
  {
    $this->db->order_by('name');
    $contacts = $this->db->get('contacts')->result();
    $this->load->view('contacts/list', ['contacts' => $contacts]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() <= 0) {
      redirect('/contacts', 'refresh');
    }
    $contact = $q->result()[0];

    $this->load->view('contacts/show', ['contact' => $contact]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() > 0) {
      $this->db->delete('contacts', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        'Le contact a bien été supprimé !'
      );
    } else {
      $this->session->set_flashdata('error', "Le contact n'existe pas.");
    }
    redirect('/contacts', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $contactName = strip_tags(trim($this->input->post('name')));
      $contactType = intval($this->input->post('type'));
      $contactMail = strip_tags(trim($this->input->post('mail')));
      $contactPhone = strip_tags(trim($this->input->post('phone')));
      $contactAddress = strip_tags(trim($this->input->post('address')));
      $contactOther = strip_tags(trim($this->input->post('other')));
      if ($contactType == 0) {
        $contactType = null;
      }

      if (empty($contactName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/contacts/new', 'refresh');
      }

      $this->db->insert('contacts', [
        'name' => $contactName,
        'type_id' => $contactType,
        'mail' => $contactMail,
        'phone' => $contactPhone,
        'address' => $contactAddress,
        'other' => $contactOther
      ]);
      $this->session->set_flashdata(
        'success',
        'Le contact a bien été créé avec succès !'
      );
      redirect('/contacts', 'refresh');
    }

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->load->view('contacts/new', ['types' => $types]);
  }

  public function edit($id)
  {
    // check if contact exists
    $this->db->where('id', $id);
    $q = $this->db->get('contacts');
    if ($q->num_rows() <= 0) {
      redirect('/contacts', 'refresh');
    }
    $contact = $q->result()[0];

    if (isset($_POST['name'])) {
      $contactName = strip_tags(trim($this->input->post('name')));
      $contactType = intval($this->input->post('type'));
      $contactMail = strip_tags(trim($this->input->post('mail')));
      $contactPhone = strip_tags(trim($this->input->post('phone')));
      $contactAddress = strip_tags(trim($this->input->post('address')));
      $contactOther = strip_tags(trim($this->input->post('other')));
      if ($contactType == 0) {
        $contactType = null;
      }

      if (empty($contactName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/contacts/new', 'refresh');
      }

      $this->db->where('id', $id);
      $this->db->update('contacts', [
        'name' => $contactName,
        'type_id' => $contactType,
        'mail' => $contactMail,
        'phone' => $contactPhone,
        'address' => $contactAddress,
        'other' => $contactOther
      ]);
      $this->session->set_flashdata(
        'success',
        'Le contact a bien été modifié avec succès !'
      );
      redirect('/contacts', 'refresh');
    }

    $this->db->select(['id', 'name']);
    $types = $this->db->get('types')->result();

    $this->load->view('contacts/edit', [
      'contact' => $contact,
      'types' => $types
    ]);
  }
}
