<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Base class for ShareBOX API Endpoints
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

require APPPATH . '/libraries/REST_Controller.php';

abstract class API_Controller extends REST_Controller
{
    protected $filename = null;

    public function __construct()
    {
        parent::__construct();

        $this->load->driver('session');
        $this->load->helper('url');
        $this->load->library('tank_auth');
        $this->load->library('form_validation');
        $this->load->library('user');

        $this->data['user'] = $this->user;
    }

    protected function response_bad_request($message = 'Zahtev nije validan')
    {
        return $this->response([
            'status' => false,
            'message' => $message
        ], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
    }

    protected function response_not_found($message = 'Nema podataka')
    {
        return $this->response([
            'status' => false,
            'message' => $message
        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    }

    protected function response_ok($result = null)
    {
        return $this->response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    protected function response_created($result = null)
    {
        return $this->response($result, REST_Controller::HTTP_CREATED); // HTTP Created (201)
    }

    protected function response_unauthorized()
    {
        return $this->response(null, REST_Controller::HTTP_UNAUTHORIZED); // 401
    }

    /**
     * Takes mixed data and optionally a status code, then creates the response
     *
     * @access public
     * @param array|NULL $data Data to output to the user
     * @param int|NULL $http_code HTTP status code
     * @param bool $continue TRUE to flush the response to the client and continue
     * running the script; otherwise, exit
     */
    public function response($data = null, $http_code = null, $continue = false)
    {
        $data = arrays_remove_keys(array('deleted', 'email', 'password'), $data);
        $this->content_disposition($this->filename);
        return parent::response($data, $http_code, $continue);
    }

    protected function set_filename($filename = null)
    {
        $this->filename = $filename;
    }

    protected function content_disposition($filename = 'response')
    {
        $format = $this->get('format');
        if (empty($format)) {
            $format = 'json';
        }
        header("Content-Disposition: inline; filename=\"{$filename}.{$format}\"");
    }

    /**
     * For digest authentication the library function should return
     * an md5(username:restrealm:password). In order to do this we would
     * need to store passwords in the database, which we don't want to
     * do. Instead, we generate and store that hash every time user
     * changes its password.
     *
     * @access protected
     * @param string $username The username to validate
     * @param string $password The password to validate
     * @return bool
     */
    protected function _perform_library_auth($username = '', $password = null)
    {
        if (strtolower($this->config->item('rest_auth')) == 'digest') {
            $this->load->model('tank_auth/users');
            if ($user = $this->users->get_user_by_username($username)) {
                return $user->digest;
            }
        }
        return parent::_perform_library_auth($username, $password);
    }

    /**
     * REST_Controller is implemented in such a way that it only allows
     * for one type of authentication. We want to allow multiple, either
     * through $_SESSION, API key, and ultimately, digest
     *
     */
    protected function _prepare_digest_auth()
    {
        // 1. First, we check if the user is already authenticated
        // through $_SESSION. This allows the user to make AJAX API
        // requests in the web application itself.
        $this->load->library('tank_auth');
        if ($user = $this->tank_auth->get_current_user()) {
            // The user is already authenticated
            return;
        }

        // 2. Next we check if the request contains a valid API
        // key. This allows devices like arduino to easily send API
        // requests.
        // This is not very secure, so make sure you limit the API key
        // to an IP address.
        if ($this->_detect_api_key()) {
            $this->load->model('tank_auth/users');

            // These checks are usually done within REST_Controller, but we hacked it
            // and need to do these check manually.

            // See if there is a minimum level required to access the controller method
            $controller_method = $this->router->fetch_method() . "_" . $this->request->method;
            $level = isset($this->methods[$controller_method]['level']) ? $this->methods[$controller_method]['level'] : 0;
            if ($level > $this->rest->level) {
                $this->response_unauthorized();
            }

            // Try to login the user that is bound to the API key
            if ($user = $this->users->get_user_by_id($this->rest->user_id, true)) {
                // The API key is valid, login the user
                $this->tank_auth->force_login_by_username($user->username);
                return;
            }
        }

        // 3. Try to authenticate the user via digest authentication.
        parent::_prepare_digest_auth();

        // If we got this far, it means that the digest auth was a success.
        // Get the username from the digest request header, and login the
        // user, using Tank_auth library.
        $matches = array();
        preg_match_all('@(username|nonce|uri|nc|cnonce|qop|response)=[\'"]?([^\'",]+)@', $this->input->server('PHP_AUTH_DIGEST'), $matches);
        $digest = (empty($matches[1]) || empty($matches[2])) ? [] : array_combine($matches[1], $matches[2]);
        $this->tank_auth->force_login_by_username($digest['username']);
    }
}
