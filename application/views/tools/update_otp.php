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
    <tr style="background-color: #F0A0A0">
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

<?php $new_antwoord_id = $new_answer_id; ?>
<?php foreach ($excel as $row):?>

    <?php if (($row['question_id'] != '') && ($row['base_type_id'] == 0)){ ?>
        insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id)
            (select <?php echo $row['new_id'];?>, abstract, '<?php echo $row['question_no_number'];?>', short_description, <?php echo $row['vraag_groep_id'];?>, <?php echo $row['vraag_type_id'];?>, exclusive, strict, neutral_description, infant_description_pos, <?php echo $row['question_id'];?>, 1
                   from vraag where vraag.id=<?php echo $row['question_id'];?>);<br>
        <?php 
            $question_id = $row['question_id'];
            print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_antwoord_id, 0, 0, 0, formulier_id, ".$row['new_id'].", value from antwoord where vraag_id=$question_id);<br>";
            $new_antwoord_id++;
            foreach ($row['duplicates'] as $duplicate){
                $question_id = $duplicate['question_id'];
                print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_antwoord_id, 0, 0, 0, formulier_id, ".$row['new_id'].", value from antwoord where vraag_id=$question_id);<br>";
                $new_antwoord_id++;
            } 
        ?>
    <?php }?>

<?php endforeach;?>
    update sequence set sequence_no=<?php echo $new_id;?> where table_name='vraag';<br>
    update sequence set sequence_no=<?php echo $new_antwoord_id;?> where table_name='antwoord';<br>




<br><br><br><br><h1>Temp</h1>
<?php foreach ($excel as $row):?>

    <?php if (($row['question_id'] != '') && ($row['base_type_id'] == 0)){ ?>
        update vraag set vraag_type_id = <?php echo $row['vraag_type_id'];?> where description = '<?php echo $row['question_no_number'];?>';<br>
    <?php }?>

<?php endforeach;?>

SET @id=(SELECT MAX(id)+1 FROM antwoord);<br>


</body>
</html>
