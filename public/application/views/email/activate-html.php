<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Dobrodošli na <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Dobrodošli na <?php echo $site_name; ?>!</h2>
Hvala Vam što ste postali član <?php echo $site_name; ?>-a. Šaljemo Vam pristupne podatke za aplikaciju, i molimo Vas da ih čuvate na sigurnom mestu.<br />
Da biste verifikovali Vašu e-mail adresu, molimo Vas da kliknete na sledeći link:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Kliknite ovde da verifikujete Vaš e-mail...</a></b></big><br />
<br />
Ukoliko link ne funkcioniše, ručno unesite sledeći link u adresu Vašeg omiljenog internet pretraživača:<br />
<nobr><a href="<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
Molimo Vas da verifikujete Vaš e-mail u sledećih <?php echo $activation_period; ?> sati, nakon čega će ovaj link prestati da važi i moraćete da se registrujete ponovo.<br />
<br />
<br />
<?php if (strlen($username) > 0) {
    ?>Vaše korisničko ime: <?php echo $username; ?><br /><?php 
} ?>
Vaša e-mail adresa: <?php echo $email; ?><br />
<?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */
} ?>
<br />
<br />
Srdačan pozdrav,<br />
Razvojni tim <?php echo $site_name; ?>-a
</td>
</tr>
</table>
</div>
</body>
</html>