<?php

/**
 * Create a DOCX file. Add RTF file in a DOCX file
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

$docx->addText('MHT content');

$docx->addMHT('../files/Web.mht');

$docx->addText('End MHT content');

$docx->createDocx('example_mht');
