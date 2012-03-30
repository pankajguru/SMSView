<?php

/**
 * Create a DOCX file. Table example
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

$valuesTable = array(
    array(
        11,
        12,
        13,
        'hola'

    ),
    array(
        21,
        22
    ),
);

$paramsTable = array(
    'border' => 'single',
    'border_sz' => 20,
    'border_color' => 'ff0000',
    'border_spacing' => '1',
    'jc' => 'right',
    'size_col' => 80,
    'TBLSTYLEval' => ''
);


$docx->addTable($valuesTable, $paramsTable);

$docx->createPdf('example_table');
