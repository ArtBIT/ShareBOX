<?php
    foreach (array(
        'common.css',
        'bootstrap/dist/css/bootstrap.min.css',
        'sharebox.css',
        'font-awesome/css/font-awesome.min.css',
    ) as $file) {
        $this->assets->add_css_file($file, DOCUMENT_HEAD);
    }
    foreach (array(
        'jquery/dist/jquery.min.js',
        'bootstrap/dist/js/bootstrap.min.js',
        'polyfills.js',
        'core.js',
    ) as $file) {
        $this->assets->add_js_file($file, DOCUMENT_HEAD);
    }
