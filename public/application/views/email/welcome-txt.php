Have fun!
The <?php echo $site_name; ?> Team
Dobrošli na <?php echo $site_name; ?>!
Hvala Vam što ste postali član <?php echo $site_name; ?>-a.
Da biste pristupili <?php echo $site_name; ?> aplikaciji, kliknite na sledeći link:

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) {
    ?>Vaše korisničko ime je <?php echo $username;
} ?>


Srdačan pozdrav,
Razvojni tim <?php echo $site_name; ?>-a