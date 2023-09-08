<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User
{
    const ROLE_NONE = 0;
    const ROLE_USER = 1;
    const ROLE_DEVELOPER = 2;
    const ROLE_SYSTEM_ADMINISTRATOR = 3;
    const ROLE_USER_ADMINISTRATOR = 4;
    const ROLE_API_ADMINISTRATOR = 5;

    protected $ci;
    protected $data = null;
    public function __get($property)
    {
        if (is_null($this->data)) {
            $this->init_data();
        }
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
    }

    protected function init_data()
    {
        if (is_null($this->data)) {
            $this->ci = get_instance();
            $this->ci->load->library('tank_auth');
            if ($this->ci->tank_auth->is_logged_in()) {
                $this->ci->load->model('Korisnici_model');
                $this->data = $this->ci->Korisnici_model->find_row(array('id' => $this->ci->tank_auth->get_user_id()));
                $this->data['loggedin'] = !empty($this->data['id']);
            } else {
                $this->data = array(
                    'id' => 0,
                    'username' => 'visitor',
                    'firstname' => '',
                    'lastname' => '',
                    'loggedin' => false,
                );
            }
        }
    }

    public function has_role(/* $role1, $role2, ... */)
    {
        $roles = func_get_args();
        $user_role_id = $this->role_id;
        foreach ($roles as $role) {
            if ($role == $user_role_id) {
                return true;
            }
        }
        return false;
    }

    public function has_role_or_die()
    {
        if (! call_user_func_array(array($this, 'has_role'), func_get_args())) {
            show_403('Nemate privilegije dovoljne za ovu akciju.');
        }
    }

    public static function roles()
    {
        return array(
            static::ROLE_NONE => 'Nepoznato'
            ,static::ROLE_USER => 'Korisnik'
            ,static::ROLE_DEVELOPER => 'Programer'
            ,static::ROLE_SYSTEM_ADMINISTRATOR => 'Administrator Sistema'
            ,static::ROLE_USER_ADMINISTRATOR => 'Administrator korisnika'
            ,static::ROLE_API_ADMINISTRATOR => 'Administrator API'
        );
    }
}
