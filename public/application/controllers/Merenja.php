<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Merenja is used as a controller for the Merenja module.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Merenja extends Auth_Controller
{
    protected $type = null;
    public $breadcrumb_methods = array(
        '' => '/pregled',
        'grafikon' => false
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Form_validation');
        $this->load->model('Merenja_model');
    }

    public function index()
    {
        return $this->pregled();
    }

    public function pregled($type = '')
    {
        switch ($type) {
            case 'staticka':
            case 'dinamicka':
            case 'nedovrsena':
                break;
            default:
                $type = '';
        }
        $this->type = $type;
        $this->data['type'] = $this->type;

        // Load data about all the groups the current user belongs to
        $this->load->model('Grupe_model');
        $this->data['groups'] = arrays_extract_key_value('id', 'name', $this->Grupe_model->get_user_groups($this->user->id));

        $where = array();
        switch ($this->type) {
            case 'dinamicka':
            case 'staticka':
                $where['group_id'] = array_keys($this->data['groups']);
                $where['type'] = $this->type;
                break;
            case 'nedovrsena':
                $where['type'] = $this->type;
                $where['user_id'] = $this->user->id;
                break;
            default:
                $where['group_id'] = array_keys($this->data['groups']);
                $where['type'] = array('staticka', 'dinamicka');
        }
        if (!empty($this->search)) {
            $where[] = $this->Merenja_model->bind_sql("(`name` LIKE '{{search}}%' OR `description` LIKE '{{search}}%')", array('search' => $this->search));
        }
        $this->data['merenja'] = $this->Merenja_model->find_rows($where, ROWS_PER_PAGE, $this->offset);

        // Load all data about the users who created the previously fetched merenja
        $this->load->model('Korisnici_model');
        $this->data['users'] = arrays_index_by_key('id', $this->Korisnici_model->find_rows(array(
            'id' => arrays_extract_key('user_id', $this->data['merenja'])
        )));

        $config['base_url'] = '/' . implode('/', array_filter(array('merenja', $this->type, 'pregled')));
        $config['total_rows'] = $this->Merenja_model->count_rows($where);
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('/pages/merenja/pregled');
    }

    public function izmeni($merenje_id)
    {
        $merenje_id = intval($merenje_id);

        $this->data['title'] = 'Ažuriraj Merenje';
        $this->load->model('Grupe_model');
        $this->data['groups'] = arrays_extract_key_value('id', 'name', $this->Grupe_model->get_user_groups($this->user->id));
        $merenje = $this->Merenja_model->get_merenje($merenje_id);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('description', 'Opis', 'trim|required|max_length[250]');
        $this->form_validation->set_rules('group_id', 'Grupa', 'trim|required|numeric');

        if ($this->form_validation->run()) {
            $row = array(
                'user_id' => $this->user->id,
                'group_id' => $this->form_validation->set_value('group_id'),
                'name' => $this->form_validation->set_value('name'),
                'description' => $this->form_validation->set_value('description'),
            );
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                // we are updating the file as well
                $filedata = $this->upload_file('file');
                // detect merenje type
                $type = $this->Merenja_model->get_csv_type($filedata['full_path']);
                $this->Merenja_model->insert_rows_from_csv($merenje_id, $filedata['full_path']);
                $row['type'] = $type;
            }
            $where = array('id' => $merenje_id);
            $this->Merenja_model->update_row($where, $row);
            redirect('/merenja/' . $type);
        }
        $this->load->view('pages/merenja/forms/merenje', $merenje);
    }

    /**
     * Prikazuje stranicu sa grafikonom za odabrano merenje
     *
     * @param mixed $merenje_id
     */
    public function grafikon($merenje_id)
    {
        if (!$this->Merenja_model->can_user('view', $merenje_id)) {
            show_403();
        }
        $this->data['merenje'] = $this->Merenja_model->get_merenje($merenje_id);
        $this->type = $this->data['merenje']['type'];
        $this->data['page']['merenje'] = $this->data['merenje'];
        $this->data['page']['type'] = $this->type;

        $this->breadcrumbs->push('Merenje', '/merenja/grafikon');

        $this->load->view('/pages/merenja/grafikon');
    }

    public function novo()
    {
        $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('description', 'Opis', 'trim|required|max_length[250]');
        $this->form_validation->set_rules('group_id', 'Grupa', 'trim|required|numeric');

        if ($this->form_validation->run()) {
            $filedata = $this->upload_file('file');
            if (!$this->form_validation->has_errors()) {
                $type = $this->Merenja_model->get_csv_type($filedata['full_path']);
                if (!$type) {
                    $this->form_validation->set_error('file', 'Nepoznat format AirBOX CSV datoteke.');
                } else {
                    // EVERYTHING IS OK, INSERT INTO DB
                    $this->Merenja_model->insert_row(array(
                        'user_id' => $this->user->id,
                        'group_id' => $this->form_validation->set_value('group_id'),
                        'name' => $this->form_validation->set_value('name'),
                        'description' => $this->form_validation->set_value('description'),
                        'type' => $type,
                        'created' => date("Y-m-d H:i:s"),
                    ));
                    $merenje_id = $this->db->insert_id();
                    if (!empty($merenje_id)) {
                        if ($this->Merenja_model->insert_rows_from_csv($merenje_id, $filedata['full_path'])) {
                            $this->set_message('Izmene uspešno sačuvane', '/merenja/grafikon/'.$merenje_id);
                        } else {
                            $this->form_validation->set_error('file', 'Dogodila se greška prilikom unosa u bazu');
                        }
                    }
                }
            } else {
                $this->form_validation->set_error('file', 'CSV datoteka je nepravilnog formata');
            }
        }

        $this->load->model('Grupe_model');
        $this->data['groups'] = arrays_extract_key_value('id', 'name', $this->Grupe_model->get_user_groups($this->user->id));
        $this->data['title'] = 'Kreiraj novo merenje';
        $this->load->view('pages/merenja/forms/merenje');
    }

    protected function upload_file($name)
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv|CSV';
        $config['max_size'] = '3000';
        $config['remove_spaces'] = true;

        $this->load->library('upload', $config);

        if (! $this->upload->do_upload($name)) {
            $this->form_validation->set_error($name, $this->upload->display_errors());
            return array();
        }
        return $this->upload->data();
    }

    /**
     * Vraca sve podatke vezane za $merenje_id kao tabelu sa paginacijom
     *
     * @param mixed $merenje_id
     */
    public function podaci($merenje_id)
    {
        $offset = (int)$this->input->get('offset', 0);
        $merenje_id = intval($merenje_id);

        // merenje
        $merenje = $this->Merenja_model->get_merenje($merenje_id);
        $this->data['merenje'] = $merenje;
        // Rows
        $model = $this->Merenja_model->get_model_by_merenje_id($merenje['id']);
        $this->data['rows'] = $model->find_rows(array(
            'merenje_id' => $merenje_id
        ), ROWS_PER_PAGE, $offset);

        // pagination
        $this->load->library('pagination');
        $config['base_url'] = '/merenja/podaci/' . $merenje_id;
        $config['total_rows'] = $model->count_rows(array(
            'merenje_id' => $merenje_id
        ));
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        // render
        $this->layout = 'naked';
        $this->data['headers'] = $model->db_headers;
        unset($this->data['headers']['deleted']);
        unset($this->data['headers']['merenje_id']);
        $this->load->view('pages/merenja/podaci', $this->data);
    }
}
