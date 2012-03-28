<html>
<head>
<title>Update tools</title>
<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
</head>
<body>
<h1>Update tools</h1>

<h3>OTP</h3>

<table>
<?php foreach ($excel as $row):?>

    <?php if ($row['rubriek']!='') {?>
    <tr>
        <th colspan='12'>
    <H1><?php echo $row['vraag_groep_id'].' '.$row['rubriek'] ?></H1>    
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


Set copy of question, basetype, copy answers to peiling for otp:<br>


<?php foreach ($excel as $row):?>

    <?php if (($row['question_id'] != '') && ($row['base_type_id'] == 0)){ ?>
        insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id)
            (select <?php echo $row['new_id'];?>, abstract, '<?php echo $row['question_no_number'];?>', short_description, <?php echo $row['vraag_groep_id'];?>, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, <?php echo $row['question_id'];?>, 1
                from vraag where vraag.id=<?php echo $row['question_id'];?>);<br>
    <?php }?>

<?php endforeach;?>
    update sequence set sequence_no=<?php echo $new_id;?> where table_name='vraag';<br>


SET @id=(SELECT MAX(id)+1 FROM antwoord);<br>


</body>
</html>
