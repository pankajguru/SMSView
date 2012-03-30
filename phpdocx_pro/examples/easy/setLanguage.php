<?php

/**
 * Sets the default language of the document.
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
// *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    05.24.2011
 * @link       http://www.phpdocx.com
 * @since      27-02-2012
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->setLanguage('en-ES');

$docx->AddText('Este documento tiene el español de España como idioma por defecto (The default document language has been set to Spanish-Spain).');

$docx->createDocx('../docx/example_setLanguage');