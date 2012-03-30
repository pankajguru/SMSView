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

$docx->addImage2($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/logo_phpdocx.gif',
//    'name' => '../../files/img/logo_phpdocx.gif',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg //'../../files/img/pieChart.jpg'
    'scaling' => 80,
    'jc' => 'center'//'center'
);

$docx->addImage2($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/image.png',
    'scaling' => 40,
    'jc' => 'asdf'//'center'
);

$docx->addImage2($paramsImg);

$docx->createPdf('example_image');
