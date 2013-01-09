<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welkom bij Scholen met Succes</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
        <link type="text/css" href="http://www.scholenmetsucces.nl/templates/scholenmetsucces/css/reset.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/webapp.css" rel="stylesheet" />
        <!--[if IE 7]>
        <link type="text/css" href="/webapp/css/ie7.css" rel="stylesheet" />
        <script type="text/javascript" src="/webapp/js/json2.js"></script>
        <![endif]-->
        <link type="text/css" href="/webapp/css/basic.css" rel="stylesheet" />
        <script type="text/javascript" src="/webapp/js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="/webapp/js/config.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery.simplemodal.js"></script>
        <script type="text/javascript" src="/webapp/js/basic.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery.simpletip-1.3.1.min.js"></script>
        <script type="text/javascript" src="/webapp/js/sms_question_admin.js"></script>
</head>
<body>

<div id="container">
	<h1>SMSView</h1>

	<div id="body">
		<?php echo form_open('web/questions/get_questionaire_from_server'); ?>
		<select id="client" name="client">
			<option value=0>Kies de klant</option>
		</select>
		<select id="filename" name="filename"></select>
		<br />
		<?php echo form_submit('get_questionaire', 'Haal vragenlijst op'); ?>
		<?php echo form_close(); ?>

		<?php echo $content; ?>
		<br />
		&nbsp;

	</div>

</div>

</body>
</html>