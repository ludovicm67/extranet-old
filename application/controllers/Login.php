<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
  public function index()
  {
    if ($this->session->has_userdata('logged')) {
      if ($this->session->userdata('logged') === true) {
        redirect('/', 'refresh');
      } else {
        $this->session->unset_userdata('logged');
      }
    }

    // @TODO: check in database instead of this unsecure thing
    $email = 'test@example.com';
    $passwd = 'AtEbcighhotArO7';

    $password = strip_tags(trim($this->input->post('password')));
    $mail = strip_tags(trim($this->input->post('mail')));
    if (!empty($password) || !empty($mail)) {
      if ($password != $passwd || $mail != $email) {
        $this->session->set_flashdata('error', 'Mauvais identifiants !');
        $this->session->set_userdata(['mail' => null, 'logged' => false]);
      } else {
        $this->session->set_flashdata(
          'success',
          'Vous êtes à présents connectés !'
        );
        $this->session->set_userdata(['mail' => $mail, 'logged' => true]);
        redirect('/', 'refresh');
      }
    }

    $this->load->view('login');
  }
}
