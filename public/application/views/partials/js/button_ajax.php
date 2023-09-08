<?php
    $this->js_begin();
?>

    $(document).ready(function() {
        $('a[data-ajax]').on('click', function(e) {
            var $this = $(this);
            sharebox.api
                .request($this.data('ajax'), $this.attr('href'))
                .success(function(data, status, xhr) {
                    var callback = $this.data('success');
                    if (callback) {
                        if ($.isFunction(callback)) {
                            callback($this, data, status, xhr);
                        } else if ($this[callback]) {
                            $this[callback](data, status, xhr);
                        } else if (window[callback]) {
                            window[callback]($this, data, status, xhr);
                        }
                    } 
                });
            e.preventDefault();
            e.stopPropagation();
        });
    });
<?php
    $this->js_end(DOCUMENT_BODY_END);
