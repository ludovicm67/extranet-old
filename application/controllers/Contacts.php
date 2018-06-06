<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contacts extends CI_Controller
{
  public function index()
  {
    $this->db->order_by('name');
    $tags = $this->db->get('tags')->result();
    $this->load->view('contacts/list', ['tags' => $tags]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() <= 0) {
      redirect('/tags', 'refresh');
    }
    $tag = $q->result()[0];

    $this->db->select('*');
    $this->db->from('project_tags');
    $this->db->join('projects', 'projects.id = project_tags.project_id');
    $value = $this->input->get('value');
    if (isset($_GET['value'])) {
      $this->db->where('value', $value);
    }
    $this->db->where('tag_id', $tag->id);
    $tag->projects = $this->db->get()->result();

    $this->load->view('contacts/show', ['tag' => $tag]);
  }

  public function delete($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() > 0) {
      $this->db->delete('tags', ['id' => $id]);
      $this->session->set_flashdata('success', 'Le tag a bien été supprimé !');
    } else {
      $this->session->set_flashdata('error', "Le tag n'existe pas.");
    }
    redirect('/tags', 'refresh');
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $tagName = strtolower(
        str_replace(
          ' ',
          '_',
          preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('name'))
        )
      );
      $this->db->where('name', $tagName);
      $q = $this->db->get('tags');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata('error', 'Le tag existe déjà !');
      } else {
        $this->db->insert('tags', ['name' => $tagName]);
        $this->session->set_flashdata(
          'success',
          'Le tag a bien été créé avec succès !'
        );
        redirect('/tags', 'refresh');
      }
    }

    $this->load->view('contacts/new');
  }

  public function edit($id)
  {
    // check if tag exists
    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() <= 0) {
      redirect('/tags', 'refresh');
    }
    $tag = $q->result()[0];

    if (isset($_POST['name'])) {
      $tagName = strtolower(
        str_replace(
          ' ',
          '_',
          preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('name'))
        )
      );
      $this->db->where('name', $tagName);
      $q = $this->db->get('tags');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata(
          'error',
          "Le tag n'as pas été modifié : un autre porte déjà le même nom !"
        );
      } else {
        $this->db->where('id', $id);
        $this->db->update('tags', ['name' => $tagName]);
        $this->session->set_flashdata(
          'success',
          'Le tag a bien été modifié avec succès !'
        );
        redirect('/tags', 'refresh');
      }
    }

    $this->load->view('contacts/edit', ['tag' => $tag]);
  }
}
