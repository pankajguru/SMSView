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

<table>
<?php foreach ($excel as $row):?>

    <?php if ($row['rubriek']!='') {?>
    <tr>
        <th colspan='12'>
    <H1><?php echo $row['rubriek'] ?></H1>    
        </th>
    </tr>
    <tr>
        <th>
            id
        </th>
        <th>
            vraag
        </th>
        <th colspan="9">
            antwoord
        </th>
    </tr>
    <?php } ?>
    <tr style="background-color: #F0F0F0">
        <td>
            <?php echo $row['question_id'].' ';?>
        </td>
        <td>
            <?php echo $row['question'].' ';?>
        </td>
            <?php for($i=0;$i<9;$i++) { ?>
        <td>
            <?php echo $row['answer'][$i].' ';?>
        </td>
            <?php }?>
    </tr>
    <?php if (isset($row['duplicates'])) foreach ($row['duplicates'] as $duplicate):?>
    <tr>
        <td>
            <?php echo $duplicate['question_id'].' ';?>
        </td>
        <td>
            <?php echo $duplicate['question'].' ';?>
        </td>
            <?php for($i=0;$i<9;$i++) { ?>
        <td>
            <?php echo $duplicate['answer'][$i].' ';?>
        </td>
            <?php }?>
    </tr>
        
    <?php endforeach;?>

<?php endforeach;?>
</table>

</body>
</html>
