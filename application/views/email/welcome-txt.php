Welkom bij <?php echo $site_name; ?>,

Bedankt voor uw aanmelding bij <?php echo $site_name; ?>. We hebben onderstaande gegevens opgeslagen, bewaar deze goed.
Begin met de <?php echo $site_name; ?> via onderstaande link link:<br />

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Uw e-mail adres: <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

Succes!
<?php echo $site_name; ?> 