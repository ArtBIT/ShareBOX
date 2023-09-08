<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Dobrošli na <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Dobrošli na <?php echo $site_name; ?>!</h2>
Hvala Vam što ste postali član <?php echo $site_name; ?>-a.<br />
Da biste pristupili <?php echo $site_name; ?> aplikaciji, kliknite na sledeći link:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;">Otvori <?php echo $site_name; ?> aplikaciju</a></b></big><br />
<br />
Ukoliko link ne funkcioniše, ručno unesite sledeći link u adresu Vašeg omiljenog internet pretraživača:<br />
<nobr><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;"><?php echo site_url('/auth/login/'); ?></a></nobr><br />
<br />
<br />
<?php if (strlen($username) > 0) {
    ?>Vaše korisničko ime je <strong><?php echo $username; ?></strong><br /><?php 
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