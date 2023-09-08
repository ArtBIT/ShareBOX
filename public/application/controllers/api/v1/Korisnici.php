<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * API Endpoint for Grupe
 *   GET /api/v1/korisnici               // show all users
 *   GET /api/v1/korisnici/autocomplete?query=Petar // show JSON data prepared for autocomplete for the query "Petar"
 *   GET /api/v1/korisnici/2             // show user with the id 2
 *   DELETE /api/v1/korisnici/2          // delete user with the id 2
 *   PUT /api/v1/korisnici/2/role/3      // change role to 3 for the user with the id 2
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

class Korisnici extends API_Controller
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
     * @apiDefine KorisnikSuccess
     * @apiSuccess {Number} korisnik.id Jedinstveni identifikator korisnika
     * @apiSuccess {Number} korisnik.role_id Jedinstveni identifikator uloge
     * @apiSuccess {String} korisnik.username Korisničko ime
     * @apiSuccess {Number} korisnik.firstname Ime korisnika
     * @apiSuccess {Number} korisnik.lastname Prezime korisnika
     * @apiSuccess {Boolean} korisnik.activated Da li je korisnički nalog verifikovan i aktiviran
     * @apiSuccess {Boolean} korisnik.banned Da li je korisnik prognan
     */

    /**
     * @api {get} /korisnici Spisak svih korisnika
     * @apiName GetKorisnici
     * @apiGroup Korisnici
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiSuccess {Object[]} korisnik Lista svih korisnika
     * @apiUse KorisnikSuccess
     */
    /**
     * @api {get} /korisnici/:id Informacije o datom korisniku
     * @apiName GetKorisnik
     * @apiGroup Korisnici
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator korisnika
     *
     * @apiUse ErrorBadRequest
     * @apiSuccess {Object} korisnik Informacije o korisniku
     * @apiUse KorisnikSuccess
     */
    public function index_get()
    {
        $public_fields = array('id', 'role_id', 'username', 'firstname', 'lastname', 'activated', 'banned');
        $id = $this->get('id');
        $this->load->model('Korisnici_model');
        if ($id === null) {
            $korisnici = $this->Korisnici_model->find_rows();
            $korisnici = arrays_extract_keys($public_fields, $korisnici);
            return $this->response_ok($korisnici);
        }
        $id = (int)$id;
        if ($id <= 0) {
            return $this->response_bad_request();
        }

        $korisnik = $this->Korisnici_model->find_row(compact('id'));
        $korisnik = array_extract_keys($public_fields, $korisnik);
        return $this->response_ok($korisnik);
    }

    /**
     * @api {put} /korisnici/:id/uloga/:id_uloge Izmena uloge datog korisnika
     * @apiName PutKorisnikUloga
     * @apiGroup Korisnici
     * @apiVersion 1.0.0
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator korisnika
     * @apiParam {Number} id_uloge Jedinstveni identifikator uloge
     *
     * @apiUse ErrorNotFound
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorBadRequest
     * @apiSuccess {Object} korisnik Informacije o korisniku
     * @apiUse KorisnikSuccess
     */
    public function index_put()
    {
        $id = (int)$this->get('id');
        if ($id <= 0) {
            return $this->response_bad_request();
        }

        $this->load->model('Korisnici_model');
        $role = (int)$this->get('role');
        if ($role <= 0) {
            return $this->response_bad_request();
        } else {
            if (!$this->Korisnici_model->can_user('update role')) {
                return $this->response_unauthorized();
            }
            if (!$this->Korisnici_model->update_user_role($id, $role)) {
                return $this->response_bad_request();
            }
        }

        $korisnik = $this->Korisnici_model->find_row(compact('id'));
        $public_fields = array('id', 'role_id', 'username', 'firstname', 'lastname', 'activated', 'banned');
        $korisnik = array_extract_keys($public_fields, $korisnik);
        return $this->response_ok($korisnik);
    }

    /**
     * @api {delete} /korisnici/:id Brisanje datog korisnika
     * @apiName DeleteKorisnik
     * @apiGroup Korisnici
     * @apiVersion 1.0.0
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator korisnika
     *
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorBadRequest
     */
    public function index_delete()
    {
        $this->load->model('Korisnici_model');
        if (!$this->Korisnici_model->can_user('delete')) {
            return $this->response_unauthorized();
        }
        $id = (int)$this->get('id');
        if (($id <= 0) || ($id == $this->user->id)) {
            // Invalid user id or trying to delete self
            return $this->response_bad_request();
        }

        $this->Korisnici_model->delete_user($id);
        return $this->response_ok();
    }

    /**
     * @api {delete} /korisnici?query=:query Pretraga korisnika
     * @apiName QueryKorisnik
     * @apiDescription Pretraga korisnika po imenu, prezimenu i korisničkom imenu
     * @apiGroup Korisnici
     * @apiVersion 1.0.0
     * @apiPermission administrator
     *
     * @apiParam {String} query Upit za pretragu
     *
     * @apiSuccess {Object} autocomplete
     * @apiSuccess {String} autocomplete.query Upit za pretragu
     * @apiSuccess {Object[]} autocomplete.suggestions Rezultati
     * @apiSuccess {String} autocomplete.suggestions.value Združeni string za lakšu pretragu
     * @apiSuccess {Object} autocomplete.suggestions.data Informacije o korisniku
     * @apiSuccess {Number} autocomplete.suggestions.data.id Jedinstveni identifikator korisnika
     * @apiSuccess {String} autocomplete.suggestions.data.username Korisničko ime
     */
    public function autocomplete_get()
    {
        $this->load->model('Korisnici_model');
        return $this->response_ok($this->Korisnici_model->autocomplete($this->get('query')));
    }
}
