<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Auth is used as an authentication controller.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Djordje Ungar 2014-2016
 * @version     1.0.0
 * @package     ShareBOX
 */

class Auth extends Public_Auth_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->layout = 'login';
    }

    public function index()
    {
        if ($message = $this->session->flashdata('message')) {
            $this->load->view('auth/general_message', array('message' => $message));
        } else {
            redirect('/auth/login/');
        }
    }

    /**
     * Login user on the site
     *
     * @return void
     */
    public function login()
    {
        if ($this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('');
        } elseif ($this->tank_auth->is_logged_in(false)) {                      // logged in, not activated
            redirect('/auth/not_activated/');
        } else {
            $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') && $this->config->item('use_username', 'tank_auth'));
            $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

            $this->form_validation->set_rules('login', 'Login', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');

            // Get login for counting attempts to login
            if ($this->config->item('login_count_attempts', 'tank_auth') && ($login = $this->input->post('login'))) {
                $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            $data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                if ($data['use_recaptcha']) {
                    $this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|required|callback__check_recaptcha');
                } else {
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|required|callback__check_captcha');
                }
            }
            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->login(
                        $this->form_validation->set_value('login'),
                        $this->form_validation->set_value('password'),
                        $this->form_validation->set_value('remember'),
                        $data['login_by_username'],
                        $data['login_by_email'])) {                             // success

                            $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_LOGIN, $this->form_validation->set_value('login'));
                    redirect('');
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    $this->logger->log(-1, LogAction::USER_LOGIN_FAILED, $this->form_validation->set_value('login'));
                    if (isset($errors['banned'])) {                             // banned user
                        $this->set_message($this->lang->line('auth_message_banned').' '.$errors['banned']);
                    } elseif (isset($errors['not_activated'])) {                // not activated user
                        redirect('/auth/not_activated/');
                    } else {                                                    // fail
                        $this->form_validation->set_errors($errors);
                    }
                }
            }
            $data['show_captcha'] = false;
            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                $data['show_captcha'] = true;
                if ($data['use_recaptcha']) {
                    $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }
            $this->load->view('auth/login_form', $data);
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
    {
        $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_LOGOUT, $this->tank_auth->get_username());
        $this->tank_auth->logout();

        $this->set_message($this->lang->line('auth_message_logged_out'));
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    public function register()
    {
        if ($this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('');
        } elseif ($this->tank_auth->is_logged_in(false)) {                      // logged in, not activated
            redirect('/auth/not_activated/');
        } elseif (!$this->config->item('allow_registration', 'tank_auth')) {    // registration is off
            $this->set_message($this->lang->line('auth_message_registration_disabled'));
        } else {
            $use_username = $this->config->item('use_username', 'tank_auth');
            if ($use_username) {
                $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
            }
            $this->form_validation->set_rules('firstname', 'Ime', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('lastname', 'Prezime', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

            $captcha_registration   = $this->config->item('captcha_registration', 'tank_auth');
            $use_recaptcha          = $this->config->item('use_recaptcha', 'tank_auth');
            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $this->form_validation->set_rules('g-recaptcha-response', 'ReCaptcha', 'trim|required|callback__check_recaptcha');
                } else {
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|required|callback__check_captcha');
                }
            }
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

                    if ($email_activation) {                                    // send "activate" email
                        $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                        $this->_send_email('activate', $data['email'], $data);

                        unset($data['password']); // Clear password (just for any case)

                        $this->set_message($this->lang->line('auth_message_registration_completed_1'));
                    } else {
                        if ($this->config->item('email_account_details', 'tank_auth')) {    // send "welcome" email

                            $this->_send_email('welcome', $data['email'], $data);
                        }
                        unset($data['password']); // Clear password (just for any case)

                        $this->set_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                    }

                    $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_REGISTERED, $this->form_validation->set_value('email'));
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                }
            }
            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }
            $data['use_username'] = $use_username;
            $data['show_captcha'] = $captcha_registration;
            $data['use_recaptcha'] = $use_recaptcha;
            $this->load->view('auth/register_form', $data);
        }
    }

    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    public function not_activated()
    {
        if (!$this->tank_auth->is_logged_in(false)) {                           // not logged in or activated
            redirect('/auth/login/');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (!is_null($data = $this->tank_auth->change_email(
                        $this->form_validation->set_value('email')))) {         // success

                    $data['site_name']  = $this->config->item('website_name', 'tank_auth');
                    $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    $this->set_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                }
            }
            $this->load->view('auth/send_again_form', $data);
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function activate()
    {
        $user_id        = $this->uri->segment(3);
        $new_email_key  = $this->uri->segment(4);

        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) {        // success
            $this->tank_auth->logout();
            $this->logger->log($user_id, LogAction::USER_ACTIVATION, $new_email_key);
            $this->set_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));
        } else {                                                                // fail
            $this->logger->log($user_id, LogAction::USER_ACTIVATION_FAILED, $new_email_key);
            $this->set_message($this->lang->line('auth_message_activation_failed'));
        }
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    public function forgot_password()
    {
        if ($this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('');
        } elseif ($this->tank_auth->is_logged_in(false)) {                      // logged in, not activated
            redirect('/auth/not_activated/');
        } else {
            $this->form_validation->set_rules('login', 'Email or login', 'trim|required');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok

                $user_id = -1;
                $login = $this->form_validation->set_value('login');
                if (!is_null($user = $this->users->get_user_by_login($login))) {
                    $user_id = $user->id;
                }
                if (!is_null($data = $this->tank_auth->forgot_password($login))) {
                    $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                    // Send email with password activation link
                    $this->_send_email('forgot_password', $data['email'], $data);

                    $this->logger->log($user_id, LogAction::USER_FORGOT_PASSWORD, $login);
                    $this->set_message($this->lang->line('auth_message_new_password_sent'));
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                    $this->logger->log($user_id, LogAction::USER_FORGOT_PASSWORD_FAILED, $login);
                }
            }
            $this->load->view('auth/forgot_password_form', $data);
        }
    }

    /**
     * Replace user password (forgotten) with a new one (set by user).
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function reset_password()
    {
        $user_id        = $this->uri->segment(3);
        $new_pass_key   = $this->uri->segment(4);

        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run()) {                                // validation ok
            if (!is_null($data = $this->tank_auth->reset_password(
                    $user_id, $new_pass_key,
                    $this->form_validation->set_value('new_password')))) {  // success

                $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                // Send email with new password
                $this->_send_email('reset_password', $data['email'], $data);

                $this->logger->log($user_id, LogAction::USER_RESET_PASSWORD);
                $this->set_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));
            } else {                                                        // fail
                $this->logger->log($user_id, LogAction::USER_RESET_PASSWORD_FAILED);
                $this->set_message($this->lang->line('auth_message_new_password_failed'));
            }
        } else {
            // Try to activate user by password key (if not activated yet)
            if ($this->config->item('email_activation', 'tank_auth')) {
                $this->tank_auth->activate_user($user_id, $new_pass_key, false);
            }

            if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
                $this->logger->log($user_id, LogAction::USER_RESET_PASSWORD_FAILED);
                $this->set_message($this->lang->line('auth_message_new_password_failed'));
            }
        }
        $this->load->view('auth/reset_password_form', $data);
    }

    /**
     * Change user password
     *
     * @return void
     */
    public function change_password()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');
        } else {
            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|matches[new_password]');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->change_password(
                        $this->form_validation->set_value('old_password'),
                        $this->form_validation->set_value('new_password'))) {   // success
                    $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_CHANGE_PASSWORD);
                    $this->set_message($this->lang->line('auth_message_password_changed'));
                } else {                                                        // fail
                    $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_CHANGE_PASSWORD_FAILED);
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                }
            }
            $this->load->view('auth/change_password_form', $data);
        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    public function change_email()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');
        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (!is_null($data = $this->tank_auth->set_new_email(
                        $this->form_validation->set_value('email'),
                        $this->form_validation->set_value('password')))) {          // success

                    $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                    // Send email with new email address and its activation link
                    $this->_send_email('change_email', $data['new_email'], $data);

                    $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_CHANGE_EMAIL, $data['new_email']);
                    $this->set_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                    $this->logger->log($this->tank_auth->get_user_id(), LogAction::USER_CHANGE_EMAIL_FAILED, $this->form_validation->set_value('email'));
                }
            }
            $this->load->view('auth/change_email_form', $data);
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function reset_email()
    {
        $user_id        = $this->uri->segment(3);
        $new_email_key  = $this->uri->segment(4);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {   // success
            $this->tank_auth->logout();
            $this->logger->log($user_id, LogAction::USER_RESET_EMAIL, $new_email_key);
            $this->set_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));
        } else {                                                                // fail
            $this->logger->log($user_id, LogAction::USER_RESET_EMAIL_FAILED, $new_email_key);
            $this->set_message($this->lang->line('auth_message_new_email_failed'));
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    public function unregister()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');
        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                $user_id = $this->tank_auth->get_user_id();
                if ($this->tank_auth->delete_user(
                        $this->form_validation->set_value('password'))) {       // success
                    $this->logger->log($user_id, LogAction::USER_DEACTIVATION);
                    $this->set_message($this->lang->line('auth_message_unregistered'));
                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    $this->form_validation->set_errors($errors);
                    $this->logger->log($user_id, LogAction::USER_DEACTIVATION_FAILED);
                }
            }
            $this->load->view('auth/unregister_form', $data);
        }
    }

    protected function set_message($message = null, $redirect = null)
    {
        $this->session->set_flashdata('message', $message);
        redirect('/auth/');
    }

    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return  string
     */
    public function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(array(
            'img_path'      => './'.$this->config->item('captcha_path', 'tank_auth'),
            'img_url'       => base_url().$this->config->item('captcha_path', 'tank_auth'),
            'font_path'     => './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
            'font_size'     => $this->config->item('captcha_font_size', 'tank_auth'),
            'img_width'     => $this->config->item('captcha_width', 'tank_auth'),
            'img_height'    => $this->config->item('captcha_height', 'tank_auth'),
            'show_grid'     => $this->config->item('captcha_grid', 'tank_auth'),
            'expiration'    => $this->config->item('captcha_expire', 'tank_auth'),
        ));

        // Save captcha params in session
        $this->session->set_flashdata(array(
                'captcha_word' => $cap['word'],
                'captcha_time' => $cap['time'],
        ));

        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param   string
     * @return  bool
     */
    public function _check_captcha($code)
    {
        $time = $this->session->flashdata('captcha_time');
        $word = $this->session->flashdata('captcha_word');

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
            return false;
        } elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') && $code != $word) || strtolower($code) != strtolower($word)) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
            return false;
        }
        return true;
    }

    /**
     * Create reCAPTCHA JS and non-JS HTML to verify user as a human
     *
     * @return  string
     */
    public function _create_recaptcha()
    {
        $this->load->helper('recaptcha');
        $html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'), 'sr');
        return $html;
    }

    /**
     * Callback function. Check if reCAPTCHA test is passed.
     *
     * @return  bool
     */
    public function _check_recaptcha()
    {
        $this->load->helper('recaptcha');

        $resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
                $_SERVER['REMOTE_ADDR'],
                $_POST['g-recaptcha-response']);

        if (!$resp->is_valid) {
            $this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
            return false;
        }
        return true;
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
        return $this->email->send();
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
