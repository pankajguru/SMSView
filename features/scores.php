<?php

class scores
{

    function render( &$data, $ref, $category='', $target_question='')
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
                    'sz' => 10,
                    'font' => 'Century Gothic'
            );
            
            $scores_docx->addText($text);
            
            $legend = array($question->{'question_type'}[0][7],$question->{'question_type'}[0][8]);
            //gather data
            $names = array(); 
//            $peiling_averages = $question->{'statistics'}->{'averages'}->{'peiling'}[0];
//            $vorige_peiling_averages = false;
//            $peiling_onderbouw_averages = false;
//            $peiling_bovenbouw_averages = false;
//            $alle_scholen_averages = false;
            $graphic_data_scores = array();
            foreach ($question->{'statistics'}->{'averages'} as $key => $average){
                if (($key == 'alle_scholen') and (!$ref['alle_scholen']) ){
                    continue;
                }
                if ($key==''){
                    continue;
                }
                $average_value = $average[0];
                if ($key == 'peiling'){
                    $names[] = "$schoolname ";
                } elseif ($key == 'vorige_peiling') {
                    $names[] = "Vorige peiling ";//.$peiling_averages;
                } elseif ($key == 'peiling_onderbouw') {
                    $names[] = "Onderbouw ";//.$peiling_averages;
                } elseif ($key == 'peiling_bovenbouw') {
                    $names[] = "Bovenbouw ";//.$peiling_averages;
                } elseif ($key == 'alle_scholen') {
                    $names[] ="Alle Scholen ";//.$alle_scholen_averages;
                } else {
                    $names[] = $key;
                }
                $graphic_data_scores[] = $average_value;
            }
                        
 /*           if (isset($question->{'statistics'}->{'averages'}->{'vorige_peiling'}[0])){
                $vorige_peiling_averages = $question->{'statistics'}->{'averages'}->{'vorige_peiling'}[0];
                $names[] = 'Vorige peiling '; //TODO: fille in schoolname and last year
            }
            if (isset($question->{'statistics'}->{'averages'}->{'peiling_onderbouw'}[0])){
                $peiling_onderbouw_averages = $question->{'statistics'}->{'averages'}->{'peiling_onderbouw'}[0];
                $names[] = 'Onderbouw '; 
            }
            if (isset($question->{'statistics'}->{'averages'}->{'peiling_bovenbouw'}[0])){
                $peiling_bovenbouw_averages = $question->{'statistics'}->{'averages'}->{'peiling_bovenbouw'}[0];
                $names[] = 'Bovenbouw '; 
            }
            if ($ref['alle_scholen']){
                $alle_scholen_averages = $question->{'statistics'}->{'averages'}->{'alle_scholen'}[0];
                $names[] = 'Alle scholen ';
            }          */   
//            $min_value = $alle_scholen_averages[0];
//            $max_value = $alle_scholen_averages[1];
            $min_value = $question->{'question_type'}[0][3];
            $max_value = $question->{'question_type'}[0][4];
            $blocksize = ($max_value - $min_value) / 30;
            $empty = array();
            $stdev_left = array();
            $block = array();
            $stdev_right = array();
            $values = array();
            $answered = array();
            foreach($graphic_data_scores as $averages){
//            foreach(array($peiling_averages,$alle_scholen_averages) as $averages){
                if (!is_array($averages)){
                    continue;
                }
                $extra_std_deviation = 0;
                if ( $max_value - $min_value >= 3 ) {
                     $extra_std_deviation = $averages[3] - $averages[2];
                }
                $empty[] = ($averages[2] - $min_value - $extra_std_deviation - $blocksize/2);
                $stdev = ($averages[3] - $averages[2] - $blocksize/2 + $extra_std_deviation);
                if ($stdev < 0) $stdev = 0;
                $stdev_left[] = $stdev;
                $block[] = $blocksize;
                $stdev = ($averages[4] - $averages[3] - $blocksize/2 + $extra_std_deviation);
                if ($stdev < 0) $stdev = 0;
                $stdev_right[] = $stdev;
                $values[] = sprintf("%01.2f",$averages[3]);
                $answered[] = $averages[5];
//                error_log('###'.$averages[3].'#'.$averages[2].'-'.$min_value.'-'.$extra_std_deviation.'='.($averages[2] - $min_value - $extra_std_deviation).'#'.($averages[3] - $averages[2] - $blocksize + $extra_std_deviation).'#'.$blocksize.'#'.($averages[4] - $averages[3] - $blocksize + $extra_std_deviation));
            }
            
            
            
            
            $scores_graphic = $this->_draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $ref['alle_scholen'], $legend, $temp);
    
            $paramsImg = array(
                'name' => $scores_graphic,
                'scaling' => 50,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
//                'border' => 0,
//                'borderDiscontinuous' => 1
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
    
    private function _draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $lastBlue, $legend, $temp)
    { 
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms-scores.color", TRUE);
        $MyData->addPoints($empty, "Zero values");
        $MyData->addPoints($stdev_left, "Min values");
        $MyData->addPoints($block, "Values");
        $MyData->addPoints($stdev_right, "max_values");
//        $MyData->setAxisName(0, "referenties");
        $MyData->addPoints($names, "Scores");
        $MyData->setSerieDescription("Scores", "Scores");
        $MyData->setAbscissa("Scores");
        //        $MyData -> setAbscissaName("Browsers");
        $MyData->setAxisDisplay(0, AXIS_FORMAT_DEFAULT);
        $MyData->setAxisPosition(0,AXIS_POSITION_RIGHT);
        $ref_count = count($names);

        /* Create the pChart object */
        $myPicture = new pImage(1400, 40+$ref_count*35, $MyData);
        $myPicture -> Antialias = FALSE;
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 24,
//            "R" => 255,
//            "G" => 255,
//            "B" => 255,
            "b" => "single"
        ));
        
        /* Draw the chart scale */
        $myPicture->setGraphArea(500, 30, 960, 10 + $ref_count*35);
        $AxisBoundaries = array(
            0 => array(
                "Min" => $min_value,
                "Max" => $max_value
            ),
        );
//        $myPicture->setFontProperties(array("FontSize"=>14));
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "DrawSubTicks" => FALSE,
            "GridR" => 0,
            "GridG" => 0,
            "GridB" => 0,
            "GridAlpha" => 30,
            "Pos" => SCALE_POS_TOPBOTTOM,
            "Mode" => SCALE_MODE_MANUAL,
            "MinDivHeight" => 500/$max_value,
            "FontSize" => 14
            //"Position" => AXIS_POSITION_LEFT
        ));
        //
        
        /* Draw the chart */
        $myPicture->drawStackedBarChart(array(
            "DisplayValues" => FALSE,
            "Rounded" => FALSE,
            "Surrounding" => 0,
            "Interleave" => 0.5,
            "RecordImageMap" => TRUE
        ));
        $myPicture->setFontProperties(array("FontSize"=>24));
        for ($i=0;$i<count($names);$i++){
//            $myPicture->drawText(280, 55 + ($i)*36,$names[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(1100, 50 + ($i)*29,$values[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(1300, 50 + ($i)*29,$answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
        }
        
        //draw legend:
        $myPicture->drawText(500, 10,$legend[0],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE,"FontSize" => 14));
        $myPicture->drawText(960, 10,$legend[1],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE,"FontSize" => 14));
                
        $alle_scholen_ref = $ref_count-1;

        $myPicture -> Antialias = TRUE;
        $imageData = $myPicture -> DataSet -> Data["Series"]['Values']["ImageData"];
        $X = $imageData[$alle_scholen_ref][2] - ($imageData[$alle_scholen_ref][2] - $imageData[$alle_scholen_ref][0])/2;
        $Y = $imageData[$alle_scholen_ref][3];
        $myPicture->drawLine($X, 36, $X, $Y, array("Weight"=>1, "R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
        $myPicture -> Antialias = FALSE;
        
        if ($lastBlue){
            //Make alle scholen bleu
            $imageData = $myPicture -> DataSet -> Data["Series"]['Min values']["ImageData"];
            $myPicture->drawFilledRectangle($imageData[$alle_scholen_ref][0],$imageData[$alle_scholen_ref][1],$imageData[$alle_scholen_ref][2],$imageData[$alle_scholen_ref][3],array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
            $imageData = $myPicture -> DataSet -> Data["Series"]['max_values']["ImageData"];
            $myPicture->drawFilledRectangle($imageData[$alle_scholen_ref][0],$imageData[$alle_scholen_ref][1],$imageData[$alle_scholen_ref][2],$imageData[$alle_scholen_ref][3], array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
        }

        $myPicture->render($temp . "scores$question_number.png");
        return $temp . "scores$question_number.png";
        
    }
}