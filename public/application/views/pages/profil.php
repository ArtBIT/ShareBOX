<?php $this->load->view('partials/pagetitle', array('title' => 'Korisnički Profil')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Izmenite Vaš korisnički profil
            </div>
            <div class="panel-body">
                <?php echo form_open('/profil/save', array('class'=>'form-horizontal')); ?>
                    <fieldset>
                    <?php echo bootstrap_form_controls(array(
                        array(
                            'label' => 'Korisničko ime',
                            'control' => 'input',
                            'name'  => 'username',
                            'id'    => 'username',
                            'value' => set_value('username', $user->username),
                            'disabled'  => 'disabled',
                        ),
                        array(
                            'label' => 'Ime',
                            'control' => 'input',
                            'name'  => 'firstname',
                            'id'    => 'firstname',
                            'value' => set_value('firstname', $user->firstname),
                            'maxlength' => 30,
                            'size'  => 30,
                            'placeholder' => 'Ime',
                        ),
                        array(
                            'label' => 'Prezime',
                            'control' => 'input',
                            'name'  => 'lastname',
                            'id'    => 'lastname',
                            'value' => set_value('lastname', $user->lastname),
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
                            'value' => set_value('email', $user->email),
                            'maxlength' => 80,
                            'size'  => 30,
                            'type' => 'email',
                        ),
                        array(
                            'label' => 'Trenutna lozinka',
                            'control' => 'input',
                            'name'  => 'old_password',
                            'id'    => 'old_password',
                            'value' => set_value('old_password'),
                            'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                            'size'  => 30,
                            'type' => 'password',
                        ),
                        array(
                            'label' => 'Nova lozinka',
                            'control' => 'input',
                            'name'  => 'password',
                            'id'    => 'password',
                            'value' => set_value('password'),
                            'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                            'size'  => 30,
                            'type' => 'password',
                        ),
                        array(
                            'label' => 'Potvrdi novu lozinku',
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
                            'value'    => 'Sačuvaj izmene',
                            'class'    => 'btn btn-success',
                        )
                    )); ?>
                    </fieldset>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
