<?php

/**
 * Create header
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
 * Create header
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
class CreateHeaderPdf extends CreateElementPdf
{
    /**
     *
     * @var CreateFooter
     * @access private
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
     * @return string
     * @access public
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     *
     * @return CreateHeader
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateHeaderPdf();
        }
        return self::$_instance;
    }

    /**
     * Create header
     *
     * @access public
     * @param string args[0]
     * @param array args[1]
     */
    public function createHeader()
    {
        $this->_xml = '';
        $args = func_get_args();
        $text = CreateTextPdf::getInstance();
        $text->createText($args[0], $args[1]);
        $this->_xml = (string) $text;
        /*$this->_xml = str_replace(
            '__GENERATEHDR__', (string) $text . '__GENERATEHDR__',
            $this->_xml
        );*/
    }
}