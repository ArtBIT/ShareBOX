<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Logger_model defines the CodeIgniter model for logging user actions.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class Logger_model extends MY_Model
{
    protected $table_name = 'log';

    protected function where_not_deleted()
    {
        // ignore deleted clause
    }
 
    public function delete_rows($where = array())
    {
        $this->where($where);
        return $this->db->delete($this->table_name);
    }
    public function delete_row($where = array())
    {
        $this->where($where);
        $this->db->limit(1);
        return $this->db->delete($this->table_name);
    }
}
