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
        <div id="logo"><a href="http://www.scholenmetsucces.nl"><img alt="Scholen met Succes" src="/templates/scholenmetsucces-frontpage/images/logo.png"></a><span style="font-size:80px; color:#fff; float:right; margin-top:17px; text-shadow: 0.1em 0.1em 0.2em grey;">Vragenplanner</span></div>
        </div>
		<div id="container">
        <div data-role="page" id="login">
            <h1>Welkom bij de Vragenplanner<span class="copyright">&copy;</span></h1>
            <p>&nbsp;</p>
           <p>Hier kunt u inloggen voor de vragenplanner.</p>
            <p>&nbsp;</p>

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<table class="inlogtabel">
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;" class="error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Wachtwoord', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;" class="error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
	</tr>

	<?php if ($show_captcha) {
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
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>

    <tr style="height:35px;">
        <td colspan="3">
            <?php echo form_checkbox($remember); ?>
            <?php echo form_label('Onthoud login', $remember['id']); ?>
        </td>
    </tr>
</table>
            <?php echo form_submit('submit', 'Inloggen'); ?>
<?php echo form_close(); ?>
        </div>
        <div id="loginfooter">
        <p>Nieuw bij de Vragenplanner? Maak dan eerste een account aan:</p>
        <?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Registreer', array('class' => 'registreer')); ?>
            <?php echo anchor('/auth/forgot_password/', 'Wachtwoord vergeten', array('class' => 'wachtwoordvergeten')); ?>
        </div>
		<div id="handleiding"><p style="font-size:16px;"><img src="http://www.scholenmetsucces.nl/bijlagen/afbeeldingen/File_pdf.png" style="vertical-align: middle;"> <a href="http://www.scholenmetsucces.nl/bijlagen/pdf/Handleiding_Vragenplanner.pdf" target="_blank">Handleiding</a></p><p style="font-size:16px; margin-top:20px;">Vragen? Bel: 023 534 11 58</p></div>
       <div id="video"><iframe width="940" height="530" src="http://www.youtube-nocookie.com/embed/vxDahDPWB9M?rel=0" frameborder="0" allowfullscreen></iframe></div>
</div> <!-- Container -->
    </body>
</html>