<?php

/**
 * Create a DOCX file. Add WordML code in a DOCX file
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
// *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.08.17
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.4
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->addText('WordML content');

$text = new CreateText();
$text->createText('Texto');

$docx->addWordML($text);

$valuesTable = array(
    array(
        11,
        12
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

$table = new CreateTable();
$table->createTable($valuesTable, $paramsTable);

$docx->addWordML($table);

$docx->addText('End WordML content');

$docx->createDocx('example_wordml');
