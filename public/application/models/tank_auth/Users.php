<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package Tank_auth
 * @author  Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends MY_Model
{
    protected $table_name           = 'users';          // user accounts

    public function __construct()
    {
        parent::__construct();

        $ci =& get_instance();
        $this->table_name           = $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
    }

    /**
     * Get user record by Id
     *
     * @param   int
     * @param   bool
     * @return  object
     */
    public function get_user_by_id($user_id, $activated)
    {
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return null;
    }

    /**
     * Get user record by login (username or email)
     *
     * @param   string
     * @return  object
     */
    public function get_user_by_login($login)
    {
        $this->db->where('LOWER(username)=', strtolower($login));
        $this->db->or_where('LOWER(email)=', strtolower($login));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return null;
    }

    /**
     * Get user record by username
     *
     * @param   string
     * @return  object
     */
    public function get_user_by_username($username)
    {
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return null;
    }

    /**
     * Get user record by email
     *
     * @param   string
     * @return  object
     */
    public function get_user_by_email($email)
    {
        $this->db->where('LOWER(email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return null;
    }

    /**
     * Check if username available for registering
     *
     * @param   string
     * @return  bool
     */
    public function is_username_available($username)
    {
        $this->db->select('1', false);
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Check if email available for registering
     *
     * @param   string
     * @return  bool
     */
    public function is_email_available($email)
    {
        $this->db->select('1', false);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Create new user record
     *
     * @param   array
     * @param   bool
     * @return  array
     */
    public function create_user($data, $activated = true)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;

        if ($this->db->insert($this->table_name, $data)) {
            $user_id = $this->db->insert_id();
            return array('user_id' => $user_id);
        }
        return null;
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param   int
     * @param   string
     * @param   bool
     * @return  bool
     */
    public function activate_user($user_id, $activation_key, $activate_by_email)
    {
        $this->db->select('1', false);
        $this->db->where('id', $user_id);
        if ($activate_by_email) {
            $this->db->where('new_email_key', $activation_key);
        } else {
            $this->db->where('new_password_key', $activation_key);
        }
        $this->db->where('activated', 0);
        $query = $this->db->get($this->table_name);

        if ($query->num_rows() == 1) {
            $this->db->set('activated', 1);
            $this->db->set('new_email_key', null);
            $this->db->where('id', $user_id);
            $this->db->update($this->table_name);

            return true;
        }
        return false;
    }

    /**
     * Purge table of non-activated users
     *
     * @param   int
     * @return  void
     */
    public function purge_na($expire_period = 172800)
    {
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete($this->table_name);
    }

    /**
     * Delete user record
     *
     * @param   int
     * @return  bool
     */
    public function delete_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param   int
     * @param   string
     * @return  bool
     */
    public function set_password_key($user_id, $new_pass_key)
    {
        $this->db->set('new_password_key', $new_pass_key);
        $this->db->set('new_password_requested', date('Y-m-d H:i:s'));
        $this->db->where('id', $user_id);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param   int
     * @param   string
     * @param   int
     * @return  void
     */
    public function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
    {
        $this->db->select('1', false);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 1;
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return  bool
     */
    public function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
    {
        $this->db->set('password', $new_pass);
        $this->db->set('new_password_key', null);
        $this->db->set('new_password_requested', null);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Change user password
     *
     * @param   int
     * @param   string
     * @return  bool
     */
    public function change_password($user_id, $new_pass)
    {
        $this->db->set('password', $new_pass);
        $this->db->where('id', $user_id);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    public function generate_digest_hash($username, $password)
    {
        $this->ci->load->config('rest');
        $realm = $this->ci->config->item('rest_realm');
        return md5($username.':'.$realm.':'.$password);
    }
    public function reset_digest_auth($username, $password)
    {
        $this->db->set('digest', $this->generate_digest_hash($username, $password));
        $this->db->where('username', $username);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   bool
     * @return  bool
     */
    public function set_new_email($user_id, $new_email, $new_email_key, $activated)
    {
        $this->db->set($activated ? 'new_email' : 'email', $new_email);
        $this->db->set('new_email_key', $new_email_key);
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param   int
     * @param   string
     * @return  bool
     */
    public function activate_new_email($user_id, $new_email_key)
    {
        $this->db->set('email', 'new_email', false);
        $this->db->set('new_email', null);
        $this->db->set('new_email_key', null);
        $this->db->where('id', $user_id);
        $this->db->where('new_email_key', $new_email_key);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param   int
     * @param   bool
     * @param   bool
     * @return  void
     */
    public function update_login_info($user_id, $record_ip, $record_time)
    {
        $this->db->set('new_password_key', null);
        $this->db->set('new_password_requested', null);

        if ($record_ip) {
            $this->db->set('last_ip', $this->input->ip_address());
        }
        if ($record_time) {
            $this->db->set('last_login', date('Y-m-d H:i:s'));
        }

        $this->db->where('id', $user_id);
        $this->db->update($this->table_name);
    }

    /**
     * Ban user
     *
     * @param   int
     * @param   string
     * @return  void
     */
    public function ban_user($user_id, $reason = null)
    {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned'        => 1,
            'ban_reason'    => $reason,
        ));
    }

    /**
     * Unban user
     *
     * @param   int
     * @return  void
     */
    public function unban_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned'        => 0,
            'ban_reason'    => null,
        ));
    }
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */
