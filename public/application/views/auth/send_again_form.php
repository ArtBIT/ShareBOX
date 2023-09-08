<?php
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
                    <h3 class="panel-title">Aktivacija naloga</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                    Poslata vam je e-mail poruka sa aktivacionim linkom. Da biste aktivirali vaš nalog, potrebno je da kliknete na taj link. Ukoliko želite da vam se ponovo pošalje e-mail poruka sa aktivacionim linkom, unesite vašu e-mail adresu ispod i kliknite na "Pošalji".
                    </div>
                    <?php echo form_open($this->uri->uri_string()); ?>
                        <fieldset>
                            <div class="form-group">
                                <?php echo form_label('Email adresa', $email['id']); ?>
                                <?php echo form_input($email); ?>
                                <?php echo form_error($email['name']); ?>
                            </div>
                            <?php echo form_submit(array(
                                'name' => 'send',
                                'value' => 'Pošalji',
                                'class'=>'btn btn-lg btn-success btn-block'
                            )); ?>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>