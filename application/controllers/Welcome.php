<?php
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

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
      $this->db->where('user_id', $this->session->id);
      $userProjects = $this->db->get()->result();
    }
    $this->view('welcome_message', ['projects' => $userProjects]);
  }

  public function logout()
  {
    $this->userLogout();
  }
}
