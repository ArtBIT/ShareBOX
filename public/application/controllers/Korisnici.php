<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Korisnici is used as a controller that manages users.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Korisnici extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user->has_role_or_die(User::ROLE_DEVELOPER, User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_USER_ADMINISTRATOR);
        $this->load->library('form_validation');
        $this->lang->load('tank_auth');
    }

    public function index()
    {
        $this->pregled();
    }

    public function pregled()
    {
        $offset = (int)$this->input->get('offset', 0);
        $this->load->model('Korisnici_model', 'model');

        $where = array();
        if (!empty($this->search)) {
            $where[] = $this->Korisnici_model->bind_sql("(`username` LIKE '{{search}}%' OR `firstname` LIKE '{{search}}%' OR `lastname` LIKE '{{search}}%')", array('search' => $this->search));
        }
        $this->data['korisnici'] = $this->model->find_rows($where, ROWS_PER_PAGE, $offset);
        $this->data['roles'] = User::roles();

        $this->load->library('pagination');
        $config['base_url'] = '/korisnici/pregled/';
        $config['total_rows'] = $this->model->count_rows($where);
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('pages/korisnici/pregled');
    }

    public function reset_password($id)
    {
        $this->load->model('Korisnici_model', 'model');
        $korisnik = $this->model->find_row(compact('id'));
        
        if (!is_null($data = $this->tank_auth->forgot_password($korisnik['username']))) {
            $this->log(LogAction::USER_RESET_PASSWORD, $data['email']);
            $data['site_name'] = $this->config->item('website_name', 'tank_auth');
            // Send email with password activation link
            $this->_send_email('forgot_password', $data['email'], $data);
            $this->session->set_flashdata('message', 'Poruka sa instrukcijama za izmenu lozinke je poslat na korisnikov e-mail');
        }
        redirect('/korisnici');
    }

    public function novo()
    {
        $use_username = $this->config->item('use_username', 'tank_auth');
        if ($use_username) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
        }
        $this->form_validation->set_rules('firstname', 'Ime', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('lastname', 'Prezime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

        $data['errors'] = array();
        $email_activation = $this->config->item('email_activation', 'tank_auth');

        if ($this->form_validation->run()) {                                // validation ok
            if (!is_null($data = $this->tank_auth->create_user(
                    $use_username ? $this->form_validation->set_value('username') : '',
                    $this->form_validation->set_value('email'),
                    $this->form_validation->set_value('password'),
                    $this->form_validation->set_value('firstname'),
                    $this->form_validation->set_value('lastname'),
                    $email_activation))) {                                  // success

                $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                $this->log(LogAction::USER_CREATE, $this->form_validation->set_value('username'));

                if ($email_activation) {                                    // send "activate" email
                    $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    unset($data['password']); // Clear password (just for any case)

                    $this->session->set_flashdata('message', $this->lang->line('auth_message_registration_completed_1'));
                } else {
                    if ($this->config->item('email_account_details', 'tank_auth')) {    // send "welcome" email

                        $this->_send_email('welcome', $data['email'], $data);
                    }
                    unset($data['password']); // Clear password (just for any case)

                    $this->session->set_flashdata('message', $this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                }

                redirect('/korisnici');
            } else {
                $errors = $this->tank_auth->get_error_message();
                $this->form_validation->set_errors($errors);
            }
        }
        $data['use_username'] = $use_username;
        $this->load->view('pages/korisnici/novo', $data);
    }

    protected function _send_email($type, $email, &$data)
    {
        $this->load->library('email');
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
        $this->email->message($this->load->view('email/'.$type.'-html', $data, true));
        $this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, true));
        $this->email->send();
    }
}

/* End of file */
