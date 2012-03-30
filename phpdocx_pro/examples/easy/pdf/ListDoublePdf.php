<?php

/**
 * Create a DOCX file. List example
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

/*$valuesList = array(
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
);*/

$paramsList = array(
    'val' => 1
);

$docx->addList($valuesList, $paramsList);

/*$valuesList = array(
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
);
$docx->addList($valuesList, $paramsList);*/

$docx->createPdf('example_list.pdf');
