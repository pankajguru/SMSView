<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Welkom bij de Vragenplanner.</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welkom bij de Vragenplanner.</h2>
Bedankt voor uw aanmelding bij de Vragenplanner.<br />
Klik op onderstaande link om in te loggen op de Vragenplanner.<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;">Inloggen Vragenplanner</a></b></big><br />
<br />
Als de link niet werkt, kunt u de onderstaande link knippen en plakken in uw browser:<br />
<nobr>http://www.vragenplanner.nl</nobr><br />
<br />
<br />
<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
U heeft zich geregistreerd met het e-mail adres: <?php echo $email; ?><br />
<?php /* Your password: <?php echo $password; ?><br /> */ ?>
<br />
<br />
Vriendelijke groet,<br />
Scholen met Succes
</td>
</tr>
</table>
</div>
</body>
</html>