<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class MY_Model is the base class for all the models in the application.
 * It implements the basic functionality that all Models share.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */
 
class MY_Model extends CI_Model
{
    protected $table_name = null;
    protected $ci = null;

    protected $memoized = array();
    protected function memoize($key, $value = null)
    {
        if (!empty($value)) {
            $this->memoized[$key] = $value;
        }
        return $this->memoized[$key];
    }

    protected function cache($cache_name, $cache_value = null, $duration = 86400 /* 60x60x24 */)
    {
        $this->ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        if (!empty($cache_value)) {
            $this->ci->cache->save($cache_name, $cache_value, $duration);
            return $cache_value;
        }
        return $this->ci->cache->get($cache_name);
    }

    public function __construct()
    {
        parent::__construct();
        $this->ci = get_instance();
        $this->ci->load->database();
        $this->ci->load->library('logger/logger');
    }

    protected function log($log_action, $message = '')
    {
        $this->ci->logger->log($this->ci->user->id, $log_action, $message);
    }

    public function insert_id()
    {
        return $this->db->insert_id();
    }
 
    public function add_row($row = null)
    {
        if (is_null($row)) {
            return false;
        }
        return $this->db->insert($this->table_name, $row);
    }
 
    public function find_row($where = array())
    {
        $this->db->select('*');
        $this->where($where);
        $query = $this->db->get($this->table_name);
        if ($query && $query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }
 
    public function find_rows($where = array(), $limit = 0, $start = 0)
    {
        $this->db->select('*');
        $this->where($where);
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table_name);
        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
       
    public function count_rows($where = array())
    {
        $this->where($where);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        if ($query) {
            return $query->num_rows();
        }
        return 0;
        // count_all_results has a bug with empty queries
        // return $this->db->count_all_results();
    }

    protected function where($where = array())
    {
        foreach ($where as $key => $value) {
            if (is_numeric($key)) {
                $this->db->where($value);
            } else {
                if (is_array($value)) {
                    if (count($value)) {
                        $this->db->where_in($key, array_unique($value));
                    }
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        $this->where_not_deleted();
        return $this;
    }

    protected function where_not_deleted()
    {
        $this->db->where('deleted', 0);
    }
    
    public function order_by($column, $direction = 'asc')
    {
        $this->db->order_by($column, $direction);
        return $this;
    }
 
    public function delete_rows($where = array())
    {
        $this->db->where($where);
        $this->db->set('deleted', 1);
        return $this->db->update($this->table_name);
    }
    public function delete_row($where = array())
    {
        $this->db->where($where);
        $this->db->limit(1);
        $this->db->set('deleted', 1);
        return $this->db->update($this->table_name);
    }
 
    public function update_row($where = array(), $row = null)
    {
        if (is_null($row)) {
            return false;
        }
        $this->db->where($where);
        return $this->db->update($this->table_name, $row);
    }

    public function insert_row($row)
    {
        if (empty($row)) {
            return false;
        }
        $this->db->insert($this->table_name, $row);
        return $this->insert_id();
    }

    /**
     * Batch insert specified rows
     *
     * @param array $rows
     * @returns Int Number of affected rows
     */
    public function insert_batch($rows = array())
    {
        if (count($rows)) {
            return $this->db->insert_batch($this->table_name, $rows);
        }
    }
    
    public function bind_sql($sql, $params = array())
    {
        foreach ($params as $params_key => $param) {
            if (is_array($param)) {
                foreach ($param as $param_key => $param_value) {
                    $param[$param_key] = $this->db->escape(trim($param_value));
                }
                if (count($param)) {
                    $params[$params_key] = "(" . implode(',', $param) . ")";
                } else {
                    unset($params[$params_key]);
                }
            } else {
                $params[$params_key] = $this->db->escape_str(trim($param));
            }
        }

        return str_template($sql, $params);
    }
}
