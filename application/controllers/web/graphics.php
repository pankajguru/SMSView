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
			$data['graphic_data'] =  $this->input->post('input_text');
		} else {
			$data['graphic_data'] = "vrij,2.5,streng\n".
									"progressief,3.5,traditioneel\n".
									"innovatief,3,behoudend\n".
									"actief,3,saai\n".
									"volks,3.1,elitair\n".
									"zwart,2.8,blank\n".
									"wanordelijk,2.6,orderlijk\n".
									"voor zwakke leerlingen,3,voor goede leerlingen\n".
									"klein,4,groot\n".
									"warm,4,anoniem\n".
									"ontplooiingsgericht,4,prestatiegericht\n".
									"leerlinggericht,4,leerstofgericht\n".
									"goed aangeschreven,4,niet goed aangeschreven\n".
									"eigen gezicht,4,zonder gezicht\n".
									"laat leerlingen vrij ,4,houd leerlingen goed in de gaten\n".
									"weinig begeleiding,4,veel begeleiding\n";
		}
		
		$data['graphic'] = base_url($this->_create_panel_gesprekken_graphic(trim($data['graphic_data'])));
		
		$this->load->view('web/panelgesprekken.php', $data);
	}

	private function _create_panel_gesprekken_graphic($graphic_data)
	{
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");

		$lines = explode("\n",$graphic_data);
		$left = array();
		$value = array();
		$right = array();
		foreach ($lines as $line){
			$line_data = explode(",",$line);
			$left[] = trim($line_data[0]);
			$value[] = $line_data[1];
			$right[] = trim($line_data[2]);
		}
       	$myData = new pData();

        $myData->addPoints($value,"waarde");
        $myData->addPoints($left,"laag");
        $myData->setAbscissa("laag");
        $myData->setPalette("waarde",array("R"=>22,"G"=>100,"B"=>200));
		$myData->setSerieWeight("waarde",2);
        
        /* Create the pChart object */
        $pictureHeigth = 110 + 54 * count($value);
        $myPicture = new pImage(1600, $pictureHeigth, $myData);

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
                "Min" => 1,
                "Max" => 5
            )
        );

        $RectangleSettings = array("R"=>254,"G"=>204,"B"=>52,"Alpha"=>100,"Surrounding"=>30,"Ticks"=>2);
		$myPicture->drawFilledRectangle(670,111,830,$pictureHeigth,$RectangleSettings);

        $myPicture->setGraphArea(450,110,1050,$pictureHeigth);

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
            ));
        $myPicture->drawPlotChart(array(
            "PlotSize"=>5,
            "PlotBorder"=>TRUE,
            "BorderSize"=>1,
            ));
        $myPicture->setShadow(FALSE);

        for ($i=0;$i<count($value);$i++){
            $myPicture->drawText(440, 138 + ($i)*54,$left[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLERIGHT, "FontSize" => 24, "DrawBox" => FALSE));
            $myPicture->drawText(1060, 138 + ($i)*54,$right[$i],array("R"=>0,"G"=>0,"B"=>0,'Align' => TEXT_ALIGN_MIDDLELEFT, "FontSize" => 24, "DrawBox" => FALSE));
        }

        $myPicture -> render("temp/previous.png");

        return "temp/previous.png";
	}
}

