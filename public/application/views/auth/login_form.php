<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Prijavite se</h3>
                </div>
                <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                        <fieldset>
                            <div class="form-group">
                                <?php
                                if ($login_by_username && $login_by_email) {
                                    $login_label = 'E-mail ili korisničko ime';
                                } elseif ($login_by_username) {
                                    $login_label = 'Korisničko ime';
                                } else {
                                    $login_label = 'E-mail';
                                }
                                ?>
                                <?php echo form_input(array(
                                    'name'  => 'login',
                                    'id'    => 'login',
                                    'value' => set_value('login'),
                                    'maxlength' => 80,
                                    'size'  => 30,
                                    'placeholder' => $login_label,
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo form_error('login'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo form_input(array(
                                    'name'  => 'password',
                                    'id'    => 'password',
                                    'size'  => 30,
                                    'placeholder' => 'Lozinka',
                                    'type' => 'password',
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo form_error('password'); ?>
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
                            <div class="checkbox">
                                <label>
                                    <?php echo form_checkbox(array(
                                        'name'  => 'remember',
                                        'id'    => 'remember',
                                        'value' => 1,
                                        'checked'   => set_value('remember'),
                                    ));?>
                                    Zapamti me
                                </label>
                            </div>
                            <div class="form-group">
                                <?php echo anchor('/auth/forgot_password/', 'Zaboravljena lozinka'); ?> | 
                                <?php if ($this->config->item('allow_registration', 'tank_auth')) {
                                        echo anchor('/auth/register/', 'Registrujte se');
                                    } ?>
                            </div>
                            <?php echo form_submit(array(
                                'type' => 'submit',
                                'value' => 'Prijavi se',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>