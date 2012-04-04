<?php

class satisfactionPriorityScatter {

    function render($data) {
        require_once ("./pChart/class/pData.class.php");
        require_once ("./pChart/class/pDraw.class.php");
        require_once ("./pChart/class/pImage.class.php");
        require_once ("./pChart/class/pScatter.class.php");

        $temp = 'temp/';
        $datastring = $data['priority.satisfaction.table.data.scatter'];
        //konqord JSON is false becuse escape character on '
        $datastring = str_replace('\\\'', '\'', $datastring);
        $data = json_decode($datastring);
        //add graphic to docx
        $satisfactionPriorityScatter_docx = new CreateDocx();

        $total_x = 0;
        $total_y = 0;
        $count = 0;
        foreach($data as $key => $row){
            $categories[]     = ($key+1).'. '.$row[1];
            $data[$key][1] = ($key+1).'. '.$row[1];
            $graphic_data_x[] = $row[2];
            $total_x += $row[2];
            $graphic_data_y[] = $row[3];
            $total_y += $row[3];
            $count++;
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
        
        $satisfactionPriorityScatter_graphic = $this->_draw_graphic($graphic_data_x, $graphic_data_y, $temp);

        $paramsImg = array(
            'name' => $satisfactionPriorityScatter_graphic, 
            'scaling' => 30, 
            'spacingTop' => 0, 
            'spacingBottom' => 20, 
            'spacingLeft' => 0, 
            'spacingRight' => 20, 
            'textWrap' => 1, 
            //'border' => 0, 
            //'borderDiscontinuous' => 0
            );
        $satisfactionPriorityScatter_docx -> addImage($paramsImg);
        
        $satisfactionPriorityScatter_docx->addText('De nummers bij de punten verwijzen naar onderstaande rubrieken:',array(
                'sz' => 10,
                'font' => 'Century Gothic'
        ));
        $satisfactionPriorityScatter_docx->addBreak('line');

        $satisfactionPriorityScatter_docx -> addList($categories);

        $satisfactionPriorityScatter_docx->addBreak('line');

        $satisfactionPriorityScatter_docx->addText('De school scoort op de volgende rubrieken \'Meer belangrijk / Meer tevreden\':',array(
                'sz' => 10,
                'font' => 'Century Gothic'
        ));

        $satisfactionPriorityScatter_docx -> addList($advice_positive);

        $satisfactionPriorityScatter_docx->addText('De school scoort op de volgende rubrieken \'Meer belangrijk / Minder tevreden\':',array(
                'sz' => 10,
                'font' => 'Century Gothic'
        ));

        $satisfactionPriorityScatter_docx -> addList($advice_negative);

        $satisfactionPriorityScatter_docx -> createDocx($temp . 'satisfactionPriorityScatter');
        unset($satisfactionPriorityScatter_docx);
        return $temp . 'satisfactionPriorityScatter.docx';

    }

    private function _draw_graphic($graphic_data_x, $graphic_data_y, $temp) {
        /* Create the pData object */
        $myData = new pData();

        /* Create the X axis and the binded series */
        $myData->addPoints($graphic_data_x, "tevreden");
        $myData -> setAxisName(0, "Meer tevreden - Minder tevreden");
        $myData -> setAxisXY(0, AXIS_X);
        $myData -> setAxisPosition(0, AXIS_POSITION_TOP);

        /* Create the Y axis and the binded series */
        $myData->addPoints($graphic_data_y, "belangrijk");
        $myData -> setSerieOnAxis("belangrijk", 1);
        $myData -> setAxisName(1, "Meer belangrijk - Minder belangrijk");
        $myData -> setAxisXY(1, AXIS_Y);
        $myData -> setAxisPosition(1, AXIS_POSITION_LEFT);

        /* Create the 1st scatter chart binding */
        $myData -> setScatterSerie("tevreden", "belangrijk", 0);
        $myData -> setScatterSerieColor(0, array("R" => 0, "G" => 0, "B" => 0));

        /* Create the pChart object */
        $myPicture = new pImage(800, 800, $myData);

        $myPicture->drawGradientArea(0,0,800,800,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));

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

        $myPicture->drawText(30, 600,'Meer tevreden  -  Minder tevreden', array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE,  "Angle"=>90));
        $myPicture->drawText(200, 30,'Minder belangrijk  -  Meer belangrijk', array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "DrawBox" => FALSE));
        
        //draw average X and Y
        $Data    = $myData->getData();
        $Series = $Data["ScatterSeries"][0];
        $SerieX = $Series["X"]; 
        $SerieXAxis = $Data["Series"][$SerieX]["Axis"];
        foreach($graphic_data_x as $X)
        {
            $totalX += $X;
        };
        $averageX = $totalX/count($graphic_data_x);
        $averagePointX = $myScatter->getPosArray($averageX,$SerieXAxis);
//        $Series = $Data["ScatterSeries"][0];
        $SerieY = $Series["Y"]; 
        $SerieYAxis = $Data["Series"][$SerieY]["Axis"];
        foreach($graphic_data_y as $Y)
        {
            $totalY += $Y;
        };
        $averageY = $totalY/count($graphic_data_y);
        $averagePointY = $myScatter->getPosArray($averageY,$SerieYAxis);
        $Settings = array("R"=>87, "G"=>87, "B"=>87);
        $myPicture->drawLine($averagePointX, 50, $averagePointX, 760, $Settings);
        $myPicture->drawLine(50, $averagePointY, 760, $averagePointY, $Settings);
        
        $myPicture -> render($temp . "satisfactionPriorityScatter.png");

        return $temp . "satisfactionPriorityScatter.png";

    }

}

        function cmp_scatter_advice($a, $b)
        {
            if ($a[2] == $b[2]) {
                return 0;
            }
            return ($a[2] < $b[2]) ? 1 : -1;
        }

