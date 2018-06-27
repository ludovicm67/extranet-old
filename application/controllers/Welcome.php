<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_AuthController
{
  public function index()
  {
    $userProjects = [];
    if (!empty($this->session->id)) {
      $this->db->select('*');
      $this->db->from('project_users');
      $this->db->join('projects', 'projects.id = project_users.project_id');
      $this->db->order_by('updated_at', 'desc');
      $this->db->order_by('id', 'desc');
      $this->db->where('user_id', $this->session->id);
      $userProjects = $this->db->get()->result();
    }

    $myId = $this->session->id;
    if (empty($myId)) {
      $myId = null;
    }
    $this->db->order_by('favorite', 'desc');
    $this->db->order_by('updated_at', 'desc');
    $this->db->order_by('id', 'desc');

    if (is_null($myId)) {
      $this->db->select('*, 0 AS favorite');
    } else {
      $this->db->select(
        '*, COALESCE(project_favorites.user_id, 0) AS favorite'
      );
      $this->db->join(
        'project_favorites',
        'projects.id = project_favorites.project_id AND user_id = ' . $myId
      );
    }

    $favProjects = $this->db->get('projects')->result();

    $this->view('welcome_message', [
      'favProjects' => $favProjects,
      'projects' => $userProjects
    ]);
  }

  public function logout()
  {
    $this->userLogout();
  }
}
