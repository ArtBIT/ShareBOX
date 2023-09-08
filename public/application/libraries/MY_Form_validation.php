<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * MY_Form_validation class extends the CI_Form_validation class and adds additional functionality.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class MY_Form_validation extends CI_Form_validation
{
    protected $_error_prefix    = '<span class="help-inline error">';
    protected $_error_suffix    = '</span>';

    public function set_error($field, $message)
    {
        if (! isset($this->_error_array[$field])) {
            $translation = get_instance()->lang->line($message);
            $this->_field_data[$field]['error'] =
            $this->_error_array[$field] = empty($translation) ? $message : $translation;
        }
    }

    public function set_errors($errors)
    {
        foreach ($errors as $field => $error) {
            $this->set_error($field, $error);
        }
    }

    public function get_errors()
    {
        return $this->_error_array;
    }

    public function has_errors()
    {
        return count($this->_error_array);
    }

    public function is_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'deleted' => 0))->num_rows() === 0)
            : false;
    }
    public function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); //length of each label
    }

    public function existing_username($username)
    {
        $CI = get_instance();
        $CI->load->model('Korisnici_model');
        $owner = $CI->Korisnici_model->get_user_by_username($username);
        if (empty($owner)) {
            $this->set_message('existing_username', 'Odabrano korisničko ime nije validno.');
            return false;
        }
        return true;
    }


    /**
     * ENUM
     * The submitted string must match one of the values given
     *
     * usage:
     * enum[value_1, value_2, value_n]
     *
     * example (any value beside exactly 'ASC' or 'DESC' are invalid):
     * $rule['order_by'] = "required|enum[ASC,DESC]";
     *
     * example of case-insenstive enum using strtolower as validation rule
     * $rule['favorite_corey'] = "required|strtolower|enum[feldman]";
     *
     * @access    public
     * @param     string $str the input to validate
     * @param     string $val a comma separated lists of values
     * @return    bool
     */
    public function enum($str, $val='')
    {
        if (empty($val)) {
            $this->form_validation->set_message('enum', '{field} mora biti jedna od sledećih vrednosti: ' . $val);
            return false;
        }
        return in_array(trim($str), array_map('trim', explode(',', $val)));
    }
}
