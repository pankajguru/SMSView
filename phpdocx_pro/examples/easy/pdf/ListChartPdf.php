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
$arrParamsLink = array(
    'title' => 'Link to Google',
    'link' => 'http://www.google.es'
);

$objLink = $docx->addElement('addLink', $arrParamsLink);
$arrParamsImg = array(
    'name' => '../../files/img/image.png'
);

$objImg = $docx->addElement('addImage', $arrParamsImg);
$args = array(
    'data' => array(
        'Explorer' => 1.6,
        'Firefox' => 2.8,
        'Chrome' => 3.8,
        'Safari' => 4,
        'Opera' => 5
    ),
    'title' => 'Wikipedia articles',
    'type' => 'pieChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$objChart = $docx->addElement('addChart', $args);
$args = array(
    'data' => array(
        'Explorer' => 1,
        'Firefox' => 2,
        'Chrome' => 3,
        'Safari' => 4,
        'Opera' => 5
    ),
    'title' => 'Wakapedia',
    'type' => 'pieChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$objChart2 = $docx->addElement('addChart', $args);


$arrDatsList = array(
    'Line 1',
    $objLink,
    $objImg,
    $objChart,
    array(
        $objChart2,
        'anidado 1',
        'anidado 2',
        'anidado 3',
        'anidado 4'
        ),
    'Line 2',
    'Line 3',
);
$arrParamsList = array(
    'val' => 1
);
$docx->addList($arrDatsList, $arrParamsList);
$docx->createPdf('example_list_image');
