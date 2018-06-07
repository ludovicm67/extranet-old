<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Identifiers extends CI_Controller
{
  public function index()
  {
    $this->db->order_by('name');
    $identifiers = $this->db->get('identifiers')->result();
    $this->load->view('identifiers/list', ['identifiers' => $identifiers]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('identifiers');
    if ($q->num_rows() > 0) {
      $this->db->delete('identifiers', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        "Le type d'identifiant a bien été supprimé !"
      );
    } else {
      $this->session->set_flashdata(
        'error',
        "Le type d'indentifiant n'existe pas."
      );
    }
    redirect('/identifiers', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $identifierName = strip_tags(trim($this->input->post('name')));

      if (empty($identifierName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/identifiers/new', 'refresh');
      }
      $this->db->where('name', $identifierName);
      $q = $this->db->get('identifiers');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le type d'indentifiant existe déjà !"
        );
      } else {
        $this->db->insert('identifiers', ['name' => $identifierName]);
        $this->session->set_flashdata(
          'success',
          "Le type d'indentifiant a bien été créé avec succès !"
        );
        redirect('/identifiers', 'refresh');
      }
    }

    $this->load->view('identifiers/new');
  }

  public function edit($id)
  {
    // check if identifier exists
    $this->db->where('id', $id);
    $q = $this->db->get('identifiers');
    if ($q->num_rows() <= 0) {
      redirect('/identifiers', 'refresh');
    }
    $identifier = $q->result()[0];

    if (isset($_POST['name'])) {
      $identifierName = strip_tags(trim($this->input->post('name')));

      if (empty($identifierName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/identifiers/new', 'refresh');
      }
      $this->db->where('name', $identifierName);
      $q = $this->db->get('identifiers');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le type d'indentifiant n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('identifiers', ['name' => $identifierName]);
        $this->session->set_flashdata(
          'success',
          "Le type d'indentifiant a bien été modifié avec succès !"
        );
        redirect('/identifiers', 'refresh');
      }
    }

    $this->load->view('identifiers/edit', ['identifier' => $identifier]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/identifiers', 'refresh');
    }
    $project = $q->result()[0];

    $this->load->view('identifiers/show', ['project' => $project]);
  }

  public function assign($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/identifiers', 'refresh');
    }
    $project = $q->result()[0];


    $this->db->select(['id', 'name']);
    $identifiers = $this->db->get('identifiers')->result();

    $this->load->view('identifiers/assign', [
      'project' => $project,
      'identifiers' => $identifiers
    ]);
  }
}
