<?php
    if (!empty($type)) {
        if ($type == "staticka") {
            $title = "Statička Merenja";
            $subtitle = '';
            $heading = "Pregled svih statičkih očitavanja";
        } elseif ($type == "dinamicka") {
            $title = "Dinamička Merenja";
            $subtitle = '';
            $heading = "Pregled svih dinamičkih očitavanja";
        } else {
            $title = "Nedovršena Merenja";
            $subtitle = '';
            $heading = "Merenja koja još nemaju podatke";
        }
    } else {
        $type = "";
        $title = "Merenja";
        $subtitle = '<small><a href="/merenja/pregled/staticka">Statička</a> | <a href="/merenja/pregled/dinamicka">Dinamička</a></small>';
        $heading = "Pregled svih očitavanja";
    }
    $this->load->view('partials/pagetitle', compact('title', 'subtitle'));
    $this->assets->add_js_file('bs-confirmation/bootstrap-confirmation.min.js', DOCUMENT_BODY_END);
    $this->load->view('partials/js/button_confirmation');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $heading ?></div>
            <div class="panel-body">
                <?php $this->load->view('partials/search'); ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="table-<?= $type ?>" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Korisnik</th>
                                <th>Grupa</th>
                                <th>Naziv</th>
                                <th>Opis</th>
                                <th>Datum</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($merenja) && count($merenja)) {
    ?>
                            <?php foreach ($merenja as $merenje) {
        ?>
                                <?php $can_edit = ($merenje['user_id'] == $user->id) || $user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR); ?>
                            <tr>
                                <td><?php echo $merenje['id']; ?></td>
                                <td><?php 
                                    $korisnik = $users[$merenje['user_id']];
        echo "{$korisnik['firstname']} {$korisnik['lastname']}"; ?></td>
                                <td><?php echo $groups[$merenje['group_id']]; ?></td>
                                <td><?php echo $merenje['name']; ?></td>
                                <td><?php echo $merenje['description']; ?></td>
                                <td><?php echo $merenje['created']; ?></td>
                                <td>
                                    <?php if ($merenje['type'] != 'nedovrsena') {
            ?>
                                    <a class="btn btn-sm btn-success" href="/merenja/grafikon/<?php echo $merenje['id']; ?>">Prikaži</a>
                                    <a class="btn btn-sm btn-success" href="/api/v1/merenja/<?php echo $merenje['id']; ?>/redovi?format=csv">Izvezi</a>
                                    <?php 
        } ?>
                                    <?php if ($can_edit) {
            ?>
                                    <a class="btn btn-sm btn-warning" href="/merenja/izmeni/<?php echo $merenje['id']; ?>">Izmeni</a>
                                    <a class="btn btn-sm btn-danger" data-toggle="confirmation" data-method="delete" data-href="/api/v1/merenja/<?php echo $merenje['id']; ?>">Obriši</a>
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
