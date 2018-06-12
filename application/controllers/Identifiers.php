<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Identifiers extends MY_AuthController
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
        "Le type d'identifiant n'existe pas."
      );
    }
    redirect('/identifiers');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $identifierName = strip_tags(trim($this->input->post('name')));

      if (empty($identifierName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/identifiers/new');
      }
      $this->db->where('name', $identifierName);
      $q = $this->db->get('identifiers');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le type d'identifiant existe déjà !"
        );
      } else {
        $this->db->insert('identifiers', ['name' => $identifierName]);
        $this->session->set_flashdata(
          'success',
          "Le type d'identifiant a bien été créé avec succès !"
        );
        redirect('/identifiers');
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
      redirect('/identifiers');
    }
    $identifier = $q->result()[0];

    if (isset($_POST['name'])) {
      $identifierName = strip_tags(trim($this->input->post('name')));

      if (empty($identifierName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/identifiers/new');
      }
      $this->db->where('id !=', $id);
      $this->db->where('name', $identifierName);
      $q = $this->db->get('identifiers');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le type d'identifiant n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('identifiers', ['name' => $identifierName]);
        $this->session->set_flashdata(
          'success',
          "Le type d'identifiant a bien été modifié avec succès !"
        );
        redirect('/identifiers');
      }
    }

    $this->load->view('identifiers/edit', ['identifier' => $identifier]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/identifiers');
    }
    $project = $q->result()[0];

    $this->db->select(
      '*, project_identifiers.id AS id, identifiers.name AS type'
    );
    $this->db->join(
      'identifiers',
      'identifiers.id = project_identifiers.identifier_id',
      'left'
    );
    $identifiers = $this->db
      ->get_where('project_identifiers', ['project_id' => $id])
      ->result();

    $this->load->view('identifiers/show', [
      'project' => $project,
      'identifiers' => $identifiers
    ]);
  }

  public function assign($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects');
    }
    $project = $q->result()[0];

    // if form was submitted
    if (count($_POST) > 0) {
      $type = ($this->input->post('type') == 0)
        ? null
        : $this->input->post('type');
      $value = htmlspecialchars(trim($this->input->post('value')));
      $confidential = (empty($this->input->post('confidential'))) ? 0 : 1;

      $this->db->insert('project_identifiers', [
        'project_id' => $id,
        'identifier_id' => $type,
        'value' => $value,
        'confidential' => $confidential
      ]);
      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été ajouté avec succès !"
      );
      redirect('/identifiers/show/' . $id);
    }

    $this->db->select(['id', 'name']);
    $identifiers = $this->db->get('identifiers')->result();

    $this->load->view('identifiers/assign', [
      'project' => $project,
      'identifiers' => $identifiers
    ]);
  }

  public function project_edit($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('project_identifiers');
    if ($q->num_rows() <= 0) {
      redirect('/projects');
    }
    $ident = $q->result()[0];

    $this->db->where('id', $ident->project_id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/projects');
    }
    $project = $q->result()[0];

    // if form was submitted
    if (count($_POST) > 0) {
      $type = ($this->input->post('type') == 0)
        ? null
        : $this->input->post('type');
      $value = htmlspecialchars(trim($this->input->post('value')));
      $confidential = (empty($this->input->post('confidential'))) ? 0 : 1;

      $this->db->where('id', $id);
      $this->db->update('project_identifiers', [
        'identifier_id' => $type,
        'value' => $value,
        'confidential' => $confidential
      ]);
      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été modifié avec succès !"
      );
      redirect('/identifiers/show/' . $ident->project_id);
    }

    $this->db->select(['id', 'name']);
    $identifiers = $this->db->get('identifiers')->result();

    $this->load->view('identifiers/modify', [
      'project' => $project,
      'identifiers' => $identifiers,
      'values' => $ident
    ]);
  }

  public function project_delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('project_identifiers');
    if ($q->num_rows() > 0) {
      $this->db->delete('project_identifiers', ['id' => $id]);
      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été supprimé !"
      );
      redirect('/identifiers/show/' . $q->result()[0]->project_id);
    } else {
      $this->session->set_flashdata('error', "L'identifiant n'existe pas.");
      redirect('/projects');
    }
  }
}
