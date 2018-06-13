<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends MY_Controller
{
  public function index()
  {
    $r = ['js_exec_request' => false, 'js_request_url' => null];

    if ($this->db->table_exists('users') && $this->db->count_all('users') > 0) {
      redirect('/');
    }

    $this->session->unset_userdata('logged');

    // init database if needed
    if (!$this->db->table_exists('users')) {
      $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
        ? 'https'
        : 'http';
      $r['js_exec_request'] = true;
      $r['js_request_url'] =
        $protocol . '://' . $_SERVER['HTTP_HOST'] . '/cron/init_database';
    }

    $password = trim($this->input->post('password'));
    $mail = strip_tags(trim($this->input->post('mail')));
    if (!empty($password) && !empty($mail)) {
      $userFirstname = strip_tags(trim($this->input->post('firstname')));
      $userLastname = strip_tags(trim($this->input->post('lastname')));
      $userPassword = password_hash($password, PASSWORD_DEFAULT);
      $userMail = $mail;
      $userRole = null;
      $userAdmin = 1;

      $this->db->insert('users', [
        'firstname' => $userFirstname,
        'lastname' => $userLastname,
        'password' => $userPassword,
        'mail' => $userMail,
        'role_id' => $userRole,
        'is_admin' => $userAdmin
      ]);

      $this->session->set_flashdata(
        'success',
        'Le compte administrateur a bien été créé, vous pouvez désormais vous connecter !'
      );
      redirect('/login');
    }

    $this->view('setup', $r);
  }
}
