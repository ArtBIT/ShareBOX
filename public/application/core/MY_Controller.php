<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class MY_Controller is used as a base class for all the controllers in the app.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class MY_Controller extends CI_Controller
{
    public $errors = array();
    public $notices = array();
    public $title = "";
    public $layout = 'default';
    public $offset = 0;
    public $search = '';
    public $sort = 0;
    public $breadcrumb_methods = array(
        '' => 'pregled'
    );

    public $data = array();
    protected $protected_params = array();

    public function protect_params()
    {
        $params = func_get_args();
        foreach ($params as $param) {
            if (is_string($param)) {
                $this->protected_params[] = $param;
            }
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->protect_params('protected_params', 'errors', 'notices', 'title', 'layout');
        $this->parse_request($_GET);
        $this->parse_request($_POST);
        $this->section = trim($this->uri->segment(1, ''));
        $this->method  = trim($this->uri->segment(2, ''));
        $this->param   = trim($this->uri->segment(3, ''));

        $this->load->library('logger/logger');
        $this->load->library('breadcrumbs');
        $this->load->library('user');
        $this->load->helper('string');
        
        $this->offset = (int)$this->offset;
        $this->sort = (int)$this->sort;
        $this->search = preg_replace("/[^\da-z ]/i", "", $this->search);

        $section = $this->section ?: '';
        $this->breadcrumbs->push(ucwords($section), '/' . $section);
        $page = $this->method;
        if (array_key_exists($page, $this->breadcrumb_methods)) {
            $remap = $this->breadcrumb_methods[$page];
            if ($remap) {
                $this->breadcrumbs->push(ucwords($page), '/' . $section . '/' . $remap);
            }
        } else {
            $this->breadcrumbs->push(ucwords($page), '/' . $section . '/' . $page);
        }
        $this->data['page'] = array();
        $this->data['user'] = $this->user;
    }

    public function get_view_data()
    {
        $allowed_objects = array('form_validation','pagination','records','myModel','table');
        $data = array();
        $public_properties = call_user_func('get_object_vars', $this);
        foreach ($public_properties as $key => $value) {
            if (is_object($this->$key)) {
                if (!in_array($key, $allowed_objects)) {
                    continue;
                }
            }
            $data[$key] = $this->$key;
        }

        $data['breadcrumbs'] = $this->breadcrumbs->show();
        return array_merge($data, $this->data);
    }

    public function _output($content)
    {
        $this->load->js_begin(); ?>
        window.page = <?= json_encode($this->data['page']); ?>;
<?php
        $this->load->js_end(DOCUMENT_HEAD_START);
        if ($this->input->is_ajax_request()) {
            $output = $this->load->view('layouts/ajax', compact('content'), true);
        } else {
            $output = $this->load->view("layouts/{$this->layout}", compact('content'), true);
        }
        echo $output;
    }

    public function reset_errors()
    {
        $this->errors = array();
    }

    public function add_error($error_message)
    {
        $this->errors[] = $error_message;
    }

    public function add_errors($errors = array())
    {
        if (count($errors)) {
            $this->errors = array_values(array_merge($this->errors, $errors));
        }
    }

    public function has_errors()
    {
        return count($this->errors) > 0;
    }

    public function output_errors()
    {
        if ($this->has_errors()) {
            ?>
            <div class="errors">
                <ul>
                <?php foreach ($this->errors as $error_message) {
                ?>
                    <li><p><strong>Greska:</strong> <?php echo $error_message?></p></li>
                <?php 
            } ?>
                </ul>
            </div>
        <?php

        }
    }

    public function add_notice($title, $message)
    {
        $this->notices[] = compact('title', 'message');
    }

    /**
     * Reads the input array and auto-initializes the controller properties
     * to specified values.
     *
     * @param mixed $params
     * @access protected
     * @return void
     */
    protected function parse_request($params)
    {
        if (empty($params)) {
            return;
        }
        $class_members = get_class_vars(get_class($this));
        foreach ($params as $key => $value) {
            if (array_key_exists($key, $class_members)) {
                if (in_array($key, $this->protected_params)) {
                    // this parameter cannot be populated via parse_request
                    continue;
                }
                $value = $this->parse_param($value);
                if (!is_null($value)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function parse_request_params($method = 'get', $param_names = array())
    {
        $parsed_params = array();
        foreach ($param_names as $param_name) {
            if ($param_value = $this->input->$method($param_name)) {
                $parsed_params[$param_name] = $this->parse_param($param_value);
            }
        }
        return $parsed_params;
    }

    protected function parse_param($value)
    {
        // do not just use empty($value) !! cause "0" is considered empty
        if (is_null($value) || (empty($value) && ($value !== 0) && ($value !== '0'))) {
            return null;
        }
        if (is_string($value)) {
            $value = trim($value);
            if (strlen($value) == 0) {
                return null;
            }
        }
        return $value;
    }

    public function to_array()
    {
        $result = array();

        $class_members = get_class_vars(get_class($this));
        foreach ($class_members as $key => $value) {
            if (is_scalar($this->$key) || is_null($this->$key)) {
                $result[$key] = $this->$key;
            }
        }

        return $result;
    }

    public function reset($init_values = array())
    {
        foreach ($this->to_array() as $name => $value) {
            if (!in_array($name, $this->protected_params)) {
                $this->$name = null;
            }
        }
        $this->parse_request($init_values);
        $this->reset_errors();
    }

    public function set_page_title($title)
    {
        $this->title = $title;
    }

    protected function is_database_loaded()
    {
        try {
            $this->load->database();
            return $this->db->initialize();
        } catch (Exception $err) {
        }
        return false;
    }
}

class Public_Auth_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->driver('session');
        $this->load->helper('url');
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param   string
     * @param   string
     * @param   array
     * @return  void
     */
    protected function send_email($email, $subject, $view, &$data)
    {
        $this->load->library('email');
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($this->load->view('email/'.$view.'-html', $data, true));
        $this->email->set_alt_message($this->load->view('email/'.$view.'-txt', $data, true));
        return $this->email->send();
    }

    protected function set_message($message = null, $redirect = null)
    {
        $this->session->set_flashdata('message', $message);
        if (!empty($redirect)) {
            redirect($redirect);
        }
    }

    public function log($log_action, $message = '')
    {
        $this->logger->log($this->user->id, $log_action, $message);
    }

    public function get_view_data()
    {
        $data = parent::get_view_data();
        $data['message'] = $this->session->flashdata('message');
        return $data;
    }
}

class Auth_Controller extends Public_Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->user->loggedin) {
            redirect('/auth/login/');
        }
    }
}
