<?php

/**
 * Create endnotes
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
 * Create endnotes
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
class CreateEndnotePdf extends CreateElementPdf
{
    /**
     *
     * @var bool
     * @access public
     * @static
     */
    public static $init = 0;

    /**
     *
     * @var CreateEndnote
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
    private static $_id;
    
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
        return $this->_xml;
    }

    /**
     *
     * @return CreateEndnote
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateEndnote();
        }
        return self::$_instance;
    }

    /**
     * Create endnote
     *
     * @access public
     */
    public function createEndnote()
    {
        $this->_xml = '';
        $args = func_get_args();
        
    
        $this->cleanTemplate();
    }
    
    /**
     * Create init endnote
     *
     * @access public
     */
    /*public function createInitEndnote()
    {
        $this->_xml = '';
        $arrArgs = func_get_args();
        $this->generateENDNOTE($arrArgs[0]['type']);
        $this->generateP();
        $this->generatePPR();
        $this->generateSPACING();
        $this->generateR();
        $this->generateSEPARATOR($arrArgs[0]['type']);
        $this->cleanTemplate();
    }*/
}
