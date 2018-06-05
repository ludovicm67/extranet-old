<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends CI_Controller
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
        $this->db->insert(
          'projects',
          (object) [
            'name' => $projectName,
            'client_id' => ($projectClient == 0) ? null : $projectClient
          ]
        );
        $this->session->set_flashdata(
          'success',
          'Le projet a bien été créé avec succès !'
        );
        redirect('/projects', 'refresh');
      }

      $this->session->set_flashdata(
        'error',
        "Veuillez donner un nom au projet."
      );
    }

    $this->db->select(['id', 'fullName']);
    $clients = $this->db->get('sellsy_clients')->result();

    $this->load->view('projects/new', ['clients' => $clients]);
  }
}
