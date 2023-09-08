<?php
$password = array(
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 30,
);
?>
<?php echo form_close(); ?>
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
                                <?php echo form_label('Lozinka', $password['id']); ?>
                                <?php echo form_password($password); ?>
                                <?php echo form_error($password['name']); ?>
                            </div>
                            <?php echo form_submit(array(
                                'name' => 'cancel',
                                'value' => 'Obriši nalog',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>