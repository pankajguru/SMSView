<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Gewijzigd wachtwoord voor de <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Nieuw wachtwoord voor de <?php echo $site_name; ?></h2>
U heeft uw wachtwoord succesvol gewijzigd.<br />
<br />
<?php if (strlen($username) > 0) { ?>Uw gebruikersnaam: <?php echo $username; ?><br /><?php } ?>
Uw e-mail adres: <?php echo $email; ?><br />
Uw nieuwe wachtwoord: <?php echo $new_password; ?><br /> ?>
<br />
<br />
Met vriendelijke groet,<br />
Scholen met Succes
</td>
</tr>
</table>
</div>
</body>
</html>