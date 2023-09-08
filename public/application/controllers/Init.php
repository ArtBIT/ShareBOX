<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once(APPPATH.'/libraries/phpass-0.1/PasswordHash.php');

/**
 * Class Init is used solely to create the admin user from the CLI
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Init extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->input->is_cli_request() === false || ENVIRONMENT !== 'development') {
            show_403('Nemate privilegije dovoljne za ovu akciju.');
        }
        $this->load->library('form_validation');
        $this->load->config('tank_auth', true);
        $this->load->database();
        $this->load->model('tank_auth/users');
        $this->lang->load('tank_auth');
    }

    public function admin_user($username, $password, $firstname, $lastname, $email) {

        $this->form_validation->set_data(compact('username', 'password', 'firstname', 'lastname', 'email'));
        $use_username = $this->config->item('use_username', 'tank_auth');
        if ($use_username) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
        }
        $this->form_validation->set_rules('firstname', 'Ime', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('lastname', 'Prezime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');

        if ($this->form_validation->run()) {
            // Hash password using phpass
            $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'tank_auth'),
                    $this->config->item('phpass_hash_portable', 'tank_auth'));
            $hashed_password = $hasher->HashPassword($password);

            $data = array(
                'username'  => $username,
                'password'  => $hashed_password,
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'role_id'   => User::ROLE_SYSTEM_ADMINISTRATOR,

                'digest'    => $this->users->generate_digest_hash($username, $password),
                'email'     => $email,
                'last_ip'   => '127.0.0.1',
            );

            if (!is_null($res = $this->users->create_user($data))) {
                $data['user_id'] = $res['user_id'];
                $this->load->model('Grupe_model');
                $this->Grupe_model->add_user_to_default_group($data['user_id']);
                die("User $username created successfully.");
            }
        }
        if ($this->form_validation->has_errors()) {
            print_r($this->form_validation->get_errors());
        }
        die();
    }
}
