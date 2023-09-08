<?php
    $this->load->view('partials/pagetitle', array('title' => 'Grupe', 'subtitle' => '<a class="btn btn-success" href="/grupe/novo">Nova grupa</a>'));
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pregled svih grupa
            </div>
            <div class="panel-body">
                <?php $this->load->view('partials/search'); ?>
                <div class="table-responsive">
                    <table id="table-grupe" class="table table-striped table-bordered table-hover" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ime grupe</th>
                                <th>Privilegije članova</th>
                                <th>Datum kreiranja</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($grupe) && count($grupe)) {
    ?>
                            <?php foreach ($grupe as $grupa) {
        ?>
                                <tr>
                                <td><?php echo $grupa['id']; ?></td>
                                <td><?php echo $grupa['name']; ?></td>
                                <td><?php echo $grupa['access_level']; ?></td>
                                <td><?php echo $grupa['created']; ?></td>
                                <td>
                                    <?php if ($grupa['id'] > 1) {
            ?>
                                    <a class="btn btn-sm btn-warning" href="/grupe/izmeni/<?php echo $grupa['id']; ?>">Izmeni</a>
                                    <a class="btn btn-sm btn-danger" data-toggle="confirmation" data-method="delete" data-href="/api/v1/grupe/<?php echo $grupa['id']; ?>">Obriši</a>
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
                </div>
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>

