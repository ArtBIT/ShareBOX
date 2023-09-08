<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * API Endpoint for Grupe
 *   GET /api/v1/roles      // show all roles
 *   GET /api/v1/roles/2    // show role with teh id 2
 *
 * @see application/config/routes.php to see how the requests are routed to here.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

require APPPATH . '/libraries/API_Controller.php';

class Roles extends API_Controller
{

    // If accessing via API Keys authentication
    // allow access to the following method only if API key has the
    // required level:
    protected $methods = array(
        'index_get' => array('level' => 1),
    );

    /**
     * @apiDefine korisnik Korisnik
     * Da biste izvršili ovu akciju morate biti registrovani korisnik
     */
    /**
     * @apiDefine ErrorNotFound
     * @apiError (Error 404) NotFound Traženi resurs nije pronadjen
     */
    

    /**
     * @api {get} /uloge Spisak svih uloga
     * @apiName GetUloge
     * @apiGroup Uloge
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiSuccess {Object[]} uloga Lista svih Uloga
     * @apiSuccess {Number} uloga.key Jedinstveni identifikator uloge
     * @apiSuccess {String} uloga.value Naziv uloge
     */
    /**
     * @api {get} /uloge/:id Naziv uloge
     * @apiName GetNazivUloge
     * @apiGroup Uloge
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id jedinstveni identifikator uloge
     *
     * @apiSuccess {String} Naziv uloge
     * @apiUse ErrorNotFound
     */
    public function index_get()
    {
        $this->load->helper('roles');
        $roles = User::roles();

        $id = $this->get('id');
        if ($id === null) {
            $this->response_ok($roles);
        }
        if (isset($roles[$id])) {
            $this->response_ok($roles[$id]);
        }
        $this->response_not_found();
    }
}
