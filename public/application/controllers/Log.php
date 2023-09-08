<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Log is used as a controller that logs and displays user actions.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Log extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user->has_role_or_die(User::ROLE_DEVELOPER, User::ROLE_SYSTEM_ADMINISTRATOR);
    }
    public function index()
    {
        $this->pregled();
    }

    public function pregled()
    {
        $offset = (int)$this->input->get('offset', 0);
        $this->load->model('logger/Logger_model', 'model');

        $this->data['akcije'] = array(
            LogAction::UNKNOWN => 'Nepoznata akcija',
            LogAction::USER_LOGIN => 'Prijava korisnika',
            LogAction::USER_LOGIN_FAILED => 'Neuspešna prijava korisnika',
            LogAction::USER_LOGOUT => 'Odjava korisnika',
            LogAction::USER_REGISTERED => 'Registracija korisnika',
            LogAction::USER_RESET_PASSWORD => 'Resetovanje lozinke',
            LogAction::USER_RESET_PASSWORD_FAILED => 'Neuspešno resetovanje lozinke',
            LogAction::USER_FORGOT_PASSWORD => 'Zaboravljena lozinka',
            LogAction::USER_FORGOT_PASSWORD_FAILED => 'Zaboravljena lozinka',
            LogAction::USER_CHANGE_PASSWORD => 'Promena lozinke',
            LogAction::USER_CHANGE_PASSWORD_FAILED => 'Neuspešna promena lozinke',
            LogAction::USER_RESET_EMAIL => 'Resetovanje e-maila',
            LogAction::USER_RESET_EMAIL_FAILED => 'Neuspešno resetovanje e-maila',
            LogAction::USER_CHANGE_EMAIL => 'Promena e-maila',
            LogAction::USER_CHANGE_EMAIL_FAILED => 'Neuspešna promena e-maila',
            LogAction::USER_ACTIVATION => 'Aktivacija naloga',
            LogAction::USER_ACTIVATION_FAILED => 'Neuspešna aktivacija naloga',
            LogAction::USER_DEACTIVATION => 'Deaktivacija naloga',
            LogAction::USER_DEACTIVATION_FAILED => 'Neuspešna deaktivacija naloga',
            LogAction::USER_CREATE => 'Kreiranje korisnika',
            LogAction::USER_ROLE => 'Promena korisničke uloge',
            LogAction::USER_DELETE => 'Brisanje korisnika',
            LogAction::GROUP_CREATE => 'Kreiranje grupe',
            LogAction::GROUP_UPDATE => 'Izmena grupe',
            LogAction::GROUP_DELETE => 'Brisanje grupe',
            LogAction::GROUP_ADD_USER => 'Dodavanje korisnika u grupu',
            LogAction::GROUP_REMOVE_USER => 'Izbacivanje korisnika iz grupe',
            LogAction::MERENJE_CREATE => 'Kreiranje merenja',
            LogAction::MERENJE_UPDATE => 'Ažuriranje merenja',
            LogAction::MERENJE_DELETE => 'Brisanje merenja',
            LogAction::APIKEY_CREATE => 'Kreiranje novog API ključa',
            LogAction::APIKEY_UPDATE => 'Ažuriranje API ključa',
            LogAction::APIKEY_DELETE => 'Brisanje API ključa',
        );

        $where = array();
        if (!empty($this->search)) {
            $sql = $this->model->bind_sql("`comment` LIKE '{{search}}%'", array('search' => $this->search));
            // we need to search actions outside of SQL
            $actions = array();
            foreach ($this->data['akcije'] as $key => $value) {
                if (strpos($value, $this->search) !== false) {
                    $actions[] = $key;
                }
            }
            if (count($actions)) {
                $sql .= $this->model->bind_sql(" OR `action` IN {{actions}}", compact('actions'));
            }
            $where[] = $sql;
        }
        $this->data['rows'] = $this->model->order_by('ts', 'desc')->find_rows($where, ROWS_PER_PAGE, $offset);

        $this->data['users'] = arrays_index_by_key('id', $this->Korisnici_model->find_rows(array(
            'id' => arrays_extract_key('user_id', $this->data['rows'])
        )));

        $this->load->library('pagination');
        $config['base_url'] = '/log/pregled/';
        $config['total_rows'] = $this->model->count_rows($where);
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('pages/log');
    }
}
/* End of file */
