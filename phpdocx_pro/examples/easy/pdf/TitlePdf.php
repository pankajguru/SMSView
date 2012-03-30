<?php

/**
 * Create a DOCX file. Title example
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
/*
 * 'b' (single),
 *  'color' (ffffff, ff0000...),
 *  'font' (Arial, Times New Roman...),
 *  'i' (single),
 *  'jc' (both, center, distribute, left, right),
 *  'pageBreakBefore' (on, off),
 *  'sz' (1, 2, 3...),
 *  'u' (dash, dotted, double, single, wave, words),
 *  'widowControl' (on, off),
 *  'wordWrap' (on, off)*/
$paramsTitle = array(
    'val' => 2,
    'b' => 'single',
    'color' => 'ff0000',
    'i' => 'single',
    'jc' => 'right',
    'u' => 'single',
    'font' => 'Blackadder ITC',
    'sz' => 22
);

$docx->addTitle('Lorem ipsum dolor sit amet', $paramsTitle);
$paramsTitle = array(
    'val' => 2,
    //'b' => 'single',
    'color' => 'ff0000',
    'i' => 'single',
    'jc' => 'right',
    'u' => 'single',
    'font' => 'Blackadder ITC',
    'sz' => 22
);

$docx->addTitle('Lorem ipsum dolor sit amet', $paramsTitle);
$paramsTitle = array(
    'val' => 2,
    //'b' => 'single',
    'color' => 'fff000',
    //'i' => 'single',
    'jc' => 'right',
    //'u' => 'single',
    //'font' => 'Blackadder ITC',
    'sz' => 22
);

$docx->addTitle('Lorem ipsum dolor sit amet', $paramsTitle);
$paramsTitle = array(
    'val' => 1,
    'b' => 'single',
    'color' => 'fff000',
    //'i' => 'single',
    'jc' => 'right',
    //'u' => 'single',
    //'font' => 'Blackadder ITC',
    'sz' => 22
);

$docx->addTitle('Lorem ipsum dolor sit amet', $paramsTitle);

$docx->createPdf('example_title');
