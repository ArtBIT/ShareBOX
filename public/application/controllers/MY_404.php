<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class MY_404 is used to display custom 404 message.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class MY_404 extends Auth_Controller
{
    public function index()
    {
        $this->output->set_status_header('404');
        $this->load->view('error_404');
    }
}
/* End of file */
