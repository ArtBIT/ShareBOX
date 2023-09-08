<?php

class Korisnici_model extends MY_Model
{
    protected $table_name = 'users';

    public function get_user_by_username($username)
    {
        $result = $this->memoize($username);
        if (empty($result)) {
            $result = $this->find_row(compact('username'));
            $this->memoize($username, $result);
        }
        return $result;
    }
    public function get_user_by_id($id)
    {
        $result = $this->memoize($id);
        if (empty($result)) {
            $result = $this->find_row(compact('id'));
            $this->memoize($id, $result);
        }
        return $result;
    }
    public function delete_user($id)
    {
        $this->delete_row(compact('id'));

        $this->ci->load->model('Grupe_model');
        $this->ci->Grupe_model->delete_user($id);

        $this->log(LogAction::USER_DELETE, $id);
    }

    public function update_user_role($id, $role_id)
    {
        $this->ci->load->model('auth/users');
        if ($affected_rows = $this->ci->users->update_row(compact('id'), compact('role_id'))) {
            $this->log(LogAction::USER_ROLE, compact('id', 'role_id'));
        }
        return $affected_rows;
    }

    public function autocomplete($query = '')
    {
        $this->ci->load->model('auth/users');
        $rows = $this->ci->users->find_rows(array('CONCAT(firstname, " ", lastname) LIKE' => "$query%"));
        $suggestions = array();
        if (count($rows)) {
            foreach ($rows as $row) {
                $suggestions[] = array(
                    "value" => "{$row['firstname']} {$row['lastname']} - {$row['username']}"
                    , "data" => array('username'=>$row['username'], 'id' => $row['id'])
                );
            }
        }
        return array(
            'query' => $query,
            'suggestions' => $suggestions
        );
    }

    public function can_user($action)
    {
        if ($this->ci->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_USER_ADMINISTRATOR)) {
            return true;
        }
        switch ($action) {
            case 'update':
            case 'create':
            case 'delete':
            case 'update role':
                return false;
                break;

            case 'view':
            default:
                return true;
        }
        return false;
    }
}
