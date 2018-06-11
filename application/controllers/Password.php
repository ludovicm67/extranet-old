<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Password extends MY_Controller
{
  public function reset()
  {
    $lastWeek = date('Y-m-d H:i:s', strtotime('-7 days'));
    $this->db->delete('reset_password', ['created_at <=' => $lastWeek]);

    $mail = htmlspecialchars(strip_tags(trim($this->input->post('mail'))));

    if (!empty($mail)) {
      $this->db->where('mail', $mail);
      $q = $this->db->get('users');
      if ($q->num_rows() <= 0) {
        $this->session->set_flashdata(
          'error',
          "Aucun compte utilisateur associé trouvé !"
        );
        redirect('/password/reset', 'refresh');
      }
      $user = $q->result()[0];

      $token = Uuid::uuid4()->toString();
      $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
        ? 'https'
        : 'http';
      $url =
        $protocol . '://' . $_SERVER['HTTP_HOST'] . '/password/token/' . $token;

      $this->db->insert('reset_password', [
        'user_id' => $user->id,
        'token' => $token
      ]);

      $this->load->library('email');
      $this->email->from(
        $this->db->dc->getConfValueDefault(
          'email_from',
          null,
          'noreply@example.com'
        ),
        $this->db->dc->getConfValueDefault('site_name', null, 'Gestion')
      );
      $this->email->to($user->mail);
      $this->email->subject('Réinitialisation du mot de passe');
      $this->email->message(
        "Utilisez le lien suivant pour réinitialiser votre mot de passe : " .
          $url
      );
      $this->email->send();

      $this->session->set_flashdata(
        'success',
        "Un mail contenant un lien permettant de redéfinir votre mot de passe vient de vous être envoyé par mail (n'oubliez pas de vérifier dans votre dossier de spams)."
      );
    }

    $this->load->view('password/reset');
  }

  public function token($token = null)
  {
    if (is_null($token)) {
      $this->session->set_flashdata('error', 'Veuillez renseigner un token !');
      redirect('/password/reset');
    }

    $tok = htmlspecialchars($token);

    $this->db->where('token', $tok);
    $q = $this->db->get('reset_password');
    if ($q->num_rows() <= 0) {
      $this->session->set_flashdata(
        'error',
        "Le lien a expiré ou n'est pas valide !"
      );
      redirect('/login', 'refresh');
    }
    $line = $q->result()[0];

    $pass = trim($this->input->post('password'));

    if (!empty($pass)) {
      $password = password_hash($pass, PASSWORD_DEFAULT);

      $this->db->where('id', $line->user_id);
      $this->db->update('users', ['password' => $password]);

      $this->db->delete('reset_password', ['id' => $line->id]);

      $this->session->set_flashdata(
        'success',
        "Le mot de passe a bien été modifié avec succès, vous pouvez dès à présent vous connecter."
      );
      redirect('/login', 'refresh');
    }

    $this->load->view('password/token');
  }
}
