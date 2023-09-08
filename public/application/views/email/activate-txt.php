Dobrodošli na <?php echo $site_name; ?>!

Hvala Vam što ste postali član <?php echo $site_name; ?>-a. Šaljemo Vam pristupne podatke za aplikaciju, i molimo Vas da ih čuvate na sigurnom mestu.
Da biste verifikovali Vašu e-mail adresu, molimo Vas da kliknete na sledeći link:
<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>

Molimo Vas da verifikujete Vaš e-mail u sledećih <?php echo $activation_period; ?> sati, nakon čega će ovaj link prestati da važi i moraćete da se registrujete ponovo.

<?php if (strlen($username) > 0) {
    ?>Vaše korisničko ime: <?php echo $username;
} ?>
Vaša e-mail adresa: <?php echo $email; ?>

Srdačan pozdrav,
Razvojni tim <?php echo $site_name; ?>-a