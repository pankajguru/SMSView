<?php

class scores
{

    function render( &$data)
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
        $scores_docx = new CreateDocx();

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
                    $scores_docx->addBreak('page');
                }
                //create group heading
                $scores_docx->addTitle($question->{'group_name'},$paramsTitle);
                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            
            $text[] =
                array(
                    'text' => $question_number.". ".$question->{'description'},
                    'b' => 'single',
            );
            
            $scores_docx->addText($text);

            //gather data
            $peiling_averages = $question->{'statistics'}->{'averages'}->{'peiling'}[0];
            $alle_scholen_averages = $question->{'statistics'}->{'averages'}->{'alle_scholen'}[0];
            
            $names = array('school', 'Alle scholen'); //TODO:fill in schoolname
            $min_value = $alle_scholen_averages[0];
            $max_value = $alle_scholen_averages[1];
            $blocksize = ($max_value - $min_value) / 30;
            $empty = array(($peiling_averages[2] - $min_value),($alle_scholen_averages[2] -$min_value));
            $stdev_left = array(($peiling_averages[3] - $peiling_averages[2] - $blocksize),($alle_scholen_averages[3] - $alle_scholen_averages[2] - $blocksize));
            $block = array(($blocksize),($blocksize));
            $stdev_right = array(($peiling_averages[4] - $peiling_averages[3] - $blocksize),($alle_scholen_averages[4] - $alle_scholen_averages[3]  - $blocksize));
            $values = array(sprintf("%01.2f",$peiling_averages[3]), sprintf("%01.2f",$alle_scholen_averages[3]));
            $answered = array($peiling_averages[5], $alle_scholen_averages[5]);
            
            
            
            
            $scores_graphic = $this->_draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $temp);
    
            $paramsImg = array(
                'name' => $scores_graphic,
                'scaling' => 25,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
                'border' => 0,
                'borderDiscontinuous' => 1
            );
            $scores_docx->addImage($paramsImg);
        }
        $scores_docx->createDocx($temp.'scores');
        unset($scores_docx);
        return $temp.'scores.docx';
        
    }
    
    private function _draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $temp)
    { 
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms-scores.color", TRUE);
        $MyData->addPoints($empty, "Zero values");
        $MyData->addPoints($stdev_left, "Min values");
        $MyData->addPoints($block, "Values");
        $MyData->addPoints($stdev_right, "max_values");
        $MyData->setAxisName(0, "referenties");
        $MyData->addPoints($names, "Scores");
        $MyData->setSerieDescription("Scores", "Scores");
        $MyData->setAbscissa("Scores");
        //        $MyData -> setAbscissaName("Browsers");
        $MyData->setAxisDisplay(0, AXIS_FORMAT_DEFAULT);

        /* Create the pChart object */
        $myPicture = new pImage(1200, 120, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 24,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "single"
        ));
        
        /* Draw the chart scale */
        $myPicture->setGraphArea(300, 30, 760, 110);
        $AxisBoundaries = array(
            0 => array(
                "Min" => $min_value,
                "Max" => $max_value
            )
        );
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "DrawSubTicks" => FALSE,
            "GridR" => 0,
            "GridG" => 0,
            "GridB" => 0,
            "GridAlpha" => 30,
            "Pos" => SCALE_POS_TOPBOTTOM,
            "Mode" => SCALE_MODE_MANUAL
        ));
        //
        
        /* Draw the chart */
        $myPicture->drawStackedBarChart(array(
            "DisplayValues" => FALSE,
            "Rounded" => FALSE,
            "Surrounding" => 0,
            "Interleave" => 1
        ));
        for ($i=0;$i<count($names);$i++){
            $myPicture->drawText(280, 55 + ($i)*36,$names[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(900, 55 + ($i)*36,$values[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(1100, 55 + ($i)*36,$answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
        }
        
        $myPicture->render($temp . "scores$question_number.png");
        // var_dump($all_questions);
        return $temp . "scores$question_number.png";
        
    }
}