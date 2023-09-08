<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Kreirajte novu lozinku za <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Kreirajte novu lozinku</h2>
Zaboravili ste lozinku? Ne brinite ništa.<br />
Da biste kreirali novu lozinku, samo kliknite na sledeći link:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">Kreiraj novu lozinku</a></b></big><br />
<br />
Ukoliko link ne funkcioniše, ručno unesite sledeći link u adresu Vašeg omiljenog internet pretraživača:<br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
<br />
Dobili ste ovaj e-mail zato što je poslat zahtev za izmenu lozinke na <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a>. Ako ste dobili ovaj e-mail greškom, molimo Vas DA NE KLIKNETE na link za konfirmaciju i da jednostavno obrišete ovu poruku. Posle nekoliko sati, ovaj zahtev će prestati da važi.<br />
<br />
<br />
Hvala,<br />
Razvojni tim <?php echo $site_name; ?>-a
</td>
</tr>
</table>
</div>
</body>
</html>