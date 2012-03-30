<?php

/**
 * Create a Pdf file. Chart example
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

$Pdf = new CreatePdf();

$data = array(
		'Explorer' => 1.6,
		'Firefox' => 2.8,
		'Chrome' => 3.8,
		'Safari' => 4,
		'Opera' => 5
);

$args = array(
    'data' => $data,
	'title' => 'Wikipedia articles',
    'type' => 'pieChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);
$Pdf->createPdf('example_chart');
die;

$args = array(
    'data' => $data,
	'title' => 'Wikipedia articles',
    'type' => 'pie3DChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);
$data = array(
	    'English' => array(
	        'Jan 2006' => 965,
	        'Feb 2006' => 1000,
	        'Mar 2006' => 1100,
	        'Apr 2006' => 1100,
	        'May 2006' => 1200,
	        'Jun 2006' => 1300,
	    ),
	    'German' =>  array(
	        'Jan 2006' => 357,
	        'Feb 2006' => 371,
	        'Mar 2006' => 387,
	        'Apr 2006' => 402,
	        'May 2006' => 429,
	        'Jun 2006' => 435,
	    ),
	    'Spanish' => array(
	        'Jan 2006' => 49,
	        'Feb 2006' => 52,
	        'Mar 2006' => 56,
	        'Apr 2006' => 59,
	        'May 2006' => 63,
	        'Jun 2006' => 67,
	    ),
);

$args = array(
    'data' => $data,
	'title' => 'Wikipedia articles',
    'type' => 'barChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);

$args = array(
    'data' => $data,
	'title' => 'Wikipedia articles',
    'type' => 'bar3DChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);

$data = array(
	    'English' => array(
	        'Jan 2006' => 1,
	        'Feb 2006' => 2,
	        'Mar 2006' => 3,
	        'Apr 2006' => 4,
	        'May 2006' => 5,
	        'Jun 2006' => 6
	    ),
	    'German' =>  array(
	        'Jan 2006' => 7,
	        'Feb 2006' => 8,
	        'Mar 2006' => 9,
	        'Apr 2006' => 10,
	        'May 2006' => 11,
	        'Jun 2006' => 12
	    ),
	    'Spanish' => array(
	        'Jan 2006' => 13,
	        'Feb 2006' => 14,
	        'Mar 2006' => 15,
	        'Apr 2006' => 16,
	        'May 2006' => 17,
	        'Jun 2006' => 18
	    ),
);

$args = array(
    'data' => $data,
	'title' => 'Wikipedia articles',
    'type' => 'colChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);

$args = array(
    'data' => $data,
    'title' => 'Wikipedia articles',
    'type' => 'col3DChart',
    'cornerX' => 60,
    'color' => 5,
    'sizeX' => 420, 'sizeY' => 400,
    'showPercent' => 1,
);
$Pdf->addChart($args);

$Pdf->createPdf('example_chart');
