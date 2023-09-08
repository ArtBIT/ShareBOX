<?php

class Merenja_staticka_model extends MY_Model
{
    protected $table_name = 'merenja_staticka';

    public $db_headers = array(
        /* db_column => user_label */
        'id'            => 'ID',
        'merenje_id'    => 'ID Merenja',
        'datetime'      => 'Datum',
        'ms'            => 'Milisekunde',
        'flow'          => 'Protok [l/min]',
        'pressure'      => 'Pritisak [bar]',
        'flow_relative' => 'Relativan protok [*l/min]',
        'volume'        => 'Zapremina [m3]',
        'temperature'   => 'Temperatura [°C]',
        'density'       => 'Gustina [kg/m3]',
        'deleted'       => 'Obrisan',
    );

    public $export_headers = array(
        'datetime', 'ms', 'flow_relative', 'flow', 'pressure', 'density', 'temperature', 'volume'
    );

    public $csv_headers = array(
        /* csv_column => user_label */
        'date'          => 'Datum',
        'time'          => 'Vreme',
        'flow_relative' => 'Relativan protok [*l/min]',
        'flow'          => 'Protok [l/min]',
        'pressure'      => 'Pritisak [bar]',
        'temperature'   => 'Temperatura [°C]',
        'density'       => 'Gustina [kg/m3]',
        'volume'        => 'Zapremina [m3]',
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
            $target_columns = array('flow', 'pressure');
            $chart_series = array();
            // seed the generator so that we allways generate the same colors
            $this->ci->load->helper('color_helper');
            srand(22);
            foreach ($this->db_headers as $column => $label) {
                if (!in_array($column, $target_columns)) {
                    continue;
                }
                // first row
                $row = reset($rows);
                $ts = strtotime("{$row['datetime']}");
                $min_ts = $ts * 1000 + intval($row['ms']);

                $data = array();
                foreach ($rows as $row) {
                    $ts = strtotime("{$row['datetime']}");
                    $ts = $ts * 1000 + intval($row['ms']);
                    $ts -= $min_ts;
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

        $csv_data = $this->parse_csv($this->clean_data($csv_data));
        $keys = array_keys($this->db_headers);
        $keys[] = 'merenje_id';
        $rows = arrays_extract_keys($keys, arrays_add_key('merenje_id', $merenje_id, $csv_data));
        if ($this->insert_batch($rows)) {
            $this->log(LogAction::MERENJE_CREATE, $merenje_id);
            return true;
        }
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


    protected function parse_csv($csv_rows)
    {
        $rows = array();
        $group_datetime = null;
        $group = array();
        foreach ($csv_rows as $row) {
            // The data stored in the CSV does not store miliseconds. We
            // need to group the rows with the same date and time (in other
            // words, group the rows that are measured within the same
            // second) and calculate at which miliseconds each row was
            // actually measured at.
            $row['date'] = DateTime::createFromFormat('d.m.Y', $row['date'])->format('Y-m-d');
            $row['datetime'] = "{$row['date']} {$row['time']}";
            if ($group_datetime != $row['datetime']) {
                if (count($group)) {
                    $diff = strtotime($row['datetime']) - strtotime($group_datetime);
                    $inc = $diff * 1000 / count($group);
                    $ms = 0;
                    foreach ($group as $group_row) {
                        $group_row['ms'] = intval($ms);
                        $rows[] = $group_row;
                        $ms += $inc;
                    }
                    $group = array();
                }
                $group_datetime = $row['datetime'];
            }
            $group[] = array(
                'datetime'      => $row['datetime'],
                'flow_relative' => $row['flow_relative'],
                'flow'          => $row['flow'],
                'pressure'      => $row['pressure'],
                'temperature'   => $row['temperature'],
                'density'       => $row['density'],
                'volume'        => $row['volume'],
            );
        }
        return $rows;
    }

    public function validate_row($row)
    {
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->reset_validation();
        $this->ci->form_validation->set_rules('datetime', 'datetime', 'trim|required|regex_match[/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/]');
        $this->ci->form_validation->set_rules('ms', 'ms', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('flow_relative', 'flow_relative', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('flow', 'flow', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('pressure', 'pressure', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('temperature', 'temperature', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('density', 'density', 'trim|required|numeric');
        $this->ci->form_validation->set_rules('volume', 'volume', 'trim|required|numeric');

        $this->ci->form_validation->set_data($row);
        return $this->ci->form_validation->run();
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
}
