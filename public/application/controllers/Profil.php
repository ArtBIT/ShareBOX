<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Profil is used as a controller for the User Profile module.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Profil extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->user->loggedin) {
            show_403();
        }
    }

    public function index()
    {
        $this->load->view('pages/profil');
    }

    public function save()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('firstname', 'Ime', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('lastname', 'Prezime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
        $this->form_validation->set_rules('old_password', 'Stara Lozinka', 'required');

        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Nova lozinka', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', 'Potvrda lozinke', 'trim|required|matches[password]');
        }
        if ($this->form_validation->run()) {
            $success = true;
            $this->load->library('tank_auth');
            if (!empty($this->input->post('password'))) {
                if ($this->tank_auth->change_password(
                    $this->form_validation->set_value('old_password'),
                    $this->form_validation->set_value('password'))) {   // success
                } else {
                    $this->form_validation->set_errors($this->tank_auth->get_error_message());
                    $success = false;
                }
            } else {
                // If old password matches the user password
                if (!$this->tank_auth->check_password($this->form_validation->set_value('old_password'))) {
                    $this->form_validation->set_error('old_password', 'Neispravna lozinka');
                    $success = false;
                }
            }
            if ($success) {
                if ($this->user->email != $this->input->post('email')) {
                    if ($data = $this->tank_auth->set_new_email($this->form_validation->set_value('email'), $this->form_validation->set_value('old_password'))) {
                        $this->log(LogAction::USER_CHANGE_EMAIL, $this->form_validation->set_value('email'));
                        $data['site_name']  = $this->config->item('website_name', 'tank_auth');
                        $this->send_email($data['new_email'], 'ShareBOX: promena email adrese', 'change_email', $data);
                    } else {
                        $errors = $this->tank_auth->get_error_message();
                        $this->form_validation->set_errors($errors);
                        $success = false;
                    }
                }
            }

            if ($success) {
                $this->load->model('tank_auth/users');
                $this->users->update_row(array('id' => $this->user->id), array(
                    'firstname' =>  $this->form_validation->set_value('firstname'),
                    'lastname' =>  $this->form_validation->set_value('lastname'),
                ));
                $this->session->set_flashdata('message', 'Izmene uspešno sačuvane');
                return redirect('/profil');
            }
        }

        $this->index();
    }
}
/* End of file */
