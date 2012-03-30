<?php

/**
 * Import header and/or footer from another Word file.
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage easy
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.5
 * @link       http://www.phpdocx.com
 * @since      22-02-2012
 */
require_once '../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->importHeadersAndFooters('../files/TemplateHeaderAndFooter.docx');

$docx->addText('This is the resulting word document with imported header and footer');

//You may import only the header with
//$docx->importHeadersAndFooters('../files/TemplateHeaderAndFooter.docx');
//and only the footer with
//$docx->importHeadersAndFooters('../files/TemplateHeaderAndFooter.docx');

$docx->createDocx('../docx/example_import_header_and_footer');
