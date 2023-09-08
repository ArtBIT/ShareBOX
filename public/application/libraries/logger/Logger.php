<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Logger is a CodeIgniter library that logs user actions.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class LogAction
{
    const UNKNOWN                     = 0;
    const USER_LOGIN                  = 1;
    const USER_LOGIN_FAILED           = 2;
    const USER_LOGOUT                 = 3;
    const USER_REGISTERED             = 4;
    const USER_RESET_PASSWORD         = 5;
    const USER_RESET_PASSWORD_FAILED  = 6;
    const USER_FORGOT_PASSWORD        = 7;
    const USER_FORGOT_PASSWORD_FAILED = 8;
    const USER_CHANGE_PASSWORD        = 9;
    const USER_CHANGE_PASSWORD_FAILED = 10;
    const USER_CHANGE_EMAIL           = 11;
    const USER_CHANGE_EMAIL_FAILED    = 12;
    const USER_RESET_EMAIL            = 13;
    const USER_RESET_EMAIL_FAILED     = 14;
    const USER_ACTIVATION             = 15;
    const USER_ACTIVATION_FAILED      = 16;
    const USER_DEACTIVATION           = 17;
    const USER_DEACTIVATION_FAILED    = 18;
    const USER_CREATE                 = 19;
    const USER_ROLE                   = 20;
    const USER_DELETE                 = 21;
    const GROUP_CREATE                = 22;
    const GROUP_UPDATE                = 23;
    const GROUP_DELETE                = 24;
    const GROUP_ADD_USER              = 25;
    const GROUP_REMOVE_USER           = 26;
    const MERENJE_CREATE              = 27;
    const MERENJE_UPDATE              = 28;
    const MERENJE_DELETE              = 29;
    const APIKEY_CREATE               = 30;
    const APIKEY_UPDATE               = 31;
    const APIKEY_DELETE               = 32;
}

class Logger
{
    protected $options;
    protected $ci;
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
        $this->ci = & get_instance();
        $this->ci->load->model('logger/logger_model');
    }
    
    public function log($user_id, $action_type = 0, $message = '')
    {
        if (is_array($message)) {
            // Convert array('a'=>1, 'b'=>2, ...) to
            // "a=1; b=2; ..."
            $message = implode("; ", array_map(function ($key, $value) {
                return "$key=$value";
            }, array_keys($message), $message));
        }
        $row = array(
            'user_id' => $user_id,
            'action' => $action_type,
            'comment' => $message,
            'ts' => date('Y-m-d H:i:s')
        );
        $this->_log($row);
    }

    protected function _log($row)
    {
        if (!$row['action']) {
            return;
        }

        if (intval($row['user_id']) > 0) {
            $this->ci->logger_model->add_row($row);
        } else {
            // Log.user_id <-> Korisnici.id contraint would trigger an error
            log_message('error', $row['comment']);
        }
    }
}
