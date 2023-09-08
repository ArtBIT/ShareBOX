<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * These are the array helper functions that are used in the ShareBOX application.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */


function bootstrap_form_controls($controls)
{
    foreach ($controls as $control) {
        bootstrap_form_control($control);
    }
}

function bootstrap_form_control($data)
{
    // Label and control are used in this function only and should not be passed to form_* methods
    $helper_keys = array('label', 'control', 'tooltip');
    $helper_values = array();
    foreach ($helper_keys as $key) {
        $helper_values[$key] = isset($data[$key]) ? $data[$key] : null;
        unset($data[$key]);
    }
    if (!isset($helper_values['id'])) {
        $helper_values['id'] = $helper_values['name'];
    }
    extract($helper_values);
    $force_size = in_array($control, array('input', 'dropdown', 'textarea')); ?>
    <div class="form-group">
        <?php if ($label) {
        ?>
            <div class="col-sm-2 control-label">
                <?php echo form_label($label, $data['name']); ?>
            </div>
        <?php 
    } ?>
        <div class="<?php echo ($label === false) ? 'col-sm-offset-2 ' : '' ?>col-sm-10">
        <?php if ($force_size) {
        ?>
            <div style="width:200px">
            <?php $data['class'] .= ' form-control'; ?>
        <?php 
    } ?>

        <?php if ($tooltip) {
        ?>
        <div data-toggle="tooltip" data-placement="right" title="<?= $tooltip ?>">
        <?php 
    } ?>

        <?php echo call_user_func("form_{$control}", $data); ?>
        <?php if ($tooltip) {
        ?>
            </div>
        <?php 
    } ?>
        <?php echo form_error($data['name']); ?>
        <?php if ($force_size) {
        ?>
            </div>
        <?php 
    } ?>
        </div>
    </div>
<?php

}
