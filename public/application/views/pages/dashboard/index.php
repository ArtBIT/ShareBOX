<?php $this->load->view('partials/pagetitle', array('title' => 'Radna površina')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Radna površina
            </div>
            <div class="panel-body">
                <div class="dashboard-btns"><?php 
                    foreach ($shortcuts as $shortcut) {
                        $class = isset($shortcut['class']) ? $shortcut['class'] : 'btn btn-default';
                        $icon = "<i class='fa fa-{$shortcut['icon']}'></i>";
                        echo "<a class='{$class}' role='button' href='{$shortcut['url']}'>$icon<br/><span>{$shortcut['label']}</span></a>";
                    }
                ?></div>
            </div>
        </div>
    </div>
    <div>
</div>
</div>
