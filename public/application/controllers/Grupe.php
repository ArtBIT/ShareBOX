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

class Grupe extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user->has_role_or_die(User::ROLE_DEVELOPER, User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_USER_ADMINISTRATOR);
        $this->load->library('form_validation');
        $this->load->model('Grupe_model');
    }

    public function index()
    {
        $this->pregled();
    }

    public function pregled()
    {
        if (!$this->Grupe_model->can_user('view')) {
            show_403();
        }
        $offset = intval($this->input->get('offset', 0));
        $where = array();
        if (!empty($this->search)) {
            $where[] = $this->Grupe_model->bind_sql("(`name` LIKE '{{search}}%')", array('search' => $this->search));
        }
        $this->data['grupe'] = $this->Grupe_model->find_rows($where, ROWS_PER_PAGE, $offset);

        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = '/grupe/pregled/';
        $config['total_rows'] = $this->Grupe_model->count_rows($where);
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('pages/grupe/pregled');
    }

    public function novo()
    {
        if (!$this->Grupe_model->can_user('create')) {
            show_403();
        }
        $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('access_level', 'Nivo pristupa', 'required|enum['.implode(',', $this->Grupe_model->access_levels).']');
        if ($this->form_validation->run()) {
            $group_id = $this->Grupe_model->create_group($this->input->post('name'), $this->input->post('access_level'));
            redirect('/grupe/izmeni/' . $group_id);
        }
        $row = array(
            'id' => '',
            'name' => '',
            'access_level' => $this->Grupe_model->access_levels,
            'access_levels' => array_combine($this->Grupe_model->access_levels, $this->Grupe_model->access_levels),
        );
        $this->load->view('pages/grupe/forms/novo', $row);
    }

    public function izmeni($id)
    {
        if (!$this->Grupe_model->can_user('update')) {
            show_403();
        }

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', 'Ime', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('id', 'ID', 'trim|required|numeric');
            $this->form_validation->set_rules('owner', 'Vlasnik', 'trim|required|existing_username');
            $access_level_rules = 'required|enum['.(implode(',', $this->Grupe_model->access_levels)).']';
            $this->form_validation->set_rules('access_level', 'Nivo pristupa', $access_level_rules);

            if ($this->form_validation->run()) {
                $owner = $this->Korisnici_model->get_user_by_username($this->input->post('owner'));
                $affected_rows = $this->Grupe_model->update_group(array(
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'owner_id' => $owner['id'],
                    'access_level' => $this->input->post('access_level'),
                ));
                if ($affected_rows) {
                    $this->session->set_flashdata('message', 'Izmene uspešno sačuvane');
                }
                redirect('/grupe/izmeni/' . $this->input->post('id'));
            }
        }

        $offset = intval($this->input->get('offset', 0));
        $row = $this->Grupe_model->find_row(compact('id'));
        $this->data = $this->data + (array)$row;

        $this->data['korisnici'] = $this->Grupe_model->get_group_users($id, ROWS_PER_PAGE, $offset);


        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = '/grupe/izmeni/'.$id;
        $config['total_rows'] = $this->db->where('group_id', $id)->count_all_results('users_groups');
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->model('Korisnici_model');
        $owner = $this->Korisnici_model->find_row(array('id' => $row['owner_id']));
        $row['owner'] = set_value('owner', $owner['username']);
        $row['access_levels'] = array_combine($this->Grupe_model->access_levels, $this->Grupe_model->access_levels);

        $this->data['form_izmeni'] = $row;
        $this->load->view('pages/grupe/grupa');
    }
}
/* End of file */
