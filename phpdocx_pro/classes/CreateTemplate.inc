<?php

/**
 * Use DOCX as templates
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.19.03
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 1.7
 */
class CreateTemplate
{

    /**
     * @access public
     * @var array
     * @static
     */
    public static $embedFiles = array();
    /**
     * @access public
     * @var string
     * @static
     */
    public static $path;
    /**
     * @access public
     * @var array
     * @static
     */
    public static $placeholderImages = array();
    /**
     * @access public
     * @var int
     * @static
     */
    public static $ridInitTemplateCharts;
    /**
     * @access public
     * @var string
     * @static
     */
    public static $templateVariables = array();
    /**
     * @access public
     * @var int
     * @static
     */
    public static $totalTemplateCharts;
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_chartsRelsChartXMLRels;
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_contentTypes;
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_document;
    /**
     * @access private
     * @var array
     * @static
     */
    private static $_footer = array();
    /**
     * @access private
     * @var array
     * @static
     */
    private static $_header = array();
    /**
     * @access private
     * @var int
     */
    private $_idDOCX = 1;
    /**
     * @access private
     * @var int
     */
    private $_idHTML = 1;
    /**
     * @access private
     * @var int
     */
    private $_idMHT = 1;
    /**
     * @access private
     * @var int
     */
    private $_idRTF = 1;
    /**
     * @access private
     * @var CreateTemplate
     * @static
     */
    private static $_instance = NULL;
    /**
     * @access private
     * @var boolean
     */
    private $_isDOCX = false;
    /**
     * @access private
     * @var boolean
     */
    private $_isHTML = false;
    /**
     * @access private
     * @var boolean
     */
    private $_isMHT = false;
    /**
     * @access private
     * @var boolean
     */
    private $_isRTF = false;
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_relsDocumentXMLRels;
    /**
     * @access private
     * @var bool
     * @static
     */
    private static $_template;
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_templateGroupSymbol = '|';
    /**
     * @access private
     * @var string
     * @static
     */
    private static $_templateSymbol = '$';

    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {

    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct()
    {
    }

    /**
     *
     * @access public
     * @return CreateTemplate
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateTemplate();
        }
        return self::$_instance;
    }

    /**
     * Check if DOCX is a template
     *
     * @access public
     * @return boolean
     * @static
     */
    public static function getBlnTemplate()
    {
        return self::$_template;
    }

    /**
     * Return current document word
     *
     * @access public
     * @return string
     * @static
     */
    public static function getDocument()
    {
        return self::$_document;
    }

    /**
     * Return current document footer
     *
     * @access public
     * @return array
     * @static
     */
    public static function getFooter()
    {
        return self::$_footer;
    }

    /**
     * Return current document header
     *
     * @access public
     * @return array
     * @static
     */
    public static function getHeader()
    {
        return self::$_header;
    }

    /**
     * Getter. Return template group symbol
     *
     * @access public
     * @return string
     * @static
     */
    public static function getTemplateGroupSymbol()
    {
        return self::$_templateGroupSymbol;
    }

    /**
     * Setter. Change if DOCX is a template
     *
     * @access public
     * @return void
     * @static
     */
    public static function setBlnTemplate($enable)
    {
        self::$_template = $enable;
    }

    /**
     * Setter. Set template group symbol
     *
     * @access public
     * @param string $templateSymbol
     * @static
     */
    public static function setTemplateGroupSymbol($templateGroupSymbol)
    {
        self::$_templateGroupSymbol = $templateGroupSymbol;
    }

    /**
     * Getter. Return template symbol
     *
     * @access public
     * @return string
     * @static
     */
    public static function getTemplateSymbol()
    {
        return self::$_templateSymbol;
    }

    /**
     * Setter. Set template symbol
     *
     * @access public
     * @param string $templateSymbol
     * @static
     */
    public static function setTemplateSymbol($templateSymbol)
    {
        self::$_templateSymbol = $templateSymbol;
    }

    /**
     * Getter. Return template Variables
     *
     * @access public
     * @return array
     * @static
     */
    public function getTemplateVariables()
    {
        return self::$templateVariables;
    }

    /**
     * Setter. Set template Variables
     *
     * @access public
     * @param array $templateVariables
     * @static
     */
    public function setTemplateVariables($templateVariables)
    {
        self::$templateVariables = $templateVariables;
    }

    /**
     * Return current rels document xml rels
     *
     * @access public
     * @return string
     * @static
     */
    public static function getRelsDocumentXMLRels()
    {
        return self::$_relsDocumentXMLRels;
    }

    /**
     * Return current content types
     *
     * @access public
     * @return string
     * @static
     */
    public static function getContentTypes()
    {
        return self::$_contentTypes;
    }

    /**
     * Add content types
     *
     * @access public
     * @param string $args[0]
     * @static
     */
    public static function addContentTypes()
    {
        $args = func_get_args();

        if (strstr(self::$_contentTypes, 'Default Extension="xlsx"') &&
            $args[0] == '<Default Extension="xlsx" ContentType="application' .
                        '/octet-stream"> </Default>'
        ) {
            return;
        }
        $domContentTypes = new DomDocument();
        $domContentTypes->loadXML(self::$_contentTypes);
        $xmlTypes = $domContentTypes->getElementsByTagName('Types');
        $xmlNewContentType = $domContentTypes->createDocumentFragment();
        $xmlNewContentType->appendXML($args[0]);
        $xmlTypes->item(0)->appendChild($xmlNewContentType);

        self::$_contentTypes = $domContentTypes->saveXML();
    }

    /**
     * Add relationship
     *
     * @access public
     * @param string $args[0]
     * @static
     */
    public static function addRelationship()
    {
        $args = func_get_args();

        $domRelsDocumentXMLRels = new DomDocument();
        $domRelsDocumentXMLRels->loadXML(self::$_relsDocumentXMLRels);
        $xmlTypes = $domRelsDocumentXMLRels->getElementsByTagName(
            'Relationships'
        );
        $xmlNewRelationship = $domRelsDocumentXMLRels->createDocumentFragment();
        $xmlNewRelationship->appendXML($args[0]);
        $xmlTypes->item(0)->appendChild($xmlNewRelationship);

        self::$_relsDocumentXMLRels = $domRelsDocumentXMLRels->saveXML();
    }

    /**
     * clear all the placeholders which start with 'BLOCK_'
     *
     * @access public
     */
    public function deleteAllBlocks()
    {
        // Sometimes Word splits tags. Find and replace all of them with
        // new string surrounded by template symbol value
        $documentSymbol = explode(self::$_templateSymbol, self::$_document);
        foreach ($documentSymbol as $documentSymbolValue) {
            if (strpos(strip_tags($documentSymbolValue), 'BLOCK_') !== false) {
                self::$_document = str_replace($documentSymbolValue, strip_tags($documentSymbolValue), self::$_document);
            }
        }
        $domDocument = new DomDocument();
        $domDocument->loadXML(self::$_document);

        $xmlWP = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main',
                                                      'p');
        $xpath = new DOMXPath($domDocument);
        $length = $xmlWP->length;
        $itemsWP = array();
        for ($i = 0; $i < $length; $i++) {
            $itemsWP[$i] = $xmlWP->item($i);
        }
        $query = 'w:r/w:t';
        for ($i = 0; $i < $length; $i++) {
            $variables = $xpath->query($query, $itemsWP[$i]);
            foreach ($variables as $entry) {
                $deleteCurrent = false;
                if (
                    strpos($entry->nodeValue,
                           self::$_templateSymbol . 'BLOCK_'
                    ) !== false
                ) {
                    //when we find a placeholder, we delete it
                    $deleteCurrent = true;
                    break;
                }
            }
            if ($deleteCurrent) {
                $padre = $itemsWP[$i]->parentNode;
                $padre->removeChild($itemsWP[$i]);
                self::$_document = $domDocument->saveXML();
            }
        }
    }

    /**
     * Clear a specific placeholder which starts with 'BLOCK_'
     *
     * @access public
     * @param string $blockName Block name
     */
    public function deleteBlock($blockName)
    {
        $aType = array('BLOCK_', 'TAB_'); //deletables types
        foreach($aType as $type){
            self::parseBlock($type . $blockName);
            self::$_document = preg_replace('/\\'.self::$_templateSymbol.$type.$blockName.'([|]|\\'.self::$_templateSymbol.').*?\\'.self::$_templateSymbol.$type.$blockName.'.*?\\'.self::$_templateSymbol.'/', '', self::$_document);
            /*$ini = strpos(self::$_document, self::$_templateSymbol . $type . $blockName . self::$_templateSymbol);
            //CreateDocx::$log->info('borra bloque ' . $ini . '.');
            if ($ini !== false) {
                $end = strpos(self::$_document, self::$_templateSymbol . $type . $blockName . self::$_templateSymbol, $ini + 1);
                if ($end !== false) {
                    self::$_document = substr(self::$_document, 0, $ini) .
                                       substr(self::$_document, $ini + (strlen(self::$_templateSymbol . $type . $blockName . self::$_templateSymbol) * 2) + $end);
                }
            }*/
        }
    }
    
     /**
     * extract the headers or the footers for the merging
     *
     * @access private
     */
    private function extractHeaderFooter($document, $relsWord, $type) {
        $domDocument = new DomDocument();
        $domDocument->loadXML($document);
        //check if there are footers
        $xmlWP = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', $type . 'Reference');
        $length = $xmlWP->length;
        $itemsWP = array();
        $buenos = array();
        for ($i = 0; $i < $length; $i++) {
            if($xmlWP->item($i)->attributes->getNamedItem("type")->nodeValue == 'default'){
                //only extract the footers or the headers which are used in document.xml
                $itemsWP[$xmlWP->item($i)->attributes->getNamedItem("id")->nodeValue] = $xmlWP->item($i)->attributes->getNamedItem("type")->nodeValue;
                $buenos[] = $xmlWP->item($i)->attributes->getNamedItem("id")->nodeValue;
            }
        }
        $domDocument = new DomDocument();
        $domDocument->loadXML($relsWord);
        $xmlWP = $domDocument->getElementsByTagName('Relationship');
        $length = $xmlWP->length;
        $newfooters = array();
        for ($i = 0; $i < $length; $i++) {
            $aux = $xmlWP->item($i);
            foreach ($buenos as $val) {
                if ($aux->attributes->getNamedItem("Id")->nodeValue == $val) {
                    //get the rId from the footers and headers
                    $newfooters[$aux->attributes->getNamedItem("Target")->nodeValue]['rid'] = $aux->attributes->getNamedItem("Id")->nodeValue;
                    $newfooters[$aux->attributes->getNamedItem("Target")->nodeValue]['node'] = $domDocument->saveXML($aux);
                }
            }
        }
        return $newfooters;
    }

    /**
     * Merge the content, the footer and the header from two files
     *
     * @access public
     * @param  $path1 File path
     * @param  $path2 File path
     */
    public function mergeFiles($path1, $path2)
    {
        //use the first file as template
        self::openTemplate($path1);
        $_newFile = tempnam(sys_get_temp_dir(), 'merge_phpdocx');
        copy($path2, $_newFile);
        if (file_exists($_newFile)) {
            $docx = new ZipArchive();
            $docx->open($_newFile);
            // read document.xml file and extract the wordxml content
            $fullContent = $docx->getFromName('word/document.xml');
            $fullContent = self::cleanDocument($fullContent);

            $startedRemovedUnwantedContent = explode('<w:body>', $fullContent);
            $endedRemovedUnwantedContent = explode('</w:body', $startedRemovedUnwantedContent[1]);
            $newDocumentXml = $endedRemovedUnwantedContent[0];
            //extract files from the zip
            $relsWord = $docx->getFromName('word/_rels/document.xml.rels');
            $_contentType = $docx->getFromName('[Content_Types].xml');
            self::$_document = self::prepareSections(self::$_document);
            self::$_document = str_replace('</w:body',
                                           str_replace($aToBeReplace, $aToReplace, $newDocumentXml) . '</w:body',
                                           self::$_document);
        }
    }

    /**
     * Open current template
     *
     * @access public
     * @param string $args[0]
     * @static
     */
    public function openTemplate()
    {
        $args = func_get_args();

        $this->objTemplate = new ZipArchive();

        self::$path = $args[0];
        self::$_template = 1;
        $this->_idDOCX = 1;
        $this->_idHTML = 1;
        $this->_idMHT = 1;
        $this->_idRTF = 1;
        $this->_isDOCX = false;
        $this->_isHTML = false;
        $this->_isMHT = false;
        $this->_isRTF = false;
        $this->objTemplate->open(self::$path);

        self::$_document = $this->objTemplate->getFromName('word/document.xml');

        if ($this->objTemplate->statName('word/_rels/document.xml.rels')) {
            self::$_relsDocumentXMLRels = $this->objTemplate->getFromName(
                'word/_rels/document.xml.rels'
            );

            $this->getCurrentRID();
        }

        if ($this->objTemplate->statName('word/charts/_rels/chart1.xml.rels')) {
            self::$_chartsRelsChartXMLRels = $this->objTemplate->getFromName(
                'word/charts/_rels/chart1.xml.rels'
            );
        }

        if ($this->objTemplate->statName('[Content_Types].xml')) {
            self::$_contentTypes = $this->objTemplate->getFromName(
                '[Content_Types].xml'
            );
        }

        if ($this->objTemplate->statName('word/footer1.xml')) {
            self::$_footer[] = $this->objTemplate->getFromName(
                'word/footer1.xml'
            );
        }
        if ($this->objTemplate->statName('word/footer2.xml')) {
            self::$_footer[] = $this->objTemplate->getFromName(
                'word/footer2.xml'
            );
        }
        if ($this->objTemplate->statName('word/footer3.xml')) {
            self::$_footer[] = $this->objTemplate->getFromName(
                'word/footer3.xml'
            );
        }
        if ($this->objTemplate->statName('word/header1.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header1.xml'
            );
        }
        if ($this->objTemplate->statName('word/header2.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header2.xml'
            );
        }
        if ($this->objTemplate->statName('word/header3.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header3.xml'
            );
        }
        if ($this->objTemplate->statName('word/header4.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header4.xml'
            );
        }
        if ($this->objTemplate->statName('word/header5.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header5.xml'
            );
        }
        if ($this->objTemplate->statName('word/header6.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header6.xml'
            );
        }
        if ($this->objTemplate->statName('word/header7.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header7.xml'
            );
        }
        if ($this->objTemplate->statName('word/header8.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header8.xml'
            );
        }
        if ($this->objTemplate->statName('word/header9.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header9.xml'
            );
        }
        if ($this->objTemplate->statName('word/header10.xml')) {
            self::$_header[] = $this->objTemplate->getFromName(
                'word/header10.xml'
            );
        }
        if ($this->objTemplate->statName('word/charts/_rels/chart1.xml.rels')) {
            self::$_chartsRelsChartXMLRels = $this->objTemplate->getFromName(
                'word/charts/_rels/chart1.xml.rels'
            );
        }
    }

    /**
     * Prepare the placeholder. Clear the wordXML code between the characters of the placeholder.
     *
     * @access public
     * @param string $blockName Block name
     */
    public function parseBlock($blockName)
    {
        // Sometimes Word splits tags. Find and replace all of them with
        $documentSymbol = explode(self::$_templateSymbol, self::$_document);
        foreach ($documentSymbol as $documentSymbolValue) {
            if (strip_tags($documentSymbolValue) == $blockName) {
                self::$_document = str_replace($documentSymbolValue, $blockName, self::$_document);
            }
        }
    }
    
    /**
     * prepares sections for merging
     *
     * @access public
     */
    public function prepareSections($document){
        $domDocument = new DomDocument();
        $domDocument->loadXML($document);
        //check if there are footers
        $xmlWSectPr = $domDocument->getElementsByTagNameNS(
                        'http://schemas.openxmlformats.org/wordprocessingml/2006/main','sectPr');
        
        $length = $xmlWSectPr->length;
        //get the last section to modify
        $itemsWSectPr = $xmlWSectPr->item($length -1);
        $new = $itemsWSectPr->cloneNode(true);
        $padre = $itemsWSectPr->parentNode;
        $padre->removeChild($itemsWSectPr);
        
        
        $xmlWP = $domDocument->getElementsByTagNameNS(
                        'http://schemas.openxmlformats.org/wordprocessingml/2006/main','p');
        $length = $xmlWP->length;
        $itemsWP = $xmlWP->item($length - 1);
        if($itemsWP->hasChildNodes()){
            $existspPr = false;
            $aSons = array();
            foreach($itemsWP->childNodes as $hijo){
                if($hijo->tagName == 'w:pPr'){
                    $existspPr = true;
                    foreach($hijo->childNodes as $nieto){
                        if($nieto->tagName == 'w:sectPr'){
                            $hijo->removeChild($nieto);
                        }                
                    }
                    $hijo->appendChild($new);
                }else{
                    $aSons[] = $hijo->cloneNode(true);
                    $itemsWP->removeChild($hijo);
                }
            }
            if(!$existspPr){
                $node = $domDocument->createElement("w:pPr");
                $node->appendChild($new);
                $itemsWP->appendChild($node);
                foreach($aSons as $val){
                    $itemsWP->appendChild($val);
                }
            }
        }else{
            $node = $domDocument->createElement("w:pPr");
            $node->appendChild($new);
            $itemsWP->appendChild($node);
        }
        return $domDocument->saveXML();
    }

    /**
     * Replace chart in template
     *
     * @access public
     * @param string $args[0]
     * @static
     */
    public static function replaceChart()
    {
        $args = func_get_args();
        $documentSymbol = explode(self::$_templateSymbol, self::$_document);
        foreach ($documentSymbol as $documentSymbolValue) {
            if (strip_tags($documentSymbolValue) == $args[0]) {
                self::$_document = str_replace($documentSymbolValue, strip_tags($documentSymbolValue), self::$_document);
            }
        }

        $domDocument = new DomDocument();
        $domDocument->loadXML(self::$_document);

        $xmlWP = $domDocument->getElementsByTagNameNS(
            'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
            'p'
        );
        $xpath = new DOMXPath($domDocument);
        $query = 'w:r/w:t';
        for ($i = 0; $i < $xmlWP->length; $i++) {
            $xmlGraphics = $xpath->query($query, $xmlWP->item($i));
            foreach ($xmlGraphics as $entry) {
                if (
                    self::$_templateSymbol . $args[0] .
                    self::$_templateSymbol ==
                    $entry->nodeValue
                ) {
                    $domChart = $entry->parentNode->parentNode;
                    $xmlR = $domChart->getElementsByTagNameNS(
                        'http://schemas.openxmlformats.org/wordprocessingml/' .
                        '2006/main',
                        'r'
                    )->item(0);

                    $domChart->removeChild($xmlR);
                    $domVarChart = $domDocument->createElement(
                        'varchart_' . CreateDocx::$intIdWord
                    );
                    $domChart->appendChild($domVarChart);
                }
            }
        }
        self::$_document = $domDocument->saveXML();
        self::$totalTemplateCharts++;
    }

    /**
     * Replace a placeholder with a checkbox
     *
     * @access public
     * @param string checkName Checkbox variable name
     * @param string $value Value
     */
    public function replaceCheckbox($checkName, $value)
    {
        self::parseBlock('CHECK_' . $checkName);
        $domDocument = new DomDocument();
        $domDocument->loadXML(self::$_document);

        $xmlWP = $domDocument->getElementsByTagNameNS(
            'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
            'p'
        );
        $xpath = new DOMXPath($domDocument);
        $length = $xmlWP->length;
        $itemsWP = array();
        for ($i = 0; $i < $length; $i++) {
            $itemsWP[$i] = $xmlWP->item($i);
        }
        $query = 'w:r/w:t';
        for ($i = 0; $i < $length; $i++) {
            $variables = $xpath->query($query, $itemsWP[$i]);
            foreach ($variables as $entry) {
                if (
                    strpos($entry->nodeValue, self::$_templateSymbol . 'CHECK_' . $checkName . self::$_templateSymbol)
                    !== false
                ) {
                    $entry->setAttribute('xml:space', 'preserve');
                    $padre = $entry->parentNode->parentNode;
                    $newNode = $domDocument->createElement('w:r', '');
                    $newNode2 = $domDocument->createElement('w:sym', '');
                    $newNode2->setAttribute('w:font', 'Wingdings');
                    if ($value)
                        $newNode2->setAttribute('w:char', 'F0FE');
                    else
                        $newNode2->setAttribute('w:char', 'F06F');
                    $newNode->appendChild($newNode2);
                    $padre->insertBefore($newNode, $entry->parentNode);

                    break;
                }
            }
        }
        self::$_document = str_replace(self::$_templateSymbol . 'CHECK_' . $checkName . self::$_templateSymbol,
                                       '', $domDocument->saveXML());
    }

    /**
     * Reset class variables
     *
     * @access public
     * @param string $args[0]
     * @param string $args[1]
     * @static
     */
    public static function reset()
    {
        self::setBlnTemplate(false);
        self::$embedFiles = array();
        self::$placeholderImages = array();
        self::$path = '';
        self::$ridInitTemplateCharts = '';
        self::$templateVariables = array();
        self::$totalTemplateCharts = '';
        self::$_chartsRelsChartXMLRels = '';
        self::$_contentTypes = '';
        self::$_document = '';
        self::$_footer = array();
        self::$_header = array();
        self::$_relsDocumentXMLRels = '';
        self::$_template = '';
    }

    /**
     * Replace image in template
     *
     * @access public
     * @param string $args[0]
     * @param string $args[1]
     * @static
     */
    public function replaceImage()
    {
        $args = func_get_args();

        $domDocument = new DomDocument();
        $domDocument->loadXML(self::$_document);

        $domImages = $domDocument->getElementsByTagNameNS('http://schemas.openxmlformats.org/drawingml/2006/' .
                                                          'wordprocessingDrawing', 'docPr');
        $domImagesId = $domDocument->getElementsByTagNameNS(
            'http://schemas.openxmlformats.org/drawingml/2006/main',
            'blip'
        );

        for ($i = 0; $i < $domImages->length; $i++) {
            if ($domImages->item($i)->getAttribute('descr') ==
                self::$_templateSymbol . $args[0] . self::$_templateSymbol) {
                $ind = $domImages->item($i)->parentNode
                	->getElementsByTagNameNS('http://schemas.openxmlformats.org/drawingml/2006/main', 'blip')
                	->item(0)->getAttribute('r:embed');
                self::$placeholderImages[$ind] = $args[1];
            }
        }
    }

    /**
     * Replace images in template
     *
     * @access public
     * @param ZipArchive $docxTemplate
     * @static
     */
    public static function replaceImages(&$docxTemplate)
    {
        $domRelsDocumentXMLRels = new DomDocument();
        $domRelsDocumentXMLRels->loadXML(self::$_relsDocumentXMLRels);

        $domRelationships = $domRelsDocumentXMLRels->getElementsByTagName(
            'Relationship'
        );

        foreach ($domRelationships as $domRelationship) {
            $id = $domRelationship->getAttribute('Id');
            if (self::$placeholderImages[$id]) {
                $docxTemplate->addFile(
                    self::$placeholderImages[$id],
                    'word/' . $domRelationship->getAttribute('Target')
                );
            }
        }
    }

    /**
     * Replace variables in template.
     * Allows text strings and array of values as variables.
     *
     * @access public
     * @param mixed $args[0]. Array or string
     * @param mixed $args[1]
     * @param mixed $args[2]. Optional. Settings template
     * @static
     */
    public function replaceVariable()
    {
        $args = func_get_args();
        $cloneDom = '';
        $domParent = '';

        if (is_string($args[0]) || $args[1] == 'table' || $args[1] == 'list') {
            // Sometimes Word splits tags. Find and replace all of them with
            // new string surrounded by template symbol value
            $documentSymbol =
                explode(self::$_templateSymbol, self::$_document);
            foreach ($documentSymbol as $documentSymbolValue) {
                //ignore group [tag_name|group]
                $tempSearch = explode('|', trim(strip_tags($documentSymbolValue)));
                $tempSearch = $tempSearch[0];

                if ($tempSearch == $args[0]) {
                    self::$_document = str_replace(
                        $documentSymbolValue, $args[0], self::$_document
                    );
                }
            }
            
            $i = 0;
            foreach (self::$_footer as $footer) {
                $documentSymbolFooter = explode(self::$_templateSymbol, self::$_footer[$i]);
                foreach ($documentSymbolFooter as $documentSymbolValue) {
                    if (strip_tags($documentSymbolValue) == $args[0]) {
                        self::$_footer[$i] =
                                str_replace($documentSymbolValue, $args[0], self::$_footer[$i]);
                    }
                }
                $i++;
            }

            $i = 0;
            foreach (self::$_header as $header) {
                $documentSymbolHeader = explode(self::$_templateSymbol, self::$_header[$i]);
                foreach ($documentSymbolHeader as $documentSymbolValue) {
                    $tempSearch = explode('|', trim(strip_tags($documentSymbolValue)));
                    $tempSearch = $tempSearch[0];
                    if ($tempSearch == $args[0]) {
                        self::$_header[$i] =
                            str_replace($documentSymbolValue, $args[0], self::$_header[$i]);
                    }
                }
                $i++;
            }
        }

        // Replace VAR within DOCX.
        if ($args[2] == 'docx' && is_string($args[0]) && is_string($args[1])) {
            if (!$this->_isDOCX) {
                $this->addContentTypes('<Default Extension="zip" ContentType="application/vnd.openxmlformats'
                                       . '-officedocument.wordprocessingml.document.main+xml"> </Default>');
                $this->_isDOCX = true;
            }
            $this->addRelationship('<Relationship Id="rDOCXId' . $this->_idDOCX .
                                   '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/' .
                                   'aFChunk" Target="docx' . $this->_idDOCX .
                                   '.zip" TargetMode="Internal"></Relationship>');
            // add source and dest filepath
            self::$embedFiles[] = array(
                'src_file' => $args[1],
                'dest_file' => 'docx' . $this->_idDOCX . '.zip'
            );
            $args[1] = '<' . CreateElement::NAMESPACEWORD . ':altChunk r:id="rDOCXId' . $this->_idDOCX . '" ' .
                       'xmlns:r="http://schemas.openxmlformats.org/' . 'officeDocument/2006/relationships" ' .
                       'xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" />';
            $this->_idDOCX++;
        }

        // Replace VAR within MHT.
        if ($args[2] == 'mht' && is_string($args[0]) && is_string($args[1])) {
            if (!$this->_isMHT) {
                $this->addContentTypes('<Default Extension="mht" ContentType="message/rfc822"> </Default>');
                $this->_isMHT = true;
            }
            $this->addRelationship('<Relationship Id="rMHTId' . $this->_idMHT .
                                   '" Type="http://schemas.openxmlformats.org/officeDocument/2006/' .
                                   'relationships/aFChunk" Target="mht' . $this->_idMHT .
                                   '.mht" TargetMode="Internal"></Relationship>');
            // add source string and dest filepath
            self::$embedFiles[] = array(
                'src_file' => $args[1],
                'dest_file' => 'mht' . $this->_idMHT . '.mht'
            );
            $args[1] = '<' . CreateElement::NAMESPACEWORD . ':altChunk r:id="rMHTId' . $this->_idMHT . '" ' .
                       'xmlns:r="http://schemas.openxmlformats.org/' . 'officeDocument/2006/relationships" ' .
                       'xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" />';
            $this->_idMHT++;
        }

        // Replace VAR within HTML.
        if ($args[2] == 'html' && is_string($args[0]) && is_string($args[1])) {
            if (!$this->_isHTML) {
                $this->addContentTypes('<Default Extension="htm" ContentType="application/xhtml+xml"> </Default>');
                $this->_isHTML = true;
            }
            $this->addRelationship('<Relationship Id="rHTMLId' . $this->_idHTML .
                                   '" Type="http://schemas.openxmlformats.org/officeDocument/2006/' .
                                   'relationships/aFChunk" Target="html' . $this->_idHTML .
                                   '.htm" TargetMode="Internal"></Relationship>');
            // add source string and dest filepath
            self::$embedFiles[] = array(
                'src_string' => '<html>' . $args[1] . '</html>',
                'dest_file' => 'html' . $this->_idHTML . '.htm'
            );
            $args[1] = '<' . CreateElement::NAMESPACEWORD . ':altChunk r:id="rHTMLId' . $this->_idHTML . '" ' .
                       'xmlns:r="http://schemas.openxmlformats.org/' . 'officeDocument/2006/relationships" ' .
                       'xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" />';
            $this->_idHTML++;
        }

        // Replace VAR within an image.
        if ($args[2] == 'image' && is_string($args[0]) && is_string($args[1])) {
            // transform image to MHT file to embed it
            $imageToMHT = new MhtFileMaker();
            list($imgWidth, $imgHeight, $imgType, $imgAttr) = getimagesize($args[1]);
            $imageToMHT->AddContents('file:///C:/2673C891/Doc1.htm', 'text/html; charset="us-ascii"', chunk_split('<html xmlns:v=3D"urn:schemas-microsoft-com:vml" xmlns:o=3D"urn:schemas-microsoft-com:office:office" xmlns:w=3D"urn:schemas-microsoft-com:office:word" xmlns:m=3D"http://schemas.microsoft.com/office/2004/12/omml" xmlns=3D"http://www.w3.org/TR/REC-html40"><head></head><body lang=3DEN-US style=3D\'tab-interval:36.0pt\'><div class=3DSection1><p class=3DMsoNormal><span style=3D\'mso-no-proof:yes\'><!--[if gte vml 1]><v=:shapetype id=3D"_x0000_t75" coordsize=3D"21600,21600" o:spt=3D"75" o:preferrelative==3D"t" path=3D"m@4@5l@4@11@9@11@9@5xe" filled=3D"f" stroked=3D"f"> <v:stroke joinstyle=3D"miter"/> <v:formulas><v:f eqn=3D"if lineDrawn pixelLineWidth 0"/><v:f eqn=3D"sum @0 1 0"/><v:f eqn=3D"sum 0 0 @1"/><v:f eqn=3D"prod @2 1 2"/><v:f eqn=3D"prod @3 21600 pixelWidth"/><v:f eqn=3D"prod @3 21600 pixelHeight"/><v:f eqn=3D"sum @0 0 1"/><v:f eqn=3D"prod @6 1 2"/><v:f eqn=3D"prod @7 21600 pixelWidth"/><v:f eqn=3D"sum @8 21600 0"/><v:f eqn=3D"prod @7 21600 pixelHeight"/><v:f eqn=3D"sum @10 21600 0"/></v:formulas><v:path o:extrusionok=3D"f" gradientshapeok=3D"t" o:connecttype=3D"rect"/><o:lock v:ext=3D"edit" aspectratio=3D"t"/></v:shapetype><v:shape id=3D"Picture_x0020_1" o:spid=3D"_x0000_i1025" type=3D"#_x0000_t75" style=3D\'width:' . $imgWidth . 'pt;height:' . $imgHeight . 'pt;visibility:visible;mso-wrap-style:square\'><v:imagedata src=3D"Doc1_files/image001.' . $imageToMHT->GetExtension($args[1]) . '" o:title=3D""/></v:shape><![endif]--></span></p></div></body></html>', 5000), 'quoted-printable');
            $imageToMHT->AddFile($args[1]);
            if (!$this->_isMHT) {
                $this->addContentTypes('<Default Extension="mht" ContentType="message/rfc822"> </Default>');
                $this->_isMHT = true;
            }
            $this->addRelationship('<Relationship Id="rMHTId' . $this->_idMHT .
                                   '" Type="http://schemas.openxmlformats.org/officeDocument/2006/' .
                                   'relationships/aFChunk" Target="mht' . $this->_idMHT .
                                   '.mht" TargetMode="Internal"></Relationship>');
            // add source string and dest filepath
            self::$embedFiles[] = array(
                'src_string' => $imageToMHT->GetFile(),
                'dest_file' => 'mht' . $this->_idMHT . '.mht'
            );
            $args[1] = '<' . CreateElement::NAMESPACEWORD . ':altChunk r:id="rMHTId' . $this->_idMHT . '" ' .
                       'xmlns:r="http://schemas.openxmlformats.org/' . 'officeDocument/2006/relationships" ' .
                       'xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" />';
            $this->_idMHT++;
        }

        // Replace VAR within RTF.
        if ($args[2] == 'rtf' && is_string($args[0]) && is_string($args[1])) {
            if (!$this->_isRTF) {
                $this->addContentTypes('<Default Extension="rtf" ContentType="application/rtf"> </Default>');
                $this->_isRTF = true;
            }
            $this->addRelationship('<Relationship Id="rRTFId' . $this->_idRTF .
                                   '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/' .
                                   'aFChunk" Target="rtf' . $this->_idRTF . '.rtf" TargetMode="Internal">' .
                                   '</Relationship>');
            // add source and dest filepath
            self::$embedFiles[] = array(
                'src_file' => $args[1],
                'dest_file' => 'rtf' . $this->_idRTF . '.rtf'
            );
            $args[1] = '<' . CreateElement::NAMESPACEWORD .
                       ':altChunk r:id="rRTFId' . $this->_idRTF . '" ' .
                       'xmlns:r="http://schemas.openxmlformats.org/' .
                       'officeDocument/2006/relationships" ' .
                       'xmlns:w="http://schemas.openxmlformats.org/' .
                       'wordprocessingml/2006/main" />';
            $this->_idRTF++;
        }

        // Only lists and tables are supported.
        if (($args[1] == 'list' || $args[1] == 'table') && is_array($args[0])) {
            $domDocument = new DomDocument();
            $domDocument->loadXML(self::$_document);

            $xmlWP = $domDocument->getElementsByTagNameNS(
                'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
                'p'
            );
            $xpath = new DOMXPath($domDocument);
            $query = 'w:r/w:t';
            $length = $xmlWP->length;
            $itemsWP = array();
            for ($i = 0; $i < $length; $i++) {
                $itemsWP[$i] = $xmlWP->item($i);
            }
            for ($i = 0; $i < $length; $i++) {
                $variables = $xpath->query($query, $itemsWP[$i]);
                foreach ($variables as $entry) {
                    if (isset($args[2]['header'])) {
                        foreach ($args[0] as $valuesArray) {
                            foreach ($valuesArray as $keys => $values) {
                                if (empty($entry->nodeValue)) {
                                    continue;
                                }
                                if (
                                    strpos(
                                        $entry->nodeValue,
                                        self::$_templateSymbol . $keys .
                                        self::$_templateSymbol
                                    ) !== false
                                ) {
                                    if ($args[1] == 'list') {
                                        $domP = $entry->parentNode->parentNode;
                                        $domParent = $entry->parentNode->
                                        parentNode->parentNode;
                                        $cloneDom = $entry->
                                        parentNode->parentNode->cloneNode(true);
                                        try {
                                            $domP->parentNode->insertBefore(
                                                $cloneDom, $domP
                                            );
                                        }
                                        catch (Exception $e) {
                                            CreateDocx::$log->fatal(
                                                $e->getMessage()
                                            );
                                            exit();
                                        }
                                    } elseif ($args[1] == 'table') {
                                        $domTR = $entry->parentNode->
                                        parentNode->parentNode->
                                        parentNode;
                                        if (
                                            $entry->parentNode->
                                            parentNode->parentNode->
                                            parentNode->nodeName == 'w:tr'
                                        ) {
                                            $domParent = $entry->parentNode->
                                            parentNode->parentNode->
                                            parentNode->parentNode;
                                            $cloneDom = $entry->parentNode->
                                            parentNode->parentNode->
                                            parentNode->cloneNode(true);
                                            try {
                                                $domTR->parentNode->insertBefore(
                                                    $cloneDom, $domTR
                                                );
                                            }
                                            catch (Exception $e) {
                                                CreateDocx::$log->fatal(
                                                    $e->getMessage()
                                                );
                                                exit();
                                            }
                                        }
                                    }
                                }
                                /* Only the first key is needed to iterate over
                                 * DOM and clone the required nodes.
                                 */
                                break;
                            }
                        }
                    } else {
                        if ($args[1] == 'list') {
                            $domP = $entry->parentNode->parentNode;
                            $domParent = $entry->parentNode->
                            parentNode->parentNode;
                            $cloneDom = $entry->
                            parentNode->parentNode->cloneNode(true);
                            try {
                                $domP->parentNode->insertBefore(
                                    $cloneDom, $domP
                                );
                            }
                            catch (Exception $e) {
                                CreateDocx::$log->fatal(
                                    $e->getMessage()
                                );
                                exit();
                            }
                        } elseif ($args[1] == 'table') {
                            $domTR = $entry->parentNode->
                            parentNode->parentNode->
                            parentNode;
                            if (
                                $entry->parentNode->
                                parentNode->parentNode->
                                parentNode->nodeName == 'w:tr'
                            ) {
                                $domParent = $entry->parentNode->
                                parentNode->parentNode->
                                parentNode->parentNode;
                                $cloneDom = $entry->parentNode->
                                parentNode->parentNode->
                                parentNode->cloneNode(true);
                                try {
                                    $domTR->parentNode->insertBefore(
                                        $cloneDom, $domTR
                                    );
                                }
                                catch (Exception $e) {
                                    CreateDocx::$log->fatal(
                                        $e->getMessage()
                                    );
                                    exit();
                                }
                            }
                        }
                        /* Only the first key is needed to iterate over
                         * DOM and clone the required nodes.
                         */
                        break;
                    }
                }
            }
            /* A last element is created due to number of values plus the first
             *  used as placeholder. Just remove it.
             */
            if (is_object($domParent)) {
                $domParent->removeChild($cloneDom);
            }
            self::$_document = $domDocument->saveXML();
        }

        // Iterate text array.
        if (is_string($args[0]) && is_array($args[1])) {
            $domDocument = new DomDocument();
            $domDocument->loadXML(self::$_document);

            $xmlWP = $domDocument->getElementsByTagNameNS(
                'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
                'p'
            );
            $xpath = new DOMXPath($domDocument);
            $query = 'w:r/w:t';
            $length = $xmlWP->length;
            $itemsWP = array();
            for ($i = 0; $i < $length; $i++) {
                $itemsWP[$i] = $xmlWP->item($i);
            }
            for ($i = 0; $i < $length; $i++) {
                $variables = $xpath->query($query, $itemsWP[$i]);
                foreach ($variables as $entry) {
                    foreach ($args[1] as $values) {
                        if (
                            self::$_templateSymbol . $args[0] .
                            self::$_templateSymbol ==
                            $entry->nodeValue
                            && $entry->nodeValue != '$'
                        ) {
                            $domP = $entry->parentNode->parentNode;
                            $domParent = $entry->parentNode->
                            parentNode->parentNode;
                            $cloneDom = $entry->
                            parentNode->parentNode->cloneNode(true);
                            try {
                                $domP->parentNode->insertBefore(
                                    $cloneDom, $domP
                                );
                            }
                            catch (Exception $e) {
                                CreateDocx::$log->fatal(
                                    $e->getMessage()
                                );
                                exit();
                            }
                        }
                    }
                }
            }
            /* A last element is created due to number of values plus the first
             *  used as placeholder. Just remove it.
             */
            if (is_object($domParent)) {
                $domParent->removeChild($cloneDom);
            }
            self::$_document = $domDocument->saveXML();
        }

        if (is_string($args[0])) {
            // Text string or array of texts
            if (is_string($args[1])) {
                // if text string escape chars
                if (empty($args[2])) {
                    $args[1] = htmlspecialchars($args[1]);
                }
                // Single text string
                self::$_document = str_replace(
                    self::$_templateSymbol . $args[0]
                    . self::$_templateSymbol,
                    self::processFields($args[0], $args[1]),
                    self::$_document
                );
                $i = 0;
                foreach (self::$_footer as $footer) {
                    self::$_footer[$i] = str_replace(self::$_templateSymbol . $args[0] . self::$_templateSymbol,
                                                     self::processFields($args[0], $args[1]), $footer);
                    $i++;
                }
                $i = 0;
                foreach (self::$_header as $header) {
                    self::$_header[$i] = str_replace(self::$_templateSymbol . $args[0] . self::$_templateSymbol,
                                                     self::processFields($args[0], $args[1]), $header);
                    $i++;
                }
            } elseif (is_array($args[1])) {
                // Text array
                foreach ($args[1] as $values) {
                    // if text string escape chars
                    if (empty($args[2])) {
                        $values = htmlspecialchars($values);
                    }
                    self::$_document = preg_replace(
                        '/\\' . self::$_templateSymbol . $args[0]
                        . '\\' . self::$_templateSymbol . '/',
                        $values,
                        self::$_document,
                        1
                    );
                    $i = 0;
                    foreach (self::$_footer as $footer) {
                        self::$_footer[$i] = preg_replace('/\\' . self::$_templateSymbol . $args[0] . '\\' .
                                                          self::$_templateSymbol . '/', $values, $footer, 1);
                        $i++;
                    }
                    $i = 0;
                    foreach (self::$_header as $header) {
                        self::$_header[$i] = preg_replace('/\\' . self::$_templateSymbol . $args[0] . '\\' .
                                                      self::$_templateSymbol . '/', $values, $header, 1);
                        $i++;
                    }
                }
            }
        } elseif (is_array($args[0])) {
            // List or table
            foreach ($args[0] as $values) {
                foreach ($values as $keys => $textValue) {
                    // escape chars
                    $textValue = htmlspecialchars($textValue);
                    $documentSymbol =
                        explode(self::$_templateSymbol, self::$_document);
                    foreach ($documentSymbol as $documentSymbolValue) {
                        if (strip_tags($documentSymbolValue) == $keys) {
                            self::$_document = str_replace(
                                $documentSymbolValue, $keys, self::$_document
                            );
                        }
                    }
                    self::$_document = preg_replace(
                        '/\\' . self::$_templateSymbol . $keys . '\\' .
                        self::$_templateSymbol . '/',
                        $textValue,
                        self::$_document,
                        1
                    );
                    $i = 0;
                    foreach (self::$_footer as $footer) {
                        self::$_footer[$i] = preg_replace('/\\' . self::$_templateSymbol . $keys . '\\' .
                                                      self::$_templateSymbol . '/', $textValue, $footer, 1);
                        $i++;
                    }
                    $i = 0;
                    foreach (self::$_header as $header) {
                        self::$_header[$i] = preg_replace('/\\' . self::$_templateSymbol . $keys . '\\' .
                                                      self::$_templateSymbol . '/', $textValue, $header, 1);
                        $i++;
                    }
                }
            }
        }
    }

    /**
     * Replace variable char in template
     *
     * @access public
     * @param string $args[0]
     * @static
     */
    public static function replaceVariableChart($chart, $i)
    {
        $chart = str_replace('<w:p>', '', $chart);
        $chart = str_replace('</w:p>', '', $chart);
        self::$_document = str_replace(
            '<varchart_' . $i . '/>',
            $chart,
            self::$_document
        );
    }

    /**
     * Process template variables before sustitution in template
     *
     * @access private
     * @param string $name Variable name
     * @param string $value Variable value
     */
    private function processFields($name, $value){
        $ret = $value;

        if(strpos(strtolower($name), 'numbering') === 0){
            if(!isset($aNameCounts)){
                static $aNameCounts = array();
            }

            $aAuxNums = array();

            switch($value){
                case '1,2,3':
                    $aAuxNums = 1;
                    break;
                case 'I,II,III':
                    $aAuxNums = array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI','XXII','XXIII','XXIV','XXV','XXVI','XXVII','XXVIII','XXIX','XXX');
                    break;
                case 'A,B,C':
                    $aAuxNums = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                    break;
                default:
                    if(strpos($value, ',') === false){
                        if(is_numeric($value)) $aAuxNums = $value;
                        else $aAuxNums = 1;
                    }
                    else $aAuxNums = explode(',', $value);
            }

            if(!isset($aNameCounts[$name])){
                $aNameCounts[$name] = 0;
                if(is_array($aAuxNums)) $ret = $aAuxNums[$aNameCounts[$name]];
                else{
                    $aNameCounts[$name] += $aAuxNums;
                    $ret = $aNameCounts[$name];
                }
            }
            else{
                if(is_array($aAuxNums)){
                    $aNameCounts[$name]++;
                    $ret = $aAuxNums[$aNameCounts[$name]%count($aAuxNums)];
                }
                else{
                    $aNameCounts[$name] += $aAuxNums;
                    $ret = $aNameCounts[$name];
                }
            }
        }

        return($ret);
    }

    /**
     * Return template variables
     *
     * @access public
     * @return array
     */
    public function returnAllVariables()
    {
        $variables = array();

        $documentSymbol = explode(self::$_templateSymbol, self::$_document);
        $i = 0;
        foreach ($documentSymbol as $documentSymbolValue) {
            // avoid first and last values, and tag elements
            if ($i == 0 || $i == count($documentSymbol)
                || $documentSymbolValue[0] == '<') {
                $i++;
                continue;
            } else {
                $variables['document'][] = strip_tags($documentSymbolValue);
                $i++;
            }
        }

        for ($j = 0; $j < count(self::$_header); $j++) {
            $documentSymbol = explode(self::$_templateSymbol, self::$_header[$j]);
            $i = 0;
            foreach ($documentSymbol as $documentSymbolValue) {
                // avoid first and last values, and tag elements
                if ($i == 0 || $i == count($documentSymbol)
                    || $documentSymbolValue[0] == '<') {
                    $i++;
                    continue;
                } else {
                    $variables['header'][] = strip_tags($documentSymbolValue);
                    $i++;
                }
            }
        }

        for ($j = 0; $j < count(self::$_footer); $j++) {
            $documentSymbol = explode(self::$_templateSymbol, self::$_footer[$j]);
            $i = 0;
            foreach ($documentSymbol as $documentSymbolValue) {
                // avoid first and last values, and tag elements
                if ($i == 0 || $i == count($documentSymbol)
                    || $documentSymbolValue[0] == '<') {
                    $i++;
                    continue;
                } else {
                    $variables['footer'][] = strip_tags($documentSymbolValue);
                    $i++;
                }
            }
        }

        return $variables;
    }

    /**
     * Return template variables
     *
     * @access public
     * @return array
     */
    public function returnVariables()
    {
        //$variables['document'] = explode(self::$_templateSymbol, $this->_preprocessDocument());
        //#cambio#
        $aEscapados = array('$', '\\', '.', '(', ')', '[', ']', '*', '+', '?');
        $sTemplateSymbol = (in_array(self::$_templateSymbol, $aEscapados)?'\\':'').self::$_templateSymbol;
        preg_match_all('/'.$sTemplateSymbol.'(.*?)'.$sTemplateSymbol.'/si', $this->_preprocessDocument(), $aResult, PREG_PATTERN_ORDER);
        $variables['document'] = array_map('trim', array_map('strip_tags', $aResult[1]));

        foreach (self::$_footer as $footer) {
            //$variables['footer'] = explode(self::$_templateSymbol, $footer);
            preg_match_all('/'.$sTemplateSymbol.'(.*?)'.$sTemplateSymbol.'/si', $footer, $aResult, PREG_PATTERN_ORDER);
            $aResult = array_map('trim', array_map('strip_tags', $aResult[1]));
            $variables['footer'] = !empty($variables['footer'])?array_merge($variables['footer'], $aResult):$aResult;
        }
        foreach (self::$_header as $header) {
            //$variables['header'] = explode(self::$_templateSymbol, $header);
            preg_match_all('/'.$sTemplateSymbol.'(.*?)'.$sTemplateSymbol.'/si', $header, $aResult, PREG_PATTERN_ORDER);
            $aResult = array_map('trim', array_map('strip_tags', $aResult[1]));
            $variables['header'] = !empty($variables['header'])?array_merge($variables['header'], $aResult):$aResult;
        }

        
        $this->_buildArray($variables);
        
        return $variables;
    }

    /**
     * Build array with given variables
     *
     * @param array $variables
     */
    private function _buildArray($variables)
    {
        $i = 0;
        $j = 0;
        foreach ($variables as $key => $section) {
            foreach ($section as $value) {
                // avoid first and last values, and tag elements
                if ($i == 0 || $i == count($variables[$key])
                    || $value[0] == '<') {
                    $i++;
                    continue;
                } else {
                    $variableParts = $this->cleanExplode("_", $value);
                    switch ($variableParts[0]) {
		                case 'BLOCK':
		                case 'GROUP':
		                case 'TAB':
		                    self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
		                    $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[1]);
		                    self::$templateVariables[$key][$j]['NAME'] = $variableGroup[0];
		                    if (!empty ($variableGroup[1])) {
		                        self::$templateVariables[$key][$j]['GROUPID'] = $variableGroup[1];
		                    }
		                    if (!empty ($variableGroup[2])) {
		                        //The group is nested inside other group
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableGroup[2];
		                    }
		                    break;

		                case 'HEADING':
		                    self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
		                    $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[1]);
		                    self::$templateVariables[$key][$j]['NAME'] =
		                    $variableGroup[0];
		                    break;

		                case 'COMMENT':
		                    self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
		                    $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[1]);
		                    self::$templateVariables[$key][$j]['NAME'] = $variableGroup[0];
		                    if (!empty ($variableGroup[1])) {
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableGroup[1];
		                    } elseif (!empty ($variableParts[2])) {
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableParts[2];
		                    }
		                    break;

		                case 'NUMBERING':
		                    self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
		                    $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[1]);
		                    self::$templateVariables[$key][$j]['NAME'] = $variableGroup[0];
		                    if (!empty ($variableGroup[1])) {
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableGroup[1];
		                    } elseif (!empty ($variableParts[2])) {
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableParts[2];
		                    }
		                    break;

		                case 'LIST':
		                case 'ROW':
		                    self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
		                    $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[3]);
		                    self::$templateVariables[$key][$j]['NAME'] = $variableParts[2].'_'.$variableParts[3];
		                    if (!empty ($variableParts[1])) {
		                        self::$templateVariables[$key][$j]['GROUPID'] = $variableParts[1];
		                    }
		                    if (!empty ($variableGroup[1])) {
		                        //The group is nested inside other group
		                        self::$templateVariables[$key][$j]['GROUP'] = $variableGroup[1];
		                    }
		                    break;

                        case 'TEXT':
                        case 'TEXTAREA':
                        case 'SELECT':
                        case 'DATE':
                            self::$templateVariables[$key][$j]['TAG'] = $variableParts[0];
                            $variableGroup = $this->cleanExplode(self::$_templateGroupSymbol, $variableParts[1]);
                            self::$templateVariables[$key][$j]['NAME'] =
                                $variableGroup[0];
                            if (!empty ($variableGroup[1])) {
                                self::$templateVariables[$key][$j]['GROUP'] =
                                    $variableGroup[1];
                            }
                            break;

                        default:
                            break;
                    }
                    $j++;
                }
                $i++;
            }
        }

    }

    /**
     * clean the sections from the unused footers and headers
     *
     * @access private
     */
    private function cleanDocument($document){
        $domDocument = new DomDocument();
        $domDocument->loadXML($document);
        //check if there are footers
        $xmlWP = $domDocument->getElementsByTagNameNS(
                        'http://schemas.openxmlformats.org/wordprocessingml/2006/main','headerReference');
        $length = $xmlWP->length;
        $itemsWP = array();
        for ($i = 0; $i < $length; $i++) {
            $itemsWP[] = $xmlWP->item($i);
        }
        foreach($itemsWP as $aux){
            if ($aux->attributes->getNamedItem("type")->nodeValue != 'default') {
                        $padre = $aux->parentNode;
                        $padre->removeChild($aux);
            }
        }
        $xmlWP = $domDocument->getElementsByTagNameNS(
                        'http://schemas.openxmlformats.org/wordprocessingml/2006/main','footerReference');
        $length = $xmlWP->length;
        $itemsWP = array();
        for ($i = 0; $i < $length; $i++) {
            $itemsWP[] = $xmlWP->item($i);
        }
        foreach($itemsWP as $aux){
            if ($aux->attributes->getNamedItem("type")->nodeValue != 'default') {
                        $padre = $aux->parentNode;
                        $padre->removeChild($aux);
            }
        }
        return $domDocument->saveXML();
    }
    
    /**
     * Returns an array with clean exploded variables
     *
     * @param string $delimiter
     * @param string $string
     * @return array
     */
    public function cleanExplode($delimiter, $string)
    {
        $array = explode($delimiter, $string);
        foreach ($array as $key => $part) {
            $array[$key] = trim(strip_tags($part));
        }
        return $array;
    }

    /**
     * Returns the xml with bookmarks formatted $NAME$
     *
     * @return string
     */
    private function _preprocessDocument()
    {
        $xml = $this->getDocument();
        $xml = preg_replace('/<w:bookmarkStart w:id="[0-9]" w:name="([0-9 A-Z _]*)"/',
                            self::$_templateSymbol . '${1}' . self::$_templateSymbol, $xml);
        return $xml;
    }

    /**
     * Get current rid
     *
     * @access private
     */
    private function getCurrentRID()
    {
        $rId = 0;
        $domRelsDocumentXMLRels = new DomDocument();
        $domRelsDocumentXMLRels->loadXML(self::$_relsDocumentXMLRels);

        $xmlRelationship = $domRelsDocumentXMLRels->getElementsByTagName(
            'Relationship'
        );

        for ($i = 0; $i < $xmlRelationship->length; $i++) {
            if (
                (int)substr(
                    $xmlRelationship->item($i)->getAttribute('Id'), 3
                ) > $rId
            ) {
                $rId = (int)substr(
                    $xmlRelationship->item($i)->getAttribute('Id'), 3
                );
            }
        }

        CreateDocx::$intIdWord = $rId + 1;
        self::$ridInitTemplateCharts = $rId + 1;
    }

}
