<?php

class reportmark
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
            $valid_question_types = array('RAPPORTCIJFER');
            if (!in_array($question->{'question_type'}[0][1], $valid_question_types)){
                continue;
            }

            $peiling_averages = round(($question->{'statistics'}->{'averages'}->{'peiling'}[0][3]*10))/10;
            $alle_scholen_averages = round(($question->{'statistics'}->{'averages'}->{'alle_scholen'}[0][3]*10))/10;
            
            $text= array();
            $text[] = "school ";//.$peiling_averages;
            $text[] ="Alle Scholen ";//.$alle_scholen_averages;
            
            $graphic_data_text          = $text;
            $graphic_data_reportmarks   = array();
            $graphic_data_reportmarks[] = $peiling_averages;
            $graphic_data_reportmarks[] = $alle_scholen_averages;
            
            $percentage_graphic = $this->_draw_graphic($graphic_data_text, $graphic_data_reportmarks, $temp);
    
            $paramsImg = array(
                'name' => $percentage_graphic,
                'scaling' => 50,
                'spacingTop' => 0,
                'spacingBottom' => 0,
                'spacingLeft' => 0,
                'spacingRight' => 0,
                'textWrap' => 0,
                'border' => 0,
                'borderDiscontinuous' => 1
            );
            $percentage_docx->addImage($paramsImg);

        }
        $percentage_docx->createDocx($temp.'reportmark');
        unset($percentage_docx);
        return $temp.'reportmark.docx';
        
    }
    
    private function _draw_graphic($graphic_data_text, $graphic_data_reportmarks, $temp)
    {
        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->loadPalette("./pChart/palettes/sms.color", TRUE);
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
        $picture_height = (1 + count($graphic_data_text)) * 80 + 40;
        $myPicture = new pImage(1200, $picture_height, $MyData);
        $myPicture->setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf",
            "FontSize" => 20,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "b" => "single"
        ));
        
        function YAxisFormat($Value) { return(round($Value)); } 
        
        /* Draw the chart scale */
        $graphic_height = (1 + count($graphic_data_text)) * 60;

        $myPicture->drawGradientArea(100,30,960,$graphic_height,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
        $myPicture->drawGradientArea(100,30,960,$graphic_height,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));

        $myPicture->setGraphArea(100, 30, 960, $graphic_height);
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
            "MinDivHeight"=>40,
//            "YMargin"=>20,
            "XMargin"=>50
//            "Formats"=>array(5,6,7,8,9,10)
        ));
        //
//        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        /* Create the per bar palette */
        $Palette = array("0"=>array("R"=>254,"G"=>153,"B"=>41,"Alpha"=>100),
                 "1"=>array("R"=>48,"G"=>101,"B"=>250,"Alpha"=>100)
                 );        
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
            "Interleave"=>0                      
        ));
        for ($i=0;$i<count($graphic_data_text);$i++){
            $myPicture->drawText(120, 82 + ($i)*47,$graphic_data_text[$i]."; ".$graphic_data_reportmarks[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE));
        }
        
        $myPicture->render($temp . "reportmark$question_number.png");

        return $temp . "reportmark$question_number.png";
        
    }

        
}
