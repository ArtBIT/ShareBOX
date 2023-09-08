<?php 
    $this->load->view('partials/pagetitle', array('title' => 'Grupa ' . $name));
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
    $this->load->view('partials/js/username_autocomplete');
    $is_main_group = $id == 1;

    $this->load->view('pages/grupe/forms/izmeni', $form_izmeni);
?>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Uredjivanje članova grupe
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-grupe" class="table table-striped table-bordered table-hover" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Korisničko ime</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>E-mail</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($korisnici) && count($korisnici)) {
    ?>
                            <?php foreach ($korisnici as $korisnik) {
        ?>
                                <tr>
                                <td><?php echo $korisnik['id']; ?></td>
                                <td><?php echo $korisnik['username']; ?></td>
                                <td><?php echo $korisnik['firstname']; ?></td>
                                <td><?php echo $korisnik['lastname']; ?></td>
                                <td><?php echo $korisnik['email']; ?></td>
                                <td>
                                <?php if (!$is_main_group) {
            ?>
                                <a class="btn btn-sm btn-danger" data-toggle="confirmation" data-method="delete" data-href="/api/v1/grupe/<?php echo $id; ?>/korisnici/<?php echo $korisnik['id']; ?>">Izbaci</a>
                                <?php 
        } ?>
                                </td>
                                </tr>
                            <?php 
    } ?>
                        <?php 
} ?>
                        </tbody>
                    </table>

                    <?php echo $pagination; ?>

                </div>

                <?php if (!$is_main_group) {
    ?>
                <div>
                <?php echo form_open('/grupe/izmeni/' . $id, array('id' => 'adduser', 'style' => 'width: 400px')); ?>
                    <fieldset>
                        <div class="form-group">
                            <?php echo form_label('Dodajte novog korisnika u odabranu grupu', 'username') ?>
                            <?php echo form_input(array(
                                'name'  => 'username',
                                'id'    => 'username',
                                'size'  => 50,
                                'placeholder' => 'Unesite korisničko ime ili krenite da unosite ime i prezime',
                                'type' => 'text',
                                'class' => 'form-control',
                                'data-toggle' => 'username_autocomplete',
                                'value' => '',
                            )); ?>
                            <?php echo form_error('username'); ?>
                        </div>
                        <?php echo form_submit(array(
                            'type' => 'submit',
                            'value' => 'Dodaj korisnika',
                            'class'=>'btn btn-success'
                        )); ?>
                    </fieldset>
                <?php echo form_close(); ?>
                <?php $this->js_begin(); ?>
                    jQuery(function() {
                        $('form#adduser').submit(function(e) {
                            var $input = $(this).find('input[name=username]');
                            sharebox.api.put('/api/v1/grupe/<?=$id; ?>/korisnici/'+$input.data('user_id')).success(function() {
                                location.reload();
                            });
                            return false;
                        });
                    });
                <?php $this->js_end(DOCUMENT_BODY_END); ?>
                </div>
                <?php 
} ?>
            </div>
        </div>
    </div>
</div>

