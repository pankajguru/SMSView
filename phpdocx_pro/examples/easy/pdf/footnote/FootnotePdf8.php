<?php

/**
 * Create a DOCX file. Footnote example
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
require_once '../../../classes/CreatePdf.inc';

$docx = new CreatePdf();


$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.';

$paramsText = array(
    'b' => 'single'
);

$docx->addText($text, $paramsText);

$docx->addFootnote(
    array(
        'textDocument' => 'habia una vez una ratita',
        'textEndNote' => 'presumida clarooo'
    )
);

$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);
$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);
$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);
$docx->addFootnote(
    array(
        'textDocument' => 'habia una vez una ratita',
        'textEndNote' => 'presumida clarooo'
    )
);

$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);
$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);
$docx->addFootnote(
    array(
        'textDocument' => 'adios al internet q conocemos ahora',
        'textEndNote' => 'repersi&oacute;n'
    )
);

$docx->createPdf('example_footnote_pdf_8');
