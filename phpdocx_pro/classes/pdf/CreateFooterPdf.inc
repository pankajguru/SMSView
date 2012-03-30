<?php

/**
 * Create footer
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
 * Create footer
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
class CreateFooterPdf extends CreateElementPdf
{
    /**
     *
     * @var CreateFooter
     * @access private
     * @static
     */
    private static $_instance = NULL;

    /**
     *
     * @var int
     * @access private
     * @static
     */
    private static $_numberPage = 0;

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
     * @return string
     * @access public
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     *
     * @return CreateFooter
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateFooterPdf();
        }
        return self::$_instance;
    }

    /**
     * Create footer
     *
     * @access public
     * @param string args[0]
     * @param array args[1]
     */
    public function createFooter()
    {
        $this->_xml = '';
        $args = func_get_args();
        /*$this->generateFTR();
        if ($args[1]['pager'] == 'true') {
            $this->generateP();
            $this->generateR();
            $this->generatePTAB('margin', $args[1]['pagerAlignment']);
            $this->generateFLDSIMPLE();
        }*/
        $text = CreateTextPdf::getInstance();
        $text->createText($args[0], $args[1]);
        /*$obj = preg_replace('/__[A-Z]+__/', '', (string) $text);
        $xml = $obj . '__GENERATEFTR__';
        $this->_xml = str_replace('__GENERATEFTR__', $xml, $this->_xml);*/
        $this->_xml = (string) $text;
    }
}