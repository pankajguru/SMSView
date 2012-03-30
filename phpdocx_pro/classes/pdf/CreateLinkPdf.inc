<?php

/**
 * Create links using text strings
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
 * Create links using text strings
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
class CreateLinkPdf extends CreateElementPdf
{

    /**
     *
     * @access private
     * @static
     */
    private static $_instance = null;
    /**
     *
     * @access private
     * @var string
     */
    private $_link;
    /**
     *
     * @access private
     * @var string
     */
    private $_title;
    /**
     *
     * @access private
     * @var string
     */
    private $_font;

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
     * @return CreateLink
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateLinkPdf();
        }
        return self::$_instance;
    }

    /**
     * Getter. Access to link var
     *
     * @access public
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * Getter. Access to title var
     *
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Getter. Access to font var
     *
     * @access public
     * @return string
     */
    public function getFont()
    {
        return $this->_font;
    }

    /**
     * Create an embedded link to add in others elements like lists, tables...
     *
     * @access public
     */
    public function createEmbeddedLink()
    {
        //$this->_xml = '';
        $args = func_get_args();
        $this->_xml = '<a href="'.$args[1].'">'.$args[0].'</a>';
    }

    /**
     * Create a link
     *
     * @access public
     * @param string $arrArgs[0] Text to add
     * @param string $arrArgs[1] URL to add
     * @param string $arrArgs[2] RFONTS
     */
    public function createLink()
    {
        $this->_xml = '';
        $args = func_get_args();

        $this->_xml = '<a href="'.$args[1].'">'.$args[0].'</a>';
    }

    /**
     * Init a link to assign values to variables
     *
     * @access public
     * @param string $arrArgs[0]['title'] Text to add
     * @param string $arrArgs[0]['link'] URL to add
     * @param string $arrArgs[0]['font'] RFONTS
     */
    public function initLink()
    {
        $args = func_get_args();

        $this->_link = $args[0]['link'];
        $this->_title = $args[0]['title'];
        $this->_font = $args[0]['font'];
    }

    /*protected function generateLink(){
        $this->_xml .= '<a __STYLES_LINK__ href="__LINK_ENLACE__">__LINK_TEXT__</a>';
    }*/
}
