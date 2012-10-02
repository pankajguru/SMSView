<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Graphics extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Create web interface for report downloads 
	 */
	public function index()
	{
		$data['content'] = '';
		$this->load->view('web/default.php', $data);
	}

	public function panelgesprekken()
	{
		$this->load->helper('form');
		$this->load->helper('url');
		
		$data['content'] = '';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['fontsize'] = $this->input->post('fontsize');
			$data['graphic_data'] =  $this->input->post('input_text');
			$data['lineticks'] = $this->input->post('lineticks');
			$data['linesize'] = $this->input->post('linesize');
			
		} else {
			$data['fontsize'] = 24;
			$data['lineticks'] = 1;
			$data['linesize'] = 2;
			$data['graphic_data'] = "Links,rechts,lijst 1, lijst 2\n".
									"vrij,streng,2.5,3\n".
									"progressief,traditioneel, 3.5, 4\n".
									"innovatief,behoudend,2, 3\n".
									"actief,saai,2.5,3.5\n".
									"volks,elitair,3,3\n".
									"zwart,blank,4,4\n".
									"wanordelijk,orderlijk,3.5,3\n".
									"voor zwakke leerlingen,voor goede leerlingen,3.2,3.4\n".
									"klein,groot,3.2,3.2\n".
									"warm,anoniem,3.2,3.5\n".
									"ontplooiingsgericht,prestatiegericht,3.4,3.5\n".
									"leerlinggericht,leerstofgericht,3.4,3.5\n".
									"goed aangeschreven,niet goed aangeschreven,3.5,3.4\n".
									"eigen gezicht,zonder gezicht,3,2.9\n".
									"laat leerlingen vrij ,houd leerlingen goed in de gaten,3,3\n".
									"weinig begeleiding,veel begeleiding,4,4\n";
		}
		
		$data['graphic'] = base_url($this->_create_panel_gesprekken_graphic(trim($data['graphic_data']),$data['fontsize'],$data['fontsize'],$data['lineticks']));
		
		$this->load->view('web/panelgesprekken.php', $data);
	}

	private function _create_panel_gesprekken_graphic($graphic_data, $fontsize, $linesize, $lineticks)
	{
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");

		$lines = explode("\n",$graphic_data);
		$left = array();
		$left_legend = '';
		$values = array();
		$legend = array();
		$right = array();
		$right_legend ='';
		foreach ($lines as $key => $line){
			$line_data = explode(",",$line);
			if ($key === 0)
			{
				foreach ($line_data as $ld_key => $value){
					if ($ld_key === 0){
						$left_legend = trim($line_data[0]);
					} elseif ($ld_key === 1) {
						$right_legend = trim($line_data[1]);
					} else {
						$legend[$ld_key - 2] = $line_data[$ld_key];
					}
				}
			} else {
				foreach ($line_data as $ld_key => $value){
					if ($ld_key === 0){
						$left[] = trim($line_data[0]);
					} elseif ($ld_key === 1) {
						$right[] = trim($line_data[1]);
					} else {
						if ($line_data[$ld_key] > 0){
							$values[$ld_key - 2][$key] = $line_data[$ld_key];
						} else {
							$values[$ld_key - 2][$key] = VOID;
													}
					}
				}
			}
		}
       	$myData = new pData();
       	$myDataLine = new pData();
		$shapes = array(
			SERIE_SHAPE_FILLEDCIRCLE,
			SERIE_SHAPE_FILLEDTRIANGLE,
			SERIE_SHAPE_FILLEDSQUARE,
			SERIE_SHAPE_FILLEDDIAMOND,
			SERIE_SHAPE_CIRCLE,
			SERIE_SHAPE_TRIANGLE,
			SERIE_SHAPE_SQUARE,
			SERIE_SHAPE_DIAMOND,
			SERIE_SHAPE_FILLEDCIRCLE,
			SERIE_SHAPE_FILLEDTRIANGLE,
			SERIE_SHAPE_FILLEDSQUARE,
			SERIE_SHAPE_FILLEDDIAMOND,
			SERIE_SHAPE_CIRCLE,
			SERIE_SHAPE_TRIANGLE,
			SERIE_SHAPE_SQUARE,
			SERIE_SHAPE_DIAMOND
			);
		foreach($values as $key => $value){
			if ($key == 0){
		        $myData->addPoints($values[$key],$legend[$key]);
				$myData->setSerieWeight($legend[$key],2);
				$myData->setSerieTicks($legend[$key],$lineticks);
			} else {
	    	    $myData->addPoints($values[$key],$legend[$key]);
				$myData->setSerieWeight($legend[$key],2);
				$myData->setSerieDrawable($legend[$key],FALSE);
				$myData->setSerieShape($legend[$key],$shapes[$key+1]);
			}
			
		}
		
		$myData->loadPalette("pChart/palettes/panelgesprekken.color", TRUE);
#	        $myData->setPalette($legend[0],array("R"=>22,"G"=>100,"B"=>200));
#        $myData->addPoints($left,$left_legend);
#        $myData->setAbscissa($left_legend);
        
        /* Create the pChart object */
        $pictureHeigth = 210 + 54 * count($left);
        $myPicture = new pImage(1600, $pictureHeigth, $myData);

        /* Set the default font */
        $myPicture -> setFontProperties(array(
            "FontName" => "./pChart/fonts/calibri.ttf", 
            "FontSize" => 24,
            "Alpha" => 0,
            "b" => "double"
            
            ));

        $AxisBoundaries = array(
            0 => array(
                "Min" => 1,
                "Max" => 5
            )
        );

        $RectangleSettings = array("R"=>254,"G"=>204,"B"=>52,"Alpha"=>100,"Surrounding"=>30,"Ticks"=>2);
		$myPicture->drawFilledRectangle(670,111,830,$pictureHeigth-100,$RectangleSettings);
		
        $myPicture->setGraphArea(470,110,1030,$pictureHeigth-100);

        $myPicture->drawScale(array(
            "ManualScale" => $AxisBoundaries,
            "Mode" => SCALE_MODE_MANUAL,
            "Pos"=>SCALE_POS_TOPBOTTOM,
            "DrawSubTicks"=>FALSE,
            "MinDivHeight" => 100,
            "RemoveXAxis" => TRUE
        ));

        $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myPicture->drawLineChart(array(
            "PlotSize"=>5,
            "PlotBorder"=>TRUE,
            "BorderSize"=>1,
            "BreakVoid"=>FALSE,
            "VoidTicks" =>0
            ));
		foreach($values as $key => $value){
			if ($key == 0){
				$myData->setSerieDrawable($legend[$key],FALSE);
			} else {
				$myData->setSerieDrawable($legend[$key],TRUE);
			}
		}
        $myPicture->drawPlotChart(array(
            "PlotSize"=>5,
            "PlotBorder"=>TRUE,
            "BorderSize"=>1,
            ));
        $myPicture->setShadow(FALSE);

        for ($i=0;$i<count($left);$i++){
            $myPicture->drawText(440, 138 + ($i)*54,$left[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => $fontsize, "DrawBox" => FALSE));
            $myPicture->drawText(1060, 138 + ($i)*54,$right[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => $fontsize, "DrawBox" => FALSE));
        }
		
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		$myPicture->drawLegend(400,$pictureHeigth-50,array("Style"=>LEGEND_ROUND,"Mode"=>LEGEND_HORIZONTAL,"R"=>173,"G"=>173,"B"=>173,"BorderR"=>255, "BorderG"=>0, "BorderB"=>0,"Margin"=>10,"Surrounding"=>200,"Family"=>LEGEND_FAMILY_CIRCLE));
//		$myPicture->drawLegend(400,$pictureHeigth-50);

        $myPicture -> render("temp/previous.png");

        return "temp/previous.png";
	}
}

