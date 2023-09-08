<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Pomoc is used as a controller for the Help/FAQ module.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Pomoc extends Public_Auth_Controller
{
    public function index()
    {
        $this->load->helper('string');
        $this->load->view('pages/pomoc');
    }
}

/* End of file */
