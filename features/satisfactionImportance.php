<?php

class satisfactionImportance {

    function render($data, $ref) {
        require_once ("./pChart/class/pData.class.php");
        require_once ("./pChart/class/pDraw.class.php");
        require_once ("./pChart/class/pImage.class.php");
        require_once ("./pChart/class/pScatter.class.php");
        require_once("./features/utils.php");

        $temp = 'temp/';
		$satisfactionImportance_graphics = array();
        $datastring     = $data['table.satisfaction.data'];
        //konqord JSON is false becuse escape character on '
        $datastring = str_replace('\\\'', '\'', $datastring);
        $dataImportance = json_decode($datastring)->{'importance'};
        $dataSatisfaction = json_decode($datastring)->{'satisfaction'};
        $scale_factor_importance = $data["question.type.importance.scalefactor"];
        $scale_factor_satisfaction = $data["question.type.satisfaction.scalefactor"];
        $schoolname     = $data['schoolnaam'];
        $schoolyear     = $data['peiling.jaar'];
        
        //add graphic to docx
        $satisfactionImportance_docx = new CreateDocx();
        $satisfactionImportance_docx->importStyles('./templates/muis-style.docx', 'merge', array('Normal', 'List Paragraph PHPDOCX'));
        $importance_categories = get_importance_categories($data);


        $satisfaction_data = Array();
        $importance_data = Array();
        $category_data = Array();
        foreach ($dataSatisfaction as $key => $reference){
            if ($key == '_empty_'){
                    continue;
            }
                if ($key == 'peiling'){
                } elseif ($key == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                } elseif ($key == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                } elseif ($key == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                } elseif ($key == 'alle_scholen') {
                    if (!$ref['alle_scholen']) continue;
                } else {
                    if (!$ref['question_based']) continue;
                }
            foreach($reference as $ref_key => $ref_value){
                if (!in_array($ref_value[0], $importance_categories)){
                    continue;
                }
                $satisfaction_data[$key][] = Scale10($ref_value[2], $scale_factor_satisfaction);
            }
        }

        foreach ($dataImportance as $key => $reference){
            if ($key == '_empty_'){
                    continue;
            }
                if ($key == 'peiling'){
                } elseif ($key == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                } elseif ($key == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                } elseif ($key == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                } elseif ($key == 'alle_scholen') {
                    if (!$ref['alle_scholen']) continue;
                } else {
                    if (!$ref['question_based']) continue;
                }
            foreach($reference as $ref_key => $ref_value){
                if (!in_array($ref_value[0], $importance_categories)){
                    continue;
                }
                $importance_data[$key][] = Scale10($ref_value[2], $scale_factor_importance);
                $category_data[$key][] = $ref_value[1];
            }
        }
        

        $paramsList = array(
            'val' => 0,
            'sz' => 10,
            'font' => 'Century Gothic',
        );

        $headerStyle = array(
            'b' => 'double',
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        
        $tableStyle = array(
            'font' => 'Century Gothic',
            'sz' => 10,
            'cell_color' => 'E6E6E6',         
            
        );

        $first = true;
        foreach ($category_data as $key => $ref_value){
            if ($key == '_empty_'){
                    continue;
            }
                if ($key == 'peiling'){
                    $name = "$schoolname ";
                } elseif ($key == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                    $name = "Vorige peiling ".$schoolname." ";
                } elseif ($key == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                    $name = $ref['onderbouw']." ";
                } elseif ($key == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                    $name = $ref['bovenbouw']." ";
                } elseif ($key == 'alle_scholen') {
                    if (!$ref['alle_scholen']) continue;
                    $name ="Alle Scholen ";
                } else {
                    if (!$ref['question_based']) continue;
                    $name = $reference.' ';
                }
            if ($first){
                $first = false;
            } else {
                //add pagebrak and enters
                for ($i=0;$i < 5; $i++){
                    $satisfactionImportance_docx->addText('',array());
                }
            }
            $satisfactionImportance_docx->addText('Belang- en tevredenheidsscores per rubriek',array(
                    'font' => 'Century Gothic',
                    'b' => 'single', 
                    'color' => 'F78E1E',
                    'sz' => 10,
            ));

            $satisfactionImportance_docx->addText('',array());

            $satisfactionImportance_docx->addText($name,array(
                    'font' => 'Century Gothic',
                    'b' => 'single', 
                    'sz' => 10,
            ));
            $satisfactionImportance_docx->addText('',array());
            $most_important_table = Array();
            $headerStyle['text'] = '';
            $text = $satisfactionImportance_docx->addElement('addText', array($headerStyle));
            $most_important_table[0][0] = $text; 
            $headerStyle['text'] = 'Rubriek';
            $text = $satisfactionImportance_docx->addElement('addText', array($headerStyle));
            $most_important_table[0][1] = $text; 
            $headerStyle['text'] = 'Belang';
            $text = $satisfactionImportance_docx->addElement('addText', array($headerStyle));
            $most_important_table[0][2] = $text; 
            $headerStyle['text'] = 'Tevredenheid';
            $text = $satisfactionImportance_docx->addElement('addText', array($headerStyle));
            $most_important_table[0][3] = $text; 
            $row=1;
            for ($i=0;$i<count($ref_value); $i++){
                $tableStyle['cell_color'] = ($row&1)?'E6E6E6':'FFFFFF';
                $tableStyle['text'] = ($i+1).'.';
                $text = $satisfactionImportance_docx->addElement('addText', array($tableStyle));
                $most_important_table[$i+1][0] = $text; 
                $tableStyle['text'] = $category_data[$key][$i];
                $text = $satisfactionImportance_docx->addElement('addText', array($tableStyle));
                $most_important_table[$i+1][1] = $text ; 
                $tableStyle['text'] = sprintf("%01.1f",$importance_data[$key][$i]);
                $text = $satisfactionImportance_docx->addElement('addText', array($tableStyle));
                $most_important_table[$i+1][2] = $text; 
                $tableStyle['text'] = sprintf("%01.1f",$satisfaction_data[$key][$i]);
                $text = $satisfactionImportance_docx->addElement('addText', array($tableStyle));
                $most_important_table[$i+1][3] = $text ; 
                $row++;            
            }
            $size_col = array(10, 7000, 1255, 1255);
    
            $paramsTable = array(
                'border' => 'none',
                'border_sz' => 20,
                'border_spacing' => 0,
                'border_color' => '000000',
                'jc' => 'left',
                'size_col' => $size_col
            );
    
            $satisfactionImportance_docx->addTable($most_important_table, $paramsTable);

                if ($key == 'peiling'){
                    $ref_text = "van $schoolname";
                } elseif ($key == 'vorige_peiling') {
                    if (!$ref['vorige_peiling']) continue;
                    $ref_text = "van $schoolname in de vorige peiling";
                } elseif ($key == 'peiling_onderbouw') {
                    if (!$ref['obb']) continue;
                    $ref_text = "van leerlingen in de onderbouw";
                } elseif ($key == 'peiling_bovenbouw') {
                    if (!$ref['obb']) continue;
                    $ref_text = "van leerlingen in de bovenbouw";
                } elseif ($key == 'alle_scholen') {
                    continue;
                } else {
                    if (!$ref['question_based']) continue;
                    $ref_text = "van leerlingen in $key";
                }
    
            $satisfactionImportance_docx->addText('',array(
            ));

            $satisfactionImportance_docx->addText("Hieronder staat op een schaal van 1 tot en met 10 een overzicht van het belang dat ouders $ref_text aan de genoemde onderwerpen hechtten.",array(
                    'font' => 'Century Gothic',
                    'sz' => 10,
            ));
            $satisfactionImportance_docx->addText("Daarachter staat de tevredenheid van de ouders met het desbetreffende onderwerp uitgedrukt in een gemiddelde waarde op een schaal van 1 tot en met 10.",array(
                    'font' => 'Century Gothic',
                    'sz' => 10,
            ));


            $satisfactionImportance_graphic = $this->_draw_graphic($importance_data[$key], $satisfaction_data[$key], $category_data[$key], $key, $temp);
			$satisfactionImportance_graphics[] = $satisfactionImportance_graphic;
            $paramsImg = array(
                'name' => $satisfactionImportance_graphic, 
                'scaling' => 40, 
                'spacingTop' => 20, 
                'spacingBottom' => 20, 
                'spacingLeft' => 0, 
                'spacingRight' => 20, 
                'textWrap' => 0, 
            );
            $satisfactionImportance_docx -> addImage($paramsImg);
            $satisfactionImportance_docx->addBreak('page');
            
        }

        

		$filename = $temp . 'satisfactionImportance'.randchars(12);
        $satisfactionImportance_docx -> createDocx($filename);
        unset($satisfactionImportance_docx);
        foreach ($satisfactionImportance_graphics as $key => $value) {
            unlink($value);
        }
        return $filename.'.docx';

    }

    private function _draw_graphic($graphic_data_x, $graphic_data_y, $categories, $key, $temp) {
        /* Create the pData object */
        $myData = new pData();

        /* Create the X axis and the binded series */
        $myData->addPoints($graphic_data_x, "tevreden");
        $myData -> setAxisName(0, "Minder tevreden - Meer tevreden");
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
        $myPicture = new pImage(1500, 800, $myData);

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
            $myPicture->drawText(900, 75 + $count*35, $count.'. '.$category, array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_TOPLEFT, "DrawBox" => FALSE));
            $count++;
        }
		$filename = $temp . sanitize_filename("satisfactionImportance$key").randchars(12).".png";
        $filename = $filename;
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


