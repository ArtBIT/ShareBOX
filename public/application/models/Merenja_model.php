<?php

class Merenja_model extends MY_Model
{
    protected $table_name = 'merenja';

    public function get_merenje($id)
    {
        $id = intval($id);
        $value = $this->memoize($id);
        if (empty($value)) {
            $value = $this->find_row(compact('id'));
            $this->memoize($id, $value);
        }
        return $value;
    }

    public function get_model_by_type($type)
    {
        switch ($type) {
            case 'staticka':
            case 'dinamicka':
            case 'nedovrsena':
                break;
            default:
                throw new Exception('Invalid merenje type');
        }
        $model = 'Merenja_'.$type.'_model';
        if (!isset($this->ci->$model)) {
            $this->ci->load->model($model);
        }
        return $this->ci->$model;
    }

    public function get_model_by_merenje_id($id)
    {
        $merenje = $this->get_merenje($id);
        $type = $merenje['type'];
        return $this->get_model_by_type($type);
    }

    public function podaci($id)
    {
        return $this->get_model_by_merenje_id($id)->find_rows(array('merenje_id'=>$id));
    }

    public function grafikon($id)
    {
        return $this->get_model_by_merenje_id($id)->get_chart_data($id);
    }

    public function delete_merenje($id)
    {
        // Delete the row from the table merenja
        $merenje_model = $this->get_model_by_merenje_id($id);
        if ($this->delete_rows(compact('id'))) {
            $this->log(LogAction::MERENJE_DELETE, $id);
            // Delete all the data from the merenja_staticka or merenja_dinamicka
            $merenje_model->delete_rows(array('merenje_id'=>$id));
        }
    }

    public function get_csv_type($filepath)
    {
        static $type_by_filepath;

        if (!isset($type_by_filepath[$filepath])) {
            $file = new SplFileObject($filepath);
            $version_number = '100157';
            // Read the first line
            $file->seek(0);
            $line = trim($file->current());
            if ($line == $version_number) {
                // Log12345.CSV files start with a line with just this number on it
                // Skip it and read the next line
                $file->next();
            }

            $line = trim($file->current());
            if (strcmp($line, 'Datum;Uhrzeit;*l/min;l/min;bar;C;kg/m3;m3') == 0) {
                $type = 'staticka';
            } elseif (strcmp($line, "Zeit [s];Durchfluss [l/min];Druck [bar];$version_number") == 0) {
                $type = 'dinamicka';
            } else {
                $type = false;
            }
            $type_by_filepath[$filepath] = $type;
        }
        return $type_by_filepath[$filepath];
    }

    public function insert_rows_from_csv($merenje_id, $filepath)
    {
        $type = $this->get_csv_type($filepath);
        return $this->Merenja_model->get_model_by_type($type)->insert_rows_from_csv($merenje_id, $filepath);
    }

    // Check whether the group in which the merenje has been submitted to allows specific action for the current user
    public function can_user($action, $id = null)
    {
        if ($this->ci->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR)) {
            return true;
        }

        $this->ci->load->model('Grupe_model');
        $groups = $this->Grupe_model->get_user_groups($this->ci->user->id);
        if (empty($groups)) {
            return false;
        }

        // when checking whether the user can create merenje, we pass in a gorup_id, since there is no merenje yet
        if ($action == 'create') {
            $grupa = $groups[$id];
        } else {
            if ($merenje = $this->get_merenje($id)) {
                $grupa = $groups[$merenje['group_id']];
                $is_user_owner = $merenje['user_id'] == $this->ci->user->id;
                if ($is_user_owner) {
                    $grupa['access_level'] = 'kreiranje';
                }
            } else {
                return false;
            }
        }
        $access_level = array_search($grupa['access_level'], $this->Grupe_model->access_levels);

        switch ($action) {
            case 'export':
            case 'view':
                return $access_level >= 0;
            case 'update':
                return $access_level >= 1;
            case 'delete':
            case 'create':
                return $access_level >= 2;
        }
        return false;
    }
}
