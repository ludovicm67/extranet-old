<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  protected $isLogged = false;
  protected $userInfos = null;
  protected $userRights = null;

  public function __construct()
  {
    parent::__construct();

    if ($this->session->has_userdata('logged')) {
      if ($this->session->userdata('logged') === true) {
        $this->isLogged = true;
      } else {
        $this->session->unset_userdata('logged');
      }
    }

    if (
      !in_array($this->router->class, ['setup', 'cron']) &&
      (!$this->db->table_exists('users') || $this->db->count_all('users') <= 0)
    ) {
      redirect('/setup');
    }
  }

  public function view($name, $args = [])
  {
    if (!is_array($args)) {
      $args = [];
    }
    $args['controller'] = $this;
    $this->load->view($name, $args);
  }

  public function isLoggedIn()
  {
    return $this->isLogged;
  }

  public function userLogout()
  {
    $this->isLogged = false;
    $this->session->unset_userdata('logged');
    redirect('/login');
  }

  public function logDB($type, $table, $content = [])
  {
    $userId = ($this->isLoggedIn() && isset($this->session->id))
      ? $this->session->id
      : null;
    $queryType = strtolower(trim($type));
    $queryTable = strtolower(trim($table));
    $queryContent = json_encode($content);

    // @TODO: write data in a database table
  }

  public function hasPermission($permissionName, $values = [])
  {
    if (!is_array($values)) {
      $values = preg_split('/[\s*,\s*]*,+[\s*,\s*]*/', $values);
    }
    $requireShow = in_array('show', $values) ? true : false;
    $requireAdd = in_array('add', $values) ? true : false;
    $requireEdit = in_array('edit', $values) ? true : false;
    $requireDelete = in_array('delete', $values) ? true : false;

    // if no rights are required, just allow the access
    if (!$requireShow && !$requireAdd && !$requireEdit && !$requireDelete) {
      return true;
    }

    if ($this->session->logged == true) {
      if (empty($this->session->id)) {
        $this->userLogout();
      }

      // get user informations
      if (is_null($this->userInfos)) {
        $q = $this->db
          ->get_where('users', ['id' => intval($this->session->id)])
          ->result();
        if (empty($q) || count($q) <= 0) {
          $this->userLogout();
        }
        $this->userInfos = $q[0];
      }
      $user = $this->userInfos;

      // admin has full access
      if ($user->is_admin == 1) {
        return true;
      }

      // get user permissions
      if (is_null($this->userRights)) {
        $r = $this->db
          ->get_where('rights', ['role_id' => $user->role_id])
          ->result();
        $this->userRights = [];
        foreach ($r as $rItem) {
          $this->userRights[$rItem->name] = $rItem;
        }
      }

      // when user has no rights, don't need to continue
      if (
        empty($this->userRights) ||
        count($this->userRights) <= 0 ||
        !isset($this->userRights[$permissionName])
      ) {
        return false;
      }

      // check if has permission or not
      if ($requireShow && $this->userRights[$permissionName]->show != 1) {
        return false;
      }

      if ($requireAdd && $this->userRights[$permissionName]->add != 1) {
        return false;
      }

      if ($requireEdit && $this->userRights[$permissionName]->edit != 1) {
        return false;
      }

      if ($requireDelete && $this->userRights[$permissionName]->delete != 1) {
        return false;
      }

      // if here, user has access
      return true;
    }

    return false;
  }

  public function hasPermissions($permissionName, $values = [])
  {
    return $this->hasPermission($permissionName, $values);
  }

  // check if current user has required permissions; if not it will be redirected
  public function checkPermission($permissionName, $values = [])
  {
    $hasPermission = $this->hasPermission($permissionName, $values);
    if (!$hasPermission) {
      $this->session->set_flashdata(
        'error',
        "Vous n'avez pas les permissions nécessaires pour effectuer cette action."
      );
      redirect('/');
    }
  }
}

class MY_AuthController extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();

    if (!$this->isLogged && $this->router->class !== 'login') {
      redirect('/login');
    }
  }
}
