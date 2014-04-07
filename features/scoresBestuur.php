<?php

class scoresBestuur
{

    function render( &$data, $ref, $category='', $target_question='', $example='')
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        require_once ('features/scoresBestuur.php');
        $temp           = 'temp/';
        if (!isset($data['all.questions.bestuur'])){
            return 0;
        };
        $datastring     = $data['all.questions.bestuur'];
        $schoolname     = $data['schoolnaam'];
        $bestuur_name   = $data['bestuur.name'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        //add graphic to docx
        $scores_docx = new CreateDocx();

        //create array iso object
        $all_questions_array = array();
        foreach($all_questions as $question_number=>$question){
            if ($question_number == '_empty_'){
                continue;
            }
            $all_questions_array[intval($question_number)] = $question;
        };
        
        ksort($all_questions_array);
        $first = TRUE;
        $targeted = FALSE;
        $question_count = 0;
        $alternate = false;
        foreach($all_questions_array as $question_number=>$question){
            if (($category != '') and ($category != $question->{'group_name'})){
                continue;
            } 
            if (($target_question != '') and ($target_question != $question_number)){
                continue;
            } 
            $invalid_question_types = array('KIND_GROEP', 'KIND_GRP_BELGIE','JONGEN_MEIJSE', 'JONGEN_MEISJE' ,'BEVOLKINGSGROEP','OUDERS_SCHOOLOPLEIDING', 'PTP_GENDER', 'PTP_AGE','SCHOOLOPLEIDING_OUDERS_BELGIE', 'NATIONALITEIT_BELGIE', 'KIND_GROEP_BELGIE');
            if (in_array($question->{'question_type'}[0][1], $invalid_question_types)){
                continue;
            }
            if (!isset($question->{'statistics'}->{'percentage'})){
                continue;
            }
            if (count($question->{'statistics'}->{'percentage'}) == 0){
                continue;
            }
            if ($example != '') {
                $valid_question_types = array('LEUK', 'TEVREDEN', 'PTP_TEVREDEN');
                if (!in_array($question->{'question_type'}[0][1], $valid_question_types)){
//                    continue;
                }
                if ($question_number == 1){
                    continue;
                }
                if ($targeted){
                    continue;
                } else {
                    $targeted = TRUE;
                }
            }
            $answer_count_peiling = 0;
            $answer_count_alle_scholen = 0;
            $text = array();
            
            $paramsTitle = array(
                'val' => 2,
            );

            if (($target_question == '') and ($first or ($question->{'group_name'} != $old_group_name))){
                if (!$first){
                    //create pagebreak
                    $scores_docx->addBreak('page');
                    $alternate = false;
                }
                if ($target_question != '') {
                    //create group heading
                    $scores_docx->addTitle(filter_text($question->{'group_name'}),$paramsTitle);
                    $question_count = 0;
                }                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            $text[] =
                array(
                    'text' => $question_number.". ".filter_text($question->{'short_description'}),
                    'b' => 'single',
                    'sz' => 10,
                    'font' => 'Century Gothic'
            );
            
//            $scores_docx->addText($text);
            $legend = array($question->{'question_type'}[0][7],$question->{'question_type'}[0][8]);
            //gather data
            $names = array(); 
            $graphic_data_scores = array();
            $alle_scholen = $ref['alle_scholen'];
            foreach ($question->{'refs'} as $reference){
                if ($reference==''){
                    continue;
                }
                if (preg_match('/\d\d\d\d$/',$reference)){
                    continue;
                }
                
                if (is_null($question->{'statistics'}->{'averages'})){
                    continue;
                }
                if (!isset($question->{'statistics'}->{'averages'}->{$reference})){
                    continue;
                }
                if (count($question->{'statistics'}->{'averages'}->{$reference}) == 0){
                    continue;
                }
                $average_value = $question->{'statistics'}->{'averages'}->{$reference}[0];
                if (is_null($average_value)){
                    continue;
                }
                if ($reference == '_empty_'){
                    continue;
                }
                if (($reference == 'peiling') || ($reference == 'bestuur')){
                    $names[] = "$bestuur_name ";
                } elseif ($reference == 'alle_scholen') {
                    $names[] = 'Alle scholen';
                } else {
                    $names[] = $reference;
                }
                $graphic_data_scores[] = $average_value;
               
            }
            $min_value = $question->{'question_type'}[0][3];
            $max_value = $question->{'question_type'}[0][4];
            if ($min_value == $max_value){
                $max_value += 0.01;
            }
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
//                    continue;
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
            }
            if (count($names) > 0){
                $scores_graphic = $this->_draw_graphic($names, $values, $question_number.". ".filter_text($question->{'short_description'}), $answered, $legend,$max_value,$min_value, $alternate, $temp);
                if (!$alternate){
                     /* Create the pChart object */
                    $pictureHeigth = 110 + 54 * count($names);
                    $greatPicture = new pImage(1500,$pictureHeigth);
                    $greatPicture->drawFromPNG(0,0,$scores_graphic);
                } else {
                    $greatPicture->drawFromPNG(900,0,$scores_graphic);
                }
                if ($alternate){
                    $greatPicture->render($temp . "greatscores$question_number.png");
                    $paramsImg = array(
                        'name' => $temp . "greatscores$question_number.png",
                        'scaling' => 40,
                        'spacingTop' => 0,
                        'spacingBottom' => 0,
                        'spacingLeft' => 0,
                        'spacingRight' => 0,
                        'textWrap' => 0,
                    );
                    $scores_docx->addImage($paramsImg);
                }
                $alternate = !$alternate; 
            }
            $question_count++;
        }
        //add last question if uneven 
        if ($alternate){
                    $paramsImg = array(
                        'name' => $scores_graphic,
                        'scaling' => 40,
                        'spacingTop' => 0,
                        'spacingBottom' => 0,
                        'spacingLeft' => 0,
                        'spacingRight' => 0,
                        'textWrap' => 0,
                    );
                    $scores_docx->addImage($paramsImg);
        }
        if ($question_count > 0){
            $filename = $temp.sanitize_filename('scoreBestuur'.$category.$target_question.randchars(12));
            
            $scores_docx->createDocx($filename);
            unset($scores_docx);
            return $filename.'.docx';
        } else {
            unset($scores_docx);
            return null;
        }
        
    }

    private function _draw_graphic($names, $values, $question, $answered, $legend,$max_value,$min_value, $alternate, $temp) {
        /* Create the pData object */
        $myData = new pData();

        $myData->addPoints($values,"peiling");
        $left = 0;
        if (!$alternate){
            $myData->addPoints($names,"rubriek");
            $myData->setAbscissa("rubriek");
        } else {
            $left = 300;
        }
        $myData->setPalette("peiling",array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
        
        /* Create the pChart object */
        $pictureHeigth = 110 + 54 * count($names);
        $myPicture = new pImage(900 - $left, $pictureHeigth, $myData);

//        $myPicture->drawGradientArea(0,0,1400,800,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));

        /* Set the default font */
        $myPicture -> setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf", 
            "FontSize" => 24,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "Alpha" => 0,
            "b" => "double"
            
            ));

        $AxisBoundaries = array(
            0 => array(
                "Min" => $min_value,
                "Max" => $max_value
            )
        );

        $myPicture->setGraphArea(300 - $left,110,600 - $left ,$pictureHeigth);
//        $myPicture->drawFilledRectangle(500,60,670,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "Mode" => SCALE_MODE_MANUAL,
            "Pos"=>SCALE_POS_TOPBOTTOM,
            "DrawSubTicks"=>FALSE,
            "MinDivHeight" => 100,
            "RemoveXAxis" => TRUE,
            "LabelSkip"=>5,
        )); 
        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myPicture->drawPlotChart(array(
            "PlotSize"=>5,
            "PlotBorder"=>TRUE,
            "BorderSize"=>1,
            ));
        $myPicture->setShadow(FALSE);

        $myPicture->drawText(300 - $left, 20,$question,array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        $myPicture->drawText(700 - $left, 70,"m",array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        $myPicture->drawText(800 - $left, 70,"n",array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        //$myPicture->drawText(800, 80,"5",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        //$myPicture->drawText(1200, 80,"10",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        foreach($names as $key => $previous_table_text_item){
        //for ($i=0;$i<count($previous_table_text);$i++){
            if (!$alternate){
                $myPicture->drawText(0, 138 + ($key)*54,$names[$key],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
            }
            $myPicture->drawText(700 - $left, 138 + ($key)*54,sprintf("%01.1f",$values[$key]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(800 - $left, 138 + ($key)*54,$answered[$key],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        }
        $myPicture->drawText(300 - $left, 70,$legend[0],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 15, "DrawBox" => FALSE));
        $myPicture->drawText(600 - $left - (strlen($legend[1]) * 8), 70,$legend[1],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 15, "DrawBox" => FALSE));
        
        
        $filename = $temp . "scores_bestuur".randchars(12).".png";
        $myPicture -> render($filename);

        return $filename;

    }
     private function _draw_graphic_old($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $lastBlue, $legend, $temp)
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
        $imageData = $myPicture -> DataSet -> Data["Series"]['Values']["ImageData"];

        $myPicture->setFontProperties(array("FontSize"=>24));
        for ($i=0;$i<count($names);$i++){
            $Y = $imageData[$i][3] - 10;
            
//            $myPicture->drawText(280, 55 + ($i)*36,$names[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(1100, $Y,$values[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(1300, $Y,$answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
        }
        
        //draw legend:
        $myPicture->drawText(500, 10,$legend[0],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE,"FontSize" => 14));
        $myPicture->drawText(960, 10,$legend[1],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE,"FontSize" => 14));
                
        $alle_scholen_ref = $ref_count-1;

        if ($lastBlue){
            $myPicture -> Antialias = TRUE;
            $X = $imageData[$alle_scholen_ref][2] - ($imageData[$alle_scholen_ref][2] - $imageData[$alle_scholen_ref][0])/2;
            $Y = $imageData[$alle_scholen_ref][3];
            $myPicture->drawLine($X, 36, $X, $Y, array("Weight"=>1, "R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
            $myPicture -> Antialias = FALSE;
        
            if (isset($myPicture -> DataSet -> Data["Series"]['Min values']["ImageData"])){
                //Make alle scholen bleu
                $imageData = $myPicture -> DataSet -> Data["Series"]['Min values']["ImageData"];
                if (isset($imageData[$alle_scholen_ref][0])){
                    $myPicture->drawFilledRectangle($imageData[$alle_scholen_ref][0],$imageData[$alle_scholen_ref][1],$imageData[$alle_scholen_ref][2],$imageData[$alle_scholen_ref][3],array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
                }
            } 
            if (isset($myPicture -> DataSet -> Data["Series"]['max_values']["ImageData"])){
                $imageData = $myPicture -> DataSet -> Data["Series"]['max_values']["ImageData"];
                if (isset($imageData[$alle_scholen_ref][0])){
                    $myPicture->drawFilledRectangle($imageData[$alle_scholen_ref][0],$imageData[$alle_scholen_ref][1],$imageData[$alle_scholen_ref][2],$imageData[$alle_scholen_ref][3], array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
                }
            }
        }

        $myPicture->render($temp . "scores$question_number.png");
        return $temp . "scores$question_number.png";
        
    }
}
