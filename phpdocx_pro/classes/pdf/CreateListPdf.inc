<?php

/**
 * Create lists
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    1.0
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.0
 */
include_once dirname(__FILE__) . '/CreateElementPdf.inc';

/**
 * Create lists
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    1.0
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 1.0
 */
class CreateListPdf extends CreateElementPdf {
    /**
     *
     * @var mixed
     * @access public
     */
    public $list;
    /**
     *
     * @var array
     * @access public
     */
    public $val;
    /**
     *
     * @var string
     * @access public
     */
    public $font;
    /**
     *
     * @var array
     * @access public
     */
    public $data;
    /**
     * @access private
     * @var CreateList
     * @static
     */
    public $default_list_style_type;
    /**
     * @access private
     * @var default_list_style_type
     */
    private static $_instance = NULL;

    /**
     * Construct
     *
     * @access public
     */
    public function __construct() {
        $this->xml = '';
        //$this->default_list_style_type = array('none','disc','circle','square','decimal','lower-alpha','lower-roman','upper-alpha','upper-roman');
        $this->default_list_style_type = array('disc','circle','square','lower-roman');
    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct() {

    }

    /**
     *
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_xml;
    }

    /**
     *
     * @access public
     * @return CreateList
     * @static
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateListPdf();
        }
        return self::$_instance;
    }

    /**
     * Create list
     *
     * @access public
     * @param array args[0]
     * @param array args[1]
     */
    private function generateList($datos = array(), $aParameters = array()){
        $xml = $this->generateUl('');
        if(!empty($aParameters['val']) && is_numeric($aParameters['val'])){//
            $xml = $this->generateStyleUl($xml);
            $xml = $this->generateNewStyleUl($xml, 'list-style-type: ' . $this->default_list_style_type[$aParameters['val']]);
        }
        foreach($datos as $ind => $val){
            $xml = $this->generateInnerUl($xml);
            if(!empty($aParameters['font'])){
                $xml = $this->generateStyleLi($xml);
                $xml = $this->generateNewStyleLi($xml, 'font-family: ' . $aParameters['font']);
            }
            //var_dump($val);
            if(is_array($val)){
                echo '->>>>array';
                $xml = $this->generateTextLi($xml, $this->generateList($val));
            }else{
                if ($val instanceof CreateLinkPdf) {
                    echo '->>>>CreateLinkPdf';
                    $val->createEmbeddedLink(
                        $val->getTitle(),
                        $val->getLink()
                    );
                    $xml = $this->generateTextLi($xml, (string) $val);
                } elseif ($val instanceof CreateImagePdf) {
                    echo '->>>>CreateImagePdf';
                    $val->createEmbeddedImage('List');
                    $xml = $this->generateTextLi($xml, (string) $val);
                } elseif ($val instanceof CreateGraphicPdf || $val instanceof CreateChartPdf) {
                    echo '->>>>CreateGraphicPdf';
                    $tempName = $val->createEmbeddedChart();
                    //echo 'aaaaaaaaa';
                    $newName = getcwd() . '/tmp/fotoaux'. $this->generateRandom() . '.svg';
                    rename($tempName, $newName);
                    $img = CreateImagePdf::getInstance();
                    $img->createImage(array('name' => $newName));
                    $xml = $this->generateTextLi($xml, (string) $img);
                } elseif ($val instanceof CreateTextBoxPdf) {
                    echo '->>>>CreateTextBoxPdf';
                    $val->createEmbeddedTextBox();
                    $xml = $this->generateTextLi($xml, (string) $val);
                } elseif ($val instanceof CreateTextPdf) {
                    echo '->>>>CreateTextPdf';
                    $val->createEmbeddedText($val);
                    $xml = $this->generateTextLi($xml, (string) $val);
                }else{
                    echo '->>>>elseeeeeeeeeeeeeeeeeeeee';
                    $xml = $this->generateTextLi($xml, $val);
                }
            }
        }
        return $this->cleanTemplate($xml);
    }

    public function createList() {
        $args = func_get_args();
        $this->_xml = $this->generateList($args[0], $args[1]);
    }

    /**
     * Create embedded list
     *
     * @access public
     */
    public function createEmbeddedList()
    {
        $this->_xml = $this->generateList($this->data, array('val' => $this->val, 'font' => $this->font));        
    }

    /**
     * Init list
     *
     * @access public
     * @param array $args[0]
     */
    public function initList()
    {
        $args = func_get_args();
        $this->val = $args[0][1]['val'];
        $this->font = $args[0][1]['font'];
        $this->data = $args[0][0];
    }


    protected function generateUl($xml) {
        if(!empty($xml))
            return str_replace('__LI__', '<ul __STYLES_UL__>__UL__</ul>', $xml);
        else
            return '<ul __STYLES_UL__>__UL__</ul>';
    }

    protected function generateStyleUl($xml) {
        return str_replace('__STYLES_UL__', ' style="__ULSTYLE__"', $xml);
    }

    protected function generateNewStyleUl($xml, $style) {
        return str_replace('__ULSTYLE__', $style . ';__ULSTYLE__', $xml);
    }

    protected function generateInnerUl($xml) {
        //$xml = str_replace('__UL__', '<li __STYLES_LI__>__LI__</li>__UL__', str_replace('__STYLES_UL__', '', str_replace('__ULSTYLE__', '', $xml)));
        return str_replace('__UL__', '<li __STYLES_LI__>__LI__</li>__UL__', $xml);
    }

    protected function generateStyleLi($xml) {
        return  str_replace('__STYLES_LI__', ' style="__LI_STYLE__"', $xml);
    }

    protected function generateNewStyleLi($xml, $style) {
        return str_replace('__LI_STYLE__', $style . ';__LI_STYLE__', $xml);
    }

    protected function generateTextLi($xml, $text) {
        //$xml = str_replace('__LI__', $text, str_replace('__STYLES_LI__', '', str_replace('__LI_STYLE__', '', $xml)));
        return str_replace('__LI__', $text, $xml);
    }
    protected function cleanTemplate($xml)
    {
        return preg_replace('/__[A-Z_]+__/', '', $xml);
    }
}