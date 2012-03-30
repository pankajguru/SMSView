<?php

/**
 * Create a DOCX file. Image example
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

$arrParamsLink = array(
    'title' => 'Link to Google',
    'link' => 'http://www.google.es'
);

$objLink = $docx->addElement('addLink', $arrParamsLink);

$arrParamsImg = array(
    'name' => '../../files/img/image.png'
);

$objImg = $docx->addElement('addImage', $arrParamsImg);

$arrDatsList = array(
    'Line 1',
    $objLink,
    $objImg,
    'Line 2',
    'Line 3',
);
$arrParamsList = array(
    'val' => 1
);
$docx->addList($arrDatsList, $arrParamsList);

$docx->createPdf('example_list_image');
