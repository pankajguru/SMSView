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
    <table>
        <thead>
            <?php foreach($fields as $field_name => $field_display): ?>
            <th <?php if ($sort_by == $field_name) echo "class=\"sort_$sort_order\"" ?>>
                <?php echo anchor("main/index/$field_name/" .
                    (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc') ,
                    $field_display); ?>
            </th>
            <?php endforeach; ?>
        </thead>
        
        <tbody>
            <?php $alternator=0; foreach($reports as $report): ?>
            <tr class="<?php  echo ($alternator % 2 == 0) ? 'even': 'odd'; ?>" onmouseover="this.className='highlight'" onmouseout="this.className='<?php  echo ($alternator++ % 2 == 0) ? 'even': 'odd'; ?>'" >
                <?php foreach($fields as $field_name => $field_display): ?>
                <td>
                    <?php echo $report->$field_name; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>            
        </tbody>
        
    </table>
    
    <?php if (strlen($pagination)): ?>
    <div class="pagination">
        Pages: <?php echo $pagination; ?>
    </div>
    <?php endif; ?>
    </div>
</div>
        <p class="footer"><?php echo $num_results; ?> rapporten gevonden </p>

</body>
</html>