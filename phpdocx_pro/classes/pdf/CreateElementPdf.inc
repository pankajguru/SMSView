<?php

/**
 * Create tag elements
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

/**
 * Main class, create tag elements
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
class CreateElementPdf
{
    /**
     *
     * @var string
     * @access protected
     */
    protected $_xml;

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

    }

    protected function generateDiv(){
        $this->_xml .= '<div __STYLES_DIV__>__DIV__</div>';
    }
    
    protected function generateStyleDiv(){
        $this->_xml = str_replace('__STYLES_DIV__', ' style="__DIVSTYLE__"', $this->_xml);
    }

    protected function generateNewStyleDiv($style){
        $this->_xml = str_replace('__DIVSTYLE__', $style . ';__DIVSTYLE__', $this->_xml);
    }

    protected function generateInnerDiv(){
        if($this->_xml == '')
                $this->_xml = '<span __STYLES_SPAN__>__TEXTSPAN__</span>__DIV__';
        else
            $this->_xml = str_replace('__DIV__', '<span __STYLES_SPAN__>__TEXTSPAN__</span>__DIV__', $this->_xml);
    }

    protected function generateStyleSpan(){
        $this->_xml = str_replace('__STYLES_SPAN__', ' style="__SPANSTYLE__"', $this->_xml);
    }

    protected function generateNewStyleSpan($style){
        $this->_xml = str_replace('__SPANSTYLE__', $style . ';__SPANSTYLE__', $this->_xml);
    }

    protected function generateTextSpan($text){
        $this->_xml = str_replace('__TEXTSPAN__', $text, $this->_xml);
    }
    
    protected function cleanTemplate()
    {
        $this->_xml = preg_replace('/__[A-Z_]+__/', '', $this->_xml);
    }

    public function generateRandom(){
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));
        return rand();
    }
}
