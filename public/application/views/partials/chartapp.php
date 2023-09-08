<?php
    $this->assets->add_js_file('jqueryui/jquery-ui.min.js', DOCUMENT_BODY_END);
    $this->assets->add_css_file('jqueryui/jquery-ui.min.css');

    $this->assets->add_js_file('jquery-contextmenu/dist/jquery.contextMenu.min.js', DOCUMENT_BODY_END);
    $this->assets->add_css_file('jquery-contextmenu/dist/jquery.contextMenu.min.css', DOCUMENT_BODY_END);

    $this->assets->add_js_file('pluginmanager.js', DOCUMENT_BODY_END);
    $this->assets->add_js_file('canvaschart.js', DOCUMENT_BODY_END);
    $this->assets->add_js_file('chartapp.js', DOCUMENT_BODY_END);
    $this->assets->add_css_file('chart.css');

    $this->js_begin();
        ?>
        $(function() {
            // Load the chart data by using the following API endpoint:
            var api_endpoint = '/api/v1/merenja/<?= $merenje['id']?>/tacke';
            sharebox.api.get(api_endpoint).done(function( data ) {
                // ...and then initialize the chart application
                var app = new ChartApp($('[role=chartapp]').get(0), data);
                sharebox.define('sharebox.chartapp', app);
            });
        });
        <?php 
    $this->js_end(DOCUMENT_BODY_END);
?>
<div class="table-responsive">
<div class="chartapp panel-body" role="chartapp">
    <div class="btn-toolbar" role="toolbar" style="margin:0 auto">
        <div class="btn-group">
            <button type="button" role="configure" class="btn btn-default"><span class="fa fa-wrench"></span></button>
            <button type="button" role="table"     class="btn btn-default"><span class="fa fa-table"></span></button>
            <button type="button" role="image"     class="btn btn-default"><span class="fa fa-image"></span></button>
            <button type="button" role="export"    class="btn btn-default"><span class="fa fa-download"></span></button>
        </div>
        <div class="btn-group">
            <button type="button" role="unzoom"    class="btn btn-default"><span class="fa fa-search-minus"></span></button>
        </div>
    </div>
    <div class="row pp">
        <div class="col-lg-12">
            <div class="rel" style="min-height: 550px; height: 90vh">
                <div role="chart" class="noselect" style="width:100%; height:100%"></div>
            </div>
        </div>
    </div>
    <div class="hidden" role="modals">
        <div role="configure" title="Podešavanja">
            <?php $this->load->view('pages/merenja/forms/settings');?>
        </div>
        <div role="table" title="Podaci" data-width="800" data-height="650">
            <iframe style="width:100%;height:100%" frameborder="0" src="/merenja/podaci/<?php echo $merenje['id']?>"></iframe>
        </div>
    </div>
</div>
</div>
