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
$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'right',
    'textWrap' => 5,
    'name' => '../../files/img/pieChart.jpg',
    'scaling' => 20
);
$docx->addHeader('Header text', $paramsHeader);
$paramsFooter = array(
    'font' => 'Times New Roman',
    'pager' => false,
    'pagerAlignment' => 'left'
);

$docx->addFooter('Footer. Times New Roman font', $paramsFooter);
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
    array('Line 1.1',
        'Line 1.1',
        $objImg,
        'Line 1.3'),
    'Line 2',
    'Line 3',
    $objLink,
    'Line 5'
);

$arrParamsList = array(
    'val' => 2
);

$objList = $docx->addElement('addList', array($arrDatsList, $arrParamsList));

$valuesTable = array(
    array(
        11,
        12,
        $objList,
        'hola'

    ),
    array(
        $objLink,
        21,
        $objImg,
        22
    ),
);
/*'border' (none, single, double),
     *  'border_color' (ffffff, ff0000),
     *  'border_spacing' (0, 1, 2...),
     *  'border_sz' (10, 11...),
     *  'font' (Arial, Times New Roman...),
     *  'jc' (center, left, right),
     *  'size_col' (1200, 1300...),
     *  'TBLSTYLEval' (Cuadrculamedia3-nfasis1, Sombreadomedio1, Tablaconcuadrcula, TableGrid)*/
$paramsTable = array(
    'border' => 'single',
    'border_sz' => 20,
    'border_color' => 'ff0000',
    'border_spacing' => '1',
    'jc' => 'right',
    //'size_col' => 80,
    'TBLSTYLEval' => ''
);


$docx->addTable($valuesTable, $paramsTable);
$paramsFooter = array(
    'font' => 'Times New Roman'
);

$docx->addFooter('Footer. Times New Roman font', $paramsFooter);

$paramsHeader = array(
    'font' => 'Times New Roman',
    'jc' => 'right',
    'textWrap' => 5,
);

$docx->addHeader('Header text', $paramsHeader);

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.';

$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'sz' => '28',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addText($text, $paramsText);
$valuesTable = array(
    array(
        11,
        12,
        13,
        14,
        15,
        'hola'

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


$docx->addTable($valuesTable, $paramsTable);

$valuesList = array(
    'Line 1',
    'Line 2',
    'Line 3',
    'Line 4',
    'Line 5'
);

$paramsList = array(
    'val' => 1
);

$docx->addList($valuesList, $paramsList);

$docx->addLink('Link to Google', 'http://www.google.es', 'Arial');

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.<br/><br/>';

$paramsText = array(
    'jc' => 'centre',
    'b' => 'single',
    'sz' => '28',
    'color' => 'ff0000',
    'u' => 'dash',
    'i' => 'single'
);

$docx->addText($text, $paramsText);

$paramsImg = array(
    'name' => '../../files/img/image.png',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg
    'scaling' => 50,
    'spacingTop' => 100,
    'spacingBottom' => 0,
    'spacingLeft' => 100,
    'spacingRight' => 0,
    'textWrap' => 1,
    'border' => 1,
    'borderDiscontinuous' => 1
);

$docx->addImage2($paramsImg);
$paramsFooter = array(
    'font' => 'Times New Roman',
    'jc' => 'right',
    'textWrap' => 5,
    'name' => '../../files/img/image.png',
    'scaling' => 10
);
$docx->addFooter('Footer. Times New Roman font', $paramsFooter);
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
$valuesList = array(
    'Line 1',
    'Line 2',
    array(
        'Line A',
        array(
            'Line B1',
            array(
                'Line B2.1',
                'Line B2.2',
                'Line B2.3',
                'Line B2.4',
                'Line B2.5'
            ),
            'Line B3',
            'Line B4',
            'Line B5'
        ),
        'Line C*',
        array(
            'Line 1*',
            'Line 2*',
            array(
                'Line 3.A*',
                'Line 3.B*',
                'Line 3.C*',
                'Line 3.D*',
                'Line 3.E*'
            ),
            'Line 4*',
            array(
                'Line 5.A*',
                'Line 5.B*',
                'Line 5.C*',
                'Line 5.D*',
                'Line 5.E*'
            ),
            'Line 6*'
        ),
        'Line E'
    ),
    'Line 4',
    'Line 5',
    array(
        'Line 1',
        'Line 2',
        array(
            'Line 3.A',
            'Line 3.B',
            'Line 3.C',
            'Line 3.D',
            'Line 3.E'
        ),
        'Line 4',
        array(
            'Line 5.A',
            'Line 5.B',
            'Line 5.C',
            'Line 5.D',
            'Line 5.E'
        ),
        'Line 6'
    )
);
$paramsList = array(
    'val' => 1
);

$docx->addList($valuesList, $paramsList);
$docx->addLink('Link to Google', 'http://www.google.es', 'Arial');
$paramsImg = array(
    'name' => '../../files/img/pieChart.jpg',
    'scaling' => 20,
    'jc' => 'right'
);

$docx->addImage($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/logo_phpdocx.gif',
//    'name' => '../../files/img/logo_phpdocx.gif',//http://www.2mdc.com/imagenes/images/logo_2mdc_header_60.jpg //'../../files/img/pieChart.jpg'
    'scaling' => 80,
    'jc' => 'center'//'center'
);

$docx->addImage($paramsImg);
$paramsImg = array(
    'name' => '../../files/img/image.png',
    'scaling' => 40,
    'jc' => 'asdf'//'center'
);

$docx->addImage($paramsImg);

$docx->createPdf('example_table');
