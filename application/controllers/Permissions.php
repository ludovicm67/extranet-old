<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends MY_AuthController
{
  private $permissions;

  public function __construct()
  {
    parent::__construct();

    $this->permissions = [
      'roles' =>
        (object) [
          'name' => 'Rôles',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      // sellsy datas, readonly
      'clients' =>
        (object) [
          'name' => 'Clients',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'clients_details' =>
        (object) [
          'name' => 'Détails clients',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'orders' =>
        (object) [
          'name' => 'Commandes',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'invoices' =>
        (object) [
          'name' => 'Factures',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'projects' =>
        (object) [
          'name' => 'Projets',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'contacts' =>
        (object) [
          'name' => 'Contacts',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'export_contacts' =>
        (object) [
          'name' => 'Exporter des contacts',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'identifiers' =>
        (object) [
          'name' => "Types d'identifiants",
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'project_identifiers' =>
        (object) [
          'name' => 'Identifiants de projets',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'project_confidential_identifiers' =>
        (object) [
          'name' => 'Identifiants confidentiels',
          'show' => true,
          'add' => false,
          'edit' => false,
          'delete' => false,
          'checked' => []
        ],
      'tags' =>
        (object) [
          'name' => 'Tags',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'users' =>
        (object) [
          'name' => 'Utilisateurs',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ],
      'project_urls' =>
        (object) [
          'name' => 'Urls de projet',
          'show' => true,
          'add' => false, // use 'projects' value
          'edit' => false, // use 'projects' value
          'delete' => false, // use 'projects' value
          'checked' => []
        ],
      // contact types
      'types' =>
        (object) [
          'name' => 'Types de contact',
          'show' => true,
          'add' => true,
          'edit' => true,
          'delete' => true,
          'checked' => []
        ]
    ];
  }

  public function index($id = null)
  {
    // when no id specified, go to the roles list
    if (is_null($id)) {
      redirect('/roles');
    }

    // permissions restrictions are the same as for roles
    $this->checkPermission('roles', 'edit');

    // check if role exists
    $this->db->where('id', $id);
    $q = $this->db->get('roles');
    if ($q->num_rows() <= 0) {
      redirect('/roles');
    }
    $role = $q->result()[0];

    // form was submitted
    if (
      isset($_SERVER['REQUEST_METHOD']) &&
      $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
      $this->db->delete('rights', ['role_id' => $id]);

      foreach ($this->input->post('permissions') as $name => $values) {
        if (!array_key_exists($name, $this->permissions)) {
          continue;
        }
        $this->db->insert('rights', [
          'role_id' => $id,
          'name' => $name,
          'show' =>
            (
              array_key_exists('show', $values) &&
                $this->permissions[$name]->show
            )
              ? 1
              : 0,
          'add' =>
            (array_key_exists('add', $values) && $this->permissions[$name]->add)
              ? 1
              : 0,
          'edit' =>
            (
              array_key_exists('edit', $values) &&
                $this->permissions[$name]->edit
            )
              ? 1
              : 0,
          'delete' =>
            (
              array_key_exists('delete', $values) &&
                $this->permissions[$name]->delete
            )
              ? 1
              : 0
        ]);
      }

      $this->session->set_flashdata(
        'success',
        "Les permissions de ce rôle ont bien été modifiées avec succès !"
      );

      redirect('/roles');
    }

    // get current role permissions
    $this->db->where('role_id', $id);
    $permissions = $this->db->get('rights')->result();

    foreach ($permissions as $permission) {
      if (!array_key_exists($permission->name, $this->permissions)) {
        continue;
      }
      if ($permission->show == 1) {
        $this->permissions[$permission->name]->checked[] = 'show';
      }
      if ($permission->add == 1) {
        $this->permissions[$permission->name]->checked[] = 'add';
      }
      if ($permission->edit == 1) {
        $this->permissions[$permission->name]->checked[] = 'edit';
      }
      if ($permission->delete == 1) {
        $this->permissions[$permission->name]->checked[] = 'delete';
      }
    }

    $this->view('permissions', [
      'role' => $role,
      'permissions' => $this->permissions
    ]);
  }
}
