<?php

/**
 * Create image graphics (charts)
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.2
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 2.2
 */
class CreateGraphicPdf
{
    protected $_xmlChart;

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
     * @access private
     * @var string
     */
    private $_name;

    /**
     * @access private
     * @var string
     */
    private $_font;
    /**
     *
     * @access private
     * @var CreateDocx
     */
    private $_docx;
    
    /**
     * @access private
     * @var CreateGraphic
     * @static
     */
    private static $_instance = null;
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        $this->_name = '';
        $this->_color = '';
        $this->_cornerX = '';
        $this->_data = '';
        $this->_font = '';
        $this->_sizeX = '';
        $this->_sizeY = '';
        $this->_title = '';
        $this->_type = '';
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
     * @return CreateGraphic
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateGraphicPdf();
        }
        return self::$_instance;
    }

    public function setName($name){
        $this->_name = $name;        
    }
    /**
     * Create graphic image
     *
     * @access public
     * @param array $args[1]
     * @param array $args[1]
     * @return boolean
     */
    public function createGraphic()
    {
    	$args = func_get_args();
    	$tempFile = tempnam($args[0], 'docXGraph');
        $data = $args[1]['data'];
        $font = dirname(__FILE__) .
        		'/../../lib/zetacomponents/Graph/docs/tutorial/tutorial_font.ttf';
        $sizeX = $args[1]['sizeX'] ? $args[1]['sizeX'] : 320;
        $sizeY = $args[1]['sizeY'] ? $args[1]['sizeY'] : 300;
        $type = $args[1]['type'];
        $title = $args[1]['title'];

        $color = $args[1]['color'];
        switch ($color) {
        	case 1:
        		$palette = 'ezcGraphPaletteBlack';
        	break;
        	case 2:
        		$palette = 'ezcGraphPaletteTango';
        	break;
        	case 3:
        		$palette = 'ezcGraphPaletteEzBlue';
        	break;
        	case 4:
        		$palette = 'ezcGraphPaletteEzRed';
        	break;
        	case 5:
        		$palette = 'ezcGraphPaletteEzGreen';
        	break;
        	default:
	        	$palette = 'ezcGraphPaletteTango';
        	break;
        }

        switch ($type) {
        	case 'barChart':
				$graph = new ezcGraphHorizontalBarChart();
				$graph->palette = new $palette();
				foreach ( $data as $language => $value )
				{
					$graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphHorizontalRenderer();
        	break;

        	case 'bar3DChart':
        		$graph = new ezcGraphHorizontalBarChart();
				$graph->palette = new $palette();
				foreach ( $data as $language => $value )
				{
				    $graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphRenderer3d();
				$graph->renderer->options->legendSymbolGleam = .5;
				$graph->renderer->options->barChartGleam = .5;

        	break;

        	case 'colChart':
				$graph = new ezcGraphBarChart();
				$graph->palette = new $palette();
				foreach ( $data as $language => $value )
				{
					$graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

        	break;

        	case 'col3DChart':
        		$graph = new ezcGraphBarChart();
				$graph->palette = new $palette();
				foreach ( $data as $language => $value )
				{
				    $graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphRenderer3d();
				$graph->renderer->options->legendSymbolGleam = .5;
				$graph->renderer->options->barChartGleam = .5;

        	break;

        	case 'pieChart':
				$graph = new ezcGraphPieChart();
				$graph->legend = false;
				$graph->palette = new $palette();
				$graph->data['Access statistics'] =
							new ezcGraphArrayDataSet($data);
        	break;

        	case 'pie3DChart':
        		$graph = new ezcGraphPieChart();
				$graph->options->label = '%2$d (%3$.1f%%)';

        		$graph->palette = new $palette();
				$graph->data['Access statistics'] =
							new ezcGraphArrayDataSet( $data );

				$graph->renderer = new ezcGraphRenderer3d();

				$graph->renderer->options->moveOut = .2;

				$graph->renderer->options->pieChartOffset = 63;

				$graph->renderer->options->pieChartSymbolColor = '#FF0000';

				$graph->renderer->options->pieChartHeight = 20;
				if (isset($args[1]['cornerX'])
					&& is_numeric($args[1]['cornerX'])
				){
					$graph->renderer->options->pieChartRotation =
						$args[1]['cornerX'] / 100;
				} else {
					$graph->renderer->options->pieChartRotation = .6;
				}
			break;

        	default:
        		return false;
        	break;
        }

		$graph->title = $title;
                $graph->options->font = $font;
		$graph->render( $sizeX, $sizeY, $tempFile.'.svg');
                return $tempFile.'.svg';
    }

    /**
     * Create graphic image
     *
     * @access public
     * @param array $args[1]
     * @param array $args[1]
     * @return boolean
     */
    public function createEmbeddedGraphic()
    {

    	$tempFile = tempnam($this->_name, 'docXGraph');
        //var_dump($tempFile);
        //$data = $args[1]['data'];
        $font = dirname(__FILE__) . '/../../lib/zetacomponents/Graph/docs/tutorial/tutorial_font.ttf';
        //var_dump($font);
        $sizeX = $this->_sizeX ? $this->_sizeX : 320;
        $sizeY = $this->_sizeY ? $this->_sizeY : 300;
        //$color = $args[1]['color'];
        switch ($this->_color) {
        	case 1:
        		$palette = 'ezcGraphPaletteBlack';
        	break;
        	case 2:
        		$palette = 'ezcGraphPaletteTango';
        	break;
        	case 3:
        		$palette = 'ezcGraphPaletteEzBlue';
        	break;
        	case 4:
        		$palette = 'ezcGraphPaletteEzRed';
        	break;
        	case 5:
        		$palette = 'ezcGraphPaletteEzGreen';
        	break;
        	default:
	        	$palette = 'ezcGraphPaletteTango';
        	break;
        }

        switch ($this->_type) {
        	case 'barChart':
				$graph = new ezcGraphHorizontalBarChart();
				$graph->palette = new $palette();
				foreach ( $this->_data as $language => $value )
				{
					$graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphHorizontalRenderer();
        	break;

        	case 'bar3DChart':
        		$graph = new ezcGraphHorizontalBarChart();
				$graph->palette = new $palette();
				foreach ( $this->_data as $language => $value )
				{
				    $graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphRenderer3d();
				$graph->renderer->options->legendSymbolGleam = .5;
				$graph->renderer->options->barChartGleam = .5;

        	break;

        	case 'colChart':
				$graph = new ezcGraphBarChart();
				$graph->palette = new $palette();
				foreach ( $this->_data as $language => $value )
				{
					$graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

        	break;

        	case 'col3DChart':
        		$graph = new ezcGraphBarChart();
				$graph->palette = new $palette();
				foreach ( $this->_data as $language => $value )
				{
				    $graph->data[$language] = new ezcGraphArrayDataSet($value);
				}

				$graph->renderer = new ezcGraphRenderer3d();
				$graph->renderer->options->legendSymbolGleam = .5;
				$graph->renderer->options->barChartGleam = .5;

        	break;

        	case 'pieChart':
				$graph = new ezcGraphPieChart();
				$graph->legend = false;
				$graph->palette = new $palette();
				$graph->data['Access statistics'] =
							new ezcGraphArrayDataSet($this->_data);
        	break;

        	case 'pie3DChart':
        		$graph = new ezcGraphPieChart();
				$graph->options->label = '%2$d (%3$.1f%%)';

        		$graph->palette = new $palette();
				$graph->data['Access statistics'] =
							new ezcGraphArrayDataSet( $this->_data );

				$graph->renderer = new ezcGraphRenderer3d();

				$graph->renderer->options->moveOut = .2;

				$graph->renderer->options->pieChartOffset = 63;

				$graph->renderer->options->pieChartSymbolColor = '#FF0000';

				$graph->renderer->options->pieChartHeight = 20;
				if (isset($args[1]['cornerX'])
					&& is_numeric($args[1]['cornerX'])
				){
					$graph->renderer->options->pieChartRotation =
						$args[1]['cornerX'] / 100;
				} else {
					$graph->renderer->options->pieChartRotation = .6;
				}
			break;

        	default:
        		return false;
        	break;
        }
		$graph->title = $title;
                $graph->options->font = $font;
                $graph->render( $sizeX, $sizeY, $tempFile.'.svg');
                return $tempFile.'.svg';
    }



    /**
     * Init image
     *
     * @access public
     * @param array $args[0]
     */
    public function initGraphic()
    {
        $args = func_get_args();
        $this->_textalign = $args[0]['textWrap'];
        $this->_jc = $args[0]['jc'];
        $this->_sizeX = $args[0]['sizeX'];
        $this->_sizeY = $args[0]['sizeY'];
        $this->_type = $args[0]['type'];
        $this->_showPercent = $args[0]['showPercent'];
        $this->_data = $args[0]['data'];
        $this->_cornerX = $args[0]['cornerX'];
        $this->_cornerY = $args[0]['cornerY'];
        $this->_cornerP = $args[0]['cornerP'];
        $this->_color = $args[0]['color'];
        $this->_groupBar = $args[0]['groupBar'];
        $this->_title = $args[0]['title'];
        $this->_font = $args[0]['font'];
    }
}
