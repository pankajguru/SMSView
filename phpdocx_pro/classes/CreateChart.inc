<?php

/**
 * Create charts
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.4
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.4
 */
include_once dirname(__FILE__) . '/CreateElement.inc';

/**
 * Create charts
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.4
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 2.4
 */
class CreateChart extends CreateElement
{
    const NAMESPACEWORD = 'c';

    /**
     *
     * @access protected
     * @var <type>
     */
    protected $_xmlChart;
    
    /**
     * @access private
     * @var CreateChart
     * @static
     */
    private static $_instance = null;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_rId;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_textalign;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_jc;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_sizeX;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_sizeY;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_type;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_showPercent;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_data;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_cornerX;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_cornerY;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_cornerP;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_color;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_groupBar;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_title;

    /**
     *
     * @access private
     * @var <type>
     */
    private $_name;

    /**
     * @access private
     * @var string
     */
    private $_font;

    /**
     * @access private
     * @var string
     */
    private $_legendpos;

    /**
     * @access private
     * @var string
     */
    private $_legendoverlay;

    /**
     * @access private
     * @var string
     */
    private $_border;

    /**
     * @access private
     * @var string
     */
    private $_haxlabel;

    /**
     * @access private
     * @var string
     */
    private $_vaxlabel;

    /**
     * @access private
     * @var string
     */
    private $_haxlabel_display;

    /**
     * @access private
     * @var string
     */
    private $_vaxlabel_display;

    /**
     * @access private
     * @var string
     */
    private $_showtable;

    /**
     * @access private
     * @var string
     */
    private $_hgrid;

    /**
     * @access private
     * @var string
     */
    private $_vgrid;

    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        $this->_rId = '';
        $this->_textalign = '';
        $this->_jc = '';
        $this->_sizeX = '';
        $this->_sizeY = '';
        $this->_type = '';
        $this->_showPercent = '';
        $this->_data = '';
        $this->_cornerX = '';
        $this->_cornerY = '';
        $this->_cornerP = '';
        $this->_color = '';
        $this->_groupBar = '';
        $this->_title = '';
        $this->_font = '';
        $this->_xml = '';
        $this->_name = '';
        $this->_legendpos = '';
        $this->_legendoverlay = '';
        $this->_border = '';
        $this->_haxlabel = '';
        $this->_vaxlabel = '';
        $this->_haxlabel_display = '';
        $this->_vaxlabel_display = '';
        $this->_showtable = '';
        $this->_hgrid = '';
        $this->_vgrid = '';
    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct()
    {

    }

    /**
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     *
     * @access public
     * @return CreateChart
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateChart();
        }
        return self::$_instance;
    }

    /**
     * Setter. Rid
     *
     * @access public
     * @param string $rId
     */
    public function setRId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Getter. Rid
     *
     * @access public
     * @return string
     */
    public function getRId()
    {
        return $this->_rId;
    }

    /**
     * Setter. Name
     *
     * @access public
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Getter. Name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Setter. Xml chart
     *
     * @access public
     * @param string $xmlChart
     */
    public function setXmlChart($xmlChart)
    {
        $this->_xmlChart = $xmlChart;
    }

    /**
     * Getter. Xml chart
     *
     * @access public
     * @return string
     */
    public function getXmlChart()
    {
        return $this->_xmlChart;
    }

    /**
     * Create embedded chart
     *
     * @access public
     * @param array $args[0]
     * @param array $args[1]
     * @return boolean
     */
    public function createEmbeddedChart()
    {
        $this->_xmlChart = '';
        if ($this->_type != '') {
            $this->createEmbeddedDocumentXml();
            return true;
        } else {
            echo 'You haven`t added a chart type';
            return false;
        }
    }

    /**
     * Create embedded xml chart
     *
     * @access public
     */
    public function createEmbeddedXmlChart()
    {
        $this->_xmlChart = '';
        $this->generateCHARTSPACE();
        $this->generateDATE1904(1);
        $this->generateLANG();
        $color = 2;
        if ($this->_color) {
            $color = $this->_color;
        }
        $this->generateSTYLE($color);
        $this->generateCHART();
        if ($this->_title != '') {
            $this->generateTITLE();
            $this->generateTITLETX();
            $this->generateRICH();
            $this->generateBODYPR();
            $this->generateLSTSTYLE();
            $this->generateTITLEP();
            $this->generateTITLEPPR();
            $this->generateDEFRPR();
            if ($this->_font != '') {
                $this->generateRFONTS($this->_font);
            }
            $this->generateTITLER();
            $this->generateTITLERPR();
            if ($this->_font != '') {
                $this->generateTITLERFONTS($this->_font);
            }
            $this->generateTITLET($this->_title);
            $this->generateTITLELAYOUT();
        } else {
            $this->generateAUTOTITLEDELETED();
            $title = '';
        }
        if (strpos($this->_type, '3D') !== false) {
            $this->generateVIEW3D();
            $cornerX = 30;
            $cornerY = 30;
            $cornerP = 30;
            if ($this->_cornerX != '') {
                $cornerX = $this->_cornerX;
            }
            if ($this->_cornerY != '') {
                $cornerY = $this->_cornerY;
            }
            if ($this->_cornerP != '') {
                $cornerP = $this->_cornerP;
            }
            $this->generateROTX($cornerX);
            $this->generateROTY($cornerY);
            $this->generatePERSPECTIVE($cornerP);
        }
        if ($this->datos == '') {
            exit('You haven`t added data');
        }
        $this->generatePLOTAREA();
        $this->generateLAYOUT();
        if (strpos($this->_type, 'pie') !== false) {
            if (strpos($this->_type, '3D') !== false) {
                $this->generatePIE3DCHART();
            } else {
                $this->generatePIECHART();
            }

            $this->generateVARYCOLORS();

            if (isset($this->datos['legend'])) $legends = $this->datos['legend'];
            else $legends = array($this->_title);

            $numDatos = count($this->datos);
        } elseif ((strpos($this->_type, 'bar') !== false) ||
            (strpos($this->_type, 'col') !== false)) {
            if (strpos($this->_type, '3D') !== false) {
                $this->generateBAR3DCHART();
            } else {
                $this->generateBARCHART();
            }

            $typeBar = 'bar';

            if (strpos($this->_type, 'col') !== false) {
                $typeBar = 'col';
            }

            $this->generateBARDIR($typeBar);
            $groupBar = 'clustered';
            if ($this->_groupBar != '') {
                $groupBar = $this->_groupBar;
            }

            $this->generateGROUPING($groupBar);
            if (isset($this->datos['legend'])) {
                $legends = $this->datos['legend'];
            } else {
                echo('You haven`t added legends');
                return false;
            }
            $numDatos = count($this->datos) - 1;
        } elseif (strpos($this->_type, 'line') !== false) {
            if (strpos($this->_type, '3D') !== false) {
                $this->generateLINE3DCHART();
            } else {
                $this->generateLINECHART();
            }

            $this->generateGROUPING('standard');

            if (isset($this->datos['legend'])) {
            	$legends = $this->datos['legend'];
            }
            else {
                echo('You haven`t added legends');
                return false;
            }
            $numDatos = count($this->datos) - 1;
       } elseif (strpos($this->_type, 'area') !== false) {
            if (strpos($type, '3D') !== false) {
            	$this->generateAREA3DCHART();
            } else {
            	$this->generateAREACHART();
            }
            $groupBar = 'standard';
            if (!empty($this->_groupBar)) {
                $groupBar = $this->_groupBar;
            }
            $this->generateGROUPING($groupBar);
            if (isset($this->datos['legend'])) {
                $legends = $this->datos['legend'];
            } else {
                echo('You must write a legend');
                return false;
            }
            $sizeDats = count($dats) - 1;
        } else {
            echo('You haven`t added a chart type');
            return false;
        }

        $letra = 'A';
        for ($i = 0; $i < count($legends); $i++) {
            $this->generateSER();
            $this->generateIDX($i);
            $this->generateORDER($i);
            $letra++;

            $this->generateTX();
            $this->generateSTRREF();
            $this->generateF(' Hoja1!$' . $letra . '$1');
            $this->generateSTRCACHE();
            $this->generatePTCOUNT();
            $this->generatePT();
            if (strpos($this->_type, 'pie') !== false) {
                $this->generateV($this->_title);
            } else {
                $this->generateV($legends[$i]);
            }

            $this->cleanTemplate2();
            $this->generateCAT();
            $this->generateSTRREF();
            $this->generateF('Hoja1!$A$2:$A$' . ($numDatos + 1));
            $this->generateSTRCACHE();
            $this->generatePTCOUNT($numDatos);

            $num = 0;
            foreach ($this->datos as $legend => $dato) {
                if ($legend == 'legend') {
                    continue;
                }
                $this->generatePT($num);
                $this->generateV($legend);
                $num++;
            }
            $this->cleanTemplate2();
            $this->generateVAL();
            $this->generateNUMREF();
            $this->generateF('Hoja1!$' . $letra . '$2:$B$' . ($numDatos + 1));
            $this->generateNUMCACHE();
            $this->generateFORMATCODE();
            $this->generatePTCOUNT($numDatos);
            $num = 0;
            foreach ($this->datos as $legend => $dato) {
                if ($legend == 'legend') {
                    continue;
                }
                $this->generatePT($num);
                $this->generateV($dato[$i]);
                $num++;
            }
            $this->cleanTemplate3();
        }
        if (strpos($this->_type, 'pie') !== false) {
            $this->generateDLBLS();
            $showPercent = 0;
            if ($this->_showPercent != '') {
                $showPercent = 1;
            }
            $this->generateSHOWPERCENT($showPercent);
            $this->generateFIRSTSLICEANG();
        } else {
            $shape = 'box';
            if (strpos($type, 'Cylinder') !== false) {
            	$shape = 'cylinder';
            } elseif (strpos($type, 'Cone') !== false) {
            	$shape = 'cone';
            } elseif (strpos($type, 'Pyramid') !== false) {
            	$shape = 'pyramid';
            }
            $this->generateSHAPE($shape);
            $this->generateAXID();
            $this->generateAXID(59040512);
            $this->generateCATAX();
            $this->generateAXAXID(59034624);
            $this->generateSCALING();
            $this->generateORIENTATION();
            $this->generateAXPOS();
            if (!empty($this->_haxlabel)) {
	            $this->generateAXLABEL($this->_haxlabel);
            	$vert = 'horz';
            	$rot = 0;
            	if ($this->_haxlabel == 'vertical') {
            		$vert = 'wordArtVert';
            	}
            	if ($this->_haxlabel == 'rotated') {
            		$rot = '-5400000';
            	}
	            $this->generateAXLABELDISP($vert, $rot);
            }
            $this->generateTICKLBLPOS();
            if ($this->_vgrid) {
            	switch ($this->_vgrid) {
            		case 1:
		            	$this->generateMAJORGRIDLINES();
            		break;
            		case 2:
		            	$this->generateMINORGRIDLINES();
            		break;
            		case 3:
		            	$this->generateMAJORGRIDLINES();
		            	$this->generateMINORGRIDLINES();
            		break;
            		default:
            		break;
            	}
            }
            $this->generateCROSSAX();
            $this->generateCROSSES();
            $this->generateAUTO();
            $this->generateLBLALGN();
            $this->generateLBLOFFSET();
            if (!empty($this->_showtable)) {
            	$this->generateDATATABLE();
            }
            $this->generateVALAX();
            $this->generateAXAXID(59040512);
            $this->generateSCALING();
            $this->generateORIENTATION();
            $this->generateAXPOS('l');
            if ($this->_hgrid) {
            	switch ($this->_hgrid) {
            		case 1:
		            	$this->generateMAJORGRIDLINES();
            		break;
            		case 2:
		            	$this->generateMINORGRIDLINES();
            		break;
            		case 3:
		            	$this->generateMAJORGRIDLINES();
		            	$this->generateMINORGRIDLINES();
            		break;
            		default:
            		break;
            	}
            } else {
            	$this->generateMAJORGRIDLINES();
            }
            if (!empty($this->_vaxlabel)) {
	            $this->generateAXLABEL($this->_vaxlabel);
            	$vert = 'horz';
            	$rot = 0;
            	if ($this->_vaxlabel_display == 'vertical') {
            		$vert = 'wordArtVert';
            	}
            	if ($this->_vaxlabel_display == 'rotated') {
            		$rot = '-5400000';
            	}
	            $this->generateAXLABELDISP($vert, $rot);
            }
            $this->generateNUMFMT();
            $this->generateTICKLBLPOS();
            if ($this->_font != '') {
                $this->generateRFONTS2($this->_font);
            }
            $this->generateCROSSAX(59034624);
            $this->generateCROSSES();
            $this->generateCROSSBETWEEN();
        }
        if ($this->_legendpos != 'none') {
	        $this->generateLEGEND();
	        if (!empty($this->_legendpos)) {
	        	$this->generateLEGENDPOS($this->_legendpos);
	        }
	        if (!empty($this->_legendoverlay)) {
	        	$this->generateLEGENDOVERLAY($this->_legendoverlay);
	        }
	        if ($this->_font != '') {
	            $this->generateLEGENDFONT($this->_font);
	        }
        }
        $this->generatePLOTVISONLY();
//        if (empty($this->_border) 
//        	&& strpos($this->_type, 'pieChart') === false) {
        if (strpos($this->_type, 'pieChart') === false) {
            $this->generateSPPR();
            $this->generateLN();
            $this->generateNOFILL();
        }

        $this->generateEXTERNALDATA();
        $this->cleanTemplateDocument();
        return $this->_xmlChart;
    }

    /**
     * Create chart
     *
     * @access public
     * @param array $args[0]
     * @param array $args[1]
     * @return boolean
     */
    public function createChart()
    {
        $this->_xmlChart = '';
        $args = func_get_args();
        if (!empty($args[1]['type']) && !empty($args[0])) {
            if ($this->createCHARTXML($args) == false) {
                echo 'You haven`t added legends';
                return false;
            }
            $this->createDOCUMENTXML($args);
            return true;
        } else {
            echo 'You haven`t added a chart type';
            return false;
        }
    }

    /**
     * Init chart
     *
     * @access public
     * @param array $args[0]
     */
    public function initChart()
    {
        $args = func_get_args();
        
        if (!isset($args[0]['textWrap'])) {
            $args[0]['textWrap'] = '';
        }
        if (!isset($args[0]['jc'])) {
            $args[0]['jc'] = '';
        }
        if (!isset($args[0]['sizeX'])) {
            $args[0]['sizeX'] = '';
        }
        if (!isset($args[0]['sizeY'])) {
            $args[0]['sizeY'] = '';
        }
        if (!isset($args[0]['type'])) {
            $args[0]['type'] = '';
        }
        if (!isset($args[0]['showPercent'])) {
            $args[0]['showPercent'] = '';
        }
        if (!isset($args[0]['data'])) {
            $args[0]['data'] = '';
        }
        if (!isset($args[0]['cornerX'])) {
            $args[0]['cornerX'] = '';
        }
        if (!isset($args[0]['cornerY'])) {
            $args[0]['cornerY'] = '';
        }
        if (!isset($args[0]['cornerP'])) {
            $args[0]['cornerP'] = '';
        }
        if (!isset($args[0]['color'])) {
            $args[0]['color'] = '';
        }
        if (!isset($args[0]['groupBar'])) {
            $args[0]['groupBar'] = '';
        }
        if (!isset($args[0]['title'])) {
            $args[0]['title'] = '';
        }
        if (!isset($args[0]['font'])) {
            $args[0]['font'] = '';
        }
        if (!isset($args[0]['legendpos'])) {
            $args[0]['legendpos'] = '';
        }
        if (!isset($args[0]['legendoverlay'])) {
            $args[0]['legendoverlay'] = '';
        }
        if (!isset($args[0]['border'])) {
            $args[0]['border'] = '';
        }
        if (!isset($args[0]['haxlabel'])) {
            $args[0]['haxlabel'] = '';
        }
        if (!isset($args[0]['vaxlabel'])) {
            $args[0]['vaxlabel'] = '';
        }
        if (!isset($args[0]['haxlabel_display'])) {
            $args[0]['haxlabel_display'] = '';
        }
        if (!isset($args[0]['vaxlabel_display'])) {
            $args[0]['vaxlabel_display'] = '';
        }
        if (!isset($args[0]['showtable'])) {
            $args[0]['showtable'] = '';
        }
        if (!isset($args[0]['hgrid'])) {
            $args[0]['hgrid'] = '';
        }
        if (!isset($args[0]['vgrid'])) {
            $args[0]['vgrid'] = '';
        }
        
        $this->_textalign = $args[0]['textWrap'];
        $this->_jc = $args[0]['jc'];
        $this->_sizeX = $args[0]['sizeX'];
        $this->_sizeY = $args[0]['sizeY'];
        $this->_type = $args[0]['type'];
        $this->_showPercent = $args[0]['showPercent'];
        $this->datos = $args[0]['data'];
        $this->_cornerX = $args[0]['cornerX'];
        $this->_cornerY = $args[0]['cornerY'];
        $this->_cornerP = $args[0]['cornerP'];
        $this->_color = $args[0]['color'];
        $this->_groupBar = $args[0]['groupBar'];
        $this->_title = $args[0]['title'];
        $this->_font = $args[0]['font'];
        $this->_legendpos = $args[0]['legendpos'];
        $this->_legendoverlay = $args[0]['legendoverlay'];
        $this->_border = $args[0]['border'];
        $this->_haxlabel = $args[0]['haxlabel'];
        $this->_vaxlabel = $args[0]['vaxlabel'];
        $this->_haxlabel_display = $args[0]['haxlabel_display'];
        $this->_vaxlabel_display = $args[0]['vaxlabel_display'];
        $this->_showtable = $args[0]['showtable'];
        $this->_showtable = $args[0]['hgrid'];
        $this->_showtable = $args[0]['vgrid'];
    }

    /**
     * Generate w:autotitledeleted
     *
     * @access protected
     * @param string $val
     */
    protected function generateAUTOTITLEDELETED($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':autoTitleDeleted val="' . $val .
            '"></' . CreateChart::NAMESPACEWORD .
            ':autoTitleDeleted>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:bar3DChart
     *
     * @access protected
     */
    protected function generateBAR3DCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':bar3DChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD . ':bar3DChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:barChart
     *
     * @access protected
     */
    protected function generateBARCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':barChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':barChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:barDir
     *
     * @access protected
     * @param string $val
     */
    protected function generateBARDIR($val = 'bar')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':barDir val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':barDir>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:bodypr
     *
     * @access protected
     */
    protected function generateBODYPR()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':bodyPr></' .
            CreateImage::NAMESPACEWORD1 . ':bodyPr>__GENERATERICH__';
        $this->_xmlChart = str_replace(
            '__GENERATERICH__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:chart
     *
     * @access protected
     */
    protected function generateCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':chart>__GENERATECHART__</' . CreateChart::NAMESPACEWORD .
            ':chart>__GENERATECHARTSPACE__';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate chartspace XML
     *
     * @access protected
     */
    protected function generateCHARTSPACE()
    {
        $this->_xmlChart = '<?xml version="1.0" encoding="UTF-8" ' .
            'standalone="yes" ?><' . CreateChart::NAMESPACEWORD .
            ':chartSpace xmlns:c="http://schemas.openxmlformats.o' .
            'rg/drawingml/2006/chart" xmlns:a="http://schemas.open' .
            'xmlformats.org/drawingml/2006/main" xmlns:r="http://s' .
            'chemas.openxmlformats.org/officeDocument/2006/relatio' .
            'nships">__GENERATECHARTSPACE__</' .
            CreateChart::NAMESPACEWORD . ':chartSpace>';
    }

    /**
     * Generate w:date1904
     *
     * @access protected
     * @param string $val
     */
    protected function generateDATE1904($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':date1904 val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':date1904>__GENERATECHARTSPACE__';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__',
            $xml,
            $this->_xmlChart
        );
    }

    /**
     * Generate w:defrpr
     *
     * @access protected
     */
    protected function generateDEFRPR()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':defRPr>__GENERATEDEFRPR__</' . CreateImage::NAMESPACEWORD1 .
            ':defRPr>__GENERATETITLEPPR__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLEPPR__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:lang
     *
     * @access protected
     * @param string $val
     */
    protected function generateLANG($val = 'es-ES')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':lang val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':lang>__GENERATECHARTSPACE__';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__',
            $xml,
            $this->_xmlChart
        );
    }

    /**
     * Generate w:layout
     *
     * @access protected
     */
    protected function generateLAYOUT()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':layout></' . CreateChart::NAMESPACEWORD .
            ':layout>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:line3DChart
     *
     * @access protected
     */
    protected function generateLINE3DCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':line3DChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':line3DChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:lineChart
     *
     * @access protected
     */
    protected function generateLINECHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':lineChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':lineChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:area3DChart
     *
     * @access protected
     */
    protected function generateAREA3DCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':area3DChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':area3DChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:areaChart
     *
     * @access protected
     */
    protected function generateAREACHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':areaChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':areaChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:lststyle
     *
     * @access protected
     */
    protected function generateLSTSTYLE()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':lstStyle></' . CreateImage::NAMESPACEWORD1 .
            ':lstStyle>__GENERATERICH__';
        $this->_xmlChart = str_replace(
            '__GENERATERICH__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:perspective
     *
     * @access protected
     * @param string $val
     */
    protected function generatePERSPECTIVE($val = '30')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':perspective val="' . $val . '"></' .
            CreateChart::NAMESPACEWORD . ':perspective>';
        $this->_xmlChart = str_replace(
            '__GENERATEVIEW3D__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:pie3DChart
     *
     * @access protected
     */
    protected function generatePIE3DCHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':pie3DChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':pie3DChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:piechart
     *
     * @access protected
     */
    protected function generatePIECHART()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':pieChart>__GENERATETYPECHART__</' .
            CreateChart::NAMESPACEWORD .
            ':pieChart>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:plotarea
     *
     * @access protected
     */
    protected function generatePLOTAREA()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':plotArea>__GENERATEPLOTAREA__</' . CreateChart::NAMESPACEWORD .
            ':plotArea>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:rich
     *
     * @access protected
     */
    protected function generateRICH()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':rich>__GENERATERICH__</' . CreateChart::NAMESPACEWORD .
            ':rich>__GENERATETITLETX__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLETX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:rotx
     *
     * @access protected
     * @param string $val
     */
    protected function generateROTX($val = '30')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':rotX val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':rotX>__GENERATEVIEW3D__';
        $this->_xmlChart = str_replace(
            '__GENERATEVIEW3D__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:roty
     *
     * @access protected
     * @param string $val
     */
    protected function generateROTY($val = '30')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':rotY val="' . $val . '"></' . CreateChart::NAMESPACEWORD .
            ':rotY>__GENERATEVIEW3D__';
        $this->_xmlChart = str_replace(
            '__GENERATEVIEW3D__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:style
     *
     * @access protected
     * @param string $val
     */
    protected function generateSTYLE($val = '2')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':style val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':style>__GENERATECHARTSPACE__';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__',
            $xml,
            $this->_xmlChart
        );
    }

    /**
     * Generate w:title
     *
     * @access protected
     */
    protected function generateTITLE()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':title>__GENERATETITLE__</' . CreateChart::NAMESPACEWORD .
            ':title>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titlelayout
     *
     * @access protected
     * @param string $nombre
     */
    protected function generateTITLELAYOUT($nombre = '')
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':layout></' . CreateImage::NAMESPACEWORD1 .
            ':layout>';
        $this->_xmlChart = str_replace(
            '__GENERATETITLE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titlep
     *
     * @access protected
     */
    protected function generateTITLEP()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':p>__GENERATETITLEP__</' . CreateImage::NAMESPACEWORD1 .
            ':p>__GENERATERICH__';
        $this->_xmlChart = str_replace(
            '__GENERATERICH__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titleppr
     *
     * @access protected
     */
    protected function generateTITLEPPR()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':pPr>__GENERATETITLEPPR__</' . CreateImage::NAMESPACEWORD1 .
            ':pPr>__GENERATETITLEP__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLEP__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titler
     *
     * @access protected
     */
    protected function generateTITLER()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 .
            ':r>__GENERATETITLER__</' . CreateImage::NAMESPACEWORD1 .
            ':r>__GENERATETITLEP__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLEP__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titlerfonts
     *
     * @access protected
     * @param string $font
     */
    protected function generateTITLERFONTS($font = '')
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':latin typeface="' .
            $font . '" pitchFamily="34" charset="0"></' .
            CreateImage::NAMESPACEWORD1 . ':latin ><' .
            CreateImage::NAMESPACEWORD1 .
            ':cs typeface="' . $font . '" pitchFamily="34" charset="0"></' .
            CreateImage::NAMESPACEWORD1 . ':cs>';
        $this->_xmlChart = str_replace(
            '__GENERATETITLERPR__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titlerpr
     *
     * @access protected
     */
    protected function generateTITLERPR($lang = 'es-ES')
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':rPr lang="' .
            $lang . '">__GENERATETITLERPR__</' . CreateImage::NAMESPACEWORD1 .
            ':rPr>__GENERATETITLER__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titlet
     *
     * @access protected
     * @param string $nombre
     */
    protected function generateTITLET($nombre = '')
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':t>' .
            $nombre . '</' . CreateImage::NAMESPACEWORD1 .
            ':t>__GENERATETITLER__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:titletx
     *
     * @access protected
     */
    protected function generateTITLETX()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':tx>__GENERATETITLETX__</' . CreateChart::NAMESPACEWORD .
            ':tx>__GENERATETITLE__';
        $this->_xmlChart = str_replace(
            '__GENERATETITLE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:varyColors
     *
     * @access protected
     * @param string $val
     */
    protected function generateVARYCOLORS($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':varyColors val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':varyColors>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:view3D
     *
     * @access protected
     */
    protected function generateVIEW3D()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':view3D>__GENERATEVIEW3D__</' . CreateChart::NAMESPACEWORD .
            ':view3D>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:grouping
     *
     * @access protected
     * @param string $val
     */
    protected function generateGROUPING($val = 'stacked')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':grouping val="' . $val . '"></' . CreateChart::NAMESPACEWORD .
            ':grouping>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:ser
     *
     * @access protected
     */
    protected function generateSER()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':ser>__GENERATESER__</' . CreateChart::NAMESPACEWORD .
            ':ser>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:idx
     *
     * @access protected
     * @param string $val
     */
    protected function generateIDX($val = '0')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':idx val="' . $val . '"></' . CreateChart::NAMESPACEWORD .
            ':idx>__GENERATESER__';
        $this->_xmlChart = str_replace(
            '__GENERATESER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:order
     *
     * @access protected
     * @param string $val
     */
    protected function generateORDER($val = '0')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':order val="' . $val . '"></' . CreateChart::NAMESPACEWORD .
            ':order>__GENERATESER__';
        $this->_xml = str_replace(
            '__GENERATESER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:tx
     *
     * @access protected
     */
    protected function generateTX()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':tx>__GENERATETX__</' . CreateChart::NAMESPACEWORD .
            ':tx>__GENERATESER__';
        $this->_xmlChart = str_replace(
            '__GENERATESER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:strref
     *
     * @access protected
     */
    protected function generateSTRREF()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':strRef>__GENERATESTRREF__</' . CreateChart::NAMESPACEWORD .
            ':strRef>__GENERATETX__';
        $this->_xmlChart = str_replace(
            '__GENERATETX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:f
     *
     * @access protected
     * @param string $val
     */
    protected function generateF($val = 'Hoja1!$B$1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':f>' .
            $val . '</' . CreateChart::NAMESPACEWORD .
            ':f>__GENERATESTRREF__';
        $this->_xmlChart = str_replace(
            '__GENERATESTRREF__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:strcache
     *
     * @access protected
     */
    protected function generateSTRCACHE()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':strCache>__GENERATESTRCACHE__</' . CreateChart::NAMESPACEWORD .
            ':strCache>__GENERATESTRREF__';
        $this->_xmlChart = str_replace(
            '__GENERATESTRREF__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:ptcount
     *
     * @access protected
     * @param string $val
     */
    protected function generatePTCOUNT($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':ptCount val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':ptCount>__GENERATESTRCACHE__';
        $this->_xmlChart = str_replace(
            '__GENERATESTRCACHE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:pt
     *
     * @access protected
     * @param string $idx
     */
    protected function generatePT($idx = '0')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':pt idx="' .
            $idx . '">__GENERATEPT__</' . CreateChart::NAMESPACEWORD .
            ':pt>__GENERATESTRCACHE__';
        $this->_xmlChart = str_replace(
            '__GENERATESTRCACHE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:v
     *
     * @access protected
     * @param string $idx
     */
    protected function generateV($idx = 'Ventas')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':v>' .
            $idx . '</' . CreateChart::NAMESPACEWORD . ':v>';
        $this->_xmlChart = str_replace(
            '__GENERATEPT__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:cat
     *
     * @access protected
     */
    protected function generateCAT()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':cat>__GENERATETX__</' . CreateChart::NAMESPACEWORD .
            ':cat>__GENERATESER__';
        $this->_xmlChart = str_replace(
            '__GENERATESER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:val
     *
     * @access protected
     */
    protected function generateVAL()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':val>__GENERATETX__</' . CreateChart::NAMESPACEWORD .
            ':val>__GENERATESER__';
        $this->_xmlChart = str_replace(
            '__GENERATESER__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:numcache
     *
     * @access protected
     */
    protected function generateNUMCACHE()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':numCache>__GENERATESTRCACHE__</' .
            CreateChart::NAMESPACEWORD . ':numCache>__GENERATESTRREF__';
        $this->_xmlChart = str_replace(
            '__GENERATESTRREF__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:numRef
     *
     * @access protected
     */
    protected function generateNUMREF()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':numRef>__GENERATESTRREF__</' . CreateChart::NAMESPACEWORD .
            ':numRef>__GENERATETX__';
        $this->_xmlChart = str_replace(
            '__GENERATETX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:formatCode
     *
     * @access protected
     * @param string $val
     */
    protected function generateFORMATCODE($val = 'General')
    {
        $this->_xmlChart = str_replace(
            '__GENERATESTRCACHE__',
            '<' . CreateChart::NAMESPACEWORD . ':formatCode>' . $val .
            '</' . CreateChart::NAMESPACEWORD .
            ':formatCode>__GENERATESTRCACHE__',
            $this->_xmlChart
        );
    }

    /**
     * Generate w:legend
     *
     * @access protected
     */
    protected function generateLEGEND()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':legend>__GENERATELEGEND__</' .
            CreateChart::NAMESPACEWORD . ':legend>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__',
            $xml,
            $this->_xmlChart
        );
    }

    /**
     * Generate c:legendPos
     *
     * @access protected
     * @param string $val
     */
    protected function generateLEGENDPOS($val = 'r')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':legendPos val="' . 
             $val . '"></' . CreateChart::NAMESPACEWORD .
             ':legendPos>__GENERATELEGEND__';
        $this->_xmlChart = str_replace(
            '__GENERATELEGEND__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:layout
     *
     * @access protected
     * @param string $font
     */
    protected function generateLEGENDFONT($font = '')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':layout />' .
        	'<' . CreateChart::NAMESPACEWORD . ':txPr>' .
        	'<a:bodyPr /><a:lstStyle /><a:p><a:pPr><a:defRPr>' .
        	'<a:latin typeface="' . $font . '" pitchFamily="34" charset="0" />' .
        	'<a:cs typeface="' . $font . '" pitchFamily="34" charset="0" />' .
        	'</a:defRPr></a:pPr><a:endParaRPr lang="es-ES" /></a:p>' .
        	'</' . CreateChart::NAMESPACEWORD . ':txPr>';
        $this->_xmlChart = str_replace(
            '__GENERATELEGEND__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:overlay

     * @access protected
     * @param string $val
     */
    protected function generateLEGENDOVERLAY($val = 0)
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':overlay val="' . 
             $val . '" />__GENERATELEGEND__';
        $this->_xmlChart = str_replace(
            '__GENERATELEGEND__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:poltVisOnly
     *
     * @access protected
     * @param string $val
     */
    protected function generatePLOTVISONLY($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':plotVisOnly val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':plotVisOnly>__GENERATECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:externalData
     *
     * @access protected
     * @param string $val
     */
    protected function generateEXTERNALDATA($val = 'rId1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':externalData r:id="' . $val . '"></' .
            CreateChart::NAMESPACEWORD . ':externalData>';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:spPr
     *
     * @access protected
     */
    protected function generateSPPR()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':spPr>__GENERATESPPR__</' . CreateChart::NAMESPACEWORD .
            ':spPr>__GENERATECHARTSPACE__';
        $this->_xmlChart = str_replace(
            '__GENERATECHARTSPACE__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:ln
     *
     * @access protected
     */
    protected function generateLN()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':ln>__GENERATELN__</' .
            CreateImage::NAMESPACEWORD1 . ':ln>';
        $this->_xmlChart = str_replace(
            '__GENERATESPPR__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:noFill
     *
     * @access protected
     */
    protected function generateNOFILL()
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':noFill></' .
            CreateImage::NAMESPACEWORD1 . ':noFill>';
        $this->_xmlChart = str_replace(
            '__GENERATELN__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:overlap
     *
     * @access protected
     * @param string $val
     */
    protected function generateOVERLAP($val = '100')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':overlap val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':overlap>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:shape
     *
     * @access protected
     * @param string $val
     */
    protected function generateSHAPE($val = 'box')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':shape val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':shape>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:axid
     *
     * @access protected
     * @param string $val
     */
    protected function generateAXID($val = '59034624')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':axId val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':axId>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:firstSliceAng
     *
     * @access protected
     * @param string $val
     */
    protected function generateFIRSTSLICEANG($val = '0')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':firstSliceAng val="' . $val . '"></' .
            CreateChart::NAMESPACEWORD . ':firstSliceAng>';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:dLbls
     *
     * @access protected
     */
    protected function generateDLBLS()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':dLbls>__GENERATEDLBLS__</' . CreateChart::NAMESPACEWORD .
            ':dLbls>__GENERATETYPECHART__';
        $this->_xmlChart = str_replace(
            '__GENERATETYPECHART__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:showPercent
     *
     * @access protected
     * @param string $val
     */
    protected function generateSHOWPERCENT($val = '0')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':showPercent val="' . $val . '"></' .
            CreateChart::NAMESPACEWORD . ':showPercent>';
        $this->_xmlChart = str_replace(
            '__GENERATEDLBLS__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:chart
     *
     * @access protected
     */
    protected function generateDOCUMENTCHART()
    {
        $this->_xml = str_replace(
            '__GENERATEGRAPHICDATA__',
            '<' . CreateChart::NAMESPACEWORD .
            ':chart xmlns:c="http://schemas.openxmlformats.org/drawingml/' .
            '2006/chart" xmlns:r="http://schemas.openxmlformats.org/offic' .
            'eDocument/2006/relationships" r:id="rId' . $this->getRId() .
            '"></' . CreateChart::NAMESPACEWORD .
            ':chart>',
            $this->_xml
        );
    }

    /**
     * Generate w:catAx
     *
     * @access protected
     */
    protected function generateCATAX()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':catAx>__GENERATEAX__</' . CreateChart::NAMESPACEWORD .
            ':catAx>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }
    
    /**
     * Generate c:dTable
     *
     * @access protected
     */
    protected function generateDATATABLE()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':dTable>' . 
        	'<' . CreateChart::NAMESPACEWORD . ':showHorzBorder val="1"/>' .
        	'<' . CreateChart::NAMESPACEWORD . ':showVertBorder val="1"/>' .
        	'<' . CreateChart::NAMESPACEWORD . ':showOutline val="1"/>' .
        	'<' . CreateChart::NAMESPACEWORD . ':showKeys val="1"/>' .
        	'</' . CreateChart::NAMESPACEWORD . ':dTable>__GENERATEPLOTAREA__';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:valAx
     *
     * @access protected
     */
    protected function generateVALAX()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':valAx>__GENERATEAX__</' .
            CreateChart::NAMESPACEWORD .
            ':valAx>';
        $this->_xmlChart = str_replace(
            '__GENERATEPLOTAREA__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:axId
     *
     * @access protected
     * @param <type> $val
     */
    protected function generateAXAXID($val = '59034624')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':axId val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':axId>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:scaling
     *
     * @access protected
     */
    protected function generateSCALING()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD .
            ':scaling>__GENERATESCALING__</' . CreateChart::NAMESPACEWORD .
            ':scaling>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:orientation
     *
     * @access protected
     * @param string $val
     */
    protected function generateORIENTATION($val = 'minMax')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':orientation val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD . ':orientation>';
        $this->_xmlChart = str_replace(
            '__GENERATESCALING__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:axPos
     *
     * @access protected
     * @param string $val
     */
    protected function generateAXPOS($val = 'b')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':axPos val="' . $val .
            '"></' . CreateChart::NAMESPACEWORD . ':axPos>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }
    
    /**
     * Generate c:title
     *
     * @access protected
     * @param string $val
     */
    protected function generateAXLABEL($val = 'Axis title')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':title><c:tx><c:rich>' .
        	'__GENERATEBODYPR__<a:lstStyle/><a:p><a:pPr><a:defRPr/></a:pPr>' .
        	'<a:r><a:t>' . $val . '</a:t></a:r></a:p></c:rich></c:tx>' .
        	'</' . CreateChart::NAMESPACEWORD . ':title>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }
    
    /**
     * Generate a:bodyPr
     *
     * @access protected
     * @param string $val
     */
    protected function generateAXLABELDISP($val = 'horz', $rot = 0)
    {
        $xml = '<a:bodyPr rot="' . $rot . '" vert="' . $val . '"/>';
        $this->_xmlChart = str_replace(
            '__GENERATEBODYPR__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:tickLblPos
     *
     * @access protected
     * @param string $val
     */
    protected function generateTICKLBLPOS($val = 'nextTo')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':tickLblPos val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':tickLblPos>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:crossAx
     *
     * @access protected
     * @param string $val
     */
    protected function generateCROSSAX($val = '59040512')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':crossAx  val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':crossAx >__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:crosses
     *
     * @access protected
     * @param string $val
     */
    protected function generateCROSSES($val = 'autoZero')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':crosses val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':crosses>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:auto
     *
     * @access protected
     * @param string $val
     */
    protected function generateAUTO($val = '1')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':auto val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':auto>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:lblAlgn
     *
     * @access protected
     * @param string $val
     */
    protected function generateLBLALGN($val = 'ctr')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':lblAlgn val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':lblAlgn>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:lblOffset
     *
     * @access protected
     * @param string $val
     */
    protected function generateLBLOFFSET($val = '100')
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':lblOffset val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD . ':lblOffset>';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:majorGridlines
     *
     * @access protected
     */
    protected function generateMAJORGRIDLINES()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':majorGridlines></' .
            CreateChart::NAMESPACEWORD . ':majorGridlines>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate c:majorGridlines
     *
     * @access protected
     */
    protected function generateMINORGRIDLINES()
    {
        $xml = '<' . CreateChart::NAMESPACEWORD . ':minorGridlines></' .
            CreateChart::NAMESPACEWORD . ':minorGridlines>__GENERATEAX__';
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', $xml, $this->_xmlChart
        );
    }

    /**
     * Generate w:numFmt
     *
     * @access protected
     * @param string $formatCode
     * @param string $sourceLinked
     */
    protected function generateNUMFMT($formatCode='General',
                                      $sourceLinked = '1')
    {
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', '<' .
            CreateChart::NAMESPACEWORD .
            ':numFmt formatCode="' . $formatCode .
            '" sourceLinked="' . $sourceLinked . '"></' .
            CreateChart::NAMESPACEWORD . ':numFmt>__GENERATEAX__',
            $this->_xmlChart
        );
    }

    /**
     * Generate w:latin
     *
     * @access protected
     * @param string $font
     */
    protected function generateRFONTS($font)
    {
        $this->_xmlChart = str_replace(
            '__GENERATEDEFRPR__', '<' .
            CreateImage::NAMESPACEWORD1 . ':latin typeface="' .
            $font . '" pitchFamily="34" charset="0"></' .
            CreateImage::NAMESPACEWORD1 . ':latin ><' .
            CreateImage::NAMESPACEWORD1 . ':cs typeface="' .
            $font . '" pitchFamily="34" charset="0"></' .
            CreateImage::NAMESPACEWORD1 . ':cs>', $this->_xmlChart
        );
    }

    /**
     * Generate w:crossBetween
     *
     * @access protected
     * @param string $val
     */
    protected function generateCROSSBETWEEN($val = 'between')
    {
        $this->_xmlChart = str_replace(
            '__GENERATEAX__', '<' .
            CreateChart::NAMESPACEWORD . ':crossBetween val="' .
            $val . '"></' . CreateChart::NAMESPACEWORD .
            ':crossBetween>',
            $this->_xmlChart
        );
    }

    /**
     * Create chart xml
     *
     * @access protected
     * @param array $args[0]
     */
    protected function createCHARTXML()
    {
        $this->_xmlChart = '';
        $args = func_get_args();
        $type = $args[0][1]['type'];
        $this->setRId($args[0][0]);
        $this->generateCHARTSPACE();
        $this->generateDATE1904(1);
        $this->generateLANG();
        $color = 2;
        if (!empty($args[0][1]['color'])) $color = $args[0][1]['color'];
        $this->generateSTYLE($color);
        $this->generateCHART();
        if (!empty($args[0][1]['title'])) {
            $this->generateTITLE();
            $title = $args[0][1]['title'];
            $this->generateTITLETX();
            $this->generateRICH();
            $this->generateBODYPR();
            $this->generateLSTSTYLE();
            $this->generateTITLEP();
            $this->generateTITLEPPR();
            $this->generateDEFRPR();
            if (!empty($args[0][1]['font'])) {
                $this->generateRFONTS($args[0][1]['font']);
            }
            $this->generateTITLER();
            $this->generateTITLERPR();
            if (!empty($args[0][1]['font'])) {
                $this->generateTITLERFONTS($args[0][1]['font']);
            }
            $this->generateTITLET($title);
            $this->generateTITLELAYOUT();
        } else {
            $this->generateAUTOTITLEDELETED();
            $title = '';
        }
        if (strpos($args[0][1]['type'], '3D') !== false) {
            $this->generateVIEW3D();
            $cornerX = 30;
            $cornerY = 30;
            $cornerP = 30;
            if (!empty($args[0][1]['cornerX'])) {
                $cornerX = $args[0][1]['cornerX'];
            }
            if (!empty($args[0][1]['cornerY'])) {
                $cornerY = $args[0][1]['cornerY'];
            }
            if (!empty($args[0][1]['cornerP'])) {
                $cornerP = $args[0][1]['cornerP'];
            }
            $this->generateROTX($cornerX);
            $this->generateROTY($cornerY);
            $this->generatePERSPECTIVE($cornerP);
        }
        if (empty($args[0][1]['data'])) {
            exit('You haven`t added data');
        }
        $dats = $args[0][1]['data'];
        $this->generatePLOTAREA();
        if (strpos($type, 'pie') !== false) {
            if (strpos($type, '3D') !== false) {
                $this->generatePIE3DCHART();
            } else {
                $this->generatePIECHART();
            }
            $this->generateVARYCOLORS();
            if (isset($dats['legend'])) { 
                $legends = $dats['legend'];
            } else {
                $legends = array($title);
            }
            $sizeDats = count($dats);
        } elseif (
            (strpos($type, 'bar') !== false) ||
            (strpos($type, 'col') !== false)
            ) {
            if (strpos($type, '3D') !== false) {
                $this->generateBAR3DCHART();
            } else {
                $this->generateBARCHART();
            }
            $typeBar = 'bar';
            if (strpos($type, 'col') !== false) {
                $typeBar = 'col';
            }
            $this->generateBARDIR($typeBar);
            $groupBar = 'clustered';
            if (!empty($args[0][1]['groupBar'])) {
                $groupBar = $args[0][1]['groupBar'];
            }
            $this->generateGROUPING($groupBar);
            if (isset($dats['legend'])) {
                $legends = $dats['legend'];
            } else {
                echo('You must write a legend');
                return false;
            }
            $sizeDats = count($dats) - 1;
        } elseif (strpos($type, 'line') !== false) {
            if (strpos($type, '3D') !== false) {
            	$this->generateLINE3DCHART();
            } else {
            	$this->generateLINECHART();
            }
            $this->generateGROUPING('standard');
            if (isset($dats['legend'])) {
            	$legends = $dats['legend'];
            }
            else {
                echo('You must write a legend');
                return false;
            }
            $sizeDats = count($dats) - 1;
        } elseif (strpos($type, 'area') !== false) {
            if (strpos($type, '3D') !== false) {
            	$this->generateAREA3DCHART();
            } else {
            	$this->generateAREACHART();
            }
            $groupBar = 'standard';
            if (!empty($args[0][1]['groupBar'])) {
                $groupBar = $args[0][1]['groupBar'];
            }
            $this->generateGROUPING($groupBar);
            if (isset($dats['legend'])) {
                $legends = $dats['legend'];
            } else {
                echo('You must write a legend');
                return false;
            }
            $sizeDats = count($dats) - 1;
        } else {
            echo('The chart is not available');
            return false;
        }


        $chart = 'A';
        for ($i = 0; $i < count($legends); $i++) {
            $this->generateSER();
            $this->generateIDX($i);
            $this->generateORDER($i);
            $chart++;

            $this->generateTX();
            $this->generateSTRREF();
            $this->generateF(' Hoja1!$' . $chart . '$1');
            $this->generateSTRCACHE();
            $this->generatePTCOUNT();
            $this->generatePT();
            if (strpos($args[0][1]['type'], 'pie') !== false) {
                $this->generateV($title);
            } else {
                $this->generateV($legends[$i]);
            }

            $this->cleanTemplate2();
            $this->generateCAT();
            $this->generateSTRREF();
            $this->generateF('Hoja1!$A$2:$A$' . ($sizeDats + 1));
            $this->generateSTRCACHE();
            $this->generatePTCOUNT($sizeDats);

            $num = 0;
            foreach ($dats as $legend => $dat) {
                if ($legend == 'legend') continue;
                $this->generatePT($num);
                $this->generateV($legend);
                $num++;
            }
            $this->cleanTemplate2();
            $this->generateVAL();
            $this->generateNUMREF();
            $this->generateF('Hoja1!$' . $chart . '$2:$B$' . ($sizeDats + 1));
            $this->generateNUMCACHE();
            $this->generateFORMATCODE();
            $this->generatePTCOUNT($sizeDats);
            $num = 0;
            foreach ($dats as $legend => $dat) {
                if ($legend == 'legend') continue;
                $this->generatePT($num);
                $this->generateV($dat[$i]);
                $num++;
            }
            $this->cleanTemplate3();
        }
        if (strpos($args[0][1]['type'], 'pie') !== false) {
            $this->generateDLBLS();
            $showPercent = 0;
            if (!empty($args[0][1]['showPercent'])) $showPercent = 1;
            $this->generateSHOWPERCENT($showPercent);
            $this->generateFIRSTSLICEANG();
        } else {
            $shape = 'box';
            if (strpos($type, 'Cylinder') !== false) {
            	$shape = 'cylinder';
            } elseif (strpos($type, 'Cone') !== false) {
            	$shape = 'cone';
            } elseif (strpos($type, 'Pyramid') !== false) {
            	$shape = 'pyramid';
            }
            $this->generateSHAPE($shape);
            if ($args[0][1]['groupBar'] == 'stacked') {
	            $this->generateOVERLAP();
            }
            $this->generateAXID();
            $this->generateAXID(59040512);
            $this->generateCATAX();
            $this->generateAXAXID(59034624);
            $this->generateSCALING();
            $this->generateORIENTATION();
            $this->generateAXPOS();
            if (!empty($args[0][1]['haxlabel'])) {
	            $this->generateAXLABEL($args[0][1]['haxlabel']);
            	$vert = 'horz';
            	$rot = 0;
            	if ($args[0][1]['haxlabel_display'] == 'vertical') {
            		$vert = 'wordArtVert';
            	}
            	if ($args[0][1]['haxlabel_display'] == 'rotated') {
            		$rot = '-5400000';
            	}
	            $this->generateAXLABELDISP($vert, $rot);
            }
            $this->generateTICKLBLPOS();
            if (array_key_exists('vgrid', $args[0][1])) {
            	switch ($args[0][1]['vgrid']) {
            		case 1:
		            	$this->generateMAJORGRIDLINES();
            		break;
            		case 2:
		            	$this->generateMINORGRIDLINES();
            		break;
            		case 3:
		            	$this->generateMAJORGRIDLINES();
		            	$this->generateMINORGRIDLINES();
            		break;
            		default:
            		break;
            	}
            }
            $this->generateCROSSAX();
            $this->generateCROSSES();
            $this->generateAUTO();
            $this->generateLBLALGN();
            $this->generateLBLOFFSET();
            if (!empty($args[0][1]['showtable'])) {
            	$this->generateDATATABLE();
            }
            $this->generateVALAX();
            $this->generateAXAXID(59040512);
            $this->generateSCALING();
            $this->generateORIENTATION();
            $this->generateAXPOS('l');
            if (array_key_exists('hgrid', $args[0][1])) {
            	switch ($args[0][1]['hgrid']) {
            		case 1:
		            	$this->generateMAJORGRIDLINES();
            		break;
            		case 2:
		            	$this->generateMINORGRIDLINES();
            		break;
            		case 3:
		            	$this->generateMAJORGRIDLINES();
		            	$this->generateMINORGRIDLINES();
            		break;
            		default:
            		break;
            	}
            } else {
            	$this->generateMAJORGRIDLINES();
            }
            if (!empty($args[0][1]['vaxlabel'])) {
	            $this->generateAXLABEL($args[0][1]['vaxlabel']);
            	$vert = 'horz';
            	$rot = 0;
            	if ($args[0][1]['vaxlabel_display'] == 'vertical') {
            		$vert = 'wordArtVert';
            	}
            	if ($args[0][1]['vaxlabel_display'] == 'rotated') {
            		$rot = '-5400000';
            	}
	            $this->generateAXLABELDISP($vert, $rot);
            }
            $this->generateNUMFMT();
            $this->generateTICKLBLPOS();
            if (!empty($args[0][1]['font'])) {
                $this->generateRFONTS2($args[0][1]['font']);
            }
            $this->generateCROSSAX(59034624);
            $this->generateCROSSES();
            $this->generateCROSSBETWEEN();
        }
        
        if ($args[0][1]['legendpos'] != 'none') {
	        $this->generateLEGEND();
	        if (!empty($args[0][1]['legendpos'])) {
	        	$this->generateLEGENDPOS($args[0][1]['legendpos']);
	        }
	        if (!empty($args[0][1]['legendoverlay'])) {
	        	$this->generateLEGENDOVERLAY($args[0][1]['legendoverlay']);
	        }
	        if (!empty($args[0][1]['font'])) {
	            $this->generateLEGENDFONT($args[0][1]['font']);
	        }
        }
        
        $this->generatePLOTVISONLY();
        if (empty($args[0][1]['border']) 
        	&& strpos($args[0][1]['type'], 'pieChart') === false) {
//        if (strpos($args[0][1]['type'], 'pieChart') === false) {
            $this->generateSPPR();
            $this->generateLN();
            $this->generateNOFILL();
        }

        $this->generateEXTERNALDATA();
        $this->cleanTemplateDocument();
        return true;
    }

    /**
     * Create document xml
     *
     * @access protected
     * @param array $args[0]
     */
    protected function createDOCUMENTXML()
    {
        $args = func_get_args();
        if (!isset($args[0][1]['textWrap']) ||
            $args[0][1]['textWrap'] < 0 ||
            $args[0][1]['textWrap'] > 5)
            $ajusteTexto = 0;
        else {
            $ajusteTexto = $args[0][1]['textWrap'];
        }
        if (isset($args[0][1]['sizeX']))
            $sizeX = $args[0][1]['sizeX'] * CreateImage::CONSTWORD;
        else {
            $sizeX = 2993296;
        }
        if (isset($args[0][1]['sizeY']))
            $sizeY = $args[0][1]['sizeY'] * CreateImage::CONSTWORD;
        else {
            $sizeY = 2238233;
        }


        $this->_xml = '';
        $this->generateP();
        if (!empty($args[0][1]['jc'])) {
            $this->generatePPR();
            $this->generateJC($args[0][1]['jc']);
        }
        $this->generateR();
        $this->generateRPR();
        $this->generateNOPROOF();
        $this->generateDRAWING();
        if ($ajusteTexto == 0) {
            $this->generateINLINE();
        } else {
            if ($ajusteTexto == 3) $this->generateANCHOR(1);
            else $this->generateANCHOR();
            $this->generateSIMPLEPOS();
            $this->generatePOSITIONH();
            $align = '';
            if (!empty($args[0][1]['jc']) && $args[0][1]['jc'] == 'center') {
                $sizePageX = 15 * CreateImage::CONSTWORD;
                $align = round(($sizePageX - $sizeX) / 2);
            } elseif (!empty($args[0][1]['jc']) &&
                      $args[0][1]['jc'] == 'right') {
                $sizePageX = 15 * CreateImage::CONSTWORD;
                $align = round($sizePageX - $sizeX);
            }
            if ($align != '') {
                $this->generatePOSOFFSET($align);
            } else {
                $this->generatePOSOFFSET(15240);
            }
            $this->generatePOSITIONV();
            $this->generatePOSOFFSET("0");
        }

        $this->generateEXTENT($sizeX, $sizeY);
        $this->generateEFFECTEXTENT();
        switch ($ajusteTexto) {
            case 1:
                $this->generateWRAPSQUARE();
                break;
            case 2:
            case 3:
                $this->generateWRAPNONE();
                break;
            case 4:
                $this->generateWRAPTOPANDBOTTOM();
                break;
            case 5:
                $this->generateWRAPTHROUGH();
                $this->generateWRAPPOLYGON();
                $this->generateSTART();
                $this->generateLINETO();
                $this->generateLINETO('21540', '21342');
                $this->generateLINETO('21540', '0');
                $this->generateLINETO('-198', '0');
                break;
            default:
                break;
        }
        $this->generateDOCPR();
        $this->generateCNVGRAPHICFRAMEPR();
        $this->generateGRAPHIC();
        $this->generateGRAPHICDATA(
            'http://schemas.openxmlformats.org/'.
            'drawingml/2006/chart'
        );
        $this->generateDOCUMENTCHART();
        $this->cleanTemplate();
        return true;
    }

    /**
     * Create embedded document xml
     *
     * @access protected
     * @return array $args
     */
    protected function createEmbeddedDocumentXml()
    {
        $args = func_get_args();
        if ($this->_textalign != '' &&
            ($this->_textalign < 0 || $this->_textalign > 5)
        ) {
            $ajusteTexto = 0;
        } else {
            $ajusteTexto = $this->_textalign;
        }
        if ($this->_sizeX != '') {
            $sizeX = $this->_sizeX * CreateImage::CONSTWORD;
        } else {
            $sizeX = 2993296;
        }
        if ($this->_sizeY != '') {
            $sizeY = $this->_sizeY * CreateImage::CONSTWORD;
        } else {
            $sizeY = 2238233;
        }

        $this->_xml = '';
        $this->generateQUITAR();
        $this->generateRPR();
        $this->generateNOPROOF();
        $this->generateDRAWING();
        if ($ajusteTexto == 0) {
            $this->generateINLINE();
        } else {
            if ($ajusteTexto == 3) $this->generateANCHOR(1);
            else $this->generateANCHOR();
            $this->generateSIMPLEPOS();
            $this->generatePOSITIONH();
            $align = '';
            if ($this->_jc != '' && $this->_jc == 'center') {
                $sizePageX = 15 * CreateImage::CONSTWORD;
                $align = round(($sizePageX - $sizeX) / 2);
            } elseif ($this->_jc != '' && $this->_jc == 'right') {
                $sizePageX = 15 * CreateImage::CONSTWORD;
                $align = round($sizePageX - $sizeX);
            }
            if ($align != '') {
                $this->generatePOSOFFSET($align);
            }else $this->generatePOSOFFSET(15240);
            $this->generatePOSITIONV();
            $this->generatePOSOFFSET("0");
        }

        $this->generateEXTENT($sizeX, $sizeY);
        $this->generateEFFECTEXTENT();
        switch ($ajusteTexto) {
            case 1:
                $this->generateWRAPSQUARE();
                break;
            case 2:
            case 3:
                $this->generateWRAPNONE();
                break;
            case 4:
                $this->generateWRAPTOPANDBOTTOM();
                break;
            case 5:
                $this->generateWRAPTHROUGH();
                $this->generateWRAPPOLYGON();
                $this->generateSTART();
                $this->generateLINETO();
                $this->generateLINETO('21540', '21342');
                $this->generateLINETO('21540', '0');
                $this->generateLINETO('-198', '0');
                break;
            default:
                break;
        }
        $this->generateDOCPR();
        $this->generateCNVGRAPHICFRAMEPR();
        $this->generateGRAPHIC();
        $this->generateGRAPHICDATA(
            'http://schemas.openxmlformats.org/'.
            'drawingml/2006/chart'
        );
        $this->generateDOCUMENTCHART();
        $this->cleanTemplate();
        return true;
    }

    /**
     * Clean tags in template document
     *
     * @access protected
     */
    private function cleanTemplateDocument()
    {
        $this->_xmlChart = preg_replace('/__[A-Z]+__/', '', $this->_xmlChart);
    }

    /**
     * Clean tags in template document
     *
     * @access protected
     */
    private function cleanTemplate2()
    {
        $this->_xmlChart = preg_replace(
            array(
                '/__GENERATE[A-B,D-O,Q-R,U-Z][A-Z]+__/',
                '/__GENERATES[A-D,F-Z][A-Z]+__/', '/__GENERATETX__/'),
            '',
            $this->_xmlChart
        );
    }

    /**
     * Clean tags in template document
     *
     * @access protected
     */
    private function cleanTemplate3()
    {
        $this->_xmlChart = preg_replace(
            array(
                '/__GENERATE[A-B,D-O,Q-S,U-Z][A-Z]+__/',
                '/__GENERATES[A-D,F-Z][A-Z]+__/',
                '/__GENERATETX__/'
                ),
            '',
            $this->_xmlChart
        );
    }

    /**
     * Generate c:txPr
     *
     * @access protected
     * @param string $font
     */
    private function generateRFONTS2($font)
    {
        $this->_xmlChart = str_replace(
            '__GENERATEAX__',
            '<c:txPr><a:bodyPr /><a:lstStyle /><a:p>' .
            '<a:pPr><a:defRPr><a:latin typeface="' .
            $font . '" pitchFamily="34" charset="0" /><a:cs typeface="' .
            $font . '" pitchFamily="34" charset="0" /></a:defRPr>' .
            '</a:pPr><a:endParaRPr lang="es-ES" /></a:p></c:txPr>' .
            '__GENERATEAX__', $this->_xmlChart
        );
    }

}
