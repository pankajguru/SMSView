<?php

class satisfactionTopBestuur
{

    function render( &$data, $ref, $top = TRUE, $reference = 'bestuur')
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        if (!isset($data['all.questions.bestuur'])){
            return 0;
        }
        $datastring     = $data['all.questions.bestuur'];
//        $datastring     = $data['all.questions.bestuur'];
        $bestuurname     = $data['bestuur.name'];
        if (!isset($data['question.type.satisfaction'])){
            return 0;
        }
        if ( ($reference != 'alle_scholen') && ($reference != 'vorige_peiling') ){
            $reference      = str_replace('_', ' ',$reference);
        }
        if ($reference == 'vorige_peiling'){
//            $reference = $bestuurname.' '.$data['previous.survey.year.first.notype.bestuur'];
        }
        $tevreden       = str_replace('\\\'', '',$data['question.type.satisfaction']);
        $belangrijk     = str_replace('\\\'', '',$data['question.type.importance']);
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
		$tevreden_array = preg_split('/,/', $tevreden);
		$belangrijk_array = preg_split('/,/', $belangrijk);
		$division 		= $data['question.type.satisfaction.scalefactor'] - 1;

        $paramsTextTitles = array(
            'b' => 'double',
            'font' => 'Century Gothic',
            'cell_color' => '00A4E4',
            'sz' => 9.5
         );        
        
        $paramsTextTableHeader = array(
            'color' => 'FFFFFF',
            'font' => 'Century Gothic',
            'b' => 'double',
            'cell_color' => '00A4E4',         
            'sz' => 9.5
        );        

        $paramsTextTableTitle = array(
            'color' => '00A4E4',
            'font' => 'Century Gothic',
            'b' => 'double',
            'cell_color' => 'DAEEF3',//lightbleu
            'sz' => 9.5
        );        
        
        $paramsTextTable = array(
            'color' => '00A4E4',
            'font' => 'Century Gothic',
            'border_color'=>'00A4E4',
            'sz' => 9.5,
            'jc' => 'center'
        );        
        
        $paramsTextTableTitleReference = array(
            'color' => 'F78E1E',
            'font' => 'Century Gothic',
            'b' => 'double',            
            'cell_color' => 'FDE9D9',//lightorange
            'sz' => 9.5
        );        
        
        $paramsTextTableReference = array(
            'color' => 'F78E1E',
            'sz' => 9.5,
            'font' => 'Century Gothic',
            'jc' => 'center'
        );        
        
        $paramsTextTableHeaderReference = array(
            'color' => 'FFFFFF',
            'b' => 'double',
            'font' => 'Century Gothic',
            'cell_color' => 'F78E1E',         
            'sz' => 9.5
        );        

        $widthTableCols = array(
            3100,
            700,
            100,
            700
        );

        $paramsTable = array(
            'border' => 'single',
            'border_sz' => 10,
            'border_color'=>'00A4E4',
            'size_col' => $widthTableCols,
            'textWrap'=>0,
        );

        $paramsTableEmpty = array(
            'border' => 'none',
            'size_col' => 100,
        );

        $paramsTableReference = array(
            'border' => 'single',
            'border_sz' => 10,
            'border_color'=>'F78E1E',
            'size_col' => 700,
            'textWrap'=>0,
        );



        $satisfactionTop_docx = new CreateDocx();
        $satisfactionTop_docx->addBreak('line');
        
        $satisfactionTop_table = array();

        //get right stuff from all_questions
        $satisfaction_array = array();
        foreach($all_questions as $question_id => $question){
            if (!in_array($question->{'question_type'}[0][1], $tevreden_array)){continue;};
            $forbidden_in_top = array(101,102,7692,137,138,139,140,141,142, 7692, 7691);
            if (in_array($question->id,$forbidden_in_top)){continue;}; 
            
            if ($top){
                if (isset($question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'alle_scholen'})){
                    $alle_scholen_perc = round(100*$question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'alle_scholen'});
                } else {
                    $alle_scholen_perc = '-';
                }
                $satisfaction_array[] = array(
                    'vraag' => filter_text(isset($question->{'neutral_description'})?$question->{'neutral_description'}:$question->{'short_description'}),
                    'bestuur' => round(100*$question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{$reference}),
                    'alle_scholen' => $alle_scholen_perc
                );
            } else {
                if (isset($question->{'statistics'}->{'percentage'}->{$division}->{'lt'}->{'alle_scholen'})){
                    $alle_scholen_perc = round(100*$question->{'statistics'}->{'percentage'}->{$division}->{'lt'}->{'alle_scholen'});
                } else {
                    $alle_scholen_perc = '-';
                }
                $satisfaction_array[] = array(
                    'vraag' => filter_text(isset($question->{'neutral_description'})?$question->{'neutral_description'}:$question->{'short_description'}),
                    'bestuur' => round(100*$question->{'statistics'}->{'percentage'}->{$division}->{'lt'}->{$reference}),
                    'alle_scholen' => $alle_scholen_perc
                );
            }
        }


        usort($satisfaction_array, "cmp_percentages_bestuur");
        
        if (!$top){
//            $satisfaction_array = array_reverse($satisfaction_array);
        }
        $satisfaction_table = array();
        for ($i=0 ; $i < 10 ; $i++){
            if (!isset($satisfaction_array[$i]['vraag'])){
                continue;
            }
            $count = 0;
            
            $paramsTextTable['text'] = ($i+1).'. '.filter_text($satisfaction_array[$i]['vraag']);
            $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTable));
            $satisfaction_table[$i][$count++] = $text; //title

            $test = $satisfaction_array[$i];
            $paramsTextTable['text'] = $satisfaction_array[$i]['bestuur'].'%';

            $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTable));
            $text->{'border'} = $paramsTable;
            $satisfaction_table[$i][$count++] = $text;

            $paramsTextTableReference['text'] = '';
            $text = $satisfactionTop_docx->addElement('addText', array());
            $text->{'border'} = $paramsTableEmpty;
            $satisfaction_table[$i][$count++] = $text;

            if ($ref['alle_scholen']){
                $paramsTextTableReference['text'] = $satisfaction_array[$i]['alle_scholen'].'%';
            } else {
                $paramsTextTableReference['text'] = '-';
            }
            $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableReference));
            $text->{'border'} = $paramsTableReference;
            $satisfaction_table[$i][$count++] = $text;
        }

        $satisfaction_titles = array();
        if ($top){
            $paramsTextTableHeader['text'] = 'Pluspunten';
        } else {
            $paramsTextTableHeader['text'] = 'Verbeterpunten';
        }
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableHeader));
        $satisfaction_titles[0][] = $text;
        
        if ($reference == 'bestuur'){
            $paramsTextTableTitle['text'] = $bestuurname;
        } else {
            $paramsTextTableTitle['text'] = $reference;
        }
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableTitle));
        $text->{'border'} = $paramsTable;
        $satisfaction_titles[0][] = $text;

        $paramsTextTableReference['text'] = '';
        $text = $satisfactionTop_docx->addElement('addText', array());
        $text->{'border'} = $paramsTableEmpty;
        $satisfaction_titles[0][] = $text;
        $paramsTextTableTitleReference['text'] = 'Alle scholen';
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableTitleReference));
        $text->{'border'} = $paramsTableReference;
        $satisfaction_titles[0][] = $text;
        
        $paramsText = array(
            'b' => 'single',
            'font' => 'Arial'
        );        
        
        $widthTableColsHeader = array(
            4600,
        );

        $paramsTableHeader = array(
            'border' => 'single',
            'border_sz' => 10,
            'border_color'=>'00A4E4',
            'size_col' => $widthTableColsHeader,
            'textWrap'=>0
            
        );

        $satisfaction_header = array();
        $paramsTextTableHeader['text'] = '';
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableHeader));
        $text->{'border'} = $paramsTable;
        $satisfaction_header[0][] = $text;
        $paramsTextTableHeader['text'] = '';
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableHeader));
        $text->{'border'} = $paramsTable;
        $satisfaction_header[0][] = $text;
        $paramsTextTableHeader['text'] = '';
        $text = $satisfactionTop_docx->addElement('addText', array());
        $text->{'border'} = $paramsTableEmpty;
        $satisfaction_header[0][] = $text;
        
        $paramsTextTableHeaderReference['text'] = 'Referentie';
        $text = $satisfactionTop_docx->addElement('addText', array($paramsTextTableHeaderReference));
        $text->{'border'} = $paramsTableReference;
        $satisfaction_header[0][] = $text;
        
        $size_col = array(
            5000,
            2000,
            100,
            2000
        );

        
        
        $table1 = $satisfactionTop_docx->addTable($satisfaction_header);
        $table2 = $satisfactionTop_docx->addTable($satisfaction_titles);
        $table3 = $satisfactionTop_docx->addTable($satisfaction_table, array('size_col' => $size_col));
        
        $satisfactionTop_docx->addBreak('line');

        $filename = ($top) ? 'satisfactionTopGoodBestuur':'satisfactionTopBadBestuur';
		$filename = $filename.randchars(12);
        $satisfactionTop_docx->createDocx($temp.$filename);
        unset($satisfactionTop_docx);
        return $temp.$filename.'.docx';
        
    }
        

}

        function cmp_percentages_bestuur($a, $b)
        {
            if ($a['bestuur'] == $b['bestuur']) {
                return 0;
            }
            return ($a['bestuur'] < $b['bestuur']) ? 1 : -1;
        }

