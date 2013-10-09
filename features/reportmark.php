<?php

class reportmark
{

    function render( $data, $ref)
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        $temp           = 'temp/';
        $datastring     = $data['get_all_question_props'];
        $schoolname     = $data['schoolnaam'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        //add graphic to docx
        $percentage_docx = new CreateDocx();
        
        //create array iso object
        $all_questions_array = array();
        foreach($all_questions as $question_number=>$question){
            $all_questions_array[intval($question_number)] = $question;
        };
        
        ksort($all_questions_array);
        $first = TRUE;
        foreach($all_questions_array as $question_number=>$question){
            $valid_question_types = array('RAPPORTCIJFER');
            if (!in_array($question->{'question_type'}[0][1], $valid_question_types)){
                continue;
            }
            $graphic_data_reportmarks   = array();
            $text= array();
//            foreach ($question->{'statistics'}->{'averages'} as $key => $average){
            foreach ($question->{'refs'} as $reference){
                if (($reference == 'alle_scholen') and (!$ref['alle_scholen']) ){
                    continue;
                }
                if (!isset($question->{'statistics'}->{'averages'}->{$reference})){
                    continue;
                }
                if (count($question->{'statistics'}->{'averages'}->{$reference}) == 0){
                    continue;
                }
                $average = $question->{'statistics'}->{'averages'}->{$reference};
                $average_value = round(($average[0][3]*100))/100;
                
                if (is_null($average_value)){
                    continue;
                }
                if ($reference == '_empty_'){
                    continue;
                }
                if ($reference == 'locatie_'){
                    continue;
                }
                if ($reference == ''){
                    continue;
                }
                if ($reference == 'peiling'){
                    $text[] = "$schoolname ";
                } elseif ($reference == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                    $text[] = "Vorige peiling ";
                } elseif ($reference == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                    $text[] = $ref['onderbouw']." ";
                } elseif ($reference == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                    $text[] = $ref['bovenbouw']." ";
                } elseif ($reference == 'alle_scholen') {
                    if (!$ref['alle_scholen']) continue;
                    $text[] ="Alle Scholen ";
                } elseif (substr($reference,0,8) === 'locatie_') {
                    if (!$ref['locaties']) continue;
                    $text[] = substr($reference,8).' ';
                } elseif (substr($reference,0,15) === 'question_based_') {
                    if (!$ref['question_based']) continue;
                    $text[] = substr($reference,15).' ';
                }
                $graphic_data_reportmarks[] = $average_value;
            }

                        
            $graphic_data_text          = $text;
            
            $percentage_graphic = $this->_draw_graphic($graphic_data_text, $graphic_data_reportmarks, $question_number, $temp, $ref['alle_scholen']);
    
            $paramsImg = array(
                'name' => $percentage_graphic,
                'scaling' => 50,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
//                'border' => 0,
//                'borderDiscontinuous' => 1
            );
            $percentage_docx->addImage($paramsImg);
            $filename = $temp.'reportmark'.randchars(12);
            $percentage_docx->createDocx($filename);
            unset($percentage_docx);
            unlink($percentage_graphic);
            return $filename.'.docx';
        }
        return '';
        
    }
    
    public function process( &$data, &$docx)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);

        //create array iso object
        $all_questions_array = array();
        foreach($all_questions as $question_number=>$question){
            $all_questions_array[intval($question_number)] = $question;
        };
        
        ksort($all_questions_array);
        $first = TRUE;
        //we assume 1st reportmark question is the one!!!
        foreach($all_questions_array as $question_number=>$question){
            $valid_question_types = array('RAPPORTCIJFER');
            if (!in_array($question->{'question_type'}[0][1], $valid_question_types)){
                continue;
            }
            $average_peiling = $question->{'statistics'}->{"averages"}->{'peiling'}[0][3]*100; //should come from data
            $number_of_respondents_peiling = $question->{'statistics'}->{"averages"}->{'peiling'}[0][5]; //should come from data
            $docx -> addTemplateVariable("class:questionProperties:reportmark:average:peiling", sprintf('%.2f',$average_peiling/100));
            $docx -> addTemplateVariable("class:questionProperties:reportmark:number_of_respondents:peiling", strval($number_of_respondents_peiling));

            $average_alle_scholen = $question->{'statistics'}->{"averages"}->{'alle_scholen'}[0][3]*100; //should come from data
            $number_of_respondents_alle_scholen = $question->{'statistics'}->{"averages"}->{'alle_scholen'}[0][5]; //should come from data
            $docx -> addTemplateVariable("class:questionProperties:reportmark:average:alle_scholen", sprintf('%.2f',$average_alle_scholen/100));
            $docx -> addTemplateVariable("class:questionProperties:reportmark:number_of_respondents:alle_scholen", strval($number_of_respondents_alle_scholen));

            $difference = ($average_peiling == $average_alle_scholen) ? "gelijk aan" : ($average_peiling > $average_alle_scholen)
                                ? sprintf("%.2f punt hoger dan", (round($average_peiling) - round($average_alle_scholen))/100)
                                : sprintf("%.2f punt lager dan", (round($average_alle_scholen) - round($average_peiling))/100);
            $docx -> addTemplateVariable("class:questionProperties:reportmark:difference", $difference);
            $docx -> addTemplateVariable("class:questionProperties:reportmark:questionnumber", strval($question_number));
            break;
        }
        return $docx;
        
    }

    private function _draw_graphic($graphic_data_text, $graphic_data_reportmarks, $question_number, $temp, $lastBlue)
    {
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms-reportmark.color", TRUE);
        $MyData->addPoints($graphic_data_text, "Percentages peiling");
//        $MyData->setAxisName(0, "Percentages");
        $MyData->addPoints($graphic_data_reportmarks, "Answers");
        $MyData->setSerieDescription("Answers", "Answers");
        $MyData->setAbscissa("Percentages peiling");
        $MyData->setAxisDisplay(0, AXIS_FORMAT_CUSTOM,"YAxisFormat");
        $MyData->setAxisColor(0, array(
            "R" => 0,
            "G" => 0,
            "B" => 0,
            "Alpha" => 0
        ));
        $MyData->setAxisPosition(0,AXIS_POSITION_RIGHT);
        $MyData->setAxisDisplay(0,AXIS_FORMAT_METRIC, 1); 
        
        /* Create the pChart object */
        $picture_height = count($graphic_data_text) * 60 + 80; //520    360
        $myPicture = new pImage(1400, $picture_height, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 20,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "single"
        ));
        
        /* Draw the chart scale */
        $graphic_height = count($graphic_data_text) * 60 + 40; //360

        $myPicture->drawGradientArea(10,30,1100,$graphic_height,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
        $myPicture->drawGradientArea(10,30,1100,$graphic_height,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));

        $myPicture->setGraphArea(10, 10, 1100, $graphic_height);
        $AxisBoundaries = array(
            0 => array(
                "Min" => 5,
                "Max" => 10
            )
        );
        
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
//            "DrawSubTicks" => FALSE,
            "GridR" => 0,
            "GridG" => 0,
            "GridB" => 0,
            "AxisR" => 80,
            "AxisG" => 80,
            "AxisB" => 80,
            "GridAlpha" => 10,
            "Pos" => SCALE_POS_TOPBOTTOM,
            "Mode" => SCALE_MODE_MANUAL,
            "CycleBackground"=>TRUE,
            "DrawXLines" => FALSE,
            "MinDivHeight"=>80,
//            "YMargin"=>20,
            "XMargin"=>50
//            "Formats"=>array(5,6,7,8,9,10)
        ));
        //
//        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        /* Create the per bar palette */
        $Palette = array(); 
        for($i=0; $i<count($graphic_data_text);$i++){
            $Palette[$i] = array("R"=>247,"G"=>142,"B"=>30,"Alpha"=>100);
        }
        if ($lastBlue){
            $Palette[count($graphic_data_text)-1] = array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100);
        }

        /* Draw the chart */
        $myPicture->drawBarChart(array(
            "DisplayValues" => FALSE,
            "Rounded" => FALSE,
            //"Surrounding" => 255,
            "DisplayR" => 0,
            "DisplayG" => 0,
            "DisplayB" => 0,
            "BorderR" => 255,
            "BorderG" => 255,
            "BorderB" => 255,
            //"DisplayPos"=>LABEL_POS_INSIDE,
            //"DisplayValues"=>TRUE,
            "OverrideColors"=>$Palette  ,
            "Interleave"=> (count($graphic_data_text) == 1) ? 0.5 : 0                     
        ));
//		var_dump($myPicture -> DataSet -> Data["Series"]);
		$imageData = $myPicture -> DataSet -> Data["Series"]['Answers']["ImageData"];
		
        for ($i=0;$i<count($graphic_data_text);$i++){
            $Y = $imageData[$i][3] - 30;
            $myPicture->drawText(20, $Y,$graphic_data_text[$i]."; ".sprintf('%.2f',$graphic_data_reportmarks[$i]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE));
        }
        
		$filename = $temp . "reportmark$question_number".randchars(12).".png";
        $myPicture->render($filename);

        return $filename;
        
    }

        
}

        function YAxisFormat($Value) { return(round($Value)); } 
