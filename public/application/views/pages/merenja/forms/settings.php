<?php
    $this->assets->add_css_file('bootstrap-select/dist/css/bootstrap-select.min.css');
    $this->assets->add_js_file('bootstrap-select/dist/js/bootstrap-select.min.js', DOCUMENT_BODY_END);
?>
<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#general" role="tab" data-toggle="tab">Opšta</a></li>
    <li><a href="#xaxis"  role="tab" data-toggle="tab">X Osa</a></li>
    <li><a href="#yaxis-left" role="tab" data-toggle="tab">Y Osa - leva</a></li>
    <li><a href="#yaxis-right" role="tab" data-toggle="tab">Y Osa - desna</a></li>
  </ul>

  <!-- Tab panes -->
  <?php echo form_open($this->uri->uri_string()); ?>
  <div class="tab-content"><div class="pp">
    <div role="tabpanel" class="tab-pane active" id="general">
        <div class="form-group">
            <?php echo form_label('Naziv', 'title'); ?>
            <?php echo form_input(array(
                'name'  => 'title',
                'id'    => 'title',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Naziv grafikona',
                'class' => 'form-control'
            )); ?>
        </div>
        <div class="checkbox">
            <label>
                <?php echo form_checkbox(array(
                    'name'  => 'show_name',
                    'id'    => 'show_name',
                    'value' => 1,
                    'checked' => 1,
                ));?>
                Prikaži i ime merenja
            </label>
        </div>
        <hr/>
        <div class="form-group">
            <?php echo form_label('Footer', 'footer'); ?>
            <?php echo form_input(array(
                'name'  => 'footer',
                'id'    => 'footer',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Tekst koji će se prikazivati ispod grafikona',
                'class' => 'form-control'
            )); ?>
        </div>
        <div class="checkbox">
            <label>
                <?php echo form_checkbox(array(
                    'name'  => 'show_date',
                    'id'    => 'show_date',
                    'value' => 1,
                    'checked' => 1,
                ));?>
                Prikaži početni datum i vreme
            </label>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="xaxis">
        <div class="form-group">
            <?php echo form_label('Naziv', 'x_title'); ?>
            <?php echo form_input(array(
                'name'  => 'x_title',
                'id'    => 'x_title',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Naziv ose',
                'class' => 'form-control'
            )); ?>
        </div>
        <fieldset>
            <legend>Ekstremumi</legend>
            <div class="checkbox">
                <label>
                    <?php echo form_checkbox(array(
                        'name'  => 'x_automatic',
                        'id'    => 'x_automatic',
                        'value' => 1,
                        'checked' => 1,
                    ));?>
                    Automatski
                </label>
            </div>
            <div class="form-group">
            <?php echo form_label('Minimum [ms]', 'x_min'); ?>
            <?php echo form_input(array(
                'name'  => 'x_min',
                'id'    => 'x_min',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
            <div class="form-group">
            <?php echo form_label('Maximum [ms]', 'x_max'); ?>
            <?php echo form_input(array(
                'name'  => 'x_max',
                'id'    => 'x_max',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
        </fieldset>
        <div class="form-group">
            <?php echo form_label('Inkrement [ms]', 'x_inc'); ?>
            <?php echo form_input(array(
                'name'  => 'x_inc',
                'id'    => 'x_inc',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Inkrement',
                'class' => 'form-control'
            )); ?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="yaxis-left">
        <div class="form-group">
            <?php echo form_label('Naziv', 'y0_title'); ?>
            <?php echo form_input(array(
                'name'  => 'y0_title',
                'id'    => 'y0_title',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Naziv ose',
                'class' => 'form-control'
            )); ?>
        </div>

        <div class="form-group">
            <?php echo form_label('Linija', 'y0_color'); ?>
            <div class="row">
            <div class="col-sm-8 no-padding">
            <?php echo form_input(array(
                'name'  => 'y0_color',
                'id'    => 'y0_color',
                'type'  => 'color',
                'class' => 'form-control'
            )); ?>
            </div>
            <select name="y0_line_type" class="linetype col-sm-4 no-padding">
              <option data-content="<span class='option-line-solid'></span>">solid</option>
              <option data-content="<span class='option-line-dashed'></span>">dashed</option>
              <option data-content="<span class='option-line-dotted'></span>">dotted</option>
            </select>
            </div>
        </div>
        <fieldset>
            <legend>Ekstremumi</legend>
            <div class="checkbox">
                <label>
                    <?php echo form_checkbox(array(
                        'name'  => 'y0_automatic',
                        'id'    => 'y0_automatic',
                        'value' => 1,
                        'checked' => 1,
                    ));?>
                    Automatski
                </label>
            </div>
            <div class="form-group">
            <?php echo form_label('Minimum [l/min]', 'y0_min'); ?>
            <?php echo form_input(array(
                'name'  => 'y0_min',
                'id'    => 'y0_min',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
            <div class="form-group">
            <?php echo form_label('Maximum [l/min]', 'y0_max'); ?>
            <?php echo form_input(array(
                'name'  => 'y0_max',
                'id'    => 'y0_max',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
        </fieldset>
        <div class="form-group">
            <?php echo form_label('Inkrement [l/min]', 'y0_inc'); ?>
            <?php echo form_input(array(
                'name'  => 'y0_inc',
                'id'    => 'y0_inc',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Inkrement',
                'class' => 'form-control'
            )); ?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="yaxis-right">
        <div class="form-group">
            <?php echo form_label('Naziv', 'y1_title'); ?>
            <?php echo form_input(array(
                'name'  => 'y1_title',
                'id'    => 'y1_title',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Naziv ose',
                'class' => 'form-control'
            )); ?>
        </div>

        <div class="form-group">
            <?php echo form_label('Linija', 'y1_color'); ?>
            <div class="row">
            <div class="col-sm-8 no-padding">
            <?php echo form_input(array(
                'name'  => 'y1_color',
                'id'    => 'y1_color',
                'type'  => 'color',
                'class' => 'form-control'
            )); ?>
            </div>
            <select name="y1_line_type" class="linetype col-sm-4 no-padding">
              <option data-content="<span class='option-line-solid'></span>">solid</option>
              <option data-content="<span class='option-line-dashed'></span>">dashed</option>
              <option data-content="<span class='option-line-dotted'></span>">dotted</option>
            </select>
            </div>
        </div>
        <fieldset>
            <legend>Ekstremumi</legend>
            <div class="checkbox">
                <label>
                    <?php echo form_checkbox(array(
                        'name'  => 'y1_automatic',
                        'id'    => 'y1_automatic',
                        'value' => 1,
                        'checked' => 1,
                    ));?>
                    Automatski
                </label>
            </div>
            <div class="form-group">
            <?php echo form_label('Minimum [bar]', 'y1_min'); ?>
            <?php echo form_input(array(
                'name'  => 'y1_min',
                'id'    => 'y1_min',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
            <div class="form-group">
            <?php echo form_label('Maximum [bar]', 'y1_max'); ?>
            <?php echo form_input(array(
                'name'  => 'y1_max',
                'id'    => 'y1_max',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => '0',
                'class' => 'form-control'
            )); ?>
            </div>
        </fieldset>
        <div class="form-group">
            <?php echo form_label('Inkrement [bar]', 'y1_inc'); ?>
            <?php echo form_input(array(
                'name'  => 'y1_inc',
                'id'    => 'y1_inc',
                'maxlength' => 100,
                'size'  => 30,
                'placeholder' => 'Inkrement',
                'class' => 'form-control'
            )); ?>
        </div>
    </div>
  </div></div>
  <?php echo form_close(); ?>

</div>

<?php $this->load->css_begin(); ?>
    .option-line-solid,
    .option-line-dashed,
    .option-line-dotted {
        display: block;
        width: 100%;
        height: 12px;
        margin-bottom: 8px;
        border-bottom: 2px #333;
    }
    
    .option-line-dotted { border-bottom-style: dotted; }
    .option-line-dashed { border-bottom-style: dashed; }
    .option-line-solid { border-bottom-style: solid; }

<?php $this->load->css_end(); ?>
<?php $this->load->js_begin(); ?>
$(function() {
    sharebox.define('sharebox.modals.init.configure', function($modal) {
        $modal.find('a[role=tab]').click(function (e) {
            e.preventDefault()
            $modal
                .find('.tab-content .tab-pane').hide().end() 
                .find($(this).attr('href')).css('visibility', 'visible').show();
        }).eq(0).click();
        $modal.find('.linetype').addClass('selectpicker').selectpicker();
        $modal.on('dialogclose', function(event, ui) {
            window.sharebox.chartapp.configChanged();
        });
    });

    $('.tab-pane').hide().eq(0).show();
});
<?php $this->load->js_end(DOCUMENT_BODY_END); ?>
