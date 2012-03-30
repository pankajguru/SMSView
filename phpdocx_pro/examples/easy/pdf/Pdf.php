<?php

/**
 * Create a DOCX file. Text example
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
$paramsFooter = array(
    'font' => 'Times New Roman'
);

$docx->addFooter('Footer. Times New Roman font', $paramsFooter);

$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'right',
    'textWrap' => 5,
);

$docx->addHeader('Header text', $paramsHeader);

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.';

$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'sz' => '28',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addText($text, $paramsText);
$valuesTable = array(
    array(
        11,
        12,
        13,
        14,
        15,
        'hola'

    ),
    array(
        21,
        22
    ),
);

$paramsTable = array(
    'border' => 'single',
    'border_sz' => 20
);


$docx->addTable($valuesTable, $paramsTable);

$valuesList = array(
    'Line 1',
    'Line 2',
    'Line 3',
    'Line 4',
    'Line 5'
);

$paramsList = array(
    'val' => 1
);

$docx->addList($valuesList, $paramsList);

$docx->addLink('Link to Google', 'http://www.google.es', 'Arial');

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.<br/><br/>';

$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'sz' => '28',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addText($text, $paramsText);

$paramsImg = array(
    'name' => '../../files/img/image.png',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg
    'scaling' => 50,
    'spacingTop' => 100,
    'spacingBottom' => 0,
    'spacingLeft' => 100,
    'spacingRight' => 0,
    'textWrap' => 1,
    'border' => 1,
    'borderDiscontinuous' => 1
);

$docx->addImage2($paramsImg);

$docx->createPdf();
