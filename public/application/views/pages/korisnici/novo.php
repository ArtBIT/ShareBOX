<?php $this->load->view('partials/pagetitle', array('title' => 'Novi korisnički profil')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Novi korisnički profil
            </div>
            <div class="panel-body">
            <?php echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal')); ?>
                <fieldset>

                    <?php echo bootstrap_form_controls(array(
                        array(
                            'label' => 'Korisničko ime',
                            'control' => 'input',
                            'name'  => 'username',
                            'id'    => 'username',
                            'value' => set_value('username'),
                            'maxlength' => $this->config->item('username_max_length', 'tank_auth'),
                            'size'  => 30,
                            'placeholder' => 'Korisničko ime',
                        ),
                        array(
                            'label' => 'Ime',
                            'control' => 'input',
                            'name'  => 'firstname',
                            'id'    => 'firstname',
                            'value' => set_value('firstname'),
                            'maxlength' => 30,
                            'size'  => 30,
                            'placeholder' => 'Ime',
                        ),
                        array(
                            'label' => 'Prezime',
                            'control' => 'input',
                            'name'  => 'lastname',
                            'id'    => 'lastname',
                            'value' => set_value('lastname'),
                            'maxlength' => 30,
                            'size'  => 30,
                            'placeholder' => 'Prezime',
                        ),
                        array(
                            'label' => 'E-mail',
                            'placeholder' => 'E-mail',
                            'control' => 'input',
                            'name'  => 'email',
                            'id'    => 'email',
                            'value' => set_value('email'),
                            'maxlength' => 80,
                            'size'  => 30,
                            'type' => 'email',
                        ),
                        array(
                            'label' => 'Lozinka',
                            'placeholder' => 'Lozinka',
                            'control' => 'input',
                            'name'  => 'password',
                            'id'    => 'password',
                            'value' => set_value('password'),
                            'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                            'size'  => 30,
                            'type' => 'password',
                        ),
                        array(
                            'label' => 'Potvrdi lozinku',
                            'control' => 'input',
                            'name'  => 'confirm_password',
                            'id'    => 'confirm_password',
                            'value' => set_value('password'),
                            'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                            'size'  => 30,
                            'type' => 'password',
                        ),
                        array(
                            'control'  => 'submit',
                            'label'    => false,
                            'name'     => 'submit',
                            'value'    => 'Kreiraj korisnika',
                            'class'    => 'btn btn-success',
                        )
                    )); ?>

                </fieldset>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
