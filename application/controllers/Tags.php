<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends CI_Controller
{
  public function index()
  {
    $this->db->order_by('name');
    $tags = $this->db->get('tags')->result();
    $this->load->view('tags/list', ['tags' => $tags]);
  }

  public function show($id)
  {
    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() <= 0) {
      redirect('/tags', 'refresh');
    }
    $tag = $q->result()[0];
    $this->load->view('tags/show', ['tag' => (object) ['name' => $tag->name]]);
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
        $this->db->insert('tags', (object) ['name' => $tagName]);
        $this->session->set_flashdata(
          'success',
          'Le tag a bien été créé avec succès !'
        );
        redirect('/tags', 'refresh');
      }
    }

    $this->load->view('tags/new');
  }
}
