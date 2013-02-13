Welkom bij <?php echo $site_name; ?>,

Bedankt voor uw aanmelding bij <?php echo $site_name; ?>. We hebben onderstaande gegevens opgeslagen, bewaar deze goed.<br />
Klik op onderstaande link om uw e-mail adres te verifieren:

<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>


U dient uw e-mail adres te verifieren binnen <?php echo $activation_period; ?> uur, anders dient u opnieuw te registreren.
<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Uw e-mail adres: <?php echo $email; ?>
<?php if (isset($password)) { /* ?>

Your password: <?php echo $password; ?>
<?php */ } ?>



Succes!
<?php echo $site_name; ?>