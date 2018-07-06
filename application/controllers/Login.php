<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
  public function index()
  {
    if ($this->session->has_userdata('logged')) {
      if ($this->session->userdata('logged') === true) {
        $url = (empty($this->session->default_page))
          ? '/'
          : $this->session->default_page;
        redirect($url);
      } else {
        $this->session->unset_userdata('logged');
      }
    }

    $password = strip_tags(trim($this->input->post('password')));
    $mail = strip_tags(trim($this->input->post('mail')));
    if (!empty($password) && !empty($mail)) {
      $this->db->where('email', $mail);
      $q = $this->db->get('users')->result();
      if (count($q) <= 0) {
        $this->session->set_flashdata('error', 'Mauvais identifiants !');
        $this->session->set_userdata(['logged' => false]);
      } else {
        // the mail is OK, just check the password
        $user = $q[0];
        if (password_verify($password, $user->password)) {
          // password is also OK, so log the user in
          $this->session->set_userdata([
            'id' => $user->id,
            'role' => $user->role_id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'is_admin' => ($user->is_admin == 1) ? true : false,
            'email' => $user->email,
            'logged' => true,
            'default_page' => $user->default_page
          ]);
          $this->session->set_flashdata(
            'success',
            'Vous êtes à présents connectés !'
          );
          redirect($user->default_page);
        } else {
          // bad password
          $this->session->set_flashdata('error', 'Mauvais identifiants !');
          $this->session->set_userdata(['logged' => false]);
        }
      }
    }

    $this->view('login');
  }
}
