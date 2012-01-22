<?php

class percentages
{

    function render( $data)
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
        foreach($all_questions_array as $question_number=>$question){
            $answer_count_peiling = 0;
            $answer_count_alle_scholen = 0;
            $text = array();
            
            $paramsTitle = array(
                'val' => 2,
            );
            $invalid_question_types = array('KIND_GROEP','JONGEN_MEIJSE');
            if (in_array($question->{'question_type'}[0][1], $invalid_question_types)){
                continue;
            }

            if ($first or ($question->{'group_name'} != $old_group_name)){
                if (!$first){
                    //create pagebreak
                    $percentage_docx->addBreak('page');
                }
                //create group heading
                $percentage_docx->addTitle($question->{'group_name'},$paramsTitle);
                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            
            $text[] =
                array(
                    'text' => $question_number.". ".$question->{'description'},
                    'b' => 'single',
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
                array_push($graphic_answer, $answer[1]);
                array_push($graphic_answered, $answer_count_peiling);
                array_push($graphic_percentage, round($percentage_peiling));
                array_push($graphic_percentage_total, round($percentage_alle_scholen));
            }
            
            $percentage_graphic = $this->_draw_graphic($question_number, $graphic_data_peiling, $graphic_data_alle_scholen, $graphic_answer, $graphic_answered, $graphic_percentage, $graphic_percentage_total, $temp);
    
            $paramsImg = array(
                'name' => $percentage_graphic,
                'scaling' => 50,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 100,
                'spacingRight' => 0,
                'textWrap' => 0,
                'border' => 0,
                'borderDiscontinuous' => 1
            );
            $percentage_docx->addImage($paramsImg);

        }
        $percentage_docx->createDocx($temp.'percentage');
        unset($percentage_docx);
        return $temp.'percentage.docx';
        
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
        $picture_height = (1 + count($graphic_answer)) * 20 + 20;
        $myPicture = new pImage(600, $picture_height, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 12,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "single"
        ));
        
        /* Draw the chart scale */
        $graphic_height = (1 + count($graphic_answer)) * 20;
        $myPicture->setGraphArea(300, 30, 480, $graphic_height);
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
        
        /* Draw the chart */
        $myPicture->drawBarChart(array(
            "DisplayValues" => FALSE,
            "Rounded" => FALSE,
            "Surrounding" => 0,
            "DisplayR" => 255,
            "DisplayG" => 255,
            "DisplayB" => 255,
                        
        ));
        for ($i=0;$i<count($graphic_answer);$i++){
            $myPicture->drawText(160, 42 + ($i)*18,$graphic_answer[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(200, 42 + ($i)*18,$graphic_answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(245, 42 + ($i)*18,$graphic_percentage[$i]."%",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(290, 42 + ($i)*18,"(".$graphic_percentage_total[$i]."%)",array("R"=>80,"G"=>80,"B"=>80,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
        }
        
        $myPicture->render($temp . "percentages$question_number.png");
        // var_dump($all_questions);
        return $temp . "percentages$question_number.png";
        
    }
}
