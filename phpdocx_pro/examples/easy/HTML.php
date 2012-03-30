<?php

/**
 * Add HTML code to a Word document.
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
// *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    05.24.2011
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.3
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->addText('HTML content');

$html= '<p><strong>PHPDOCX</strong> is a PHP library designed to generate completely dynamic and fully customizable Word documents.</p>';

$docx->addHTML($html);

$docx->addText('End HTML content');

$docx->createDocx('../docx/example_html');
