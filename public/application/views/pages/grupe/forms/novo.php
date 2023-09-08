<?php $this->load->view('partials/pagetitle', array('title' => 'Nova korisnička grupa')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Nova korisnička grupa
            </div>
            <div class="panel-body">
            <?php echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal')); ?>
                <fieldset>
                    <?php echo form_hidden('id', set_value('id', $id)); ?>
                    <div class="form-group">
                    <?php echo bootstrap_form_controls(array(
                        array(
                            'label' => 'Naziv grupe',
                            'control' => 'input',
                            'name'  => 'name',
                            'id'    => 'name',
                            'size'  => 50,
                            'placeholder' => 'Ime',
                            'type' => 'text',
                            'style' => 'width:200px',
                            'value' => set_value('name', $name),
                        ),
                        array(
                            'control'  => 'dropdown',
                            'label'    => 'Akcije dozvoljene članovima',
                            'name'     => 'access_level',
                            'selected' => set_value('access_level'),
                            'options'  => $access_levels,
                        ),
                        array(
                            'control'  => 'submit',
                            'label'    => false,
                            'name'     => 'submit',
                            'value'    => 'Snimi',
                            'class'    => 'btn btn-success',
                        )
                    )); ?>
                </fieldset>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

