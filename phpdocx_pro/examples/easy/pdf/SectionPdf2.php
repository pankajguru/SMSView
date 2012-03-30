<?php

/**
 * Create a DOCX file. Section example
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

$text = '__1__Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.__1__';
$texto2 = 'asdf asdfa sdf asfd dsfasdf aklsd fsdkflksdj faskldf sdkjfdkf dk d dkd d ls lj fkjs lkjslkfj alskd a fjkal a kj fla alsj df alsdf as dfasdfas fas dfa sdfaklj asdfjiealSKJF ASIDF  asdas df';

$docx->addText($text);
$docx->addText($text);
$docx->addText($text);
$docx->addText($text);

$paramsText = array(
    'b' => 'single'
);

$docx->addText($text, $paramsText);

$paramsSection = array(
    'orient' => 'landscape',
    'top' => 10,
    'bottom' => 10,
    'right' => 10,
    'left' => 10
);

$docx->addSection($paramsSection);

$docx->addText($texto2);

$docx->addText($texto2, $paramsText);
$docx->addText($texto2, $paramsText);
$docx->addText($texto2, $paramsText);
$docx->addText($texto2, $paramsText);
$docx->addText($texto2, $paramsText);

$docx->createPdf('example_section_pdf');
