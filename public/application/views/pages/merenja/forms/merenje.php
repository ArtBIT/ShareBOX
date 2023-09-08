<?php $this->load->view('partials/pagetitle', array('title' => $title)); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $title ?>
            </div>
            <div class="panel-body">
            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
                <?php echo form_hidden('id', $merenje['id']); ?>
                <fieldset>

                    <?php echo bootstrap_form_controls(array(
                        array(
                            'label' => 'Naziv',
                            'control' => 'input',
                            'name'  => 'name',
                            'id'    => 'name',
                            'size'  => 50,
                            'placeholder' => 'Naziv merenja',
                            'type' => 'text',
                            'style' => 'width:200px',
                            'value' => set_value('name', $name),
                        ),
                        array(
                            'label' => 'Opis',
                            'control' => 'input',
                            'name'  => 'description',
                            'id'    => 'description',
                            'size'  => 250,
                            'placeholder' => 'Opis merenja',
                            'type' => 'text',
                            'style' => 'width:200px',
                            'value' => set_value('name', $description),
                            'maxlength' => 250,
                        ),
                        array(
                            'control'  => 'dropdown',
                            'label'    => 'Grupa',
                            'name'     => 'group_id',
                            'selected' => set_value('group_id', $group_id),
                            'options'  => $groups,
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'right',
                            'title'    => 'Ovo merenje će biti vidljivo samo članovima odabrane grupe',
                        ),
                    )); ?>
                    <div class="form-group fileinput">
                        <?php echo form_label('Kliknite da odaberete datoteku ili je prevucite ovde...', 'file'); ?>
                        <?php echo form_upload(array(
                            'name'  => 'file',
                            'id'    => 'file',
                            'value' => set_value('file'),
                            'class' => 'form-control',
                        )); ?>
                        <?php echo form_error('file'); ?>
                        <?php $this->js_begin(); ?>
                            $(function() {
                                $('.fileinput input').on('change', function(e) {
                                    var filename = $('input[type=file]')[0].files[0].name
                                    $(this).parent().find('label').text('Odabrana datoteka: '+filename);
                                });
                            });
                        <?php $this->js_end(DOCUMENT_BODY_END); ?>
                    </div>

                    <?php echo bootstrap_form_control(
                        array(
                            'control'  => 'submit',
                            'label'    => false,
                            'name'     => 'submit',
                            'value'    => 'Kreiraj merenje',
                            'class'    => 'btn btn-success',
                        )
                    ); ?>
                </fieldset>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
