<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * API Endpoint for Merenja
 *   GET /api/v1/merenja                    // Returns all merenja
 *   GET /api/v1/merenja/16                 // Returns merenje with the id=16
 *   GET /api/v1/merenja/16/redovi          // Returns data for merenje with the id=16
 *   GET /api/v1/merenja/16/redovi/12       // Returns data row 12 for merenje with the id=16
 *   DELETE /api/v1/merenja/16              // Deletes merenje with the id=16 (if the user has the privilege to do so)
 *   DELETE /api/v1/merenja/16/redovi/12    // Delete data row 12 for merenje with the id=16
 *   POST /api/v1/merenja/16/redovi         // Add a new row to the merenje id=16
 *   GET /api/v1/merenja/16/tacke           // Returns chart data for merenje with the id=16
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

class Merenja extends API_Controller
{
    // If accessing via API Keys authentication
    // allow access to the following method only if API key has the
    // required level:
    protected $methods = array(
        'merenja_get' => array('level' => 1),
        'merenje_put' => array('level' => 5),
        'merenje_post' => array('level' => 5),
        'merenje_delete' => array('level' => 15),
        'redovi_get' => array('level' => 5),
        'redovi_put' => array('level' => 5),
        'redovi_post' => array('level' => 5),
        'datoteka_post' => array('level' => 5),
        'redovi_delete' => array('level' => 15),
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
     * @apiDefine MerenjeSuccess
     * @apiSuccess {Number} merenje.id Jedinstveni identifikator merenja
     * @apiSuccess {Number} merenje.user_id Jedinstveni identifikator korisnika koji je kreirao merenje
     * @apiSuccess {Number} merenje.group_id Jedinstveni identifikator grupe kojoj merenje pripada
     * @apiSuccess {String} merenje.name Naslov merenja
     * @apiSuccess {String} merenje.description Kratak opis merenja
     * @apiSuccess {Date} merenje.created Datum kada je merenje kreirano
     * @apiSuccess {String} merenje.type Tip merenja created Datum kada je merenje kreirano
     */
    /**
     * @apiDefine StatickiRedSuccess
     * @apiSuccess {Number} red.id Jedinstveni identifikator reda u tabeli
     * @apiSuccess {Number} red.merenje_id Jedinstveni identifikator merenja
     * @apiSuccess {Date} red.datetime Datum kada je merenje obavljeno
     * @apiSuccess {Number} red.ms Broj milisekundi
     * @apiSuccess {Number} red.flow Protok [l/min]
     * @apiSuccess {Number} red.flow_relative Protok [*l/min]
     * @apiSuccess {Number} red.pressure Pritisak [bar]
     * @apiSuccess {Number} red.density Gustina [kg/m3]
     * @apiSuccess {Number} red.temperature Temperatura [°C]
     * @apiSuccess {Number} red.volume Zapremina [m³]
     */
    /**
     * @apiDefine DinamickiRedSuccess
     * @apiSuccess {Number} red.id Jedinstveni identifikator reda u tabeli
     * @apiSuccess {Number} red.merenje_id Jedinstveni identifikator merenja
     * @apiSuccess {Number} red.time Vreme u sekundama proteklo od pocetka merenja
     * @apiSuccess {Number} red.flow Protok [l/min]
     * @apiSuccess {Number} red.pressure Pritisak [bar]
     */

    /**
     * @api {get} /merenja Spisak svih merenja
     * @apiName GetMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiSuccess {Object[]} merenje Lista svih merenja
     * @apiUse MerenjeSuccess
     *
     * @apiUse ErrorNotFound
     */
    public function merenja_get()
    {
        $offset = max(0, (int)$this->get('offset'));
        $limit = max(0, min(100, $this->get('limit') ?: 100));
        $where = null;
        if ($type = $this->get('type')) {
            $where = compact('type');
        }
        $this->load->model('Merenja_model', 'model');
        $rows = $this->model->find_rows($where, $limit, $offset);
        if ($rows) {
            return $this->response_ok($rows); // OK (200) being the HTTP response code
        } else {
            $this->response_not_found();
        }
    }


    /**
     * @api {post} /merenja/ Kreiraj novo merenje
     * @apiName PostMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} group_id Jedinstveni identifikator grupe
     * @apiParam {String} name Naziv merenja
     * @apiParam {String} description Opis merenja
     *
     * @apiSuccess {Object} merenje Informacije o merenju
     * @apiUse MerenjeSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     */

    /**
     * @api {post} /merenja/:id Ažuriraj postojeće merenje
     * @apiName PutMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     *
     * @apiParam {Number} id Jedinstveni identifikator Merenja
     * @apiParam {Number} group_id Jedinstveni identifikator grupe
     * @apiParam {String} name Naziv merenja
     * @apiParam {String} description Opis merenja
     *
     * @apiSuccess {Object} merenje Informacije o merenju
     * @apiUse MerenjeSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorNotFound
     * @apiUse ErrorUnauthorized
     */
    public function merenje_post()
    {
        $this->load->model('Merenja_model');
        $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('description', 'Opis', 'trim|required|max_length[250]');
        $this->form_validation->set_rules('group_id', 'Grupa', 'trim|required|numeric');

        $id = $this->get('id');
        if ($id === null) {
            // CREATE
            if ($this->form_validation->run()) {
                $this->load->model('Grupe_model');
                $grupe = $this->Grupe_model->get_user_groups($this->user->id);
                if (!array_key_exists($this->post('group_id'), $grupe)) {
                    return $this->response_bad_request();
                }

                if (!$this->Merenja_model->can_user('create', $this->post('group_id'))) {
                    return $this->response_unauthorized();
                }

                if ($this->Merenja_model->insert_row(array(
                    'user_id' => $this->user->id,
                    'group_id' => $this->post('group_id'),
                    'name' => $this->post('name'),
                    'description' => $this->post('description'),
                    'created' => date("Y-m-d H:i:s"),
                ))) {
                    if ($merenje = $this->Merenja_model->get_merenje($this->db->insert_id())) {
                        return $this->response_ok($merenje);
                    }
                }
            }
        } else {
            // UPDATE
            $id = intval($id);
            $this->form_validation->set_rules('id', 'ID', 'trim|required|numeric');
            if ($this->form_validation->run()) {
                if (!$this->Merenja_model->can_user('update', $id)) {
                    return $this->response_unauthorized();
                }

                $this->load->model('Grupe_model');
                $grupe = $this->Grupa_model->get_user_groups($this->user->id);
                if (!array_key_exists($this->post('group_id'), $grupe)) {
                    return $this->response_bad_request();
                }

                $this->Merenja_model->update_row(compact('id'), array(
                    'user_id' => $this->user->id,
                    'group_id' => $this->post('group_id'),
                    'name' => $this->post('name'),
                    'description' => $this->post('description'),
                ));
                if ($merenje = $this->Merenja_model->get_merenje($id)) {
                    return $this->response_ok($merenje);
                }
            }
        }
        return $this->response_bad_request();
    }

    /**
     * @api {get} /merenja/:id Informacije o datom merenju
     * @apiName GetMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     *
     * @apiSuccess {Object} merenje Informacije o merenju
     * @apiUse MerenjeSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorNotFound
     */
    public function merenje_get()
    {
        $this->load->model('Merenja_model');
        $id = $this->get('id');
        if ($id === null) {
            $merenja = $this->Merenja_model->find_rows();
            return $this->response_ok($merenja);
        }
        $id = (int)$id;
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $merenje = $this->Merenja_model->get_merenje($id);
        if ($merenje) {
            return $this->response_ok($merenje); // OK (200) being the HTTP response code
        } else {
            $this->response_not_found();
        }
    }

    /**
     * @api {delete} /merenja/:id Brisanje merenja
     * @apiName DeleteMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     *
     * @apiSuccess {Number} id Jedinstveni identifikator merenja koje je obrisano
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     */
    protected function merenje_delete()
    {
        $id = (int)$this->get('id');
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $this->load->model('Merenja_model', 'model');
        if (!$this->model->can_user('delete', $id)) {
            return $this->response_unauthorized();
        }

        $this->model->delete_merenje($id);
        return $this->response_ok(null); // OK (200) being the HTTP response code
    }

    /**
     * @api {get} /merenja/:id/redovi Prikaz svih podataka za dato staticko merenje
     * @apiName GetSviRedoviStatickogMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     *
     * @apiSuccess {Object[]} red Podaci merenja
     * @apiUse StatickiRedSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    /**
     * @api {get} /merenja/:id/redovi Prikaz svih podataka za dato dinamicko merenje
     * @apiName GetSviRedoviDinamickogMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     *
     * @apiSuccess {Object[]} red Podaci merenja
     * @apiUse DinamickiRedSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    /**
     * @api {get} /merenja/:id/redovi/:row_id Prikaz jednog reda iz tabele podataka za dato staticko merenje
     * @apiName GetRedoviStatickogMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {Number} row_id Jedinstveni identifikator reda
     *
     * @apiSuccess {Object[]} red Podaci merenja
     * @apiUse StatickiRedSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    /**
     * @api {get} /merenja/:id/redovi/:row_id Prikaz jednog reda iz tabele podataka za dato dinamicko merenje
     * @apiName GetRedoviDinamickogMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {Number} row_id Jedinstveni identifikator reda
     *
     * @apiSuccess {Object[]} red Podaci merenja
     * @apiUse DinamickiRedSuccess
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    public function redovi_get()
    {
        $merenje_id = (int)$this->get('id');
        if ($merenje_id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $this->load->model('Merenja_model');
        if (!$this->Merenja_model->can_user('view', $merenje_id)) {
            return $this->response_unauthorized();
        }
        $merenje = $this->Merenja_model->get_merenje($merenje_id);
        if ($merenje['type'] == 'nedovrsena') {
            return $this->response_bad_request();
        }
        $this->set_filename($merenje['name']);
        $id = $this->get('row_id');
        if (empty($id)) {
            // return all rows
            $all_rows = $this->Merenja_model->podaci($merenje_id);
            if ($all_rows) {
                return $this->response_ok($all_rows); // OK (200) being the HTTP response code
            } else {
                $this->response_not_found();
            }
        }
        $id = (int)$id;
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }
        $row = $this->Merenja_model->get_model_by_type($merenje['type'])->find_row(compact('merenje_id', 'id'));
        if ($row) {
            return $this->response_ok($row); // OK (200) being the HTTP response code
        } else {
            $this->response_not_found();
        }
    }

    /**
     * @api {DELETE} /merenja/:id/redovi/:row_id Brisanje odabranog reda za dato merenje
     * @apiName DeleteRedoviMerenja
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     * @apiPermission administrator
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {Number} row_id Jedinstveni identifikator reda
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    public function redovi_delete()
    {
        $merenje_id = (int)$this->get('id');
        if ($merenje_id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $this->load->model('Merenja_model');
        if (!$this->Merenja_model->can_user('delete', $merenje_id)) {
            return $this->response_unauthorized();
        }
        $merenje = $this->Merenja_model->get_merenje($merenje_id);
        if ($merenje['type'] == 'nedovrsena') {
            return $this->response_bad_request();
        }
        $id = (int)$this->get('row_id');
        if (empty($id)) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }
        $row = $this->Merenja_model->get_model_by_type($merenje['type'])->delete_row(compact('merenje_id', 'id'));
        if ($row) {
            return $this->response_ok(); // OK (200) being the HTTP response code
        } else {
            $this->response_not_found();
        }
    }


    /**
     * @api {post} /merenja/:id/redovi Dodavanje podataka u dato staticko merenje
     * @apiName PostRedStatickoMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {DateTime} datetime Datum i vreme kada je izvrešeno očitavanje formata "Y-m-d H:i:s"
     * @apiParam {Number} ms Broj milisekundi od gore navedenog datuma i vremena
     * @apiParam {Number} flow Protok [l/min]
     * @apiParam {Number} flow_relative Protok [*l/min]
     * @apiParam {Number} pressure Pritisak [bar]
     * @apiParam {Number} density Gustina [kg/m3]
     * @apiParam {Number} temperature Temperatura [°C]
     * @apiParam {Number} volume Zapremina [m³]
     *
     * @apiSuccess {Number} Jedinstveni identifikator reda
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    /**
     * @api {post} /merenja/:id/redovi Dodavanje podataka u dato dinamicko merenje
     * @apiName PostRedDinamickoMerenje
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {Number} time Vreme u sekundama proteklo od pocetka merenja
     * @apiParam {Number} flow Protok [l/min]
     * @apiParam {Number} pressure Pritisak [bar]
     *
     * @apiSuccess {Number} Jedinstveni identifikator reda
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    public function redovi_post()
    {
        $id = (int)$this->get('id');
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $this->load->model('Merenja_model');
        if (!$this->Merenja_model->can_user('update', $id)) {
            return $this->response_unauthorized();
        }
        $merenje = $this->Merenja_model->get_merenje($id);

        if ($merenje['type'] == 'nedovrsena') {
            // detect type
            if ($this->Merenja_model->get_model_by_type('staticka')->validate_row($this->post())) {
                $merenje['type'] = 'staticka';
                $this->Merenja_model->update_row(compact('id'), $merenje);
            } elseif ($this->Merenja_model->get_model_by_type('dinamicka')->validate_row($this->post())) {
                $merenje['type'] = 'dinamicka';
                $this->Merenja_model->update_row(compact('id'), $merenje);
            } else {
                return $this->response_bad_request();
            }
        }

        switch ($merenje['type']) {
            case 'staticka':
                $row = array(
                    'merenje_id'    => $id,
                    'datetime'      => $this->post('datetime'),
                    'ms'            => $this->post('ms'),
                    'flow_relative' => $this->post('flow_relative'),
                    'flow'          => $this->post('flow'),
                    'pressure'      => $this->post('pressure'),
                    'temperature'   => $this->post('temperature'),
                    'density'       => $this->post('density'),
                    'volume'        => $this->post('volume'),
                );
                break;

            case 'dinamicka':
                $row = array(
                    'merenje_id'    => $id,
                    'time'          => $this->post('time'),
                    'flow'          => $this->post('flow'),
                    'pressure'      => $this->post('pressure'),
                );
                break;

            default:
                return $this->response_bad_request();
        }



        $model = $this->Merenja_model->get_model_by_type($merenje['type']);
        if (!$model->validate_row($row)) {
            return $this->response_bad_request();
        }
        if ($affected_rows = $model->insert_row($row)) {
            return $this->response_ok($model->insert_id());
        } else {
            $this->response_not_found();
        }
    }

    /**
     * @api {get} /merenja/:id/tacke Prikaz podataka za grafikon
     * @apiName GetMerenjeTacke
     * @apiDescription Dobavi podatke vezane za merenje potrebne za iscrtavanje grafikona
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission korisnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     *
     * @apiSuccess {Object[]} tacke Podaci merenja za iscrtavanje grafikona
     * @apiSuccess {Number} tacke.key Number of miliseconds
     * @apiSuccess {Number} tacke.vaue Value at that point
     *
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorBadRequest
     * @apiUse ErrorNotFound
     */
    public function tacke_get()
    {
        $id = (int)$this->get('id');
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            return $this->response_bad_request();
        }

        $this->load->model('Merenja_model');
        if (!$this->Merenja_model->can_user('view', $id)) {
            return $this->response_unauthorized();
        }

        $podaci = $this->Merenja_model->grafikon($id);
        if ($podaci) {
            return $this->response_ok($podaci); // OK (200) being the HTTP response code
        } else {
            $this->response_not_found();
        }
    }

    /**
     * @api {post} /merenja/:id/datoteka Uvoz CSV datoteke u dato merenje
     * @apiName PostMerenjeDatoteka
     * @apiGroup Merenja
     * @apiVersion 1.0.0
     * @apiPermission vlasnik
     *
     * @apiParam {Number} id Jedinstveni identifikator merenja
     * @apiParam {File} file CSV datoteka
     *
     * @apiUse ErrorBadRequest
     * @apiUse ErrorUnauthorized
     * @apiUse ErrorNotFound
     */
    public function datoteka_post()
    {
        $id = (int)$this->get('id');
        if ($id <= 0) {
            return $this->response_bad_request();
        }
        $this->load->model('Merenja_model');
        $merenje = $this->Merenja_model->get_merenje($id);
        if (empty($merenje)) {
            return $this->response_bad_request();
        }

        if (!$this->Merenja_model->can_user('update', $id)) {
            return $this->response_unauthorized();
        }

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv|CSV';
        $config['max_size'] = '3000';
        $config['remove_spaces'] = true;
        $this->load->library('upload', $config);

        $name = 'file';
        if (! $this->upload->do_upload($name)) {
            $this->form_validation->set_error($name, $this->upload->display_errors());
            $this->response_bad_request(strip_tags($this->upload->display_errors()));
        }
        $filedata = $this->upload->data();
        $type = $this->Merenja_model->get_csv_type($filedata['full_path']);
        if (($merenje['type'] !== 'nedovrsena') && ($merenje['type'] !== $type)) {
            return $this->response_bad_request();
        }
        $this->Merenja_model->insert_rows_from_csv($id, $filedata['full_path']);
        if ($merenje['type'] == 'nedovrsena') {
            $merenje['type'] = $type;
            $this->Merenja_model->update_row(compact('id'), $merenje);
        }
        $this->response_ok($filedata);
    }
}
