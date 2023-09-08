<?php
$password = array(
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 30,
);
$email = array(
    'name'  => 'email',
    'id'    => 'email',
    'value' => set_value('email'),
    'maxlength' => 80,
    'size'  => 30,
);
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Promeni e-mail</h3>
                </div>
                <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                        <fieldset>
                            <div class="form-group">
                                <?php echo form_label('Lozinka', $password['id']); ?><br>
                                <?php echo form_password($password); ?><br>
                                <?php echo form_error($password['name']); ?>
                            </div>
                            <div class="form-group">
                                <?php echo form_label('Nova e-mail adresa', $email['id']); ?><br>
                                <?php echo form_input($email); ?><br>
                                <?php echo form_error($email['name']); ?>
                            </div>
                            <?php echo form_submit(array(
                                'name' => 'change',
                                'value' => 'Pošalji konfirmacioni e-mail',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>