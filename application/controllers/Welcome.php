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

    // leave
    if (!$this->hasPermissions('leave', 'show')) {
      $this->db->where('leave.user_id', $this->session->id);
    }
    $this->db->where('accepted', 0);
    $this->db->select('*, leave.id AS id');
    $this->db->order_by(
      '(CASE leave.accepted WHEN 0 THEN 1 WHEN 1 THEN 2 WHEN -1 THEN 3 END)',
      'asc'
    );
    $this->db->order_by('leave.start', 'desc');
    $this->db->order_by('leave.id', 'desc');
    $this->db->join('users', 'users.id = leave.user_id', 'left');
    $leave = $this->db->get('leave')->result();

    // expenses
    if (!$this->hasPermissions('expenses', 'show')) {
      $this->db->where('users.id', $this->session->id);
    }
    $this->db->where('accepted', 0);
    $this->db->select('*, expenses.id AS id');
    $this->db->order_by('expenses.id', 'desc');
    $this->db->join('users', 'users.id = expenses.user_id', 'left');
    $expenses = $this->db->get('expenses')->result();

    $this->view('welcome_message', [
      'favProjects' => $favProjects,
      'projects' => $userProjects,
      'leave' => $leave,
      'expenses' => $expenses
    ]);
  }

  public function logout()
  {
    $this->userLogout();
  }
}
