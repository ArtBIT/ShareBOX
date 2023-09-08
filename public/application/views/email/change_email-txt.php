Zdravo <?php if (strlen($username) > 0) {
    ?> <?php echo $username; ?><?php 
} ?>,

Promenili ste e-mail adresu na sajtu <?php echo $site_name; ?>.
Molimo Vas da kliknete na sledeći link da potvrdite Vašu novu e-mail adresu:

<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>


Vaša e-mail adresa: <?php echo $new_email; ?>

Dobili ste ovaj e-mail zato što je poslat zahtev za izmenu e-mail adrese na <?php echo site_url(''); ?>. Ako ste dobili ovaj e-mail greškom, molimo Vas DA NE KLIKNETE na link za konfirmaciju i da jednostavno obrišete ovu poruku. Posle nekoliko sati, ovaj zahtev će prestati da važi.

Hvala,
Razvojni tim <?php echo $site_name; ?>-a