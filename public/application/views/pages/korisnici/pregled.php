<?php $this->load->view('partials/pagetitle', array('title' => 'Korisnici', 'subtitle' => '<a class="btn btn-success" href="/korisnici/novo">Novi korisnik</a>')); ?>
<?php
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
    $this->load->view('partials/js/button_ajax');
    $this->js_begin();
?>
    $(document).ready(function() {
        var on_endpoint_success = function($this, data) {
            $this.parent().find('select').data('original', data.role_id);
            $this.fadeOut();
        };
        $('select').on('change', function(e) {
            var $this = $(this);
            var btn = $this.parent().find('a.btn');
            if ($this.val() != $this.data('original')) {
                btn.data('success', on_endpoint_success).attr('href', btn.data('endpoint') + $this.val()).show();
            }
            else {
                btn.hide();
            }
        });
    });
<?php
    $this->js_end(DOCUMENT_BODY_END);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pregled svih korisnika
            </div>
            <div class="panel-body">
                <?php $this->load->view('partials/search'); ?>
                <div class="table-responsive">
                    <table id="table-korisnici" class="table table-striped table-bordered table-hover" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Korisničko ime</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>E-mail</th>
                                <th>Uloga</th>
                                <th>Datum kreiranja</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($korisnici) && count($korisnici)) {
    ?>
                            <?php foreach ($korisnici as $korisnik) {
        ?>
                                <tr data-id="<?php echo $korisnik['id']; ?>">
                                <td><?php echo $korisnik['id']; ?></td>
                                <td><?php echo $korisnik['username']; ?></td>
                                <td><?php echo $korisnik['firstname']; ?></td>
                                <td><?php echo $korisnik['lastname']; ?></td>
                                <td><?php echo $korisnik['email']; ?></td>
                                <td><?php echo form_dropdown('role_id', $roles, $korisnik['role_id'], 'data-original="'.$korisnik['role_id'].'"'); ?><a class="btn btn-sm btn-default" style="display:none" href="/api/v1/korisnici/<?= $korisnik['id']?>/uloga/<?= $korisnik['role_id'] ?>" data-endpoint="/api/v1/korisnici/<?= $korisnik['id']?>/uloga/" data-ajax="put">Snimi</a></td>
                                <td><?php echo $korisnik['created']; ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="confirmation" title="Da li ste sigurni da želite da resetujete lozinku?" data-href="/korisnici/reset_password/<?php echo $korisnik['id']; ?>">Resetuj lozinku</a>
                                    <a class="btn btn-sm btn-danger" data-toggle="confirmation" data-method="delete" data-href="/api/v1/korisnici/<?php echo $korisnik['id']; ?>">Obriši korisnika</a>
                                </td>
                                </tr>
                            <?php 
    } ?>
                        <?php 
} ?>
                        </tbody>
                    </table>
                </div>
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>

