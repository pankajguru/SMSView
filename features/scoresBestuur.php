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
        $datastring     = $data['all.questions.bestuur'];
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
        $targeted = FALSE;
        $question_count = 0;
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
//                continue;
            }
            if (count($question->{'statistics'}->{'percentage'}) == 0){
//                continue;
            }
            if ($example != '') {
                $valid_question_types = array('TEVREDEN', 'PTP_TEVREDEN');
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
                    'text' => $question_number.". ".filter_text($question->{'description'}),
                    'b' => 'single',
                    'sz' => 10,
                    'font' => 'Century Gothic'
            );
            
            $scores_docx->addText($text);
            $legend = array($question->{'question_type'}[0][7],$question->{'question_type'}[0][8]);
            //gather data
            $names = array(); 
            $graphic_data_scores = array();
            $alle_scholen = $ref['alle_scholen'];
            foreach ($question->{'refs'} as $reference){
                if ($reference==''){
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
                if ($reference == 'Leerjaar 6'){
                    continue;
                }
                if ($reference == 'BS De Octopus'){
                    continue;
                }
                if ($reference == 'Bs De Poel'){
                    continue;
                }
                if ($reference == 'peiling'){
                    $names[] = "$schoolname ";
                } elseif ($reference == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                    $names[] = "Vorige peiling ".$schoolname." ";
                } elseif ($reference == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                    $names[] = $ref['onderbouw']." ";
                } elseif ($reference == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                    $names[] = $ref['bovenbouw']." ";
                } elseif ($reference == 'alle_scholen') {
                    if (!$ref['alle_scholen']) continue;
                    $names[] ="Alle Scholen ";
                } elseif (substr($reference,0,8) === 'locatie_') {
                    if (!$ref['locaties']) continue;
                    $names[] = substr($reference,8).' ';
                } elseif (substr($reference,0,15) === 'question_based_') {
                    if (!$ref['question_based']) continue;
                    $names[] = substr($reference,15).' ';
                } else {
                    $names[] = $reference;
                }
                $graphic_data_scores[] = $average_value;
               
            }
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
            }
            $scores_graphic = $this->_draw_graphic($question_number, $names, $empty, $stdev_left, $block, $stdev_right, $min_value, $max_value,$values, $answered, $alle_scholen, $legend, $temp);
    
            $paramsImg = array(
                'name' => $scores_graphic,
                'scaling' => 50,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
            );
            $scores_docx->addImage($paramsImg);
            $question_count++;
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
