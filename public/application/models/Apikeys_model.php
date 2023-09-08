<?php

class Apikeys_model extends MY_Model
{
    protected $table_name = 'api_keys';
    public $levels = array(
        1 => 'Samo pregled',
        5 => 'Kreiranje i izmena merenja',
    );

    public function get_row($id)
    {
        $cache_key = "key:{$id}";
        $result = $this->memoize($cache_key);
        if (empty($result)) {
            $result = $this->find_row(compact('id'));
            $this->memoize($cache_key, $result);
        }
        return $result;
    }

    public function get_user_keys($user_id)
    {
        $cache_key = "user:{$user_id}";
        $result = $this->memoize($cache_key);
        if (empty($result)) {
            $result = $this->find_row(compact('user_id'));
            $this->memoize($cache_key, $result);
        }
        return $result;
    }

    public function delete_user_keys($user_id)
    {
        $this->delete_row(compact('user_id'));
        $this->log(LogAction::APIKEY_DELETE, compact('user_id'));
    }

    public function generate_key()
    {
        $this->ci->load->library('guid');
        return Guid::generate()->format('D');
    }

    public function can_user($action, $id = null)
    {
        if ($this->ci->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_API_ADMINISTRATOR)) {
            return true;
        }
        switch ($action) {
            case 'create':
                return true;
            case 'update':
            case 'delete':
                if (empty($id)) {
                    return false;
                }
                $row = $this->get_row($id);
                if (empty($row)) {
                    return false;
                }
                return $row['user_id'] == $this->ci->user->id;
                break;

            case 'view':
            default:
                return true;
        }
        return false;
    }
}
