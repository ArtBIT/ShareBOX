<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Color Helper
 *
 * @package     ShareBOX
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Djordje Ungar (djordje@ungar.rs)
 */

// ------------------------------------------------------------------------

if (! function_exists('generate_random_pastel_color')) {
    function generate_random_pastel_color($intensity = 0.5)
    {
        $intensity = max(0, min(1, $intensity));
        $a = intval($intensity * 255);
        $b = 255 - $a;
        $r = (rand(0, $a) + $b) << 16;
        $g = (rand(0, $a) + $b) << 8;
        $b = (rand(0, $a) + $b) << 0;
        return "#" . dechex($r + $g + $b);
    }
}
