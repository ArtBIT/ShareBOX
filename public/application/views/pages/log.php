<?php $this->load->view('partials/pagetitle', array('title' => 'Log')); ?>
<?php
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
    $this->js_begin();
?>
    $(document).ready(function() {
        $('select').on('change', function(e) {
            var $this = $(this);
            if ($this.val() != $this.data('original')) {
                $this.parent().find('a.btn').removeClass('hidden');
            }
            else {
                $this.parent().find('a.btn').addClass('hidden');
            }
        });
        $('a[data-form="role"]').on('click', function(e) {
            var $this = $(this);
            var role_id = $this.parent().find('select').eq(0).val();
            var user_id = $this.closest('tr').data('id');
            $('#role_form')
                .find('[name=user_id]').val(user_id).end()
                .find('[name=role_id]').val(role_id).end()
                .submit();
        });
    });
<?php
    $this->js_end(DOCUMENT_BODY_END);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Dnevnik svih akcija
            </div>
            <div class="panel-body">
                <?php $this->load->view('partials/search'); ?>
                <div class="table-responsive">
                    <table id="table-korisnici" class="table table-striped table-bordered table-hover" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Akciju izvršio</th>
                                <th>Datum/Vreme</th>
                                <th>Akcija</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($rows) && count($rows)) {
    ?>
                            <?php foreach ($rows as $row) {
        ?>
                                <tr data-id="<?php echo $row['id']; ?>">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $users[$row['user_id']]['username']; ?></td>
                                <td><?php echo $row['ts']; ?></td>
                                <td><?php echo $akcije[$row['action']]; ?></td>
                                <td><?php echo $row['comment']; ?></td>
                                </tr>
                            <?php 
    } ?>
                        <?php 
} ?>
                        </tbody>
                    </table>
                    <div class="hidden">
                        <?php echo form_open('/korisnici/uloga', array('id' => 'role_form')); ?>
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="role_id" value="">
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>

