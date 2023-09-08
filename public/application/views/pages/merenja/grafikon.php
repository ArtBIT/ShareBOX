<?php $this->load->view('partials/pagetitle', array('title' => 'Merenje: ' . $merenje['name']));?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <?php $this->load->view('partials/chartapp'); ?>
        </div>
    </div>
</div>
