<?php

class satisfactionSummary {

    function render($data, $ref, $config) {

        $temp = 'temp/';
        $datastring = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
        $datastring = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        //add graphic to docx
        $summary_docx = new CreateDocx();
//        $satisfactionPriorityScatter_docx->importStyles('./templates/otp-muis.docx', 'merge', array('Normal','ListParagraphPHPDOCX'));
        $summary_docx->importStyles($config->item('template_dir').'/muis-style.docx', 'merge', array('Normal', 'List Paragraph PHPDOCX'));
        $basetype = $data['basetype'];

        $target = '';
        if ( ($basetype == 1) || ($basetype == 4) ){
            $target = 'ouders'; //afhankelijk van basetype
        } elseif ($basetype == 2) {
            $target = 'leerlingen'; //afhankelijk van basetype
        } elseif ($basetype == 3) {
            $target = 'medewerkers'; //afhankelijk van basetype
        }


        $paramsList = array(
            'val' => 0,
            'sz' => 10,
            'font' => 'Century Gothic',
        );

        $satisfactionSummary = array();
        foreach($all_questions as $question_number=>$question){
            $satisfactionArray = array(3,14,16,17,33,38,39,41,43,9823,46,47,9944,9948,9963,9973,9986,  10423,10421,10422,10406,10407,10408,10409,10416,10410,10412,10411
										,10936, 10937, 10938, 10929, 10948, 10949, 10950, 10951, 10955 
										); 
            if (in_array($question->{'id'}, $satisfactionArray)){
                if ( ($question->question_type[0][1] == 'TEVREDEN') || ($question->question_type[0][1] == 'PTP_TEVREDEN')
					  || ($question->question_type[0][1] == 'NOOIT_SOMS_VAAK') || ($question->question_type[0][1] == 'NIETZO_GAATWEL_JA')
					  || ($question->question_type[0][1] == 'BNSV_REVERSED') || ($question->question_type[0][1] == 'NZGWJ_REVERSED')
					  || ($question->question_type[0][1] == 'NOOIT_SOMS_VAAK_NOSAT') || ($question->question_type[0][1] == 'NIETZO_GAATWEL_JA_NOSAT')
				){
                    $satisfactionSummary[] = 
                        $question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'peiling'} . "% van de $target is tevreden over " . filter_text($question->{'short_description'}).'.';
                }
                if ($question->question_type[0][1] == 'JA_NEE'){
                    $satisfactionSummary[] = 
                        $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'} . "% van de $target  " . filter_text($question->{'short_description'}).'.';
                }
                if ($question->question_type[0][1] == 'NEE_SOMS_VAAK'){
                    $satisfactionSummary[] = 
                        $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'} . "% van de $target  " . filter_text($question->{'short_description'}).'.';
                }
            }
        }
        foreach($all_questions as $question_number=>$question){
            if ($question->{'id'} == 65){
                $satisfactionSummary[] = 
                    $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'}."% van de $target ziet hun kind met plezier naar school gaan (landelijk is dit".$question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'}.' %)';
            }
            if ($question->{'id'} == 126){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                    "Van de leerlingen vindt $satisfied% dat je op school veel leert (landelijk is dit 76%).";
            }
            if ($question->{'id'} == 127){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                        "Volgens $satisfied% zijn hun ouders tevreden over de school; 3% denkt dat hun ouders niet tevreden zijn (landelijk zijn deze percentages 79% en 4%).";
            }
            if ($question->{'id'} == 128){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $unsatisfied = $question->{'statistics'}->{'percentage'}->{2}->{'lt'}->{'peiling'};
                $unsatisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'lt'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                         "Van de leerlingen denkt $unsatisfied% soms of vaak ‘zat ik maar op een andere school’; $satisfied% denkt dit bijna nooit. De landelijke percentages zijn respectievelijk $unsatisfied_all_schools% en $satisfied_all_schools%.";
            }
            if ($question->{'id'} == 3667){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                         "$satisfied% van de leerlingen voelt zich veilig in de school, landelijk is dit $satisfied_all_schools%.";
            }
            if ($question->{'id'} == 140){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                          "47% van de ouders is actief als hulpouder of commissielid. Landelijk is dit percentage 52%.";
            }
            if ($question->{'id'} == 3695){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                          "Van de ouders helpt $satisfied% hun kind met huiswerk als zij dit willen. Landelijk is dit $satisfied_all_schools%.";
            }
            if ($question->{'id'} == 3698){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                          "Een gesprek thuis over de gebeurtenissen op school komt bij $satisfied% soms of vaak voor; landelijk is dit ook bij $satisfied_all_schools% van de leerlingen het geval.";
            }
            if ($question->{'id'} == 139){
                $satisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $satisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $unsatisfied = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'};
                $unsatisfied_all_schools = $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'};
                $satisfactionSummary[] = 
                          "Van de leerlingen eet $satisfied% vaak goed voordat ze naar school gaan; $unsatisfied% van de kinderen eet ’s morgens bijna nooit. Landelijk zijn deze gemiddelden respectievelijk $satisfied_all_schools% en $unsatisfied_all_schools%.";
            }
        }
        $summary_docx -> addList($satisfactionSummary, $paramsList);
		
		$filename = $temp . 'satisfactionSummary'.randchars(12);
        $summary_docx -> createDocx($filename);
        unset($summary_docx);
        return $filename.'.docx';

    }


    function _error_dump($object){
        ob_start();
        //var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

