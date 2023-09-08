Zdravo <?php if (strlen($username) > 0) {
    ?> <?php echo $username; ?><?php 
} ?>,

Zaboravili ste lozinku? Ne brinite ništa.
Da biste kreirali novu lozinku, samo kliknite na sledeći link:

<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>

Dobili ste ovaj e-mail zato što je poslat zahtev za izmenu lozinke na <?php echo site_url(''); ?>. Ako ste dobili ovaj e-mail greškom, molimo Vas DA NE KLIKNETE na link za konfirmaciju i da jednostavno obrišete ovu poruku. Posle nekoliko sati, ovaj zahtev će prestati da važi.

Hvala,
Razvojni tim <?php echo $site_name; ?>-a