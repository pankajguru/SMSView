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
/*'border'(1, 2, 3...),
     *  'borderDiscontinuous' (0, 1),
     *  'font' (Arial, Times New Roman...),
     *  'jc' (center, left, right),
     *  'name', 'scaling' (50, 100),
     *  'sizeX' (10, 11, 12...),
     *  'sizeY' (10, 11, 12...),
     *  spacingTop (10, 11...),
     *  spacingBottom (10, 11...),
     *  spacingLeft (10, 11...),
     *  spacingRight (10, 11...),
     *  'textWrap' (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom), 5 (clear))
*/
/*
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
    'sz' => '12',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addText($text, $paramsText);*/

$paramsImg = array(
    'name' => '../../files/img/pieChart.jpg',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg
    'jc' => 'right',
    'scaling' => 20,
    'spacingTop' => 100
);

$docx->addImage($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/logo_phpdocx.gif',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg
    'jc' => 'center'
);
  
$docx->addImage($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/image.png',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg
    'jc' => 'left'
);

$docx->addImage($paramsImg);

$docx->createPdf('example_image');
