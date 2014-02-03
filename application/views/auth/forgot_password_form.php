<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Scholen met Succes Vragenplanner</title>
                <link type="text/css" href="http://www.scholenmetsucces.nl/templates/scholenmetsucces/css/reset.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url();?>webapp/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url();?>webapp/css/webapp.css" rel="stylesheet" />
    </head>
    <body>
                  <div data-role="header" id="header">
        <div id="logo"><a href="http://www.vragenplanner.nl"><img alt="Scholen met Succes" src="/templates/scholenmetsucces-frontpage/images/logo.png"></a><span style="font-size:80px; color:#fff; float:right; margin-top:17px; text-shadow: 0.1em 0.1em 0.2em grey;">Vragenplanner</span></div>
        </div>
		<div id="container">
        <div data-role="page" id="login">
            <h1 style="margin-bottom:30px;">Wachtwoord vergeten?</h1>
            <p style="margin-bottom:20px;">Voer hieronder het e-mail adres in waarmee u zich heeft geregistreerd en klik op wachtwoord aanvragen. U ontvangt per mail uw nieuwe wachtwoord.</p>

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<tr style="height:40px;">
		<td style="width:60px;"><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
</table>
<?php echo form_submit('reset', 'Wachtwoord aanvragen'); ?>
<?php echo form_close(); ?>
</div>
        </div>
    </body>
</html>