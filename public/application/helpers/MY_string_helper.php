<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter URL Helper
 *
 * @package     ShareBOX
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Djordje Ungar (djordje@ungar.rs)
 */

// ------------------------------------------------------------------------

if (! function_exists('str_template')) {
    function str_template($template, $data = array())
    {
        if (preg_match_all("/{{([^}]+)}}/", $template, $matches)) {
            foreach ($matches[1] as $i => $code) {
                $params = array();
                $open_bracket_pos = strpos($code, '(');
                $close_bracket_pos = strpos($code, ')');
                if ($open_bracket_pos >= 0 && $close_bracket_pos) {
                    $params = array_map('trim', explode(',', substr($code, $open_bracket_pos + 1, $close_bracket_pos - $open_bracket_pos - 1)));
                    $code = substr($code, 0, $open_bracket_pos);
                }
                if (isset($data[$code])) {
                    $value = $data[$code];
                    if (!is_string($value) && is_callable($value)) {
                        $value = call_user_func_array($value, $params);
                    }
                    $template = str_replace($matches[0][$i], sprintf('%s', $value), $template);
                }
            }
        }
        return $template;
    }
}
