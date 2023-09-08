<?php

class Merenja_dinamicka_model extends MY_Model
{
    protected $table_name = 'merenja_dinamicka';

    public $db_headers = array(
        /* db_column => user_label */
        'id' => 'ID',
        'merenje_id' => 'ID Merenja',
        'time' => 'Vreme [s]',
        'flow' => 'Protok [l/min]',
        'pressure' => 'Pritisak [bar]',
        'deleted' => 'Obrisan',
    );
    public $csv_headers = array(
        /* csv_column => user_label */
        'time' => 'Vreme [s]',
        'flow' => 'Protok [l/min]',
        'pressure' => 'Pritisak [bar]',
    );
    public $export_headers = array(
        'time', 'flow', 'pressure'
    );

    public function get_merenje($merenje_id)
    {
        return $this->find_rows(array(
            'merenje_id' => $merenje_id
        ));
    }

    public function get_chart_data($merenje_id)
    {
        $rows = $this->get_merenje($merenje_id);
        if (count($rows)) {
            $skip_columns = array('id', 'merenje_id', 'deleted', 'time');
            $chart_series = array();
            // seed the generator so that we allways generate the same colors
            $this->load->helper('color_helper');
            srand(22);
            foreach ($this->csv_headers as $column => $label) {
                if (in_array($column, $skip_columns)) {
                    continue;
                }
                $data = array();
                foreach ($rows as $row) {
                    $ts = ((float)$row['time'])*1000;
                    $data[$ts] = (float)$row[$column];
                }
                $series = array(
                    'name' => $label,
                    'data' => $data,
                    'color' => generate_random_pastel_color(0.9),
                );
                $chart_series[] = $series;
            }
            return $chart_series;
        }
    }

    public function insert_rows_from_csv($merenje_id, $filepath)
    {
        $this->ci->load->library('csvimport');
        $csv_data = $this->ci->csvimport->get_array(array(
            'filepath' => $filepath,
            'column_headers' => array_keys($this->csv_headers),
            'initial_line' => 1,
            'delimiter' => ';'
        ));
        if (!count($csv_data)) {
            return false;
        }

        $csv_data = $this->clean_data($csv_data);
        $keys = array_keys($this->csv_headers);
        $keys[] = 'merenje_id';
        $this->delete_rows(compact('merenje_id'));
        $rows = arrays_extract_keys($keys, arrays_add_key('merenje_id', $merenje_id, $csv_data));
        if ($this->insert_batch($rows)) {
            $this->log(LogAction::MERENJE_CREATE, $merenje_id);
            return true;
        }
        return false;
    }

    /**
     * This basically just converts the floating point numbers to use '.' instead of ','
     *
     * @param mixed $csv_data
     */
    protected function clean_data($csv_data)
    {
        foreach ($csv_data as $row_id => $row) {
            foreach ($row as $cell_id => $cell) {
                $row[$cell_id] = str_replace(',', '.', $cell);
            }
            $csv_data[$row_id] = $row;
        }
        return $csv_data;
    }

    public function to_csv($id)
    {
        $rows = $this->find_rows(array(
            'merenje_id' => $id
        ));
        if (is_null($rows)) {
            return null;
        }
        $headers = array_extract_keys($this->export_headers, $this->db_headers);
        $rows = arrays_extract_keys($this->export_headers, $rows);
        array_unshift($rows, $headers);
        return $rows;
    }

    public function validate_row($row)
    {
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->reset_validation();
        $this->ci->form_validation->set_rules('time', 'time', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('flow', 'flow', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('pressure', 'pressure', 'trim|required|numeric');

        $this->ci->form_validation->set_data($row);
        return $this->ci->form_validation->run();
    }
}
