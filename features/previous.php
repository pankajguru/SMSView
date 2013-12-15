<?php

class previous
{

    function render( &$data, $ref, $type='satisfaction')
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['table.satisfaction.data'];
        $schoolname     = $data['schoolnaam'];
        $scale_factor   = $data["question.type.$type.scalefactor"];
        $importance_categories = get_importance_categories($data);
        $column_count   = 0;
        
        
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $previous_data  = json_decode($datastring)->{$type};
        
        //add graphic to docx
        $previous_docx = new CreateDocx();
        
        $previous_table_text = array();
        $previous_table_peiling = array();
        $previous_table_vorige_peiling = array();
        $count = 0;
        foreach ($previous_data->{'peiling'} as $key => $previous){
            if (!isset($previous[1])){
                continue;
            }
            //do not take categories wich are not ment to be categories:
            if (!in_array($previous[0], $importance_categories)){
                continue;
            }
            $previous_number = 0;
            if (isset($previous_data->{'vorige_peiling'})){
                foreach ($previous_data->{'vorige_peiling'} as $previous){
                    if ($previous[0] == $previous_data->{'peiling'}[$key][0]){
                        $previous_number = Scale10($previous[2], $scale_factor);
                    }
                }
            }
            $previous_table_vorige_peiling[] = $previous_number;
            $previous_table_peiling[] = Scale10($previous_data->{'peiling'}[$key][2], $scale_factor);
            $previous_table_text[] = $previous_data->{'peiling'}[$key][1];
        }


        if (count($previous_table_vorige_peiling) == 0){
            print "no previous";
            return 0;
        }
        
        $previous_graphic = $this->_draw_graphic($previous_table_text, $previous_table_peiling, $previous_table_vorige_peiling, $schoolname, $temp);

        $paramsImg = array(
            'name' => $previous_graphic, 
            'scaling' => 50, 
            'spacingTop' => 0, 
            'spacingBottom' => 20, 
            'spacingLeft' => 0, 
            'spacingRight' => 20, 
            'textWrap' => 1, 
            //'border' => 0, 
            //'borderDiscontinuous' => 0
            );
        $previous_docx -> addImage($paramsImg);
        
        $good = array();
        $bad = array();
        $equal = array();
        foreach ($previous_table_peiling as $key => $previous_peiling){
            $difference = (round(10*$previous_table_peiling[$key])/10 - round(10*$previous_table_vorige_peiling[$key])/10);
            if (abs($difference) < 0.05){
                $equal[] = $previous_table_text[$key];
            } elseif ($difference < 0){
                $bad[] = filter_text($previous_table_text[$key]).'   '.sprintf("%01.1f",$difference);
            }else{
                $good[] = filter_text($previous_table_text[$key]).'   '.sprintf("%01.1f",$difference);
            }
            
        }
        $paramsList = array(
            'val' => 1,
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        $text = array();
        $text[] = array(
            'text' => 'In vergelijking met de vorige peiling wordt de school beter beoordeeld op de rubrieken:',
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        $previous_docx->addText($text);
        $previous_docx->addList($good, $paramsList);
        $text[] = array(
            'text' => 'Minder goed beoordeeld worden de rubrieken:',
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        $previous_docx->addText($text);
        $previous_docx->addList($bad, $paramsList);
        $text[] = array(
            'text' => 'Deze rubrieken worden hetzelfde beoordeeld als bij de vorige peiling.:',
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        $previous_docx->addText($text);
        $previous_docx->addList($equal, $paramsList);

        $previous_docx->modifyPageLayout('A4');

		$filename = $temp.'previous'.randchars(12);
        $previous_docx->createDocx($filename);
        unset($previous_docx);
		unlink($previous_graphic);
        return $filename.'.docx';
        
    }
    
    private function _draw_graphic($previous_table_text, $previous_table_peiling, $previous_table_vorige_peiling, $schoolname, $temp) {
        /* Create the pData object */
        $myData = new pData();

        $myData->addPoints($previous_table_vorige_peiling,"vorig");
        $myData->addPoints($previous_table_peiling,"nu");

        $myData->addPoints($previous_table_text,"rubriek");
        $myData->setAbscissa("rubriek");
        $myData->setPalette("nu",array("R"=>254,"G"=>204,"B"=>52));
        $myData->setPalette("vorig",array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100));
        
//        $myData->setSerieTicks("nu",5);
//        $myData->setSerieTicks("vorig",5);

        /* Create the pChart object */
        $pictureHeigth = 110 + 54 * count($previous_table_text);
        $myPicture = new pImage(1600, $pictureHeigth, $myData);

//        $myPicture->drawGradientArea(0,0,1400,800,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));

        /* Set the default font */
        $myPicture -> setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf", 
            "FontSize" => 24,
//            "R" => 255,
//            "G" => 255,
//            "B" => 255,
            "Alpha" => 0,
            "b" => "double"
            
            ));

        $AxisBoundaries = array(
            0 => array(
                "Min" => 5,
                "Max" => 10
            )
        );

        $myPicture->setGraphArea(800,110,1200,$pictureHeigth);
//        $myPicture->drawFilledRectangle(500,60,670,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
        $myPicture->drawScale(array(
//            "ManualScale" => $AxisBoundaries,
//            "Mode" => SCALE_MODE_MANUAL,
            "Pos"=>SCALE_POS_TOPBOTTOM,
            "DrawSubTicks"=>FALSE,
            "MinDivHeight" => 100,
            "RemoveXAxis" => TRUE
        ));
        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myPicture->drawPlotChart(array(
            "PlotSize"=>5,
            "PlotBorder"=>TRUE,
            "BorderSize"=>1,
            ));
        $myPicture->setShadow(FALSE);

        $myPicture->drawText(120, 30,$schoolname,array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        $myPicture->drawText(120, 70,"Rubriek",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        $myPicture->drawText(770, 70,"vorig:",array("R"=>0,"G"=>164,"B"=>228,"Alpha"=>100,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        $myPicture->drawText(1300, 70,"nu:",array("R"=>254,"G"=>204,"B"=>52,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        //$myPicture->drawText(800, 80,"5",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        //$myPicture->drawText(1200, 80,"10",array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        foreach($previous_table_text as $key => $previous_table_text_item){
        //for ($i=0;$i<count($previous_table_text);$i++){
            $myPicture->drawText(120, 138 + ($key)*54,$previous_table_text[$key],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(770, 138 + ($key)*54,sprintf("%01.1f",$previous_table_vorige_peiling[$key]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(1300, 138 + ($key)*54,sprintf("%01.1f",$previous_table_peiling[$key]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            $difference = (round(10*$previous_table_peiling[$key])/10 - round(10*$previous_table_vorige_peiling[$key])/10);
            if (abs($difference) < 0.05){
                $myPicture->drawText(1500, 138 + ($key)*54,"-",array("R"=>0,"G"=>112,"B"=>192,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            } elseif ($difference < 0){
                $myPicture->drawText(1500, 138 + ($key)*54,sprintf("%01.1f",$difference),array("R"=>142,"G"=>10,"B"=>8,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            }else{
                $myPicture->drawText(1500, 138 + ($key)*54,'+'.sprintf("%01.1f",$difference),array("R"=>158,"G"=>238,"B"=>122,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            }
        }
        
        $filename = $temp . "previous".randchars(12).".png";
        $myPicture -> render($filename);

        return $filename;

    }

}

function arr_reindex( $arr ) 
{
    ksort($arr);
    $arr2 = array();
    foreach($arr as $key => $value)
    { 
        $arr2[] = $value;     
    } 
    return $arr2; 
} 