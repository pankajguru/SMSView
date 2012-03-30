<?php

/**
 * Transform DOCX to PDF, ODT, SXW, RTF, DOC, TXT, HTML or WIKI
 *
 * @category   Phpdocx
 * @package    converters
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.10.05
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.5
 */

require_once dirname(__FILE__) . '/CreateDocx.inc';

class TransformDocAdv
{
    /**
     * Transform DOCX to PDF, ODT, SXW, RTF, DOC, TXT, HTML or WIKI
     *
     * @access public
     * @param $docSource
     * @param $docDestination
     * @return void
     */
    public function transformDocument($docSource, $docDestination)
    {
        //$resultOpenOffice = system(dirname(__FILE__) . '/../lib/openoffice/program/soffice -headless -accept="socket,host=127.0.0.1,port=8100;urp;" -nofirststartwizard');
        $resultTransform = @system('java -jar ' . dirname(__FILE__) . '/../lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar ' . $docSource . ' ' . $docDestination);
        if(!$resultTransform) {
            die('Could not run jodconverter');
        }
    }
    
}