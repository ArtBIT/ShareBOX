<?php $this->load->view('partials/js/username_autocomplete'); ?>
<?php $this->load->view('partials/pagetitle', compact('title')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $title ?>
            </div>
            <div class="panel-body">
                <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
                    <fieldset>
                        <?php echo form_hidden('id', set_value('id', $id)); ?>
                        <?php echo bootstrap_form_controls(array(
                            array(
                                'name'     => 'key',
                                'control'  => 'input',
                                'label'    => 'Ključ',
                                'size'     => 50,
                                'type'     => 'text',
                                'style'    => 'width:320px',
                                'readonly' => 'readonly',
                                'value'    => set_value('name', $key),
                            ),
                            array(
                                'name'     => 'username',
                                'control'  => 'input',
                                'label'    => 'Vezan za korisnika',
                                'size'     => 50,
                                'type'     => 'text',
                                'data-toggle' => 'username_autocomplete',
                                'value'    => set_value('username', $username),
                                'tooltip'  => 'Oprez: API zahtev će biti izvršen kao da ga je izvršio odabrani korisnik, sa svim privilegijama koje korisnik ima.',
                            ),
                            array(
                                'control'  => 'textarea',
                                'label'    => 'IP adrese<br>(po jedna adresa u svakom redu)',
                                'name'     => 'ip_addresses',
                                'rows'     => 5,
                                'cols'     => 20,
                                'tooltip'  => 'Lista IP adresa sa kojih je dozvoljen pristup koristeći ovaj API ključ',
                                'value'    => set_value('ip_addresses', $ip_addresses)
                            ),
                            array(
                                'control'  => 'dropdown',
                                'label'    => 'Nivo pristupa',
                                'name'     => 'level',
                                'selected' => set_value('level', $level),
                                'options'  => $levels,
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
