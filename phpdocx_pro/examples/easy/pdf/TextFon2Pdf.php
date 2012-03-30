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

$text1 = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ';
$text2 = 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ';
$text3 = 'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut';
$text4 = 'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ';
$text5 = 'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ';
$text6 = 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ';
$text7 = 'officia deserunt mollit anim id est laborum.';

$paramsText = array(
    'text' => $text1
);
$aTextos = array();
$aTextos[] = $paramsText;
$paramsText = array(
    'jc' => 'centre',
    'font' => 'helvetica',
    'text' => $text2
);
$aTextos[] = $paramsText;
$paramsText = array(
    'jc' => 'right',
    'font' => 'times',
    'text' => $text3
);

$aTextos[] = $paramsText;
$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'font' => 'courier',
    'sz' => '12',
    'text' => $text4
);
$aTextos[] = $paramsText;
$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'font' => 'symbol',
    'sz' => '12',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single',
    'text' => $text5
);

$aTextos[] = $paramsText;
$paramsText = array(
    'text' => $text6
);

$aTextos[] = $paramsText;
$docx->addText($aTextos);

$docx->createPdf();
