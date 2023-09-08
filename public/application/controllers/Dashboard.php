<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Dashboard is used as a controller for the Dashboard module.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Dashboard extends Auth_Controller
{
    public $shortcuts = array();

    public function index()
    {
        if ($this->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_USER_ADMINISTRATOR)) {
            $this->shortcuts[] = array('url' => "/korisnici", 'icon' => 'users', 'label' => 'Korisnici');
        }
        if ($this->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER)) {
            $this->shortcuts[] = array('url' => "/log", 'icon' => 'book', 'label' => 'Log');
        }
        $this->shortcuts[] = array('url' => "/merenja/pregled/staticka", 'icon' => 'truck', 'label' => 'Statička Merenja');
        $this->shortcuts[] = array('url' => "/merenja/pregled/dinamicka", 'icon' => 'truck2', 'label' => 'Dinamička Merenja');
        $this->shortcuts[] = array('url' => "/pomoc", 'icon' => 'life-buoy', 'label' => 'Pomoć');
        $this->shortcuts[] = array('url' => "/auth/logout", 'icon' => 'sign-out', 'label' => 'Odjavi se');

        $this->load->view('pages/dashboard/index');
    }
}
/* End of file */
