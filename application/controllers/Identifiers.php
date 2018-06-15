<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Identifiers extends MY_AuthController
{
  private $isMyProject = false;
  private $myProjects = null;

  public function index()
  {
    $this->checkPermission('identifiers', 'show');

    $this->db->order_by('name');
    $identifiers = $this->db->get('identifiers')->result();
    $this->view('identifiers/list', ['identifiers' => $identifiers]);
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

  public function delete($id)
  {
    $this->checkPermission('identifiers', 'delete');

    $this->db->where('id', $id);
    $q = $this->db->get('identifiers');
    if ($q->num_rows() > 0) {
      $this->db->delete('identifiers', ['id' => $id]);
      $this->writeLog('delete', 'identifiers', $q->result()[0], $id);
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
    $this->checkPermission('identifiers', 'add');

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
        $content = ['name' => $identifierName];
        $this->db->insert('identifiers', $content);
        $content['id'] = $this->db->insert_id();
        $this->writeLog('insert', 'identifiers', $content, $content['id']);
        $this->session->set_flashdata(
          'success',
          "Le type d'identifiant a bien été créé avec succès !"
        );
        redirect('/identifiers');
      }
    }

    $this->view('identifiers/new');
  }

  public function edit($id)
  {
    $this->checkPermission('identifiers', 'edit');

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
        $content = ['name' => $identifierName];
        $this->db->where('id', $id);
        $this->db->update('identifiers', $content);
        $content['id'] = $id;
        $this->writeLog('update', 'identifiers', $content, $content['id']);
        $this->session->set_flashdata(
          'success',
          "Le type d'identifiant a bien été modifié avec succès !"
        );
        redirect('/identifiers');
      }
    }

    $this->view('identifiers/edit', ['identifier' => $identifier]);
  }

  public function show($id)
  {
    $this->isMyProject = $this->imAssigned($id);
    if (!$this->isMyProject) {
      $this->checkPermission('project_identifiers', 'show');
    }

    $this->db->where('id', $id);
    $q = $this->db->get('projects');
    if ($q->num_rows() <= 0) {
      redirect('/identifiers');
    }
    $project = $q->result()[0];

    // get all assigned users to check if has right to access to confidential datas or not
    $this->db->select('user_id');
    $usersDB = $this->db
      ->get_where('project_users', ['project_id' => $project->id])
      ->result();
    $users = array_map(function ($c) {
      return $c->user_id;
    }, $usersDB);

    if (
      !in_array($this->session->id, $users) &&
      !$this->hasPermission('project_confidential_identifiers', 'show') &&
      !$this->isMyProject
    ) {
      $this->db->where('confidential !=', 1);
    }
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

    $this->view('identifiers/show', [
      'project' => $project,
      'identifiers' => $identifiers,
      'isMyProject' => $this->isMyProject
    ]);
  }

  public function assign($id)
  {
    $this->isMyProject = $this->imAssigned($id);
    if (!$this->isMyProject) {
      $this->checkPermission('project_identifiers', 'add');
    }

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

      if (!$this->isMyProject && $confidential == 1) {
        $this->checkPermission('project_confidential_identifiers', 'add');
      }

      $content = [
        'project_id' => $id,
        'identifier_id' => $type,
        'value' => $value,
        'confidential' => $confidential
      ];
      $this->db->insert('project_identifiers', $content);
      $content['id'] = $this->db->insert_id();
      $this->writeLog(
        'insert',
        'project_identifiers',
        $content,
        $content['id']
      );

      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été ajouté avec succès !"
      );
      redirect('/identifiers/show/' . $id);
    }

    $this->db->select(['id', 'name']);
    $identifiers = $this->db->get('identifiers')->result();

    $this->view('identifiers/assign', [
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

    $this->isMyProject = $this->imAssigned($ident->project_id);
    if (!$this->isMyProject) {
      $this->checkPermission('project_identifiers', 'edit');
      if ($ident->confidential == 1) {
        $this->checkPermission('project_confidential_identifiers', 'edit');
      }
    }

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

      $content = [
        'identifier_id' => $type,
        'value' => $value,
        'confidential' => $confidential
      ];

      $this->db->where('id', $id);
      $this->db->update('project_identifiers', $content);
      $content['id'] = $id;
      $this->writeLog(
        'update',
        'project_identifiers',
        $content,
        $content['id']
      );

      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été modifié avec succès !"
      );
      redirect('/identifiers/show/' . $ident->project_id);
    }

    $this->db->select(['id', 'name']);
    $identifiers = $this->db->get('identifiers')->result();

    $this->view('identifiers/modify', [
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
      $res = $q->result()[0];
      $this->isMyProject = $this->imAssigned($res->project_id);
      if (!$this->isMyProject) {
        $this->checkPermission('project_identifiers', 'delete');
        if ($res->confidential == 1) {
          $this->checkPermission('project_confidential_identifiers', 'delete');
        }
      }

      $this->db->delete('project_identifiers', ['id' => $id]);
      $this->writeLog('delete', 'project_identifiers', $res, $id);
      $this->session->set_flashdata(
        'success',
        "L'identifiant a bien été supprimé !"
      );
      redirect('/identifiers/show/' . $res->project_id);
    } else {
      $this->session->set_flashdata('error', "L'identifiant n'existe pas.");
      redirect('/projects');
    }
  }
}
