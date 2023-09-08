<?php
    $this->load->view('partials/pagetitle', array('title' => 'API Ključevi', 'subtitle' => '<a class="btn btn-success" href="/apikeys/novo">Novi ključ</a>'));
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pregled svih API Ključeva
            </div>
            <div class="panel-body">
                <?php $this->load->view('partials/search'); ?>
                <div class="table-responsive">
                    <table id="table-grupe" class="table table-striped table-bordered table-hover" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Korisnik</th>
                                <th>Ključ</th>
                                <th>Nivo pristupa</th>
                                <th>IP adrese</th>
                                <th>Datum kreiranja</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($rows) && count($rows)) {
    ?>
                            <?php foreach ($rows as $row) {
        ?>
                                <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $korisnici[$row['user_id']]['username']; ?></td>
                                <td><?php echo $row['key']; ?></td>
                                <td><?php echo $levels[$row['level']]; ?></td>
                                <td><?php echo $row['ip_addresses']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $row['date_created']); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="/apikeys/izmeni/<?php echo $row['id']; ?>">Izmeni</a>
                                    <a class="btn btn-sm btn-danger" data-toggle="confirmation" data-href="/apikeys/obrisi/<?php echo $row['id']; ?>">Obriši</a>
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

