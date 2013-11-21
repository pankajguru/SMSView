<?php

class satisfactionPriorityScatter {

    function render($data, $ref, $config) {
        require_once ("./pChart/class/pData.class.php");
        require_once ("./pChart/class/pDraw.class.php");
        require_once ("./pChart/class/pImage.class.php");
        require_once ("./pChart/class/pScatter.class.php");
        require_once("./features/utils.php");

        if (!isset($data["priority.satisfaction.table.data.scatter"])){
            return '';
        }

        $temp = 'temp/';
        $datastring = $data['priority.satisfaction.table.data.scatter'];
        //konqord JSON is false becuse escape character on '
        $datastring = str_replace('\\\'', '\'', $datastring);
        $importance_categories = get_importance_categories($data);
        $data = json_decode($datastring);
        
        //add graphic to docx
        $satisfactionPriorityScatter_docx = new CreateDocx();
//        $satisfactionPriorityScatter_docx->importStyles('./templates/otp-muis.docx', 'merge', array('Normal','ListParagraphPHPDOCX'));
        $satisfactionPriorityScatter_docx->importStyles($config->item('template_dir').'/muis-style.docx', 'merge', array('Normal', 'List Paragraph PHPDOCX'));

        $total_x = 0;
        $total_y = 0;
        $count = 0;
        foreach($data as $key => $row){
            if (in_array($row[0], $importance_categories)){
                $categories[]     = ($count+1).'. '.$row[1];
                $data[$key][1] = ($count+1).'. '.$row[1];
                $graphic_data_x[] = $row[2];
                $total_x += $row[2];
                $graphic_data_y[] = $row[3];
                $total_y += $row[3];
                $count++;
            }
        }
        if ($count === 0){
            return 0;
        }
        
        $average_x = $total_x / $count;
        $average_y = $total_y / $count;
        $advice_positive = array();
        $advice_negative = array();
        usort($data, 'cmp_scatter_advice');
        foreach($data as $key => $row){
            if ($row[2]>$average_x){
                if ($row[3]>$average_y){
                    $advice_positive[] = $row[1];
                } else {
                    $advice_negative[] = $row[1];
                }
            }
        }
        
        $satisfactionPriorityScatter_graphic = $this->_draw_graphic($graphic_data_x, $graphic_data_y, $categories, $temp);

        $paramsImg = array(
            'name' => $satisfactionPriorityScatter_graphic, 
            'scaling' => 40, 
            'spacingTop' => 20, 
            'spacingBottom' => 20, 
            'spacingLeft' => 0, 
            'spacingRight' => 20, 
            'textWrap' => 0, 
            //'border' => 0, 
            //'borderDiscontinuous' => 0
            );
        $satisfactionPriorityScatter_docx -> addImage($paramsImg);
        $paramsList = array(
            'val' => 0,
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        
        $satisfactionPriorityScatter_docx->addText('De school scoort op de volgende rubrieken \'Meer belangrijk/Meer tevreden\':',array(
                'sz' => 10,
                'font' => 'Century Gothic'
        ));

        $satisfactionPriorityScatter_docx -> addList($advice_positive, $paramsList);

        $satisfactionPriorityScatter_docx->addText('De school scoort op de volgende rubrieken \'Meer belangrijk/Minder tevreden\':',array(
                'sz' => 10,
                'font' => 'Century Gothic'
        ));

        $satisfactionPriorityScatter_docx -> addList($advice_negative, $paramsList);
		
		$filename = $temp . 'satisfactionPriorityScatter'.randchars(12);
        $satisfactionPriorityScatter_docx -> createDocx($filename);
        unset($satisfactionPriorityScatter_docx);
		unlink($satisfactionPriorityScatter_graphic);
        return $filename.'.docx';

    }

    private function _draw_graphic($graphic_data_x, $graphic_data_y, $categories, $temp) {
        /* Create the pData object */
        $myData = new pData();

        /* Create the X axis and the binded series */
        $myData -> addPoints($graphic_data_x, "tevreden");
        $myData -> setAxisName(0, "Minder tevreden - Meer tevreden");
        $myData -> setAxisXY(0, AXIS_X);
        $myData -> setAxisPosition(0, AXIS_POSITION_TOP);

        /* Create the Y axis and the binded series */
        $myData -> addPoints($graphic_data_y, "belangrijk");
        $myData -> setSerieOnAxis("belangrijk", 1);
        $myData -> setAxisName(1, "Meer belangrijk - Minder belangrijk");
        $myData -> setAxisXY(1, AXIS_Y);
        $myData -> setAxisPosition(1, AXIS_POSITION_LEFT);

        /* Create the 1st scatter chart binding */
        $myData -> setScatterSerie("tevreden", "belangrijk", 0);
        $myData -> setScatterSerieColor(0, array("R"=>0,"G"=>164,"B"=>228));
        $myData -> setScatterSerieShape(0,SERIE_SHAPE_FILLEDCIRCLE);
        $myData -> setScatterSerieTicks(0,2);

        /* Create the pChart object */
        $myPicture = new pImage(1500, 800, $myData);

        $myPicture->drawGradientArea(0,0,800,800,DIRECTION_VERTICAL,array("StartR"=>230,"StartG"=>230,"StartB"=>230,"EndR"=>230,"EndG"=>230,"EndB"=>230,"Alpha"=>100));

        /* Turn of Anti-aliasing */
  //      $myPicture -> Antialias = FALSE;

        /* Add a border to the picture */
  //      $myPicture -> drawRectangle(0, 0, 399, 399, array("R" => 0, "G" => 0, "B" => 0));

        /* Set the default font */
        $myPicture -> setFontProperties(array(
//            "FontName" => "./pChart/fonts/calibri.ttf", 
//            "FontSize" => 0,
            "R" => 255,
            "G" => 255,
            "B" => 255,
            "Alpha" => 0,
            "b" => "double"
            
            ));

        /* Set the graph area */
        $myPicture -> setGraphArea(40, 40, 770, 770);

        /* Create the Scatter chart object */
        $myScatter = new pScatter($myPicture, $myData);

        /* Draw the scale */
        $scaleSettings = array(
            "XMargin" => 15, 
            "YMargin" => 15, 
            "Floating" => TRUE, 
            "GridR" => 200, 
            "GridG" => 200, 
            "GridB" => 200, 
            "DrawSubTicks" => FALSE, 
            "CycleBackground" => TRUE,
            "AxisAlpha"=>0,
            "TickAlpha"=>0,
            "Alpha"=>0
        );
        $myScatter -> drawScatterScale($scaleSettings);


        
        /* Draw the legend */
//        $myScatter -> drawScatterLegend(280, 380, array("Mode" => LEGEND_HORIZONTAL, "Style" => LEGEND_NOBORDER));

        /* Draw a scatter plot chart */
        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myScatter -> drawScatterPlotChartSms();
        
        /* Draw scales by hand */
        /* Set the default font */
        $myPicture -> setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf", 
            "FontSize" => 24,
            "R" => 255,
            "G" => 0,
            "B" => 0,
            "b" => "double"
            
            ));

        $myPicture->drawText(30, 600,'Minder tevreden  -  Meer tevreden', array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE,  "Angle"=>90));
        $myPicture->drawText(200, 30,'Minder belangrijk  -  Meer belangrijk', array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE));
        
        //draw average X and Y
        $Data    = $myData->getData();
        $Series = $Data["ScatterSeries"][0];
        $SerieX = $Series["X"]; 
        $SerieXAxis = $Data["Series"][$SerieX]["Axis"];
        $totalX = 0;
        foreach($graphic_data_x as $X)
        {
            $totalX += $X;
        };
        $averageX = $totalX/count($graphic_data_x);
        $averagePointX = $myScatter->getPosArray($averageX,$SerieXAxis);
//        $Series = $Data["ScatterSeries"][0];
        $SerieY = $Series["Y"]; 
        $SerieYAxis = $Data["Series"][$SerieY]["Axis"];
        $totalY = 0;
        foreach($graphic_data_y as $Y)
        {
            $totalY += $Y;
        };
        $averageY = $totalY/count($graphic_data_y);
        $averagePointY = $myScatter->getPosArray($averageY,$SerieYAxis);
        $Settings = array("R"=>87, "G"=>87, "B"=>87);
        $myPicture->drawLine($averagePointX, 50, $averagePointX, 760, $Settings);
        $myPicture->drawLine(50, $averagePointY, 760, $averagePointY, $Settings);
        
        //draw legend
        $myPicture->drawText(900, 40, 'De nummers bij de punten verwijzen naar',array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_TOPLEFT, "DrawBox" => FALSE)); 
        $myPicture->drawText(900, 75, 'onderstaande rubrieken:', array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_TOPLEFT, "DrawBox" => FALSE));
        
        $count = 1;    
        foreach($categories as $category){
            $myPicture->drawText(900, 75 + $count*35, $category, array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_TOPLEFT, "DrawBox" => FALSE));
            $count++;
        }
        
		$filename = $temp . "satisfactionPriorityScatter".randchars(12).".png";
        $myPicture -> render($filename);

        return $filename;

    }

    function _error_dump($object){
        ob_start();
        //var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

        function cmp_scatter_advice($a, $b)
        {
            if ($a[2] == $b[2]) {
                return 0;
            }
            return ($a[2] < $b[2]) ? 1 : -1;
        }

