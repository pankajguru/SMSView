<?php

/**
 * Create a DOCX file. List example
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

$valuesList = array(
    'Line 1',
    'Line 2',
    'Line 3',
    'Line 4',
    'Line 5'
);

$paramsList = array('val' => 0, 'font' => 'Arial');
$docx->addList($valuesList, $paramsList);
$paramsList = array('val' => 1, 'font' => 'Comic Sans MS');
$docx->addList($valuesList, $paramsList);
$paramsList = array('val' => 2, 'font' => 'Georgia');
$docx->addList($valuesList, $paramsList);
$paramsList = array('val' => 3, 'font' => 'Times New Roman');//
$docx->addList($valuesList, $paramsList);


$docx->createPdf('example_list.pdf');
