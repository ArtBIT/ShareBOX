<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Grupe_model defines the CodeIgniter model for managing user groups.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Grupe_model extends MY_Model
{
    const DEFAULT_GROUP_ID = 1;

    protected $table_name = 'groups';

    public $access_levels = array('pregled', 'izmena', 'kreiranje');

    public function get_user_groups($user_id)
    {
        $result = $this->memoize($user_id);
        if (empty($result)) {
            $result = arrays_index_by_key('id',
                    $this->db->select('id, name, access_level, owner_id')
                         ->from('users_groups')
                         ->join('groups', 'users_groups.group_id = groups.id', 'left')
                         ->where('users_groups.user_id', $user_id)
                         ->get()
                         ->result_array());
            $this->memoize($user_id, $result);
        }
        return $result;
    }

    public function delete_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('users_groups');
    }

    public function add_user_to_group($user_id, $group_id)
    {
        $insert_query = $this->db->insert_string('users_groups', compact('user_id', 'group_id'));
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        if ($rows_affected = $this->db->query($insert_query)) {
            $this->log(LogAction::GROUP_ADD_USER, compact('user_id', 'group_id'));
        }
        return $rows_affected;
    }

    public function add_user_to_default_group($user_id)
    {
        return $this->add_user_to_group($user_id, self::DEFAULT_GROUP_ID);
    }

    public function remove_user_from_group($user_id, $group_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('group_id', $group_id);
        if ($rows_affected = $this->db->delete('users_groups')) {
            $this->log(LogAction::GROUP_REMOVE_USER, compact('user_id', 'group_id'));
        }
        return $rows_affected;
    }

    public function create_group($name, $access_level = 'pregled')
    {
        $row = array(
            'name' => $name,
            'user_id' => $this->user->id,
            'access_level' => $access_level,
            'created' => date("Y-m-d H:i:s")
        );
        $this->insert_row($row);
        if ($group_id = $this->db->insert_id()) {
            $this->log(LogAction::GROUP_CREATE, compact('name', 'group_id'));
        }
        return $group_id;
    }

    public function update_group($row)
    {
        if ($affected_rows = $this->update_row(array('id' => $row['id']), $row)) {
            $this->log(LogAction::GROUP_UPDATE, $row);
        }
        return $affected_rows;
    }


    public function delete_group($group_id)
    {
        $this->db->where(compact('group_id'));
        $this->db->delete('users_groups');
        if ($rows_affected = $this->delete_row(array('id' => $group_id))) {
            $this->log(LogAction::GROUP_DELETE, $group_id);
        }
        return $rows_affected;
    }


    public function get_group_users($id, $limit = ROWS_PER_PAGE, $offset = 0)
    {
        return $this->db->select('users.id, users.username, users.firstname, users.lastname, users.email')
                 ->from('users_groups')
                 ->join('users', 'users_groups.user_id = users.id', 'right')
                 ->where('users_groups.group_id', $id)
                 ->limit($limit, $offset)
                 ->get()
                 ->result_array();
    }

    public function can_user($action, $group_id = null)
    {
        if ($this->ci->user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_USER_ADMINISTRATOR)) {
            return true;
        }
        switch ($action) {
            case 'update':
            case 'create':
            case 'delete':
            case 'add user':
            case 'remove user':
                $group = $this->find_row(array('id' => $group_id));
                if (!empty($group)) {
                    if (!empty($group['owner_id']) && $group['owner_id'] == $this->user->id) {
                        return true;
                    }
                    return $group['user_id'] == $this->user->id;
                }
                break;

            case 'view':
            default:
                return true;
        }
        return false;
    }
}
