<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends MY_AuthController
{
  public function index()
  {
    $this->checkPermission('tags', 'show');

    $this->db->order_by('name');
    $tags = $this->db->get('tags')->result();
    $this->view('tags/list', ['tags' => $tags]);
  }

  public function show($id)
  {
    $this->checkPermission('tags', 'show');

    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() <= 0) {
      redirect('/tags');
    }
    $tag = $q->result()[0];

    $tag->projects = [];
    if ($this->hasPermission('projects', 'show')) {
      $this->db->select('*');
      $this->db->from('project_tags');
      $this->db->join('projects', 'projects.id = project_tags.project_id');
      $value = $this->input->get('value');
      if (isset($_GET['value'])) {
        $this->db->where('value', $value);
      }
      $this->db->where('tag_id', $tag->id);
      $projects = $this->db->get()->result();
      foreach ($projects as $p) {
        if (!isset($tag->projects[$p->project_id])) {
          $tag->projects[$p->project_id] = (object) [
            'values' => [],
            'name' => $p->name,
          ];
        }
        $tag->projects[$p->project_id]->values[] = $p->value;
      }
    }

    $this->view('tags/show', ['tag' => $tag]);
  }

  public function delete($id)
  {
    $this->checkPermission('tags', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() > 0) {
      $this->db->delete('tags', ['id' => $id]);
      $this->writeLog('delete', 'tags', $q->result()[0], $id);
      $this->session->set_flashdata('success', 'Le tag a bien été supprimé !');
    } else {
      $this->session->set_flashdata('error', "Le tag n'existe pas.");
    }
    redirect('/tags');
  }

  public function new()
  {
    $this->checkPermission('tags', 'add');

    if (isset($_POST['name'])) {
      $tagName = strtolower(
        str_replace(
          ' ',
          '_',
          preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('name'))
        )
      );
      if (empty($tagName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/tags/new');
      }
      $this->db->where('name', $tagName);
      $q = $this->db->get('tags');
      if ($q->num_rows() > 0) {
        $this->session->set_flashdata('error', 'Le tag existe déjà !');
      } else {
        $this->db->insert('tags', ['name' => $tagName]);
        $newId = $this->db->insert_id();
        $this->writeLog(
          'insert',
          'tags',
          ['name' => $tagName, 'id' => $newId],
          $newId
        );
        $this->session->set_flashdata(
          'success',
          'Le tag a bien été créé avec succès !'
        );
        redirect('/tags');
      }
    }

    $this->view('tags/new');
  }

  public function edit($id)
  {
    $this->checkPermission('tags', 'edit');

    // check if tag exists
    $this->db->where('id', $id);
    $q = $this->db->get('tags');
    if ($q->num_rows() <= 0) {
      redirect('/tags');
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
      if (empty($tagName)) {
        $this->session->set_flashdata('error', 'Veuillez insérer un nom !');
        redirect('/tags/new');
      }

      $this->db->where('id !=', $id);
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
        $this->writeLog(
          'update',
          'tags',
          ['name' => $tagName, 'id' => $id],
          $id
        );
        $this->session->set_flashdata(
          'success',
          'Le tag a bien été modifié avec succès !'
        );
        redirect('/tags');
      }
    }

    $this->view('tags/edit', ['tag' => $tag]);
  }
}
