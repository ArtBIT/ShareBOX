<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter URL Helper
 *
 * @package     ShareBOX
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Djordje Ungar (djordje@ungar.rs)
 */

// ------------------------------------------------------------------------

if (! function_exists('show_403')) {
    function show_403($message = '')
    {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_error('Zabranjeno', $message, 'error_general', 403);
        exit(4);
    }
}
