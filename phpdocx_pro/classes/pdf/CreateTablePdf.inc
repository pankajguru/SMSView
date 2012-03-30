<?php

/**
 * Create tables
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
 * Create tables
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
class CreateTablePdf extends CreateElementPdf
{
    /**
     * @access private
     * @var CreateTable
     * @static
     */
    private static $_instance = NULL;

    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
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
        $this->cleanTemplate();
        return $this->_xml;
    }

    /**
     *
     * @access public
     * @return CreateTable
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateTablePdf();
        }
        return self::$_instance;
    }

    /**
     * Create table
     *
     * @access public
     * @param array args[0]
     */
    public function createTable()
    {
        $this->_xml = '';
        $args = func_get_args();

        if (is_array($args[0])) {
            $this->generateTable();
            if (!empty($args[1]['border'])) {
                $this->generateAttrTable('border="1"');
            }
            if (!empty($args[1]['border_spacing'])) {
                //$this->generateAttrTable('cellspacing="10"');
                $this->generateAttrTable('cellpadding="10"');
            }
            //$this->generateAttrTable('BGCOLOR="#ff0000"');
            $this->generateAttrTable('BORDERCOLOR="#ff0000"');
            //$this->generateAttrTable('align="center"');
            //$this->generateAttrTable('align="right"');
            //$this->generateAttrTable('align="left"');
            //$this->generateAttrTable('width="200px"');
            /*$this->generateTBLPR();
            if (!empty($args[1]['TBLSTYLEval'])) {
                $this->generateTBLSTYLE($args[1]['TBLSTYLEval']);
            } else {
                $this->generateTBLSTYLE();
            }
            if (!empty($args[1]['jc'])) $this->generateJC($args[1]['jc']);
            $tblWtype = 'auto';
            if (!empty($args[1]['TBLWtype'])) $tblWtype = $args[1]['TBLWtype'];
            $this->generateTBLW($tblWtype, $args[1]['TBLWw']);
            if (!empty($args[1]['border'])) {
                $this->generateTBLBORDERS();
                $sz = 4;
                if (!empty($args[1]['border_sz'])) $sz = $args[1]['border_sz'];
                $color = 'auto';
                if (!empty($args[1]['border_color']))
                        $color = $args[1]['border_color'];
                $spacing = '0';
                if (!empty($args[1]['border_spacing']))
                        $spacing = $args[1]['border_spacing'];

                $border = $args[1]['border'];
                $this->generateTBLBOTTOM($border, $sz, $spacing, $color);
                $this->generateTBLLEFT($border, $sz, $spacing, $color);
                $this->generateTBLTOP($border, $sz, $spacing, $color);
                $this->generateTBLRIGHT($border, $sz, $spacing, $color);
                $this->generateTBLINSIDEH($border, $sz, $spacing, $color);
                $this->generateTBLINSIDEV($border, $sz, $spacing, $color);
            }

            $this->generateTBLLOOK($args[1]['TBLLOOKval']);
            $this->generateTBLOVERLAP();
            $intLine = 0;*/
            $this->cleanAttrTable();
            foreach ($args[0] as $datDepth) {
                $this->generateNewRow();
                foreach ($datDepth as $cont) {
                    $this->generateNewCol();
                    if (!empty($args[1]['size_col']) && is_numeric($args[1]['size_col'])) {
                        $this->generateStyleTd();
                        $this->generateNewStyleTd('width: '. $args[1]['size_col'].'px');
                    }
                    if ($cont instanceof CreateLinkPdf) {
                        $cont->createEmbeddedLink(
                            $cont->getTitle(),
                            $cont->getLink()
                        );
                        //var_dump((string) $cont);
                        $this->generateNewCell((string) $cont);
                    } elseif ($cont instanceof CreateImagePdf) {
                        $cont->createEmbeddedImage();
                        $this->generateNewCell((string) $cont);
                    } elseif ($cont instanceof CreateGraphicPdf || $cont instanceof CreateChartPdf) {
                        /*$cont->createEmbeddedGraphic();
                        $this->generateNewCell((string) $cont);*/
                        $tempName = $cont->createEmbeddedGraphic();
                        $newName = getcwd() . '/tmp/fotoaux'. $this->generateRandom() . '.svg';
                        rename($tempName, $newName);
                        $img = CreateImagePdf::getInstance();
                        $img->createImage(array('name' => $newName));
                        $this->generateNewCell((string) $img);
                    } elseif ($cont instanceof CreateListPdf) {
                        $cont->createEmbeddedList();
                        $this->generateNewCell((string) $cont);
                    } elseif ($cont instanceof CreateTextPdf) {
                        $cont->createEmbeddedText($cont);
                        $this->generateNewCell((string) $cont);
                    } else {
                        $text = CreateTextPdf::getInstance();
                        $text->createText($cont);
                        $this->generateNewCell((string) $text);
                    }
                    $this->cleanStyleTd();
                }
                $this->cleanTD();
            }
        }
    }

    protected function generateTable(){
        $this->_xml .= '<table __ATTR_TABLE__ __STYLES_TABLE__>__TABLE__</table>';
    }

    protected function generateAttrTable($atributo){
        $this->_xml = str_replace('__ATTR_TABLE__', $atributo.' __ATTR_TABLE__', $this->_xml);
    }
    
    protected function generateStyleTable(){
        $this->_xml = str_replace('__STYLES_TABLE__', ' style="__TABLESTYLE__"', $this->_xml);
    }

    protected function generateNewStyleTable($style){
        $this->_xml = str_replace('__TABLESTYLE__', $style . ';__TABLESTYLE__', $this->_xml);
    }

    protected function generateNewRow(){
        $this->_xml = str_replace('__TABLE__', '<tr __STYLES_TR__>__TD__</tr>__TABLE__', $this->_xml);
    }

    protected function generateNewCol(){
        $this->_xml = str_replace('__TD__', '<td __STYLES_TD__>__INNER_TD__</td>__TD__', $this->_xml);
    }

    protected function generateStyleTd(){
        $this->_xml = str_replace('__STYLES_TD__', ' style="__STYLE_TD__"', $this->_xml);
    }

    protected function generateNewStyleTd($style){
        $this->_xml = str_replace('__STYLE_TD__', $style . ';__STYLE_TD__', $this->_xml);
    }

    protected function generateNewCell($text){
        $this->_xml = str_replace('__INNER_TD__', $text, $this->_xml);
    }

    protected function cleanTD()
    {
        $this->_xml = str_replace('__TD__', '', $this->_xml);
    }

    protected function cleanAttrTable()
    {
        $this->_xml = str_replace('__ATTR_TABLE__', '', $this->_xml);
    }

    protected function cleanStyleTd()
    {
        $this->_xml = str_replace('__STYLE_TD__', '', $this->_xml);
    }

}