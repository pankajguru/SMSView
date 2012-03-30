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

$text1 = '1111111111';
$text2 = '222222222222';
$text3 = '333333333';
$text4 = '4444444444';
$text5 = '555555555555555';
$text6 = '666666666666666666';
$text7 = '777777777777777777777777777777';

$paramsText = array(
    //'font' => 'helvetica'
);
$docx->addText($text1, $paramsText);

$paramsText = array(
    'font' => 'helvetica'
);
$docx->addText($text2, $paramsText);
$paramsText = array(
    'font' => 'times'
);
$docx->addText($text3, $paramsText);

$paramsText = array(
    'font' => 'courier'
);
$docx->addText($text4, $paramsText);

$paramsText = array(
    'font' => 'symbol'
);
$docx->addText($text5, $paramsText);

//$docx->AddPage();
$docx->addText($text6, $paramsText);
$docx->addText('**********************');
$docx->addText('+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');


$docx->createPdf();
