<?php

class scores
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
        $scores_docx = new CreateDocx();

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
            );
            $invalid_question_types = array('KIND_GROEP','JONGEN_MEIJSE','BEVOLKINGSGROEP','OUDERS_SCHOOLOPLEIDING');
            if (in_array($question->{'question_type'}[0][1], $invalid_question_types)){
                continue;
            }

            if (($target_question == '') and ($first or ($question->{'group_name'} != $old_group_name))){
                if (!$first){
                    //create pagebreak
                    $scores_docx->addBreak('page');
                }
                if ($target_question != '') {
                    //create group heading
                    $scores_docx->addTitle($question->{'group_name'},$paramsTitle);
                    $question_count = 0;
                }                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            
            $text[] =
                array(
                    'text' => html_entity_decode($question_number.". ".$question->{'description'},null, 'UTF-8'),
                    'b' => 'single',
            );
            
            $scores_docx->addText($text);

            //gather data
            $names = array('school'); //TODO: fille in schoolname and this year
            $peiling_averages = $question->{'statistics'}->{'averages'}->{'peiling'}[0];
            if (isset($question->{'statistics'}->{'averages'}->{'vorige_peiling'}[0])){
                $vorige_peiling_averages = $question->{'statistics'}->{'averages'}->{'vorige_peiling'}[0];
                $names[] = 'vorige peiling'; //TODO: fille in schoolname and last year
            }
            $alle_scholen_averages = $question->{'statistics'}->{'averages'}->{'alle_scholen'}[0];
            $names[] = 'Alle scholen';
            
            $min_value = $alle_scholen_averages[0];
            $max_value = $alle_scholen_averages[1];
            $blocksize = ($max_value - $min_value) / 30;
            $empty = array();
            $stdev_left = array();
            $block = array();
            $stdev_right = array();
            $values = array();
            $answered = array();
            
//            foreach(array($peiling_averages,$alle_scholen_averages, $vorige_peiling_averages) as $averages){
            foreach(array($peiling_averages,$alle_scholen_averages) as $averages){
                $empty[] = ($averages[2] - $min_value);
                $stdev_left[] = ($averages[3] - $averages[2] - $blocksize);
                $block[] = $blocksize;
                $stdev_right[] = ($averages[4] - $averages[3] - $blocksize);
                $values[] = sprintf("%01.2f",$averages[3]);
                $answered[] = $averages[5];
            }
            
            
            
            
            $scores_graphic = $this->_draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $temp);
    
            $paramsImg = array(
                'name' => $scores_graphic,
                'scaling' => 30,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
                'border' => 0,
                'borderDiscontinuous' => 1
            );
            $scores_docx->addImage($paramsImg);
            $question_count++;
        }
        if ($question_count > 0){
            $scores_docx->createDocx($temp.'score'.$category.$target_question);
            unset($scores_docx);
            return $temp.'score'.$category.$target_question.'.docx';
        } else {
            unset($scores_docx);
            return null;
        }
        
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
        $ref_count = count($empty);

        /* Create the pChart object */
        $myPicture = new pImage(1200, 20+$ref_count*50, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 24,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "single"
        ));
        
        /* Draw the chart scale */
        $myPicture->setGraphArea(300, 30, 760, 10 + $ref_count*50);
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