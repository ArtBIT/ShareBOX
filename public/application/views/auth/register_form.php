<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Registracija</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open($this->uri->uri_string()); ?>
                            <fieldset>

                                <?php if ($use_username) {
    ?>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'username',
                                        'id'    => 'username',
                                        'value' => set_value('username'),
                                        'maxlength' => $this->config->item('username_max_length', 'tank_auth'),
                                        'size'  => 30,
                                        'placeholder' => 'Korisničko ime',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('username'); ?>
                                </div>
                                <?php 
} ?>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'firstname',
                                        'id'    => 'firstname',
                                        'value' => set_value('firstname'),
                                        'maxlength' => 30,
                                        'size'  => 30,
                                        'placeholder' => 'Ime',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('firstname'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'lastname',
                                        'id'    => 'lastname',
                                        'value' => set_value('lastname'),
                                        'maxlength' => 50,
                                        'size'  => 30,
                                        'placeholder' => 'Prezime',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('lastname'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'email',
                                        'id'    => 'email',
                                        'value' => set_value('email'),
                                        'maxlength' => 80,
                                        'size'  => 30,
                                        'placeholder' => 'E-mail',
                                        'type' => 'email',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('email'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'password',
                                        'id'    => 'password',
                                        'value' => set_value('password'),
                                        'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                                        'size'  => 30,
                                        'placeholder' => 'Lozinka',
                                        'type' => 'password',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('password'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_input(array(
                                        'name'  => 'confirm_password',
                                        'id'    => 'confirm_password',
                                        'value' => set_value('confirm_password'),
                                        'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
                                        'size'  => 30,
                                        'placeholder' => 'Potvrdi lozinku',
                                        'type' => 'password',
                                        'class' => 'form-control'
                                    )); ?>
                                    <?php echo form_error('confirm_password'); ?>
                                </div>

                                <?php if ($show_captcha) {
                                        ?>
                                <div class="form-group">
                                    <?php if ($use_recaptcha) {
                                            ?>
                                        <?php echo form_error('g-recaptcha-response'); ?>
                                        <?php echo $recaptcha_html; ?>
                                    <?php 
                                        } else {
                                            ?>
                                        <p>Molimo Vas unesite sledeći kod:</p>
                                        <?php echo $captcha_html; ?>
                                        <?php echo form_input(array(
                                            'name'  => 'captcha',
                                            'id'    => 'captcha',
                                            'maxlength' => 8,
                                            'class' => 'form-control'
                                        )); ?>
                                        <?php echo form_error('captcha'); ?>
                                    <?php 
                                        } ?>
                                </div>
                                <?php 
                                    } ?>
                                <?php echo form_submit(array(
                                    'type' => 'submit',
                                    'value' => 'Registruj se',
                                    'class'=>'btn btn-lg btn-success btn-block'
                                )); ?>
                            </fieldset>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>