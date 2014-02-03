<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Stel een nieuw wachtwoord in voor de Vragenplanner.</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Nieuw wachtwoord instellen</h2>
Klik op onderstaande link om een nieuw wachtwoord aan te maken:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">Nieuw wachtwoord</a></b></big><br />
<br />
Kunt u niet op de link klikken, knip en plak dan onderstaande link in uw browser:<br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
<br />
U heeft deze e-mail ontvangen omdat u een nieuw wachtwoord heeft aangevraagd via de Vragenplanner. Als u uw wachtwoord niet wilt veranderen kunt u deze e-mail negeren.<br />
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