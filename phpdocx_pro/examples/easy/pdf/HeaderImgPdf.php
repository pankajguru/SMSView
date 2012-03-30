<?php

/**
 * Create a DOCX file. Header
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage intermediate
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    1.8
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.8
 */
require_once '../../../classes/pdf/CreatePdf.inc';

$docx = new CreatePdf();

$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'right',
    'textWrap' => 5,
    'name' => '../../files/img/pieChart.jpg',
    'scaling' => 20
);



$text = '||Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.||';

$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'sz' => '20',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addHeader('Header text', $paramsHeader);
$docx->addText($text, $paramsText);
$docx->addText($text, $paramsText);
$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'center',
    'name' => '../../files/img/image.png',
    'textWrap' => 5,
    'scaling' => 20
);
$docx->addHeader('aaaaaaaaa', $paramsHeader);
//$docx->AddPage();
$docx->addText($text, $paramsText);
$docx->addText($text, $paramsText);
$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'left',
    'name' => '../../files/img/image.png',
    'textWrap' => 5,
    'scaling' => 20
);
$docx->addHeader('vbbbbbbbbb', $paramsHeader);
$docx->addText($text, $paramsText);
$docx->addText($text, $paramsText);
$docx->addText($text, $paramsText);

$docx->createPdf('example_header_img.pdf');
