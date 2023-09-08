<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Grupe is used as a controller for the Grupe module.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Apikeys extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user->has_role_or_die(User::ROLE_DEVELOPER, User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_API_ADMINISTRATOR);
        $this->load->library('form_validation');
        $this->load->model('Apikeys_model');
    }

    public function index()
    {
        $this->pregled();
    }

    public function pregled()
    {
        if (!$this->Apikeys_model->can_user('view')) {
            show_403();
        }
        $offset = intval($this->input->get('offset', 0));
        $where = array();
        if (!empty($this->search)) {
            $where[] = $this->Apikeys_model->bind_sql("(`name` LIKE '{{search}}%')", array('search' => $this->search));
        }
        $this->data['rows'] = $this->Apikeys_model->find_rows($where, ROWS_PER_PAGE, $offset);

        // Pagination
        $this->load->model('Korisnici_model');
        $this->data['levels'] = $this->Apikeys_model->levels;
        $this->data['korisnici'] = arrays_index_by_key('id', $this->Korisnici_model->find_rows(array('id' => arrays_extract_key('user_id', $this->data['rows']))));
        $this->load->library('pagination');
        $config['base_url'] = '/apikeys/pregled/';
        $config['total_rows'] = $this->Apikeys_model->count_rows($where);
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('pages/apikeys/pregled');
    }

    public function obrisi($id)
    {
        if (!$this->Apikeys_model->can_user('delete', $id)) {
            show_403();
        }
        $id = (int)$id;
        $this->Apikeys_model->delete_row(compact('id'));
        $this->set_message('API ključ '.$id.' uspešno obrisan.', '/apikeys/pregled');
    }


    public function validate_api_key($value)
    {
        try {
            $this->load->library('guid');
            $guid = Guid::parse($value);
        } catch (Exception $err) {
            $this->form_validation->set_message('key', '{field} mora biti validn API ključ.');
            return false;
        }
        return true;
    }

    public function validate_ip_addresses($value)
    {
        if (!empty($value)) {
            $lines = array_map('trim', explode("\n", $value));
            foreach ($lines as $line) {
                if (!preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $line)) {
                    $this->form_validation->set_message('validate_ip_addresses', '{field} mora biti validna IP adresa.');
                    return false;
                }
            }
        }
        return true;
    }


    public function novo()
    {
        if (!$this->Apikeys_model->can_user('create')) {
            show_403();
        }

        $this->load->model('Korisnici_model');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('key', 'Ključ', 'trim|required|callback_validate_api_key');
            $this->form_validation->set_rules('ip_addresses', 'IP adrese', 'required|callback_validate_ip_addresses');
            $this->form_validation->set_rules('level', 'Nivo', 'required|numeric');
            $this->form_validation->set_rules('username', 'Korisničko ime', 'required|existing_username');

            $korisnik = $this->Korisnici_model->get_user_by_username($this->input->post('username'));
            $data = array(
                'user_id' => $korisnik['id'],
                'key' => $this->input->post('key'),
                'level' => $this->input->post('level'),
                'ignore_limits' => 0,
                'is_private_key' => 1,
                'ip_addresses' => implode(',', explode("\n", $this->input->post('ip_addresses'))),
                'date_created' => time(),
            );
            if ($this->form_validation->run()) {
                if ($apikey_id = $this->Apikeys_model->insert_row($data)) {
                    $this->log(LogAction::APIKEY_CREATE, array_extract_keys(array('level', 'ip_addresses'), $data));
                    redirect('/apikeys/pregled');
                } else {
                    $this->form_validation->set_error('key', 'Dogodila se greška prilikom unosa u bazu');
                }
            }
        } else {
            $data = array(
                'id' => '',
                'name' => '',
                'user_id' => $this->user->id,
                'username' => $this->user->username,
                'key' => $this->Apikeys_model->generate_key(),
            );
        }
        $data['levels'] = $this->Apikeys_model->levels;
        $data['title'] = "Novi API ključ";
        $korisnik = $this->Korisnici_model->get_user_by_id($data['user_id']);
        $data['username'] = $korisnik['username'];
        $this->load->view('pages/apikeys/form', $data);
    }

    public function check_owner_username($username)
    {
        $this->load->model('Korisnici_model');
        $owner = $this->Korisnici_model->get_user_by_username($username);
        if (empty($owner)) {
            $this->form_validation->set_message('check_owner_username', 'Odabrano korisničko ime nije validno.');
            return false;
        }
        return true;
    }

    public function izmeni($id)
    {
        if (!$this->Apikeys_model->can_user('update')) {
            show_403();
        }

        $id = (int)$id;
        if ($id <= 0) {
            show_404();
        }
        
        $dbdata = $this->Apikeys_model->find_row(compact('id'));
        if (empty($dbdata)) {
            show_404();
        }

        $this->load->model('Korisnici_model');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('key', 'Ključ', 'trim|required|callback_validate_api_key');
            $this->form_validation->set_rules('ip_addresses', 'IP adrese', 'required|callback_validate_ip_addresses');
            $this->form_validation->set_rules('level', 'Nivo', 'required|numeric');
            $this->form_validation->set_rules('username', 'Korisničko ime', 'required|existing_username');

            $korisnik = $this->Korisnici_model->get_user_by_username($this->input->post('username'));
            $data = array(
                'user_id' => $korisnik['id'],
                'key' => $this->input->post('key'),
                'level' => $this->input->post('level'),
                'ignore_limits' => 0,
                'is_private_key' => 1,
                'ip_addresses' => implode(',', explode("\n", $this->input->post('ip_addresses'))),
                'date_created' => time(),
            );
            if ($this->form_validation->run()) {
                if ($apikey_id = $this->Apikeys_model->update_row(compact('id'), $data)) {
                    $this->log(LogAction::APIKEY_UPDATE, array_extract_keys(array('level', 'ip_addresses'), $data));
                    redirect('/apikeys/pregled');
                } else {
                    $this->form_validation->set_error('key', 'Dogodila se greška prilikom unosa u bazu');
                }
            }
        } else {
            $data = $dbdata;
            if (!empty($data['ip_addresses'])) {
                $data['ip_addresses'] = implode("\n", explode(",", $data['ip_addresses']));
            }
        }

        $data['levels'] = $this->Apikeys_model->levels;
        $data['title'] = "Izmeni API ključ";
        $korisnik = $this->Korisnici_model->get_user_by_id($data['user_id']);
        $data['username'] = $korisnik['username'];
        $this->load->view('pages/apikeys/form', $data);
    }
}
/* End of file */
