<?php

/**
 * Create a DOCX file. Template MHT example
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.08.17
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2,3
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->addTemplate('../files/TemplateTableImage.docx');

$docx->getTemplateVariables();

$docx->addTemplateVariable('IMAGE1', '../files/img/imageP1.png', 'image');
$docx->addTemplateVariable('IMAGE2', '../files/img/imageP2.png', 'image');
$docx->addTemplateVariable('IMAGE3', '../files/img/imageP3.png', 'image');

$docx->addTemplateVariable('NAME', 'David Hume');

$docx->createDocx('template_image_text');