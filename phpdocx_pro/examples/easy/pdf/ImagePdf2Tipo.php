<?php

/**
 * Create a DOCX file. Image example
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    1.8
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.8
 */
require_once '../../../classes/pdf/CreatePdf.inc';
$docx = new CreatePdf();

$paramsImg = array(
    'name' => '../../files/img/pieChart.jpg',
    'scaling' => 20,
    'jc' => 'right'
);

//$docx->addImage($paramsImg);
//$docx->addImage2($paramsImg);
/*$paramsImg = array(
    'name' => '../../files/img/pieChart.png',
//    'name' => '../../files/img/logo_phpdocx.gif',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg //'../../files/img/grafiti.jpg'
    'scaling' => 80,
    'jc' => 'center'//'center'
);

$docx->addImage2($paramsImg);*/

$aImg = array(
    //'name' => '../../files/img/pieChart.svg',
    'name' => '../../files/img/prueba.SVG',
    'border' => 1,
    'jc' => 'center',
    'sizeX' => 52,
    'sizeY' => 50,
    'spacingTop' => 0
);
$docx->addImage($aImg);
//$docx->addImage2($paramsImg);
$docx->addImage($paramsImg);
//$docx->ImagenesSVG($file='../../files/img/pieChart.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);

$docx->createPdf('example_image_svg');
