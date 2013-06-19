<?php

class percentages
{

    function render( &$data, $ref, $category='', $target_question='', $show_legend = FALSE, $example = FALSE)
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['get_all_question_props'];
        $schoolname     = $data['schoolnaam'];
		
		$percentage_graphics = array();
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
        $targeted = FALSE;
        foreach($all_questions_array as $question_number=>$question){
            if (($category != '') and ($category != $question->{'group_name'})){
                continue;
            } 
            if (($target_question != '') and ($target_question != $question_number)){
                continue;
            } 
            if (!isset($question->{'statistics'}->{'percentage'})){
                continue;
            }
            if (count($question->{'statistics'}->{'percentage'}) == 0){
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
                    'text' => $question_number.". ".filter_text($question->{'description'}),
                    'b' => 'single',
                    'sz' => 10,
                    'font' => 'Century Gothic'
            );
            
            $percentage_docx->addText($text);

            $peiling_distribution      = $question->{'statistics'}->{'distribution'}->{'peiling'};
            $alle_scholen_distribution = $question->{'statistics'}->{'distribution'}->{'alle_scholen'};
            $answer_peiling            = array();
            $answer_alle_scholen            = array();
            foreach ($peiling_distribution as $answer) {
                $answer_count_peiling += $answer[2];
                $answer_peiling[$answer[0]] = $answer;
            }
            foreach ($alle_scholen_distribution as $answer) {
                $answer_count_alle_scholen += $answer[2];
                $answer_alle_scholen[$answer[0]] = $answer;
            }
            $graphic_data_peiling      = array();
            $graphic_data_alle_scholen = array();
            $graphic_answer            = array();
            $graphic_answered          = array();
            $graphic_percentage        = array();
            $graphic_percentage_total  = array();
            //TODO:: get list of answers from definition
//            var_dump($question);
            foreach ($question->{'statistics'}->{'percentage'} as $key=>$answer){
                //all questions are here
                if ($answer_count_peiling == 0){
                    continue;
                }
                $answer_text = $answer->{'value'}->{'description'};
                if (strlen($answer_text)>23){
                    $answer_text = substr($answer_text, 0, 20).'...';
                }
                $graphic_answer[$key] = htmlspecialchars_decode($answer_text);
                $answered = (isset($answer_peiling[$key])) ? $answer_peiling[$key][2] : 0;
                $percentage_peiling = $answered / $answer_count_peiling * 100;
                $graphic_data_peiling[$key] = $percentage_peiling;
                //get perc from all schools
                if ($ref['alle_scholen']){
                    if (isset($answer_alle_scholen[$key]) && ($answer_count_alle_scholen != 0)){
                        $percentage_alle_scholen = $answer_alle_scholen[$key][2] / $answer_count_alle_scholen * 100;
                        $graphic_data_alle_scholen[$key] = $percentage_alle_scholen;
                    } else {
                        $percentage_alle_scholen = 0;
                        $graphic_data_alle_scholen[$key] = 0;
                    }
                }
                $graphic_answered[$key] = $answered;
                $graphic_percentage[$key] = round($percentage_peiling);
                if ($ref['alle_scholen']){
                    $graphic_percentage_total[$key] = round($percentage_alle_scholen);
                }
            }
            ksort($graphic_data_peiling);
            ksort($graphic_data_alle_scholen);
            ksort($graphic_answer);
            ksort($graphic_answered);
            ksort($graphic_percentage);
            ksort($graphic_percentage_total);            
            if (isset($all_questions_array[$question_number + 1])){
                $next_groupname = $all_questions_array[$question_number + 1];
                if ($all_questions_array[$question_number + 1]->{'group_name'} != $question->{'group_name'}){
                    $show_legend = TRUE;
                }
            } else {
                $show_legend = TRUE;
            }
            
            $percentage_graphic = $this->_draw_graphic($question_number, $graphic_data_peiling, $graphic_data_alle_scholen, $graphic_answer, $graphic_answered, $graphic_percentage, $graphic_percentage_total, $show_legend, $schoolname, $temp);
    
			$percentage_graphics[] = $percentage_graphic;
			
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
//            $filename = encodeForURL($temp.'percentage'.$category.$target_question);
            $filename = $temp.sanitize_filename('percentage'.$category.$target_question.randchars(12));
            $percentage_docx->createDocx($filename);
            unset($percentage_docx);
			foreach ($percentage_graphics as $key => $value) {
				unlink($value);
			}
            return $filename.'.docx';
        } else {
            unset($percentage_docx);
            return null;
        }

        
    }
    
    private function _draw_graphic($question_number, $graphic_data_peiling, $graphic_data_alle_scholen, $graphic_answer, $graphic_answered, $graphic_percentage, $graphic_percentage_total, $show_legend, $schoolname, $temp)
    {
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms.color", TRUE);
        $MyData->addPoints($graphic_data_peiling, "Percentage ". $schoolname);
        if (count($graphic_data_alle_scholen)>0){
            $MyData->addPoints($graphic_data_alle_scholen, "Percentage alle scholen");
        }
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
        $legend_height = ($show_legend) ? 50 : 0;
        $picture_height = 2 * ( (1 + count($graphic_answer)) * 15) + 18 + $legend_height;
        $myPicture = new pImage(1200, $picture_height, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 22,
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
            $myPicture->drawText(320, 40 + ($i)*31,$graphic_answer[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(400, 40 + ($i)*31,$graphic_answered[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            $myPicture->drawText(490, 40 + ($i)*31,$graphic_percentage[$i]."%",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            if (isset($graphic_percentage_total[$i])){
                $myPicture->drawText(580, 40 + ($i)*31,"(".$graphic_percentage_total[$i]."%)",array("R"=>80,"G"=>80,"B"=>80,'Align' => TEXT_ALIGN_MIDDLERIGHT, "DrawBox" => FALSE));
            }
        }
        if ($show_legend){
            $myPicture->drawLegend(10,40 + ($i)*31,array("BoxWidth"=>20,"BoxHeight"=>20,"Style"=>LEGEND_NOBORDER ,"Mode"=>LEGEND_VERTICAL, "FontR" => 0, "FontG" => 0, "FontB" => 0));
        }
        
		$filename = $temp . "percentages$question_number".randchars(12).".png";
        $myPicture->render($filename);
        // var_dump($all_questions);
        return $filename;
        
    }



}
