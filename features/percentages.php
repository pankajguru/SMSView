<?php

class percentages
{

    function render( &$data, $category='', $target_question='')
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        $temp           = 'temp/';
        $datastring     = $data['get_all_question_props'];
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
        $question_count = 0;
        foreach($all_questions_array as $question_number=>$question){
            if (($category != '') and ($category != $question->{'group_name'})){
                continue;
            } 
            if (($target_question != '') and ($target_question != $question_number)){
                continue;
            } 
            $answer_count_peiling = 0;
            $answer_count_alle_scholen = 0;
            $text = array();
            
            $paramsTitle = array(
                'val' => 2,
                'sz' => 10,
                'font' => 'Century Gothic'
            );
            $invalid_question_types = array();
            if (in_array($question->{'question_type'}[0][1], $invalid_question_types)){
                continue;
            }

            if (($target_question == '') and ($first or ($question->{'group_name'} != $old_group_name))){
                if (!$first){
                    //create pagebreak
                    $percentage_docx->addBreak('page');
                }
                if ($target_question != '') {
                    //create group heading
                    $percentage_docx->addTitle($question->{'group_name'},$paramsTitle);
                    $question_count = 0;
                }
                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            
            $text[] =
                array(
                    'text' => html_entity_decode($question_number.". ".$question->{'description'},null, 'UTF-8'),
                    'b' => 'single',
                    'sz' => 10,
                    'font' => 'Century Gothic'
            );
            
            $percentage_docx->addText($text);

            $peiling_distribution      = $question->{'statistics'}->{'distribution'}->{'peiling'};
            $alle_scholen_distribution = $question->{'statistics'}->{'distribution'}->{'alle_scholen'};
            $answer_peiling            = array();
            foreach ($peiling_distribution as $answer) {
                $answer_count_peiling += $answer[2];
                $answer_peiling[$answer[0]] = $answer;
            }
            foreach ($alle_scholen_distribution as $answer) {
                $answer_count_alle_scholen += $answer[2];
            }
            $graphic_data_peiling      = array();
            $graphic_data_alle_scholen = array();
            $graphic_answer            = array();
            $graphic_answered          = array();
            $graphic_percentage        = array();
            $graphic_percentage_total  = array();
            foreach ($alle_scholen_distribution as $answer) {
                //get percentage for this school
                $answered = (isset($answer_peiling[$answer[0]])) ? $answer_peiling[$answer[0]][2] : 0;
                $percentage_peiling = $answered / $answer_count_peiling * 100;
                array_push($graphic_data_peiling, $percentage_peiling);
                //get perc from all schools
                $percentage_alle_scholen = $answer[2] / $answer_count_alle_scholen * 100;
                array_push($graphic_data_alle_scholen, $percentage_alle_scholen);
                $answer_text = $answer[1];
                if (strlen($answer_text)>17){
                    $answer_text = substr($answer_text, 0, 14).'...';
                }
                array_push($graphic_answer, htmlspecialchars_decode($answer_text));
                array_push($graphic_answered, $answered);
                array_push($graphic_percentage, round($percentage_peiling));
                array_push($graphic_percentage_total, round($percentage_alle_scholen));
            }
            
            $percentage_graphic = $this->_draw_graphic($question_number, $graphic_data_peiling, $graphic_data_alle_scholen, $graphic_answer, $graphic_answered, $graphic_percentage, $graphic_percentage_total, $temp);
    
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
            $question_count++;
        }
        if ($question_count > 0){
            $percentage_docx->createDocx($temp.'percentage'.$category.$target_question);
            unset($percentage_docx);
            return $temp.'percentage'.$category.$target_question.'.docx';
        } else {
            unset($percentage_docx);
            return null;
        }

        
    }
    
    private function _draw_graphic($question_number, $graphic_data_peiling, $graphic_data_alle_scholen, $graphic_answer, $graphic_answered, $graphic_percentage, $graphic_percentage_total, $temp)
    {
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms.color", TRUE);
        $MyData->addPoints($graphic_data_peiling, "Percentages peiling");
        $MyData->addPoints($graphic_data_alle_scholen, "Percentages alle scholen");
//        $MyData->setAxisName(0, "Percentages");
        $MyData->addPoints($graphic_answer, "Answers");
        $MyData->setSerieDescription("Answers", "Answers");
        $MyData->setAbscissa("Answers");
        //        $MyData -> setAbscissaName("Browsers");
//        $MyData->setAxisDisplay(0, AXIS_FORMAT_DEFAULT);
        $MyData->setAxisColor(0, array(
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "Alpha" => 0
        ));
        
        /* Create the pChart object */
        $picture_height = 2 * ( (1 + count($graphic_answer)) * 15) + 18;
        $myPicture = new pImage(1200, $picture_height, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 20,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "double"
        ));
        
        /* Draw the chart scale */
        $graphic_height = 2 * ( (1 + count($graphic_answer)) * 15 );
        $myPicture->setGraphArea(600, 18, 960, $graphic_height);
        $AxisBoundaries = array(
            0 => array(
                "Min" => 0,
                "Max" => 100
            )
        );
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "DrawSubTicks" => FALSE,
            "GridR" => 0,
            "GridG" => 0,
            "GridB" => 0,
            "AxisR" => 80,
            "AxisG" => 80,
            "AxisB" => 80,
            "GridAlpha" => 10,
            "Pos" => SCALE_POS_TOPBOTTOM,
            "Mode" => SCALE_MODE_MANUAL
        ));
        //
        
            $Palette[] = array( 0=>array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100),
                                1=>array("R"=>247,"G"=>142,"B"=>30,"Alpha"=>100));

        /* Draw the chart */
        $myPicture->drawBarChart(array(
            "DisplayValues" => FALSE,
            "Rounded" => FALSE,
            "Surrounding" => 5,
            "DisplayR" => 255,
            "DisplayG" => 255,
            "DisplayB" => 255,
//            "OverrideColors"=>$Palette
                                    
        ));
        for ($i=0;$i<count($graphic_answer);$i++){
            $myPicture->drawText(320, 40 + ($i)*44,$graphic_answer[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(400, 40 + ($i)*44,$graphic_answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(490, 40 + ($i)*44,$graphic_percentage[$i]."%",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(580, 40 + ($i)*44,"(".$graphic_percentage_total[$i]."%)",array("R"=>80,"G"=>80,"B"=>80,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
        }
        
        $myPicture->render($temp . "percentages$question_number.png");
        // var_dump($all_questions);
        return $temp . "percentages$question_number.png";
        
    }
}
