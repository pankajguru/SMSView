<?php

class previous
{

    function render( &$data, $type='satisfaction')
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['table.satisfaction.data'];
        $schoolname     = $data['schoolnaam'];
        $column_count   = 0;
        
        
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $previous_data  = json_decode($datastring)->{$type};
        
        //add graphic to docx
        $previous_docx = new CreateDocx();
        
        $previous_table_text = array();
        $previous_table_peiling = array();
        $previous_table_vorige_peiling = array();
        for ($i=0 ; $i < 10 ; $i++){
            foreach ($previous_data as $key => $previous_column){
                if ($key == 'vorige_peiling'){
                    $previous_table_vorige_peiling[] = Scale10($previous_column[$i][2], 4);
                }
                if ($key == 'peiling'){
                    $previous_table_peiling[] = Scale10($previous_column[$i][2], 4);
                    $previous_table_text[] = $previous_data->{'alle_scholen'}[$i][1];
                }   
            }
            
        }
        
        $previous_graphic = $this->_draw_graphic($previous_table_text, $previous_table_peiling, $previous_table_vorige_peiling, $temp);

        $paramsImg = array(
            'name' => $previous_graphic, 
            'scaling' => 30, 
            'spacingTop' => 0, 
            'spacingBottom' => 20, 
            'spacingLeft' => 0, 
            'spacingRight' => 20, 
            'textWrap' => 1, 
            //'border' => 0, 
            //'borderDiscontinuous' => 0
            );
        $previous_docx -> addImage($paramsImg);


        $previous_docx->createDocx($temp.$type);
        unset($previous_docx);
        return $temp.$type.'.docx';
        
    }
    
    private function _draw_graphic($previous_table_text, $previous_table_peiling, $previous_table_vorige_peiling, $temp) {
        /* Create the pData object */
        $myData = new pData();

        $myData->addPoints($previous_table_peiling,"nu");
        $myData->addPoints($previous_table_vorige_peiling,"vorig");

        $myData->addPoints($previous_table_text,"rubriek");
        $myData->setAbscissa("rubriek");
//        $myData->setSerieTicks("nu",2);
//        $myData->setSerieTicks("vorig",2);

        /* Create the pChart object */
        $myPicture = new pImage(1600, 600, $myData);

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


        $myPicture->setGraphArea(800,60,1200,600);
//        $myPicture->drawFilledRectangle(500,60,670,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "Pos"=>SCALE_POS_TOPBOTTOM,
            "DrawSubTicks"=>FALSE
        ));
        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myPicture->drawPlotChart(array("PlotSize"=>5,"PlotBorder"=>TRUE,"BorderSize"=>1));
        $myPicture->setShadow(FALSE);

        for ($i=0;$i<count($previous_table_text);$i++){
            $myPicture->drawText(120, 88 + ($i)*54,$previous_table_text[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(770, 88 + ($i)*54,sprintf("%01.1f",$previous_table_vorige_peiling[$i]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(1300, 88 + ($i)*54,sprintf("%01.1f",$previous_table_peiling[$i]),array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
        }
        
        $myPicture -> render($temp . "previous.png");

        return $temp . "previous.png";

    }

}
