<?php

/**
 * Create text
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
 * Create text
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
class CreateTextPdf extends CreateElementPdf
{
    /**
     *
     * @access private
     * @var string
     */
    private static $_instance = NULL;
    /**
     *
     * @access private
     * @var string
     */
    private static $_typeUnderline = NULL;

    /**
     * Destruct
     *
     * @access public
     */
    public function __construct()
    {
        $this->_typeUnderline = array('dash' => 'border-bottom: 1px dotted #ba0000' , 'dotted' => 'text-decoration: underline; text-underline-style: dotted', 'double' => 'border-bottom: 3px double', 'single' => 'text-decoration: underline');
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
     * Magic method, returns current XML
     *
     * @access public
     * @return string Return current XML
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     * Singleton, return instance of class
     *
     * @access public
     * @return CreateText
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateTextPdf();
        }
        return self::$_instance;
    }

    /**
     * Create embedded text
     *
     * @access public
     * @param array $args[0]
     * @param array $texts
     */
    public function createEmbeddedText()
    {
        
    }

    /**
     * Create text
     *
     * @access public
     * @param mixed $args[0]
     * @param array $texts
     */
    public function createText()
    {
        $this->_xml = '';
        $args = func_get_args();
        
        //var_dump($args);
        if(is_array($args[0])){
            foreach($args[0] as $ind => $val){
                $this->generateInnerDiv();
                if (
                    $val['jc'] != '' ||
                    $val['b'] != '' ||
                    $val['i'] != '' ||
                    $val['u'] != '' ||
                    $val['sz'] != '' ||
                    $val['color'] != '' ||
                    $val['font'] != '' ||
                    $val['tabs'] != ''
                ) {
                    $this->generateStyleSpan();

                    if (!empty($val['jc'])){
                        $this->generateNewStyleSpan('text-align: ' . $val['jc']);
                    }
                    if (isset($val['b']) && $val['b'] != '') {
                        $this->generateNewStyleSpan('font-weight: ' . ($val['b'] == 'single'? 'bold' : 'normal'));
                    }
                    if (isset($val['i']) && $val['i'] != '') {
                        $this->generateNewStyleSpan('font-style: ' . ($val['i'] == 'single'? 'italic' : 'normal'));
                    }
                    if (isset($val['u']) && $val['u'] != '') {//
                        $this->generateNewStyleSpan($this->_typeUnderline[$val['u']]);
                    }
                    if (isset($val['sz']) && $val['sz'] != '') {
                        $this->generateNewStyleSpan('font-size: ' . (is_numeric($val['sz'])? $val['sz'] : 'medium'));//$this->generateSZ($val['sz']);
                    }
                    if (isset($val['color']) && $val['color'] != '') {
                        $this->generateNewStyleSpan('color: #' . $val['color']);
                    }
                }
                $this->generateTextSpan($val['text']);//, $texts['spaces']
                $this->cleanTemplateDiv();
            }
            $this->cleanTemplate();
        }else{
            $this->generateInnerDiv();
            if (
                $args[1]['jc'] != '' ||
                $args[1]['b'] != '' ||
                $args[1]['i'] != '' ||
                $args[1]['u'] != '' ||
                $args[1]['sz'] != '' ||
                $args[1]['color'] != '' ||
                $args[1]['font'] != '' ||
                $args[1]['tabs'] != ''
            ) {
                $this->generateStyleSpan();

                if (!empty($args[1]['jc'])){
                    $this->generateNewStyleSpan('text-align: ' . $args[1]['jc']);
                }
                if (isset($args[1]['b']) && $args[1]['b'] != '') {
                    $this->generateNewStyleSpan('font-weight: ' . ($args[1]['b'] == 'single'? 'bold' : 'normal'));
                }
                if (isset($args[1]['i']) && $args[1]['i'] != '') {
                    $this->generateNewStyleSpan('font-style: ' . ($args[1]['i'] == 'single'? 'italic' : 'normal'));
                }
                if (isset($args[1]['u']) && $args[1]['u'] != '') {//
                    $this->generateNewStyleSpan($this->_typeUnderline[$args[1]['u']]);
                }
                if (isset($args[1]['sz']) && $args[1]['sz'] != '') {
                    $this->generateNewStyleSpan('font-size: ' . (is_numeric($args[1]['sz'])? $args[1]['sz'] : 'medium'));//$this->generateSZ($args[1]['sz']);
                }
                if (isset($args[1]['color']) && $args[1]['color'] != '') {
                    $this->generateNewStyleSpan('color: #' . $args[1]['color']);
                }
            }
            $this->generateTextSpan($args[0]);//, $texts['spaces']
            $this->cleanTemplate();
        }
        
      
    }
    /**
     * Init text
     *
     * @access public
     * @param array $args[0]
     */
    public function initText()
    {
        $args = func_get_args();

        $this->_embeddedText = $args[0];
    }

    protected function cleanStyleSpan()
    {
        $this->_xml = str_replace('__SPANSTYLE__', '', $this->_xml);
    }


    /**
     * Create title
     *
     * @access protected
     * @param string $arrArgs[0]
     * @param array $arrArgs[1]
     */
    public function createTitle()
    {
        $this->_xml = '';
        $args = func_get_args();
        if (!empty($args[1]['val']) && is_numeric($args[1]['val']) && !empty($args[0])) {
            $this->generateH($args[1]['val']);
            if (!empty($args[1]['pageBreakBefore'])){
                //$this->generatePAGEBREAKBEFORE($args[1]['pageBreakBefore']);
            }
            if (!empty($args[1]['widowControl'])){
                //$this->generateWIDOWCONTROL($args[1]['widowControl']);
            }
            if (!empty($args[1]['wordWrap'])){
                //$this->generateWORDWRAP($args[1]['wordWrap']);
            }
            if (
                $args[1]['b'] != '' ||
                $args[1]['i'] != '' ||
                $args[1]['u'] != '' ||
                $args[1]['sz'] != '' ||
                $args[1]['color'] != '' ||
                $args[1]['font'] != ''
            ) {
                $this->generateStylesH();

                if (!empty($args[1]['jc'])){
                    $this->generateStyleH('text-align: ' . $args[1]['jc']);
                }
                if (isset($args[1]['b']) && $args[1]['b'] != '') {
                    $this->generateStyleH('font-weight: ' . ($args[1]['b'] == 'single'? 'bold' : 'normal'));
                }
                if (isset($args[1]['i']) && $args[1]['i'] != '') {
                    $this->generateStyleH('font-style: ' . ($args[1]['i'] == 'single'? 'italic' : 'normal'));
                }
                if (isset($args[1]['u']) && $args[1]['u'] != '') {//
                    $this->generateStyleH($this->_typeUnderline[$args[1]['u']]);
                }
                if (isset($args[1]['sz']) && $args[1]['sz'] != '') {
                    $this->generateStyleH('font-size: ' . (is_numeric($args[1]['sz'])? $args[1]['sz'] : 'medium'));//$this->generateSZ($args[1]['sz']);
                }
                if (isset($args[1]['color']) && $args[1]['color'] != '') {
                    $this->generateStyleH('color: #' . $args[1]['color']);
                }
            }
            $this->innerH($args[0]);
            $this->cleanTemplate();
            //var_dump($this->_xml);
        }
    }

    public function generateH($num){
        $this->_xml = '<h' . $num . '__STYLES_H__>__INNER_H__</h' . $num . '>';
    }

    public function generateStylesH(){
        $this->_xml = str_replace('__STYLES_H__', ' style="__H_STYLES__"', $this->_xml);
    }

    public function generateStyleH($style){
        $this->_xml = str_replace('__H_STYLES__', $style.';__H_STYLES__', $this->_xml);
    }

    public function innerH($text){
        $this->_xml = str_replace('__INNER_H__', $text, $this->_xml);
    }
    
    public function cleanTemplateDiv(){
        $this->_xml = preg_replace('/__[A-CE-Z][A-HJ-Z][A-UW-Z][A-Z_]+__/', '', $this->_xml);
    }


}

