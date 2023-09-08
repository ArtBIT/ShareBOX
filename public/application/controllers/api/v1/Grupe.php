<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * API Endpoint for Grupe
 *   GET /api/v1/grupe                   // show all groups
 *   GET /api/v1/grupe/2                 // show info about group 2
 *   GET /api/v1/grupe/2/korisnici       // get all the users belonging to the group 2
 *   PUT /api/v1/grupe/2/korisnici/16    // add user with the id 16 to the group 2
 *   DELETE /api/v1/grupe/2/korisnici/16 // remove user with the id 16 from the group 2
 *   DELETE /api/v1/grupe/2              // delete group 2
 *   POST /api/v1/grupe                  // send form data to this endpoint to create a new group
 *   POST /api/v1/grupe/2                // send form data to this endpoint to update group 2
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

class Grupe extends API_Controller
{

    // If accessing via API Keys authentication
    // allow access to the following method only if API key has the
    // required level:
    protected $methods = array(
        'index_get' => array('level' => 1),
        'index_put' => array('level' => 10),
        'index_post' => array('level' => 10),
        'index_delete' => array('level' => 15),
    );

    /**
     * @apiDefine administrator Administrator
     * Da biste izvršili ovu akciju morate biti administrator
     */
    /**
     * @apiDefine korisnik Korisnik
     * Da biste izvršili ovu akciju morate biti registrovani korisnik
     */
    /**
     * @apiDefine vlasnik Vlasnik grupe
     * Da biste izvršili ovu akciju morate biti vlasnik grupe
     */
    /**
     * @apiDefine ErrorNotFound
     * @apiError (Error 404) NijePronadjeno Traženi resurs nije pronadjen
     */
    /**
     * @apiDefine ErrorBadRequest
     * @apiError (Error 400) NeispravanZahtev Zahtev koji ste poslali ne sadrži sve potrebne parametre
     */
    /**
     * @apiDefine ErrorUnauthorized
     * @apiError (Error 401) Neautorizovano Nemate dozvolu za pristup traženom resursu
     */
    /**
     * @apiDefine GrupaSuccess
     * @apiSuccess {Number} grupa.id Jedinstveni identifikator grupe
     * @apiSuccess {String} grupa.name Naziv grupe
     * @apiSuccess {Number} grupa.user_id Jedinstveni identifikator korisnika koji je kreirao grupu
     * @apiSuccess {Number} grupa.owner_id Jedinstveni identifikator korisnika koji je vlasnik grupe
     * @apiSuccess {String="pregled","izmena","kreiranje"} grupa.access_level Nivo pristupa koji imaju članovi grupe
     * @apiSuccess {Date} grupa.created Datum kada je grupa kreirana
     */
    /**
     * @apiDefine KorisnikSuccess
     * @apiSuccess {Number} korisnik.id Jedinstveni identifikator korisnika
     * @apiSuccess {String} korisnik.username Korisničko ime
     * @apiSuccess {Number} korisnik.firstname Ime korisnika
     * @apiSuccess {Number} korisnik.lastname Prezime korisnika
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Grupe_model');
    }

    /**
     * @api {get} /grupe Spisak grupa
     * @apiName GetGrupe
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiSuccess {Object[]} grupa Lista svih grupa
     * @apiUse GrupaSuccess
     */
    /**
     * @api {get} /grupe/:id Informacije o datoj grupi
     * @apiName GetGrupa
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     *
     * @apiSuccess {Object} grupa Informacije o grupi
     * @apiUse GrupaSuccess
     * @apiUse ErrorNotFound
     * @apiUse ErrorBadRequest
     */
    /**
     * @api {get} /grupe/:id/korisnici Spisak korisnika koji pripadaju datoj grupi
     * @apiName GetGrupaKorisnici
     * @apiDescription Informacije o korisnicima koji pripadaju datoj grupi
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     *
     * @apiSuccess {Object[]} korisnik Informacije o korisnicima koji pripadaju grupi
     * @apiUse KorisnikSuccess
     * @apiUse ErrorNotFound
     */
    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            // show all groups
            $groups = $this->Grupe_model->find_rows();
            return $this->response_ok($groups);
        }
        $id = (int)$id;
        if ($id <= 0) {
            return $this->response_bad_request();
        }

        $args = $this->get();
        if (array_key_exists('korisnici', $args)) {
            $user_id = $this->get('korisnici');
            $users = $this->get_users_in_group($id, $user_id);
            if (empty($users)) {
                return $this->response_not_found();
            }
            return $this->response_ok($users);
        }

        $group = $this->Grupe_model->find_row(compact('id'));
        return $this->response_ok($group);
    }

    /**
     * @api {put} /grupe/:id/korisnici/:user_id Dodavanje korisnika u grupu
     * @apiName PutKorisnikInGrupa
     * @apiDescription Dodaj korisnika u grupu
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     * @apiParam {Number} user_id Jedinstveni identifikator korisnika
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     */
    public function index_put()
    {
        if (!$this->Grupe_model->can_user('add user')) {
            return $this->response_unauthorized();
        }
        $id = (int)$this->get('id');
        if ($id <= 1) {
            return $this->response_bad_request();
        }

        $user_id = (int)$this->get('korisnici');
        if ($user_id <= 0) {
            return $this->response_bad_request();
        }

        if ($this->Grupe_model->add_user_to_group($user_id, $id)) {
            return $this->response_ok();
        }

        return $this->response_bad_request();
    }

    /**
     * @api {post} /grupe Kreiranje nove grupe
     * @apiName PostGrupa
     * @apiDescription Kreiranje nove grupe
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {String} name Naziv grupe
     *
     * @apiUse ErrorUnauthorized
     * @apiSuccess {Object} grupa
     * @apiUse GrupaSuccess
     */
    /**
     * @api {post} /grupe/:id Izmena date grupe
     * @apiName PostGrupa
     * @apiDescription Izmena grupe
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     * @apiParam {String} name Naziv grupe
     *
     * @apiUse ErrorUnauthorized
     * @apiUse GrupaSuccess
     */
    public function index_post()
    {
        $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');

        if ($this->form_validation->run()) {
            $id = $this->get('id');
            if ($id === null) {
                if (!$this->Grupe_model->can_user('create')) {
                    return $this->response_unauthorized();
                }
                $id = $this->Grupe_model->create_group($this->post('name'));
                $group = $this->Grupe_model->find_row(compact('id'));
                if ($group) {
                    return $this->response_ok($group);
                }
            } else {
                if (!$this->Grupe_model->can_user('update')) {
                    return $this->response_unauthorized();
                }
                $id = (int)$id;
                if ($id > 1) {
                    if ($this->Grupe_model->update_group(array('id' => $id, 'name'=>$this->post('name')))) {
                        $group = $this->Grupe_model->find_row(compact('id'));
                        return $this->response_ok($group);
                    }
                }
            }
        }
        return $this->response_bad_request();
    }


    /**
     * @api {delete} /grupe/:id Brisanje grupe
     * @apiName DeleteGrupa
     * @apiDescription Brisanje grupe
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     *
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     * @apiUse ErrorBadRequest
     */
    /**
     * @api {delete} /grupe/:id/korisnici/:user_id Izbacivanje korisnika iz grupe
     * @apiName DeleteKorisnikFromGrupa
     * @apiDescription Izbacivanje korisnika iz grupe
     * @apiGroup Grupe
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator grupe
     * @apiParam {Number} user_id Jedinstveni identifikator korisnika
     *
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     * @apiUse ErrorBadRequest
     */
    public function index_delete()
    {
        $id = (int)$this->get('id');
        if ($id <= 1) {
            return $this->response_bad_request();
        }

        $user_id = (int)$this->get('korisnici');
        if ($user_id <= 0) {
            if (!$this->Grupe_model->can_user('delete')) {
                return $this->response_unauthorized();
            }
            if ($this->Grupe_model->delete_group($id)) {
                return $this->response_ok();
            }
            return $this->response_not_found();
        }

        if (!$this->Grupe_model->can_user('remove user')) {
            return $this->response_unauthorized();
        }
        if ($this->Grupe_model->remove_user_from_group($user_id, $id)) {
            return $this->response_ok();
        }

        return $this->response_bad_request();
    }

    protected function get_users_in_group($group_id, $user_id = null)
    {
        $this->load->model('Grupe_model');
        $users = $this->Grupe_model->get_group_users($group_id);
        if (!empty($user_id)) {
            foreach ($users as $user) {
                if ($user['id'] == $user_id) {
                    return $user;
                }
            }
            return null;
        }
        return $users;
    }
}
