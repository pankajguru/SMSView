<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Welkom bij <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welkom bij <?php echo $site_name; ?>!</h2>
Bedankt voor uw aanmelding bij <?php echo $site_name; ?>. We hebben onderstaande gegevens opgeslagen, bewaar deze goed.<br />
Begin met de <?php echo $site_name; ?> via onderstaande link link:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;">Go to <?php echo $site_name; ?> now!</a></b></big><br />
<br />
Als de link niet werkt, kunt u de onderstaande url copieren en plakken in uw browser:<br />
<nobr><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;"><?php echo site_url('/auth/login/'); ?></a></nobr><br />
<br />
<br />
<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
Uw e-mail adres: <?php echo $email; ?><br />
<?php /* Your password: <?php echo $password; ?><br /> */ ?>
<br />
<br />
Succes!<br />
<?php echo $site_name; ?>
</td>
</tr>
</table>
</div>
</body>
</html>