<?php

/**
 * Inserts a (very) simple shape into the Word document.
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.2
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.2
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$type = 'line';

$paramsShape = array(
    'width'     => 0,
    'height'    => 0
);

$docx->addShape($type, $paramsShape);

$docx->createDocx('../docx/example_shape');