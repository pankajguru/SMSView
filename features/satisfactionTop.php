<?php

class satisfactionTop
{

    function render( &$data, $ref, $top = TRUE)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['get_all_question_props'];
        $schoolname     = $data['schoolnaam'];
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
        
        $satisfactionTop_table = array();

        //get right stuff from all_questions
        $satisfaction_array = array();
        foreach($all_questions as $question){
            if (!in_array($question->{'question_type'}[0][1], $tevreden_array)){continue;};
            if ($top){
                $satisfaction_array[] = array(
                    'vraag' => filter_text($question->{'short_description'}),
                    'peiling' => $question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'peiling'},
                    'alle_scholen' => $question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'alle_scholen'}
                );
            } else {
                $satisfaction_array[] = array(
                    'vraag' => filter_text($question->{'short_description'}),
                    'peiling' => $question->{'statistics'}->{'percentage'}->{$division}->{'lt'}->{'peiling'},
                    'alle_scholen' => $question->{'statistics'}->{'percentage'}->{$division}->{'lt'}->{'alle_scholen'}
                );
            }
        }


        usort($satisfaction_array, "cmp_percentages");
        
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

            $paramsTextTable['text'] = $satisfaction_array[$i]['peiling'].'%';
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
        
        $paramsTextTableTitle['text'] = $schoolname;
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
        $paramsTextTableHeader['text'] = 'Onze school';
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
        
        $filename = ($top) ? 'satisfactionTopGood':'satisfactionTopBad';
        $satisfactionTop_docx->createDocx($temp.$filename);
        unset($satisfactionTop_docx);
        return $temp.$filename.'.docx';
        
    }
        

}
        function cmp_percentages($a, $b)
        {
            if ($a['peiling'] == $b['peiling']) {
                return 0;
            }
            return ($a['peiling'] < $b['peiling']) ? 1 : -1;
        }
