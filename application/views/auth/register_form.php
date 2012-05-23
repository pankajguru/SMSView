<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Scholen met Succes Vragenplanner</title>
                <link type="text/css" href="http://www.scholenmetsucces.nl/templates/scholenmetsucces/css/reset.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/webapp.css" rel="stylesheet" />
    </head>
    <body>
        <div data-role="page" id="login">
            <h1>Welkom bij de vragenplanner<span class="copyright">&copy;</span></h1>
        

<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
$schoolname = array(
    'name'  => 'schoolname',
    'id'    => 'schoolname',
    'value' => set_value('schoolname'),
    'size'  => 30,
);
$brin = array(
    'name'  => 'brin',
    'id'    => 'brin',
    'value' => set_value('brin'),
    'size'  => 30,
);

?>
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<?php if ($use_username) { ?>
	<tr>
		<td><?php echo form_label('Username', $username['id']); ?></td>
		<td><?php echo form_input($username); ?></td>
		<td style="color: red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo form_label('Email adres', $email['id']); ?></td>
		<td><?php echo form_input($email); ?></td>
		<td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Wachtwoord', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;"><?php echo form_error($password['name']); ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Bevestig wachtwoord', $confirm_password['id']); ?></td>
		<td><?php echo form_password($confirm_password); ?></td>
		<td style="color: red;"><?php echo form_error($confirm_password['name']); ?></td>
	</tr>
    <tr>
        <td><?php echo form_label('Schoolnaam', $schoolname['id']); ?></td>
        <td><?php echo form_input($schoolname); ?></td>
        <td style="color: red;"><?php echo form_error($schoolname['name']); ?><?php echo isset($errors[$schoolname['name']])?$errors[$schoolname['name']]:''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('BRIN nummer', $brin['id']); ?></td>
        <td><?php echo form_input($brin); ?></td>
        <td style="color: red;"><?php echo form_error($brin['name']); ?><?php echo isset($errors[$brin['name']])?$errors[$brin['name']]:''; ?></td>
    </tr>

	<?php if ($captcha_registration) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p>Vul de onderstaande bevestigingscode exact in:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Bevestigingscode', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>
</table>
<?php echo form_submit('register', 'Registreer'); ?>
<?php echo form_close(); ?>
        </div>
    </body>
</html>