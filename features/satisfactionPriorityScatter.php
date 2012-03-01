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

        var_dump($data);
        foreach($data as $row){
            $graphic_data_x[] = $row[2];
            $graphic_data_y[] = $row[3];
        }
        $satisfactionPriorityScatter_graphic = $this->_draw_graphic($graphic_data_x, $graphic_data_y, $temp);

        $paramsImg = array('name' => $satisfactionPriorityScatter_graphic, 'scaling' => 50, 'spacingTop' => 0, 'spacingBottom' => 0, 'spacingLeft' => 0, 'spacingRight' => 0, 'textWrap' => 0, 'border' => 0, 'borderDiscontinuous' => 1);
        $satisfactionPriorityScatter_docx -> addImage($paramsImg);

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
