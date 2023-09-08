<?php
    $this->assets->add_css_file('eucookie.css');
    $this->assets->add_js_file('eucookie.js', DOCUMENT_BODY_END);
    $this->js_begin();
?>

window.cookieconsent.initialise({
    "palette":{
        "popup":{
            "background":"#e7e7e7",
            "text":"#838391"
        },
        "button":{
            "background":"#4b81e8"
        }
    },
    "theme":"classic",
    "content":{
        "message": "Ova web aplikacija koristi kolačiće (cookies) kako bi pružila najbolje korisničko iskustvo",
        "dismiss":"U redu",
        "link":"Saznaj više"
    }
});

<?php $this->js_end(DOCUMENT_BODY_END);
