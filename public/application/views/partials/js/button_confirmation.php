<?php
    $this->js_begin();
?>
    $(document).ready(function() {
        $('[data-toggle="confirmation"]')
        .confirmation({
            title: 'Da li ste sigurni?'
            ,btnOkLabel: 'Da'
            ,btnCancelLabel: 'Ne'
            ,onConfirm: function(e, $element) {
                if ($element.data('method')) {
                    $.ajax({
                        url: $element.data('href'),
                        type: $element.data('method'),
                        success: function(result) {
                            location.reload();
                        }
                    });
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });
    });
<?php
    $this->js_end(DOCUMENT_BODY_END);
