<html>
<head>
<title>Update tools</title>
<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
</head>
<body>
<h1>Update tools</h1>

<h3>Test</h3>

<ul>
<?php foreach ($peilingen as $peiling):?>

<li><?php echo $peiling->id;?></li>

<?php endforeach;?>
</ul>


</body>
</html>
