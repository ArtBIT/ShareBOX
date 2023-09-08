<?php
$this->assets->add_js_file('devbridge-autocomplete/dist/jquery.autocomplete.min.js', DOCUMENT_BODY_END);
$this->js_begin(); ?>
    jQuery(function() {
        $('[data-toggle=username_autocomplete]').each(function() {
            var $this = $(this);
            $this.autocomplete({
                width: 'flex',
                serviceUrl: '/api/v1/korisnici/autocomplete',
                onSelect: function (suggestion) {
                    $(this).data('user_id', suggestion.data.id).val(suggestion.data.username);
                }
            });
        });
    });
<?php $this->js_end(DOCUMENT_BODY_END); ?>

