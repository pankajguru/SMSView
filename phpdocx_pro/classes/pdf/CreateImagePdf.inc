<?php

/**
 * Create images
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
 * Create images
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
class CreateImagePdf extends CreateElementPdf
{
    /**
     * @access private
     * @var CreateImage
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
     * @return CreateImage
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateImagePdf();
        }
        return self::$_instance;
    }

    /**
     * Create image
     *
     * @access public
     * @param array $args[0]
     */
    public function createImage()
    {
        $this->_xml = '';
        $args = func_get_args();
        $this->generateImg();
        $this->generateStylesImg();
            if(in_array($args[0]['jc'], array('right', 'left', 'center'))){
                $this->generateStyleImg('text-align:'. $args[0]['jc']);
                //$this->generateAttrImg('align="'. $args[0]['jc'].'"');
            }
        $atributos = getimagesize($args[0]['name']);
        if (!empty($args[0]['sizeX'])) {
            $tamPxX = $args[0]['sizeX'];
        } elseif (!empty($args[0]['scaling'])) {
            $tamPxX = $atributos[0] * $args[0]['scaling'] / 100;
        } elseif(isset($atributos[0])) {
            $tamPxX = $atributos[0];
        }else{
            $tamPxX = 300;
        }
        $this->generateAttrImg('width="'.$tamPxX.'"');
        if (!empty($args[0]['scaling'])) {
            $tamPxY = $atributos[1] * $args[0]['scaling'] / 100;
        } elseif (!empty($args[0]['sizeY'])) {
            $tamPxY = $args[0]['sizeY'];
        } elseif(isset($atributos[1])) {
            $tamPxY = $atributos[1];
        }else{
            $tamPxY = 300;
        }
        $this->generateAttrImg('height="'.$tamPxY.'"');
        $this->generateSrcImg($args[0]['name']);
        $this->cleanTemplate();
        if(is_numeric($args[0]['spacingTop'])){
            $this->_xml = '<table border="0" cellpadding="'.$args[0]['spacingTop'].'"><tr><td>'. $this->_xml . '</td></tr></table>';
        }
    }
    
    /**
     * Create image
     *
     * @access public
     * @param array $args[0]
     */
    public function createImage2()
    {
        $args = func_get_args();
        $aImgHeader = array('name' => $args[0]['name']);
        $atributos = getimagesize($args[0]['name']);
        if (!empty($args[0]['sizeX'])) {
            $aImgHeader['w'] = $args[0]['sizeX'];
        } elseif (!empty($args[0]['scaling'])) {
            $aImgHeader['w'] = $atributos[0] * $args[0]['scaling'] / 100;
        } else {
            $aImgHeader['w'] = $atributos[0];
        }

        if (!empty($args[0]['scaling'])) {
            $aImgHeader['h'] = $atributos[1] * $args[0]['scaling'] / 100;
        } elseif (!empty($args[0]['sizeY'])) {
            $aImgHeader['h'] = $args[0]['sizeY'];
        } else {
            $aImgHeader['h'] = $atributos[1];
        }
        if($args[1]){
            if(in_array($args[0]['jc'], array('center', 'left', 'right'))){
                if($args[0]['jc'] == 'left')
                    $aImgHeader['x'] = 200 - $aImgHeader['w'];
                else
                    $aImgHeader['x'] = 15;
            }else{
                $aImgHeader['x'] = 200 - $aImgHeader['w'];
            }
        }else{
            if($args[0]['jc'] == 'right'){
                $x = 200 - $tamPxX;
            }elseif($args[0]['jc'] == 'center'){
                $x = 100 - $tamPxX/2;
            }else{
                $x = 20;
            }
        }
        return $aImgHeader;        
    }

    /**
     * Create embedded image
     *
     * @access public
     * @param string $comeFrom
     */
    public function createEmbeddedImage(){
        //return
        $aDatos = array('name' => $this->_name,
            'textWrap' => $this->_ajusteTexto,
            'sizeX' => $this->_sizeX,
            'sizeY'=> $this->_sizeY,
            'spacingTop' => $this->_spacingTop,
            'spacingBottom' => $this->_spacingBottom,
            'spacingLeft' => $this->_spacingLeft,
            'spacingRight' => $this->_spacingRight,
            'jc' => $this->_jc,
            'border' => $this->_border,
            'borderDiscontinuous' => $this->_borderDiscontinuo,
            'scaling' => $this->_scaling);
        $this->createImage($aDatos);
    }

    protected function generateImg(){
        $this->_xml .= '<img __ATTR_IMG__ src="__SRC_IMG__" __STYLES_IMG__ />';//__STYLES_IMG__
    }

    protected function generateStylesImg(){
        $this->_xml = str_replace('__STYLES_IMG__', ' style="__IMG_STYLE__"', $this->_xml);
    }

    protected function generateStyleImg($style){
        $this->_xml = str_replace('__IMG_STYLE__', $style . ';__IMG_STYLE__', $this->_xml);//__IMG_STYLE__
    }

    protected function generateAttrImg($style){
        $this->_xml = str_replace('__ATTR_IMG__', $style . ' __ATTR_IMG__', $this->_xml);//__IMG_STYLE__
    }

    protected function generateSrcImg($src){
        $this->_xml = str_replace('__SRC_IMG__', $src, $this->_xml);
    }


    /**
     * Init image
     *
     * @access public
     * @param array $args[0]
     */
    public function initImage()
    {
        $args = func_get_args();
        $this->_name = $args[0]['name'];
        $this->_ajusteTexto = $args[0]['textWrap'];
        $this->_sizeX = $args[0]['sizeX'];
        $this->_sizeY = $args[0]['sizeY'];
        $this->_spacingTop = $args[0]['spacingTop'];
        $this->_spacingBottom = $args[0]['spacingBottom'];
        $this->_spacingLeft = $args[0]['spacingLeft'];
        $this->_spacingRight = $args[0]['spacingRight'];
        $this->_jc = $args[0]['jc'];
        $this->_border = $args[0]['border'];
        $this->_borderDiscontinuo = $args[0]['borderDiscontinuous'];
        $this->_scaling = $args[0]['scaling'];
    }
}
