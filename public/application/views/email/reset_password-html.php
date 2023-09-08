<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Vaša lozinka za <?php echo $site_name; ?> je promenjena</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Vaša lozinka za <?php echo $site_name; ?> je promenjena</h2>
<?php if (strlen($username) > 0) {
    ?>Zdravo <?php echo $username; ?>,<br /><?php 
} ?>
Vaša lozinka za <?php echo $site_name; ?> je nedavno promenjena.<br />
Ukoliko niste inicirali promenu lozinke, pod hitno se obratite administratoru sajta.<br />
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