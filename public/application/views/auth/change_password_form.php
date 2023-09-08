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
                                <?php echo form_input(array(
                                    'name'        => 'old_password',
                                    'id'          => 'old_password',
                                    'size'        => 30,
                                    'placeholder' => 'Stara Lozinka',
                                    'type'        => 'password',
                                    'class'       => 'form-control'
                                )); ?>
                                <?php echo form_error('old_password'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo form_input(array(
                                    'name'        => 'new_password',
                                    'id'          => 'new_password',
                                    'size'        => 30,
                                    'placeholder' => 'Nova Lozinka',
                                    'type'        => 'password',
                                    'maxlength'   => $this->config->item('password_max_length', 'tank_auth'),
                                    'class'       => 'form-control'
                                )); ?>
                                <?php echo form_error('new_password'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo form_input(array(
                                    'name'        => 'confirm_new_password',
                                    'id'          => 'confirm_new_password',
                                    'size'        => 30,
                                    'placeholder' => 'Potvrdite Novu Lozinku',
                                    'maxlength'   => $this->config->item('password_max_length', 'tank_auth'),
                                    'type'        => 'password',
                                    'class'       => 'form-control'
                                )); ?>
                                <?php echo form_error('confirm_new_password'); ?>
                            </div>
                            <?php echo form_submit(array(
                                'name' => 'change',
                                'value' => 'Promeni lozinku',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>