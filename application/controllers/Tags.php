<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends CI_Controller
{
  public function index()
  {
    $tags = $this->db->get('tags')->result();
    $this->load->view('tags/list', ['tags' => $tags]);
  }

  public function show($id)
  {
    $this->load->view('tags/show', [
      'tag' => (object) ['name' => 'tag#' . $id]
    ]);
  }

  public function new()
  {
    if (isset($_POST['name'])) {
      $this->session->set_flashdata(
        'success',
        'Le tag a bien été créé avec succès !'
      );
      redirect('/tags', 'refresh');
      return;
    }

    $this->session->set_flashdata('error', 'Le tag existe déjà !');

    $this->load->view('tags/new');
  }
}
