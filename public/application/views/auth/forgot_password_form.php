<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Zaboravljena lozinka</h3>
                </div>
                <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                        <fieldset>
                            <div class="form-group">
                                <?php
                                if ($this->config->item('use_username', 'tank_auth')) {
                                    $login_label = 'E-mail ili korisničko ime';
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
                            <?php echo form_submit(array(
                                'name' => 'reset',
                                'value' => 'Zahtev nove lozinke',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>