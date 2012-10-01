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
</head>
<body>

<div id="container">
	<h1>SMSView</h1>

	<div id="body">
		<p>Hieronder kunnen de waardes aangegeven worden om een grafiek voor een pannelgesprek te maken. Op elke regel moeten 3 velden staan, gescheiden door een komma. De eerste regel geeft de legenda aan. Zorg ervoor dat er op elke regel een gelijk aantal velden staat:</p>
		<ol>
			<li>de beschrijving voor de linker aanduiding</li>
			<li>de waarde voor de rechter aanduiding</li>
			<li>de waardes tussen 0 en 5 en een punt als decimaal seperator (bijvoorbeeld: 3.54, 4,87)</li>
		</ol>
		<p>Er is al een voorbeeld ingesteld. Als de waardes kloppen, klik dan op 'maak grafiek' </p>
		<?php echo form_open('web/graphics/panelgesprekken'); ?>
		<?php echo form_textarea(array('value' => $graphic_data, 'name' => 'input_text')); ?>
		<?php echo '<br>Teken grootte: '.form_input(array(
              'name'        => $fontsize,
              'value'       => '24',
              'maxlength'   => '100',
              'size'        => '50',
            ));
		 ?>
		<?php echo '<br>Lijn dikte (1 of 2 of 3 of ....): '.form_input(array(
              'name'        => $linesize,
              'value'       => '2',
              'maxlength'   => '100',
              'size'        => '50',
            ));
		 ?>
		<?php echo '<br>Ruimte tussen stippels (1 voor hele lijn): '.form_input(array(
              'name'        => $lineticks,
              'value'       => '1',
              'maxlength'   => '100',
              'size'        => '50',
            ));
		 ?>
		<?php echo form_submit('create_graphic', 'Maak grafiek'); ?>
		<?php echo form_close(); ?>
		<img width="700px" src="<?php echo $graphic; ?>">
		<?php echo $content; ?>

	</div>

</div>

</body>
</html>