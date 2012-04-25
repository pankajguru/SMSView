<?php

/**
 * Generate a DOCX file
 *
 * @category   Phpdocx
 * @package    create
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.19.03
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.0
 */

require_once dirname(__FILE__) . '/AutoLoader.inc';
AutoLoader::load();
require_once dirname(__FILE__) . '/Phpdocx_config.inc';

/**
 * Main class. Methods and vars to generate a DOCX file
 *
 * @category   Phpdocx
 * @package    create
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.19.03
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 1.0
 */
class CreateDocx extends CreateDocument
{
    const NAMESPACEWORD = 'w';
    const SCHEMA_IMAGEDOCUMENT =
    'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image';
    const SCHEMA_OFFICEDOCUMENT =
    'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument';
 
     /**
     *
     * @access public static
     * @var integer
     */
    public static $numUL;
    /**
     *
     * @access public static
     * @var integer
     */
    public static $numOL;
    

    /**
     *
     * @access public
     * @var array
     */
    public $fileGraphicTemplate;
    /**
     *
     * @access private
     * @var boolean
     */
    private $footerAdded;
    /**
     *
     * @access private
     * @var boolean
     */
    private $headerAdded;
    /**
     *
     * @access public
     * @var string
     */
    public $graphicTemplate;
    /**
     *
     * @access public
     * @static
     * @var int
     */
    public static $intIdWord;
    /**
     *
     * @access public
     * @static
     * @var Logger
     */
    public static $log;
    /**
     *
     * @access private
     * @var string
     */
    private $_background; 
     /**
     *
     * @access private
     * @var string
     */
    private $_baseTemplateFilesPath; 
   /**
     *
     * @access private
     * @var string
     */
    private $_baseTemplatePath;  
   /**
     *
     * @access private
     * @var string
     */
    private $_baseTemplateZip;          
   /**
     *
     * @access private
     * @var boolean
     */
    private $_compatibilityMode;
    /**
     *
     * @access private
     * @var string
     */
    private $_contentTypeC;
    /**
     *
     * @access private
     * @var string
     */
    private $_defaultFont;
    /**
     *
     * @access private
     * @var Debug
     */
    private $_debug;
    /**
     *
     * @access private
     * @var array
     */
    private $_defaultPHPDOCXStyles;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsAppC;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsAppT;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsCoreC;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsCoreT;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsCustomC;
    /**
     *
     * @access private
     * @var string
     */
    private $_docPropsCustomT;
    /**
     *
     * @access private
     * @var string
     */
    private static $_encodeUTF;
    /**
     *
     * @access private
     * @var string
     */
    private $_extension;
    /**
     *
     * @access private
     * @var int
     */
    private $_idImgHeader;
    /**
     *
     * @access private
     * @var int
     */
    private $_idRels;
    /**
     *
     * @access private
     * @var array
     */
    private $_idWords;
    /**
     *
     * @access private
     * @var string
     */
    private $_language;
    /**
     *
     * @access private
     * @var boolean
     */
    private $_macro;
    /**
     *
     * @access private
     * @var int
     */
    private $_markAsFinal;
    /**
     *
     * @access private
     * @var string
     */
    private $_relsRelsC;
     /**
     *
     * @access private
     * @var string
     */
    private $_relsRelsT;   
    /**
     *
     * @access private
     * @var array
     */
    private $_relsHeader;
     /**
     *
     * @access private
     * @var array
     */
    private $_relsFooter;
     /**
     *
     * @access private
     * @var string
     */
    private $_sectPr;
     /**
     *
     * @access private
     * @var array
     */
    private $_parsedStyles;
    /**
    /**
     * Directory path used for temporary files
     *
     * @access private
     * @var string
     */
    private $_tempDir;
    /**
     * Path of temp file to use as DOCX file
     *
     * @access private
     * @var string
     */
    private $_tempFile;
    /**
     * Paths of temps files to use as DOCX file
     *
     * @access private
     * @var array
     */
    private $_tempFileXLSX;
    /**
     * Unique id for the insertion of new elements
     *
     * @access private
     * @var string
     */
    private $_uniqid;    
    /**
     *
     * @access private
     * @var string
     */
    private $_wordDocumentC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordDocumentT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordDocumentStyles;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordEndnotesC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordEndnotesT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordFontTableC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordFontTableT;
    /**
     *
     * @access private
     * @var array
     */
    private $_wordFooterC;
    /**
     *
     * @access private
     * @var array
     */
    private $_wordFooterT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordFootnotesC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordFootnotesT;
    /**
     *
     * @access private
     * @var array
     */
    private $_wordHeaderC;
    /**
     *
     * @access private
     * @var array
     */
    private $_wordHeaderT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordNumberingC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordNumberingT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordRelsDocumentRelsC;
    /**
     *
     * @access private
     * @var DOMDocument
     */
    private $_wordRelsDocumentRelsT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordRelsFooterRelsC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordRelsFooterRelsT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordRelsHeaderRelsC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordRelsHeaderRelsT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordSettingsC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordSettingsT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordStylesC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordStylesT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordThemeThemeT;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordThemeThemeC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordWebSettingsC;
    /**
     *
     * @access private
     * @var string
     */
    private $_wordWebSettingsT;
    /**
     *
     * @access private
     * @var ZipArchive
     */
    private $_zipDocx;

    /**
     * Construct
     *
     * @access public
     * @param string $baseTemplatePath. Optional, basicTemplate.docx as default
     */
    public function __construct($baseTemplatePath = PHPDOCX_BASE_TEMPLATE)
    {
        $this->_background = '';
        $this->_baseTemplateFilesPath;
        $this->_baseTemplatePath = $baseTemplatePath;  
        $this->_baseTemplateZip = new ZipArchive();      
        $this->_idRels = array();
        $this->_idWords = array();
        $this->_debug = Debug::getInstance();
        Logger::configure(dirname(__FILE__) . '/conf/log4php.properties');
        self::$log = Logger::getLogger('phpdocx_logger');
        $this->_idImgHeader = 1;
        $this->_idRels = 1;
        self::$intIdWord = rand(9999999,99999999);
        self::$_encodeUTF = 0;
        $this->_language = 'en-US';
        $this->_markAsFinal = 0;
        $this->graphicTemplate = array();
        $this->fileGraphicTemplate = array();
        $this->_zipDocx = new ZipArchive();
        $this->_tempDir = $this->getTempDir();
        $this->_tempFile = tempnam($this->_tempDir, 'document');
        $this->_zipDocx->open($this->_tempFile, ZipArchive::OVERWRITE);
        $this->_extension = 'docx';
        $this->_compatibilityMode = false;
        self::$log->info(
            'Create a temp file to use as initial ZIP file. ' .
            'DOCX is a ZIP file.'
        );
        $this->_relsRelsC = '';
        $this->_relsRelsT = '';
        $this->_contentTypeC = '';
        $this->_contentTypeT = NULL;
        $this->_defaultFont = '';
        $this->_docPropsAppC = '';
        $this->_docPropsAppT = '';
        $this->_docPropsCoreC = '';
        $this->_docPropsCoreT = '';
        $this->_docPropsCustomC = '';
        $this->_docPropsCustomT = '';
        $this->_macro = 0;
        $this->_relsHeader = array();
        $this->_relsFooter = array();
        $this->_parsedStyles = array();
        $this->_sectPr = NULL;
        $this->_tempFileXLSX = array();
        $this->_uniqid = 'phpdocx_'.uniqid();
        $this->_wordDocumentT = '';
        $this->_wordDocumentC = '';
        $this->_wordDocumentStyles = '';
        $this->_wordEndnotesC = '';
        $this->_wordEndnotesT = '';
        $this->_wordFontTableT = '';
        $this->_wordFontTableC = '';
        $this->_wordFooterC = '';
        $this->_wordFooterT = '';
        $this->_wordFootnotesC = '';
        $this->_wordFootnotesT = '';
        $this->_wordHeaderC = '';
        $this->_wordHeaderT = '';
        $this->_wordNumberingC;
        $this->_wordNumberingT;
        $this->_wordRelsDocumentRelsC = '';
        $this->_wordRelsDocumentRelsT = NULL;
        $this->_wordRelsHeaderRelsC = '';
        $this->_wordRelsHeaderRelsT = '';
        $this->_wordRelsFooterRelsC = '';
        $this->_wordRelsFooterRelsT = '';
        $this->_wordSettingsT = '';
        $this->_wordSettingsC = '';
        $this->_wordStylesT = '';
        $this->_wordStylesC = '';
        $this->_wordThemeThemeT = '';
        $this->_wordThemeThemeC = '';
        $this->_wordWebSettingsT = '';
        $this->_wordWebSettingsC = '';   
        $this->_defaultPHPDOCXStyles = array('Default Paragraph Font PHPDOCX', //This is the default paragraph font style used in multiple places
                                            'List Paragraph PHPDOCX', //This is the style used for the defolt ordered and unorderd lists
                                            'Title PHPDOCX', //This style is used by the addTitle method
                                            'Subtitle PHPDOCX', //This style is used by the addTitle method
                                            'Normal Table PHPDOCX', //This style is used for the basic table
                                            'Table Grid PHPDOCX', //This style is for basic tables and is also used to embedd HTML tables with border="1"
                                            'footnote text PHPDOCX', //This style is used for default footnotes
                                            'footnote text Car PHPDOCX', //The character style for footnotes
                                            'footnote reference PHPDOCX', // The style for the footnote
                                            'endnote text PHPDOCX', //This style is used for default endnotes
                                            'endnote text Car PHPDOCX', //The character style for endnotes
                                            'endnote reference PHPDOCX'); // The style for the endnote
        //Some variables to control that some v2.4 keep working
        $this->footerAdded = false;
        $this->headerAdded = false;
        
        try {
            GenerateDocx::beginDocx();
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
                     
        
        //We now try to open the zip file defined as base template
        try {
            $openBaseTemplate = $this->_baseTemplateZip->open($this->_baseTemplatePath);
            if ($openBaseTemplate !== true){
        throw new Exception('Error while opening the Base Template: please, check the path');
        }                                           
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        //We now extract the contents of the base template into a temp dir for further manipulation 
        try {  
        $this->_baseTemplateFilesPath = $this->_tempDir.'/'.uniqid(true);    
        $extractBaseTemplate =$this->_baseTemplateZip->extractTo($this->_baseTemplateFilesPath);
        if ($extractBaseTemplate !== true) {
            throw new Exception('Error while extracting the Base Template: there may be problems writing in the default tmp folder');
            }
        }
        catch (Exception $e) {
          self::$log->fatal($e->getMessage());
          exit();
        }
        
        //We should now check if there is any structured content as front page  to include it in the resulting document

        try{
            $baseTemplateDocumentT = $this->_baseTemplateZip->getFromName('word/document.xml');
            if ($baseTemplateDocumentT == '') {
                throw new Exception('Error while extracting the document.xml file from the base template');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $baseDocument = new DOMDocument();
        $baseDocument->loadXML($baseTemplateDocumentT);
        $docXpath = new DOMXPath($baseDocument);       
        $docXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');      
        $queryDoc = '//w:body/w:sdt';
        $docNodes = $docXpath->query($queryDoc);

        if(isset($docNodes->item(0)->nodeName) && $docNodes->item(0)->nodeName == 'w:sdt'){
            $tempDoc = new DomDocument();
            $sdt =$tempDoc->importNode($docNodes->item(0), true);
            $newNode = $tempDoc->appendChild($sdt);
            $frontPage = $tempDoc->saveXML($newNode);
            $this->_wordDocumentC .= $frontPage;
        }
        
        //Let us extract now the section information to include it at the end of the document.xml file
        
        $sectPr = $baseDocument->getElementsByTagName('sectPr')->item(0);
        $this->_sectPr = new DOMDocument();
        $sectNode = $this->_sectPr->importNode($sectPr, true);
        $this->_sectPr->appendChild($sectNode);
        
        //Let us extract the contents of the [Content_Types].xml file for further manipulation
        
        try{
            $baseTemplateContentTypeT = $this->_baseTemplateZip->getFromName('[Content_Types].xml');
            if ($baseTemplateContentTypeT  == '') {
        throw new Exception('Error while extracting the [Content_Types].xml file from the base template');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $this->_contentTypeT = new DOMDocument();
        $this->_contentTypeT->loadXML($baseTemplateContentTypeT);
        
        //We are going to include the standard image defaults
        
        $this->generateDEFAULT('gif', 'image/gif');
        $this->generateDEFAULT('jpg', 'image/jpg');
        $this->generateDEFAULT('png', 'image/png');
        $this->generateDEFAULT('jpeg', 'image/jpeg');
        $this->generateDEFAULT('bmp', 'image/bmp');
        
 
        //Let us extract the document.xml.rels for further manipulation
        try{
            $baseTemplateDocumentRelsT = $this->_baseTemplateZip->getFromName('word/_rels/document.xml.rels');
            if ($baseTemplateDocumentRelsT == '') {
                throw new Exception('Error while extracting the document.xml.rels file from the base template');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        $this->_wordRelsDocumentRelsT = new DOMDocument();
        $this->_wordRelsDocumentRelsT->loadXML($baseTemplateDocumentRelsT);
        $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
        
        //Now we have to take care of the case that the template used is not one of the default preprocessed tempaltes
        
        if($this->_baseTemplatePath == PHPDOCX_BASE_TEMPLATE){
            CreateDocx::$numUL = 1;
            CreateDocx::$numOL = 2;
        
        }else{
            //We should do now some cleaning of the files from the base template zip
            //Let us first look at the document.xml.rels file to analyze the contents
            //Let us analyze its structure
            //In order to do that we should parse word/_rels/document.xml.rels

            $counter = $relationships->length -1;

            for($j=$counter; $j > -1; $j--){
                $completeType = $relationships->item($j)->getAttribute('Type');
                $target = $relationships->item($j)->getAttribute('Target');
                $type = array_pop(explode('/', $completeType));
                //This array holds the data that has to be changed in settings.xml
                $arrayCleaner = array();

                switch($type){
                    case 'header':
                        //TODO: this should be changed if we use default templates with headers
                        array_push($this->_relsHeader,$target);
                        break;
                    case 'footer':
                        //TODO: this should be changed if we use default templates with footers
                        array_push($this->_relsFooter,$target);
                        break;  
                    case 'chart':
                        $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/charts');
                        $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                        break;
                    case 'embeddings':
                        $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/embeddings');
                        $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                        break;
                }

            }
            
                    
           //Let us now manage the numbering.xml and style.xml files      
           // We are going to use some default styles, for example, in the creation of lists, footnotes, titles, ...
           // So we should make sure that it is included in the styles.xml document
           $this->importStyles(PHPDOCX_BASE_TEMPLATE, 'merge', $this->_defaultPHPDOCXStyles);
           //Let us first check if the base template file has a numbering.xml file 
            if(file_exists($this->_baseTemplateFilesPath.'/word/numbering.xml')){
                $numRef = rand(9999999, 99999999);
                CreateDocx::$numUL = $numRef;
                CreateDocx::$numOL = $numRef +1;
                
                //We first extract the standard UL and OL numbering from our estandar base template
                $numZip = new ZipArchive();
                try {
                    $openNumZip = $numZip->open(PHPDOCX_BASE_TEMPLATE);                 
                    if ($openNumZip !== true){
                    throw new Exception('Error while opening the standard base template to extract the word/numbering.xml file');
                    }                                           
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                     exit();
                }
                //We will first extract all the numberings from the standard base template
                try{
                    $newNumberings = $numZip->getFromName('word/numbering.xml');
                    if ($newNumberings == '') {
                        throw new Exception('Error while extracting the numberings from the standard base template');
                    }               
                }
                catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
                }
                //let's parse the different numbering for ULs and OLs via XPath
                $newNumberingsDoc = new DOMDocument();
                $newNumberingsDoc->loadXML($newNumberings);
                $numberingsXpath = new DOMXPath($newNumberingsDoc);       
                $numberingsXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');      
                $queryNumberings = '//w:abstractNum[@w:abstractNumId < 2]';
                $numberingsNodes = $numberingsXpath->query($queryNumberings);
                
                //Let's get the original template numbering.xml file as a DOMdocument             
                try{
                    $this->_wordNumberingT = $this->_baseTemplateZip->getFromName('word/numbering.xml');
                    if ($this->_wordNumberingT == '') {
                        throw new Exception('Error while extracting the numbering file from the base template');
                    }               
                }
                catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
                }
                $numberingsDocument = new DomDocument();
                $numberingsDocument->loadXML($this->_wordNumberingT);
                $refNode = $numberingsDocument->getElementsByTagName('num')->item(0);
                
                $arrayNums = array(rand(9999999, 99999999), rand(9999999, 99999999));
                
                 foreach($numberingsNodes as $node){
                    if($node->getAttribute("w:abstractNumId") == 0){
                            $node->setAttribute("w:abstractNumId", $arrayNums[0]);
                        }else{
                            $node->setAttribute("w:abstractNumId", $arrayNums[1]);
                        }
                    $insertNode = $numberingsDocument->importNode($node, true);       
                    $refNode->parentNode->insertBefore($insertNode, $refNode);               
                    }        

                $this->_wordNumberingT = $numberingsDocument->saveXML();
                //echo($this->_wordNumberingT);
                $myNums ='<w:num w:numId="'.CreateDocx::$numUL.'"><w:abstractNumId w:val="'.$arrayNums[0].'" />
                           </w:num><w:num w:numId="'.CreateDocx::$numOL.'">
                           <w:abstractNumId w:val="'.$arrayNums[1].'" /></w:num>';
                //echo $myNums;
                $this->_wordNumberingT = str_replace('</w:numbering>', $myNums.'</w:numbering>', $this->_wordNumberingT);
                //echo($this->_wordNumberingT);
                try{
                    $numberingsFile=fopen($this->_baseTemplateFilesPath.'/word/numbering.xml', 'w');
                    if ($numberingsFile == false) {
                        throw new Exception('Error while opening the base template numbering.xml file');
                    }               
                }
                catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
                }
                try{
                    $writeNumberings = fwrite($numberingsFile,$this->_wordNumberingT );
                    if ($writeNumberings == 0) {
                     throw new Exception('There were no new numberings written');
                    }               
                }
                catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
                }
                
            }else{
               //We copy the numbering.xml file from the standard PHPDocX template into the new base template
                $numZip = new ZipArchive();
                try {
                    $openNumZip = $numZip->open(PHPDOCX_BASE_TEMPLATE);                 
                    if ($openNumZip !== true){
                    throw new Exception('Error while opening the standard base template to extract the word/numbering.xml file');
                    }                                           
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                     exit();
                } 
                
                $numZip->extractTo($this->_baseTemplateFilesPath.'/', 'word/numbering.xml');
            }
            
            //Let us now make sure that there are the corresponding xmls, with all their relationships for endnotes and footnotes
            if(!file_exists($this->_baseTemplateFilesPath.'/word/endnotes.xml') || !file_exists($this->_baseTemplateFilesPath.'/word/footnotes.xml')){
                $notesZip = new ZipArchive();
                try {
                    $openNotesZip = $notesZip->open(PHPDOCX_BASE_TEMPLATE);                 
                    if ($openNotesZip !== true){
                    throw new Exception('Error while opening the standard base template to extract the word/footnotes.xml  and word/endnotes.xml file');
                    }                                           
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                     exit();
                } 
                
                $arraySettings = array();
                if(!file_exists($this->_baseTemplateFilesPath.'/word/footnotes.xml')){
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/footnotes.xml');
                    //Now we should include the corresponding relationshipand Override
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                     'rId' . rand(99999999, 999999999), 'footnotes', 'footnotes.xml'
                    );
                   $this->generateOVERRIDE('/word/footnotes.xml','application/vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml');
                   array_push($arraySettings, '<w:footnotePr><w:footnote w:id="-1" /><w:footnote w:id="0" /></w:footnotePr>');
                }
                if(!file_exists($this->_baseTemplateFilesPath.'/word/endnotes.xml')){
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/endnotes.xml');
                    //Now we should include the corresponding relationshipand Override
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                     'rId' . rand(99999999, 999999999), 'endnotes', 'endnotes.xml'
                    );
                   $this->generateOVERRIDE('/word/endnotes.xml','application/vnd.openxmlformats-officedocument.wordprocessingml.endnotes+xml');
                   array_push($arraySettings,'<w:endnotePr><w:endnote w:id="-1" /><w:endnote w:id="0" /></w:endnotePr>');
                }
                
                //$this->includeSettings($arraySettings)    
            }
        }
        //To finish we make sure that we are using the default paper size and the default language
        $this->modifyPageLayout($paperType = DEFAULT_PAPER_SIZE);
        $this->setLanguage(DEFAULT_LANGUAGE);
        
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
     * Magic method, returns current word XML
     *
     * @access public
     * @return string Return current word
     */
    public function __toString()
    {
        $this->generateTemplateWordDocument();
        self::$log->debug('Get document template content.');
        return $this->_wordDocumentT;
    }
    /**
     * Setter
     *
     * @access public
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getExtension()
    {
        return $this->_extension;
    }
    /**
     * Setter
     *
     * @access public
     */
    public function setTemporaryDirectory($tempDir)
    {
        $this->_tempDir = $tempDir;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getTemporaryDirectory()
    {
        return $this->_tempDir;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlContentTypes($xmlContentTypes)
    {
        $this->_contentTypeC = $xmlContentTypes;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlContentTypes()
    {
        return $this->_contentTypeC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlRelsRels($xmlRelsRels)
    {
        $this->_relsRelsC = $xmlRelsRels;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlRelsRels()
    {
        return $this->_relsRelsC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlDocPropsApp($xmlDocPropsApp)
    {
        $this->_docPropsAppC = $xmlDocPropsApp;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlDocPropsApp()
    {
        return $this->_docPropsAppC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlDocPropsCore($xmlDocPropsCore)
    {
        $this->_docPropsCoreC = $xmlDocPropsCore;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlDocPropsCore()
    {
        return $this->_docPropsCoreC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlDocPropsCustom($xmlDocPropsCustom)
    {
        $this->_docPropsCustomC = $xmlDocPropsCustom;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlDocPropsCustom()
    {
        return $this->_docPropsCustomC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordDocument($xmlWordDocument)
    {
        $this->_wordDocumentC = $xmlWordDocument;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordDocumentContent()
    {
        return $this->_wordDocumentC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordDocumentStyles($xmlWordDocumentStyles)
    {
        $this->_wordDocumentStyles = $xmlWordDocumentStyles;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordDocumentStyles()
    {
        return $this->_wordDocumentStyles;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordEndnotes($xmlWordEndnotes)
    {
        $this->_wordEndnotesC = $xmlWordEndnotes;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordEndnotes()
    {
        return $this->_wordEndnotesC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordFontTable($xmlWordFontTable)
    {
        $this->_wordFontTableC = $xmlWordFontTable;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordFontTable()
    {
        return $this->_wordFontTableC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordFooter1($xmlWordFooter)
    {
        $this->_wordFooterC = $xmlWordFooter;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordFooter1()
    {
        return $this->_wordFooterC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordHeader1($xmlWordHeader)
    {
        $this->_wordHeaderC = $xmlWordHeader;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordHeader1()
    {
        return $this->_wordHeaderC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordRelsDocumentRels($xmlWordRelsDocumentRels)
    {
        $this->_wordRelsDocumentRelsC = $xmlWordRelsDocumentRels;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordRelsDocumentRels()
    {
        return $this->_wordRelsDocumentRelsC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordSettings($xmlWordSettings)
    {
        $this->_wordSettingsC = $xmlWordSettings;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordSettings()
    {
        return $this->_wordSettingsC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordStyles($xmlWordStyles)
    {
        $this->_wordStylesC = $xmlWordStyles;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordStyles()
    {
        return $this->_wordStylesC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordThemeTheme1($xmlWordThemeTheme)
    {
        $this->_wordThemeThemeC = $xmlWordThemeTheme;
    }

    /**
     * Getter
     *
     * @access public
     */
    public function getXmlWordThemeTheme1()
    {
        return $this->_wordThemeThemeC;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function setXmlWordWebSettings($xmlWordWebSettings)
    {
        $this->_wordWebSettingsC = $xmlWordWebSettings;
    }

    /**
     * Setter
     *
     * @access public
     */
    public function getXml_Word_WebSettings()
    {
        return $this->_wordWebSettingsC;
    }

     /**
     * Add a new base template into the templates folder.
     *
     * @access public
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     * @param string $name. Must be a valid name for the new base template. It should include the extension
     * @param boolean $overwrite. Optional. If true overwrites any existing template with the given name 
     * 
     */
    public function addNewBaseTemplate($path, $name, $overwrite=false)
    {
        if(!$overwrite){
         try{
         $exists= file_exists(PHPDOCX_BASE_FOLDER.'/'.$name);
         if ($exists == true) {
           throw new Exception('There is a base template by that name. If you want to overwrite it set the overwrite parameter to true');
         }
        }
         catch (Exception $e) {
         self::$log->fatal($e->getMessage());
         exit();
        }          

       }
        try{
         $newTemplate= copy($path, PHPDOCX_BASE_FOLDER.'/'.$name);
         if ($newTemplate !== true) {
           throw new Exception('Error while trying to copy the new template: please, check the path or the permission rights of the template folder');
         }
        }
         catch (Exception $e) {
         self::$log->fatal($e->getMessage());
         exit();
        }
    }
    /**
     * Imports an existing style sheet from an existing docx document.
     *
     * @access public
     * @example ../examples/easy/importStyles.php
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     * @param string $type. You may choose 'replace' (overwrites the current styles) or 'merge' (adds the selected styles)
     * @param array $myStyles. A list of specific styles to be merged. If it is empty or the choosen type is 'replace' it will be ignored.
     */
    public function importStyles($path, $type= 'replace', $myStyles= array())
    {
        $zipStyles = new ZipArchive();
       try {
        $openStyle = $zipStyles->open($path);               
        if ($openStyle !== true) {
           throw new Exception('Error while opening the Style Template: please, check the path');
         }
        }
        catch (Exception $e) {
         self::$log->fatal($e->getMessage());
         exit();
        }
       if($type == 'replace'){
    //Now we overwrite the original styles file
            try{
                $extractingStyleFile = $zipStyles->extractTo($this->_baseTemplateFilesPath.'/','word/styles.xml');
        if (!$extractingStyleFile){
                  throw new Exception('Error while trying to overwrite the styles.xml of the base template');  
                 }
                }
                catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
                }
            //In order not to loose certain styles needed for certain PHPDOCX methods we should merge them
            $this->importStyles(PHPDOCX_BASE_TEMPLATE, 'merge', $this->_defaultPHPDOCXStyles);    
      }else{
         //We will first extract the new styles from the external docx
         try{
            $newStyles = $zipStyles->getFromName('word/styles.xml');
            if ($newStyles == '') {
        throw new Exception('Error while extracting the styles from the external docx');
            }               
         }
         catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
         } 
        //let's parse the different styles via XPath
        $newStylesDoc = new DOMDocument();
        $newStylesDoc->loadXML($newStyles);
        $stylesXpath = new DOMXPath($newStylesDoc);       
        $stylesXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');      
        $queryStyle = '//w:style';
        $styleNodes = $stylesXpath->query($queryStyle);
        
        //Let's get the original styles as a DOMdocument             
        try{
            $styleHandler = fopen($this->_baseTemplateFilesPath.'/word/styles.xml', 'r');
            $styleXML = fread($styleHandler, filesize($this->_baseTemplateFilesPath.'/word/styles.xml'));
            fclose($styleHandler);
            $this->_wordStylesT = $styleXML;
            if ($styleXML == '') {
                throw new Exception('Error while extracting the style file from the base template');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $stylesDocument = new DomDocument();
        $stylesDocument->loadXML($this->_wordStylesT);
        $baseNode = $stylesDocument->documentElement;
        
        //Now we start to insert the new styles at the end of the styles.xml   
        foreach($styleNodes as $node){
           // in order to avoid duplicated Ids we first remove from the 
           // original styles.xml any duplicity with the new ones
           // TODO: check performance
            $originalNodes = $stylesDocument->childNodes;
            foreach($originalNodes as $oldNode){
                if($oldNode->getAttribute('w:styleId') == $node->getAttribute('w:styleId')){
                    $oldNode->parent->removeChild($oldNode);
                }
            }
           if(count($myStyles)>0){
               //Lets remove the unwanted styles in case there is a list of styles to insert
               $nodeChilds = $node->childNodes;       
               foreach($nodeChilds as $child){
                   if ($child->nodeName == 'w:name'){
                       $styleName = $child->getAttribute('w:val');
                       if(in_array($styleName, $myStyles)){
                        $insertNode = $stylesDocument->importNode($node, true);       
                        $baseNode->appendChild($insertNode);
                       }                      
                    }
                  }   
           }else{
           $insertNode = $stylesDocument->importNode($node, true);       
           $baseNode->appendChild($insertNode);               
           }
        }
        $this->_wordStylesT = $stylesDocument->saveXML();
        try{
            $stylesFile=fopen($this->_baseTemplateFilesPath.'/word/styles.xml', 'w');
            if ($stylesFile == false) {
                throw new Exception('Error while opening the base template styles.xml file');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        try{
            $writeStyles = fwrite($stylesFile,$this->_wordStylesT );
            if ($writeStyles == 0) {
                throw new Exception('There were no new styles written');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
      }
      //echo $this->_wordStylesT;
      //readfile($this->_baseTemplateFilesPath.'/word/styles.xml');
      self::$log->info('Importing styles from an external docx.');      
    }

 
     /**
     * Gets the Ids associated with the different styles in the current document or an external docx.
     * It returns a docx with all the avalaible paragraph, list and table styles. 
     *
     * @access public
     * @example ../examples/easy/parseStyles.php
     * @param string $path. Optional, if empty lists de Ids of the current style sheet
     */
    public function parseStyles($path='')
    {        
       if($path != ''){
           $tempTitle = explode('/',$path);
           $title = array_pop($tempTitle);
           $parseStyles = new ZipArchive();
           try {
            $openParseStyle = $parseStyles->open($path);                
            if ($openParseStyle !== true) {
               throw new Exception('Error while opening the Style sheet to be tested: please, check the path');
             }
            }
            catch (Exception $e) {
             self::$log->fatal($e->getMessage());
             exit();
            }  

             try{
                $parsedStyles = $parseStyles->getFromName('word/styles.xml');
                if ($parsedStyles == '') {
                    throw new Exception('Error while extracting the styles to be parsed from the external docx');
                }               
             }
             catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
             }
             
             try{
                $parsedNumberings = $parseStyles->getFromName('word/numbering.xml');
                if ($parsedNumberings == '') {
                    throw new Exception('Error while extracting the numberings to be parsed from the external docx');
                }               
             }
             catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
             }
        }else{
            $tempTitle = explode('/',PHPDOCX_BASE_TEMPLATE);
            $title = array_pop($tempTitle);
            $parsedStyles = $this->_baseTemplateZip->getFromName('word/styles.xml');
            $parsedNumberings = $this->_baseTemplateZip->getFromName('word/numbering.xml');
        }
        
        
        //We now include certain sample content to create the resulting style docx
        
        $myParagraph = 'This is some sample paragraph test';
        $myList = array('item 1', 'item 2', array('subitem 2_1', 'subitem 2_2'), 'item 3', array('subitem 3_1', 'subitem 3_2', array('sub_subitem 3_2_1', 'sub_subitem 3_2_1')), 'item 4');
        $myTable = array(
                            array(
                            'Title A',
                            'Title B',
                            'Title C'
                            ),
                            array(
                            'First row A',
                            'First row B',
                            'First row C'
                            ),
                            array(
                            'Second row A',
                            'Second row B',
                            'Second row C'
                            )
                         );
        
        //let's parse the different list numberings from 
        $this->addText('List styles: '.$title, array('jc' => 'center', 'color' => 'b90000', 'b' => 'single', 'sz' => '18', 'u' => 'double'));
        
        $wordListChunk ='<w:p><w:pPr><w:rPr><w:b/></w:rPr></w:pPr>
        <w:r><w:rPr><w:b/></w:rPr><w:t>SAMPLE CODE:</w:t></w:r>
        </w:p><w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
        <w:shd w:val="clear" w:color="auto" w:fill="DDD9C3"/>
        <w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid>
        <w:gridCol w:w="8644"/></w:tblGrid><w:tr><w:tc>
        <w:tcPr><w:tcW w:w="8644" w:type="dxa"/>
        <w:shd w:val="clear" w:color="auto" w:fill="DCDAC4"/>
        </w:tcPr><w:p><w:pPr><w:spacing w:before="200"/></w:pPr>
        <w:r><w:t>$</w:t></w:r><w:r>
        <w:t>myList</w:t></w:r><w:r>
        <w:t xml:space="preserve"> = array(\'item 1\', </w:t>
        </w:r><w:r>
        <w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r>
        <w:t xml:space="preserve">\'item 2\', </w:t>
        </w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>subitem</w:t>
        </w:r><w:r>
        <w:t xml:space="preserve"> 2_1\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>\'</w:t>
        </w:r><w:r><w:t>subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 2_2\'), </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t xml:space="preserve">\'item 3\', </w:t></w:r>
        <w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>subitem</w:t>
        </w:r><w:r><w:t xml:space="preserve"> 3_1\', </w:t></w:r>
        <w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>\'</w:t></w:r><w:r><w:t>subitem</w:t></w:r>
        <w:r><w:t xml:space="preserve"> 3_2\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>sub_subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 3_2_1\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                                   </w:t>
        </w:r><w:r><w:t>\'</w:t></w:r><w:r><w:t>sub_subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 3_2_1\')),</w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t xml:space="preserve"> \'item 4\');</w:t></w:r></w:p>
        <w:p><w:pPr><w:spacing w:before="200"/></w:pPr>
        <w:r><w:t>addList</w:t></w:r><w:r><w:t>($</w:t></w:r>
        <w:r><w:t>myList</w:t></w:r><w:r><w:t>, array(\'</w:t></w:r>
        <w:r><w:t>val</w:t></w:r><w:r>
        <w:t>\' =</w:t></w:r><w:r><w:t>&gt; NUMID</w:t></w:r>
        <w:r><w:t>))</w:t></w:r></w:p></w:tc></w:tr></w:tbl><w:p><w:pPr></w:pPr>
        </w:p>
        <w:p><w:pPr><w:rPr><w:b/></w:rPr></w:pPr>
        <w:r><w:rPr><w:b/></w:rPr><w:t>SAMPLE RESULT:</w:t></w:r>
        </w:p>';
        $NumberingsDoc = new DOMDocument();
        $NumberingsDoc->loadXML($parsedNumberings);
        $numberXpath = new DOMXPath($NumberingsDoc);       
        $numberXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');      
        $queryNumber = '//w:num';
        $numberingsNodes = $numberXpath->query($queryNumber);
        foreach($numberingsNodes as $node){
            $wordListChunkTemp = str_replace('NUMID',$node->getAttribute('w:numId'), $wordListChunk);
            $this->_wordDocumentC .= $wordListChunkTemp;
            $this->addList($myList, array('val' => $node->getAttribute('w:numId')));
            $this->addBreak('page');
        }
        
        $this->addText('Paragraph and Table styles: '.$title, array('jc' => 'center', 'color' => 'b90000', 'b' => 'single', 'sz' => '18', 'u' => 'double'));
        
        //let's parse the different styles via XPath
        $StylesDoc = new DOMDocument();
        $StylesDoc->loadXML($parsedStyles);
        $parseStylesXpath = new DOMXPath($StylesDoc);       
        $parseStylesXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');      
        $query = '//w:style';
        $parsedNodes = $parseStylesXpath->query($query);
        //Lets list the present styles and their respective Ids
        $count=1;
        foreach($parsedNodes as $node){
          $styleId = $node->getAttribute('w:styleId');
          $styleType= $node->getAttribute('w:type');
          $styleDefault = $node->getAttribute('w:default');
          $styleCustom= $node->getAttribute('w:custom');
          $nodeChilds = $node->childNodes;       
          foreach($nodeChilds as $child){
          if ($child->nodeName == 'w:name'){
             $styleName = $child->getAttribute('w:val');   
              }
           }
           $this->parsedStyles[$count]= array('id'=>$styleId, 'name'=>$styleName, 'type'=>$styleType,'default'=>$styleDefault, 'custom'=>$styleCustom);
              
                     
           $default = ($styleDefault == 1)?'true':'false';
           $custom = ($styleCustom == 1)?'true':'false';
           
           $wordMLChunk = '<w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
                </w:tblPr><w:tblGrid><w:gridCol w:w="4322"/><w:gridCol w:w="4322"/>
                </w:tblGrid><w:tr><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="BD1503"/>
                </w:tcPr><w:p><w:pPr><w:spacing w:after="0"/><w:rPr>
                <w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r><w:rPr>
                <w:color w:val="FFFFFF"/></w:rPr><w:t>NAME:</w:t></w:r></w:p>
                </w:tc><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="BD1503"/></w:tcPr>
                <w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:color w:val="FFFFFF"/>
                </w:rPr></w:pPr><w:r><w:rPr><w:color w:val="FFFFFF"/>
                </w:rPr><w:t>'.$styleName.'</w:t></w:r></w:p></w:tc>
                </w:tr><w:tr><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Type</w:t>
                </w:r><w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>:</w:t></w:r></w:p></w:tc><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>'.$styleType.'</w:t></w:r></w:p></w:tc></w:tr>
                <w:tr><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>ID:</w:t></w:r></w:p></w:tc><w:tc>
                <w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>'.$styleId.'</w:t></w:r></w:p></w:tc></w:tr><w:tr><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/></w:tcPr>
                <w:p><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Default:</w:t></w:r>
                </w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr>
                <w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>'.$default.'</w:t></w:r></w:p></w:tc></w:tr><w:tr>
                <w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr>
                <w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Custom</w:t>
                </w:r><w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>:</w:t></w:r></w:p></w:tc><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>'.$custom.'</w:t>
                </w:r></w:p></w:tc></w:tr></w:tbl>
                <w:p w:rsidR="000F6147" w:rsidRDefault="000F6147" w:rsidP="00B42E7D">
                <w:pPr><w:spacing w:after="0"/></w:pPr></w:p>
                <w:p w:rsidR="00DC3ACE" w:rsidRDefault="00DC3ACE">
                <w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:r>
                <w:rPr><w:b/></w:rPr><w:t>SAMPLE CODE:</w:t></w:r></w:p>
                <w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
                <w:shd w:val="clear" w:color="auto" w:fill="DDD9C3"/>
                </w:tblPr><w:tblGrid><w:gridCol w:w="8644"/>
                </w:tblGrid><w:tr><w:tc><w:tcPr><w:tcW w:w="8644" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="DCDAC4"/></w:tcPr>
                <w:p w:rsidR="00DC3ACE" w:rsidRDefault="00DC3ACE">
                <w:pPr><w:spacing w:before="200" /></w:pPr><w:r>
                <w:t>CODEX</w:t></w:r></w:p></w:tc></w:tr></w:tbl><w:p/><w:p>
                <w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:r><w:rPr><w:b/>
                </w:rPr><w:t>SAMPLE RESULT:</w:t></w:r></w:p>
                ';
           
           switch($styleType){
               case 'table':
                   $wordMLChunk = str_replace('CODEX',"addTable(array(array('Title A','Title B','Title C'),array('First row A','First row B','First row C'),array('Second row A','Second row B','Second row C')), array('TBLSTYLEval'=> '$styleId'))", $wordMLChunk);
                   $this->_wordDocumentC .= $wordMLChunk;
                   $params = array('TBLSTYLEval'=> $styleId);
                   $this->addTable($myTable, $params);
                   if($count%2 == 0){
                   $this->_wordDocumentC .= '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
                   }else{
                    $this->_wordDocumentC .= '<w:p /><w:p />';
                   }
                   $count++;
                   break;
               case 'paragraph':
                   $myPCode = "addText('This is some sample paragraph test', array('pStyle' => '".$styleId."'))";
                   $wordMLChunk = str_replace('CODEX',$myPCode, $wordMLChunk);
                   $this->_wordDocumentC .= $wordMLChunk;
                   $params = array('pStyle'=> $styleId);
                   $this->addText($myParagraph, $params);
                   if($count%2 == 0){
                    $this->_wordDocumentC .= '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
                   }else{
                    $this->_wordDocumentC .= '<w:p /><w:p />';
                   }
                   $count++;
                   break;
           }
           
        } 
      
    }
    
    
     /**
     * Imports an existing theme from an existing docx document.
     *
     * @access public
     * @example ../examples/intermediate/importTheme.php
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     */
    public function importThemeXML($path){
        try{
            $zipTheme = new ZipArchive();
            $extractingThemeFile = $zipTheme->extractTo($this->_baseTemplateFilesPath.'/','word/theme/theme1.xml');
            if (!$extractingThemeFile){
              throw new Exception('Error while trying to overwrite the theme1.xml of the base template');  
             }
            }
            catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
            }        
    } 
     /**
     * Imports an existing webSettings.xml file from an existing docx document.
     *
     * @access public
     * @example ../examples/intermediate/importWebSettings.php
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     */
    public function importWebSettingsXML($path){
        try{
            $zipWebSettings = new ZipArchive();
            $extractingWebSettingsFile = $zipTheme->extractTo($this->_baseTemplateFilesPath.'/','word/webSettings.xml');
            if (!$extractingWebSettingsFile){
              throw new Exception('Error while trying to overwrite the webSettings.xml of the base template');  
             }
            }
            catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
            }        
    } 
     /**
     * Imports an existing settings.xml file from an existing docx document.
     *
     * @access public
     * @example ../examples/intermediate/importSettings.php
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     */
    public function importSettingsXML($path){
        try{
            $zipSettings = new ZipArchive();
            $extractingSettingsFile = $zipTheme->extractTo($this->_baseTemplateFilesPath.'/','word/settings.xml');
            if (!$extractingSettingsFile){
              throw new Exception('Error while trying to overwrite the settings.xml of the base template');  
             }
            }
            catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
            }        
    }     
     /**
     * Imports an existing fontTable.xml file from an existing docx document.
     *
     * @access public
     * @example ../examples/intermediate/importFontTable.php
     * @param string $path. Must be a valid path to an existing .docx, .dotx o .docm document
     */
    public function importFontTableXML($path){
        try{
            $zipFontTable = new ZipArchive();
            $extractingFontTableFile = $zipTheme->extractTo($this->_baseTemplateFilesPath.'/','word/fontTable.xml');
            if (!$extractingFontTableFile){
              throw new Exception('Error while trying to overwrite the fontTable.xml of the base template');  
             }
            }
            catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
            }        
    }
    /**
     * Embed HTML into the Word document by parsing the HTML code and converting it into WordML
     * It preserves many CSS styles
     *
     * @access public
     * @example ../examples/easy/embedSimpleHTML.php
     * @example ../examples/intermediate/embedExternalHTML.php
     * @param string $html HTML to add. Must be a valid XHTML
     * @param string $options: isFile (boolean),baseURL (string), downloadImages (boolean), id (string), parseDivsAsPs (boolean), tableStyle (string), paragraphStyle (string) 
     */
    public function embedHTML($html = '<html><body></body></html>', $options = array())
    {        
        $htmlDOCX = new HTML2WordML();
        $sFinalDocX = $htmlDOCX->render($html, $options);
        self::$log->info('Add converted HTML to word document.');
        $this->_wordDocumentC .= (string) $sFinalDocX[0];
        $relsLinks = '';
        foreach ($sFinalDocX[1] as $key => $value) {
          $relsLinks .= '<Relationship Id="'.$key.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="'.$value.'" TargetMode="External" />'; 
        }
        if($relsLinks != ''){
         $relsNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
         $relsNode->appendXML($relsLinks);                  
         $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNode);
        } 
        $relsImages = '';
        foreach ($sFinalDocX[2] as $key => $value) {
          $value = array_shift(explode('?', $value));//We just get the photo path
          if(isset($options['downloadImages']) && $options['downloadImages']) {
              $photo = file_get_contents($value);
              $arrayExtension = explode('.', $value);
              $extension = strtolower(array_pop($arrayExtension));
              $tempId = rand(999999, 9999999999);
              if(!is_dir($this->_baseTemplateFilesPath.'/word/media')){
                  mkdir($this->_baseTemplateFilesPath.'/word/media');
              }
              $predefinedExtensions = array('gif', 'png', 'jpg', 'jpeg', 'bmp');
              if (! in_array($extension, $predefinedExtensions)){
                $this->generateDEFAULT($extension, 'image/'.$extension);
              }
              $photoHandle = fopen($this->_baseTemplateFilesPath.'/word/media/img'.$tempId.'.'.$extension, "w+");
              $contents = fwrite($photoHandle, $photo);
              fclose($photoHandle);
              $relsImages .= '<Relationship Id="'.$key.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img'.$tempId.'.'.$extension.'" />'; 
          }else{  
              $relsImages .= '<Relationship Id="'.$key.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="'.$value.'" TargetMode="External" />'; 
          }
        }
        if($relsImages != ''){
         $relsNodeImages = $this->_wordRelsDocumentRelsT->createDocumentFragment();
         $relsNodeImages->appendXML($relsImages);                  
         $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNodeImages);
        } 
    }
    
    /**
     *
     * @param string $path
     * 
     */
    public function addBackgroundImage($path)
    {
        //Let us extract some basic info about the background image
        $image = pathinfo($path);
        $extension = $image['extension'];
        $imageName = $image['filename'];
        //We define an unique identifier
        $identifier = 'rId'.uniqid(true);
        //We construct the background WordML code
        $this->_background ='<w:background w:color="FFFFFF">
                      <v:background id="id_'.uniqid().'" o:bwmode="white" o:targetscreensize="800,600">
                      <v:fill r:id="'.$identifier.'" o:title="tit_'.uniqid(true).'" recolor="t" type="frame" /> 
                      </v:background></w:background>';
        //We make sure that there exists the corresponding content type
        $this->generateDEFAULT($extension, 'image/'.$extension);
        //We copy the image in the media folder
        $tempId = uniqid(true);
        $backgroundImage = file_get_contents($path);
        if(!is_dir($this->_baseTemplateFilesPath.'/word/media')){
              mkdir($this->_baseTemplateFilesPath.'/word/media');
        }
        $backgroundImageHandle = fopen($this->_baseTemplateFilesPath.'/word/media/img'.$tempId.'.'.$extension, "w+");
        $contents = fwrite($backgroundImageHandle, $backgroundImage);
        fclose($backgroundImageHandle);
        //We insert the relationship
        $relsImage = '<Relationship Id="'.$identifier.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img'.$tempId.'.'.$extension.'" />'; 
        $relsNodeImage = $this->_wordRelsDocumentRelsT->createDocumentFragment();
        $relsNodeImage->appendXML($relsImage);                  
        $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNodeImage);
        //Now we have to modify the settings to display the background image
        try{
            $settings = fopen($this->_baseTemplateFilesPath.'/word/settings.xml', "r");
            $baseTemplateSettingsT = fread($settings, 1000000);
            fclose($settings);
            if ($baseTemplateSettingsT == '') {
                throw new Exception('Error while extracting settings.xml file from the base template to insert the background image');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        $this->_wordSettingsT = new DOMDocument();
        $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
        $settingsImage = $this->_wordSettingsT->createDocumentFragment();
        $settingsImage->appendXML('<w:displayBackgroundShape xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />');                  
        $this->_wordSettingsT->documentElement->appendChild($settingsImage);
        $newSettings = $this->_wordSettingsT->saveXML();
        $settingsHandle = fopen($this->_baseTemplateFilesPath.'/word/settings.xml', "w+");
        $contents = fwrite($settingsHandle, $newSettings);
        fclose($settingsHandle);
        
    }
    
    /**
     *
     * @param string $html
     * @param array $options 
     * @deprecated
     */
    public function addBasicHTML($html = '<html><body></body></html>', $options = array())
    {
        $this->embedHTML($html, $options);
    }

    /**
     * Add a break
     *
     * @access public
     * @example ../examples/easy/PageBreak.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/FooterPager.php
     * @example ../examples/intermediate/Multidocument.php
     * @example ../examples/advanced/Report.php
     * @param string $type Break type.
     *  Values: 'line', 'page'
     */
    public function addBreak($type = '')
    {
        $page = CreatePage::getInstance();
        $page->generatePageBreak($type);
        self::$log->info('Add break to word document.');
        $this->_wordDocumentC .= (string) $page;
    }

    /**
     * Add a chart
     *
     * @access public
     * @example ../examples/easy/Chart.php
     * @example ../examples/easy/BarChart.php
     * @example ../examples/intermediate/Chart.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/Multidocument.php
     * @example ../examples/advanced/Report.php
     * @param array $dats Parameters of graphic
     *  Values: 'color' (1, 2, 3...), 'cornerP' (20, 30...),
     *  'cornerX' (20, 30...),
     *  'cornerY' (20, 30...),
     *  'data' (array of values),
     *  'font' (Arial, Times New Roman...),
     *  'groupBar' (clustered, stacked),
     *  'jc' (center, left, right),
     *  'showPercent' (0, 1), 'sizeX' (10, 11, 12...),
     *  'sizeY' (10, 11, 12...),
     *  'textWrap' (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom), 5 (clear)),
     *  'title',
     *  'type' (barChart, bar3DChart, bar3DChartCylinder, bar3DChartCone,  bar3DChartPyramid, colChart, col3DChart,
     *          col3DChartCylinder,  col3DChartCone, bar3DChartPyramid, pieChart, pie3DChart, lineChart, line3DChart,
     *          areaChart, area3DChart, radar)
     *  'legendpos' (r, l, t, b, none),
     *  'legendoverlay' (0, 1),
     *  'border' (0, 1),
     *  'haxlabel',
     *  'vaxlabel',
     *  'showtable' (0, 1),
     *  'vaxlabel_display' (rotated, vertical, horizontal),
     *  'haxlabel_display' (rotated, vertical, horizontal),
     *  'hgrid' (0, 1, 2, 3),
     *  'vgrid' (0, 1, 2, 3)
     */
    public function addChart($dats)
    {
        self::$log->debug('Create chart.');
        try {
            if (isset($dats['data']) && isset($dats['type'])) {
                self::$intIdWord++;
                self::$log->debug('New ID ' . self::$intIdWord . ' . Chart.');
                $graphic = CreateGraphic::getInstance();
                if ($graphic->createGraphic(self::$intIdWord, $dats) != false) {
                    self::$log->info(
                        'Add chart word/charts/chart' . self::$intIdWord .
                        '.xml to DOCX.'
                    );
                    $this->_zipDocx->addFromString(
                        'word/charts/chart' . self::$intIdWord . '.xml',
                        $graphic->getXmlChart()
                    );
                    $this->_wordRelsDocumentRelsC .=
                        $this->generateRELATIONSHIP(
                            'rId' . self::$intIdWord, 'chart',
                            'charts/chart' . self::$intIdWord . '.xml'
                        );
                    $this->_wordDocumentC .= (string) $graphic;
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE(
                        '/word/charts/chart' . self::$intIdWord . '.xml',
                        'application/vnd.openxmlformats-officedocument.' .
                        'drawingml.chart+xml'
                    );
                } else {
                    throw new Exception(
                        'There was an error related to the chart.'
                    );
                }
                $excel = CreateXlsx::getInstance();
                $this->_tempFileXLSX[self::$intIdWord] =
                    tempnam($this->_tempDir, 'documentxlsx');
                if (
                    $excel->createXlsx(
                        $this->_tempFileXLSX[self::$intIdWord],
                        $dats['data'], $dats['type']
                    ) != false
                ) {
                    $this->_zipDocx->addFile(
                        $this->_tempFileXLSX[self::$intIdWord],
                        'word/embeddings/datos' . self::$intIdWord . '.xlsx'
                    );

                    $chartRels = CreateChartRels::getInstance();
                    $chartRels->createRelationship(self::$intIdWord);
                    $this->_zipDocx->addFromString(
                        'word/charts/_rels/chart' . self::$intIdWord .
                        '.xml.rels',
                        (string) $chartRels
                    );
                }
            } else {
                throw new Exception(
                    'Images must have "data" and "type" values.'
                );
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }
    
    /**
     * Merge DOCX. Keep content and styles.
     *
     * @access public
     * @example ../examples/easy/DOCX.php
     * @param string $pathDOCX Path to DOCX
     */
    public function addDOCX($pathDOCX)
    {
        try {
            if ($this->_compatibilityMode) {
              throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            if (file_exists($pathDOCX)) {
                $wordDOCX = EmbedDOCX::getInstance();
                $wordDOCX->embed();
                self::$log->info('Add DOCX file to word document.');
                $this->_wordDocumentC .= (string)$wordDOCX;
                $this->_zipDocx->addFile($pathDOCX, 'word/docx' . $wordDOCX->getId() .
                                                    '.zip');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                    'rDOCXId' . $wordDOCX->getId(), 'aFChunk', 'docx' .
                                                               $wordDOCX->getId() . '.zip', 'TargetMode="Internal"');
                $this->generateDEFAULT('zip', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
            } else {
                throw new Exception('File does not exist.');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Add an element to embedd in other elements
     *
     * @access public
     * @example ../examples/intermediate/List.php
     * @example ../examples/intermediate/TableStyled.php
     * @param string $type Element type.
     *  Values: 'addImage', 'addGraphic', 'addLink', 'addTextBox', 'addText'
     * @param array $parrams Set of parameters.
     *  Values: same as elements
     * @return Element object
     */
    public function addElement($type, $params = '')
    {
        $type = str_replace('add', 'Create', $type);
        $element = CreateFactory::createObject($type);
        $type = str_replace('Create', 'init', $type);
        $element->$type($params);
        if ($type == 'initImage') {
            self::$log->debug('Create embedded image.');
            try {
                if (isset($params['name']) && file_exists($params['name'])) {
                    $attrImages = getimagesize($params['name']);
                    try {
                        if ($attrImages['mime'] == 'image/jpg' ||
                            $attrImages['mime'] == 'image/jpeg' ||
                            $attrImages['mime'] == 'image/png' ||
                            $attrImages['mime'] == 'image/gif'
                        ) {
                            self::$intIdWord++;
                            self::$log->debug(
                                'New ID ' . self::$intIdWord .
                                ' . Embedded image.'
                            );
                            $type = str_replace('add', 'Create', $type);
                            $dir = $this->parsePath($params['name']);
                            $element->setRId(self::$intIdWord);
                            self::$log->info(
                                'Add image ' . $params['name']
                                . ' to DOCX.'
                            );
                            $this->_zipDocx->addFile(
                                $params['name'],
                                'word/media/image' . self::$intIdWord . '.'
                                . $dir['extension']
                            );
                            $this->generateDEFAULT(
                                $dir['extension'], $attrImages['mime']
                            );
                            self::$log->debug(
                                'Add a new relationship related to image '
                                . $params['name'] . ' .'
                            );
                            $this->_wordRelsDocumentRelsC .=
                                $this->generateRELATIONSHIP(
                                    'rId'
                                    . self::$intIdWord, 'image', 'media/image'
                                    . self::$intIdWord . '.'
                                    . $dir['extension']
                                );
                        } else {
                            throw new Exception(
                                'Image format is not supported.'
                            );
                        }
                    }
                    catch (Exception $e) {
                        self::$log->fatal($e->getMessage());
                        exit();
                    }
                } else {
                    throw new Exception('Image does not exist.');
                }
            }
            catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
            }
        } elseif ($type == 'initGraphic' || $type == 'initChart') {
            self::$log->debug('Create embedded chart.');
            try {
                if (isset($params['data']) && isset($params['type'])) {
                    self::$intIdWord++;
                    self::$log->debug(
                        'New ID ' . self::$intIdWord . ' . Embedded chart.'
                    );
                    $element->setRId(self::$intIdWord);
                    self::$log->info(
                        'Add chart ' . 'word/charts/chart' . self::$intIdWord
                        . '.xml to DOCX.'
                    );
                    $this->_zipDocx->addFromString(
                        'word/charts/chart' . self::$intIdWord . '.xml',
                        $element->createEmbeddedXmlChart()
                    );
                    self::$log->debug(
                        'Add a new relationship related to chart.'
                    );
                    $this->_wordRelsDocumentRelsC .=
                        $this->generateRELATIONSHIP(
                            'rId' . self::$intIdWord, 'chart', 'charts/chart' .
                            self::$intIdWord . '.xml'
                        );
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE(
                        '/word/charts/chart' . self::$intIdWord .
                        '.xml',
                        'application/vnd.openxmlformats-officedocument' . 
                        '.drawingml.chart+xml'
                    );
                    $excel = CreateXlsx::getInstance();
                    $this->_tempFileXLSX[self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                    if (
                        $excel->createXlsx(
                            $this->_tempFileXLSX[self::$intIdWord], $params['data'],
                            $params['type']
                        ) != false
                    ) {
                        $this->_zipDocx->addFile(
                            $this->_tempFileXLSX[self::$intIdWord],
                            'word/embeddings/datos' . self::$intIdWord . '.xlsx'
                        );

                        $chartRels = CreateChartRels::getInstance();
                        $chartRels->createRelationship(self::$intIdWord);
                        self::$log->info(
                            'Add chart ' . 'word/charts/_rels/chart'
                            . self::$intIdWord . '.xml.rels to DOCX.'
                        );
                        $this->_zipDocx->addFromString(
                            'word/charts/_rels/chart' . self::$intIdWord
                            . '.xml.rels', (string) $chartRels
                        );
                    }
                } else {
                    throw new Exception(
                        'Charts must have "data" and "type" values.'
                    );
                }
            }
            catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
            }
        }

        return $element;
    }

    /**
     * Add an endnote
     *
     * @access public
     * @example ../examples/easy/Endnote.php
     * @example ../examples/intermediate/EndnoteAndFootnote.php
     * @param string $paramsEndnote Parameters of endnote.
     *  Optional, default empty.
     *  Values: 'font', 'textDocument', 'textEndnote'
     */
    public function addEndnote($paramsEndnote = '')
    {
        $id = rand(9999,32766);//this number can not be bigger than 32767
        $endnoteBase ='<w:endnote w:id="'.$id.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:p>
            <w:pPr><w:pStyle w:val="endnoteTextPHPDOCX"/></w:pPr>
            <w:r><w:rPr><w:rStyle w:val="endnoteReferencePHPDOCX"/></w:rPr><w:endnoteRef/></w:r>
            <w:r><w:t xml:space="preserve">'.$paramsEndnote['textEndnote'].'</w:t></w:r></w:p>
            </w:endnote>';
        
        $endnoteDocument ='<w:p><w:r><w:t>'.$paramsEndnote['textDocument'].'</w:t></w:r>
            <w:r><w:rPr><w:rStyle w:val="endnoteReferencePHPDOCX" /></w:rPr><w:endnoteReference w:id="'.$id.'" /></w:r></w:p>';
        //We now try to open the zip file defined as base template
        
        $filename = $this->_baseTemplateFilesPath.'/word/endnotes.xml';
        $handle = fopen($filename, "r");
        $contents = fread($handle, 999999);
        fclose($handle);
        
         
        $this->_wordEndnotesT = new DOMDocument();
        $this->_wordEndnotesT->loadXML($contents);
        
        $tempNode = $this->_wordEndnotesT->createDocumentFragment();
        $tempNode->appendXML($endnoteBase);                  
        $this->_wordEndnotesT->documentElement->appendChild($tempNode);
        
        //We copy the  content into the respective file
        $endnoteHandler = fopen($this->_baseTemplateFilesPath.'/word/endnotes.xml', "w+");
        fwrite($endnoteHandler, $this->_wordEndnotesT->saveXML());
        fclose($endnoteHandler);
        
        self::$log->info('Add footnote to endnote document.');
       
        $this->_wordDocumentC .= (string) $endnoteDocument;
        
        self::$log->info('Add endnote to word document.');
    }

    /**
     * Add a new font
     *
     * @access public
     * @param array $fonts Fonts to add
     */
    public function addFont($fonts)
    {
        $font = CreateFontTable::getInstance();
        $font->createFont($fonts);
        self::$log->info('Add font to fontable document.');
        $this->_wordFontTableC .= (string) $font;
    }

    /**
     * Add a footer
     *
     * @access public
     * @example ../examples/easy/Footer.php
     * @example ../examples/intermediate/HeaderAndFooter.php
     * @example ../examples/intermediate/FooterPager.php
     * @example ../examples/advanced/Report.php
     * @param string $dat Text to add
     * @param array $paramsFooter Parameters of footer
     *  Values: 'name', 'pager' (true, false),
     *  'pagerAlignment' (left, right, false)
     */
    public function addFooter($dat = '', $paramsFooter = '')
    {
        $this->footerAdded = true;
        $this->removeFooters();
        $footer = CreateFooter::getInstance();
        $footer->createFooter($dat, $paramsFooter);
        self::$log->info('Add footer to footer document.');
        $this->_wordFooterC .= (string) $footer;
        if (!empty($paramsFooter['name'])) {
            self::$log->info('Add image to footer document.');
            $xmlHeaderImg = $this->addImageFooter($paramsFooter);
            $this->_wordFooterC = str_replace(
                '__GENERATEIMGFTR__', $xmlHeaderImg, $this->_wordFooterC
            );
        }
        $this->generateOVERRIDE(
            '/word/footer.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' .
            '.footer+xml'
        );
    }

    /**
     * Add a footnote
     *
     * @access public
     * @example ../examples/easy/Footnote.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/EndnoteAndFootnote.php
     * @param array $paramsFootnote Parameters of footnote
     *  Values: 'font', 'textDocument', 'textFootnote'
     */
    public function addFootnote($paramsFootnote = '')
    {
        $id = rand(9999,32766);//this number can not be bigger than 32767
        $footnoteBase ='<w:footnote w:id="'.$id.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:p>
            <w:pPr><w:pStyle w:val="footnoteTextPHPDOCX"/></w:pPr>
            <w:r><w:rPr><w:rStyle w:val="footnoteReferencePHPDOCX"/></w:rPr><w:footnoteRef/></w:r>
            <w:r><w:t xml:space="preserve">'.$paramsFootnote['textFootnote'].'</w:t></w:r></w:p>
            </w:footnote>';
        
        $footnoteDocument ='<w:p><w:r><w:t>'.$paramsFootnote['textDocument'].'</w:t></w:r>
            <w:r><w:rPr><w:rStyle w:val="footnoteReferencePHPDOCX" /></w:rPr><w:footnoteReference w:id="'.$id.'" /></w:r></w:p>';
        //We now try to open the zip file defined as base template
        
        $filename = $this->_baseTemplateFilesPath.'/word/footnotes.xml';
        $handle = fopen($filename, "r");
        $contents = fread($handle, 999999);
        fclose($handle);
        
         
        $this->_wordFootnotesT = new DOMDocument();
        $this->_wordFootnotesT->loadXML($contents);
        
        $tempNode = $this->_wordFootnotesT->createDocumentFragment();
        $tempNode->appendXML($footnoteBase);                  
        $this->_wordFootnotesT->documentElement->appendChild($tempNode);
        
        //We copy the  content into the respective file
        $footnoteHandler = fopen($this->_baseTemplateFilesPath.'/word/footnotes.xml', "w+");
        fwrite($footnoteHandler, $this->_wordFootnotesT->saveXML());
        fclose($footnoteHandler);
        
        self::$log->info('Add footnote to footnote document.');
       
        $this->_wordDocumentC .= (string) $footnoteDocument;
        
        self::$log->info('Add footnote to word document.');
    }

    /**
     * Add a graphic
     *
     * @access public
     * @example ../examples/easy/Chart.php
     * @example ../examples/easy/BarChart.php
     * @example ../examples/intermediate/Chart.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/Multidocument.php
     * @example ../examples/advanced/Report.php
     * @param array $dats Parameters of graphic
     *  Values: 'color' (1, 2, 3...), 'cornerP' (20, 30...),
     *  'cornerX' (20, 30...), 'cornerY' (20, 30...), 'data' (array of values),
     *  'font' (Arial, Times New Roman...), 'groupBar' (clustered, stacked),
     *  'jc' (center, left, right), 'showPercent' (0, 1), 'sizeX' (10, 11,
     *   12...), 'sizeY' (10, 11, 12...), 'textWrap' (0 (inline), 1 (square),
     *  2 (front), 3 (back), 4 (up and bottom), 5 (clear)), 'title', 'type'
     *  (barChart, bar3DChart, colChart, col3DChart, pieChart, pie3DChart)
     *  @deprecated
     */
    public function addGraphic($dats)
    {
        self::$log->debug('Create chart.');
        try {
            if (isset($dats['data']) && isset($dats['type'])) {
                self::$intIdWord++;
                self::$log->debug('New ID ' . self::$intIdWord . ' . Chart.');
                $graphic = CreateGraphic::getInstance();
                if ($graphic->createGraphic(self::$intIdWord, $dats) != false) {
                    self::$log->info(
                        'Add chart word/charts/chart' . self::$intIdWord .
                        '.xml to DOCX.'
                    );
                    $this->_zipDocx->addFromString(
                        'word/charts/chart' . self::$intIdWord . '.xml',
                        $graphic->getXmlChart()
                    );
                    $this->_wordRelsDocumentRelsC .=
                        $this->generateRELATIONSHIP(
                            'rId' . self::$intIdWord, 'chart',
                            'charts/chart' . self::$intIdWord . '.xml'
                        );
                    $this->_wordDocumentC .= (string) $graphic;
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE(
                        '/word/charts/chart' . self::$intIdWord . '.xml',
                        'application/vnd.openxmlformats-officedocument.' .
                        'drawingml.chart+xml'
                    );
                } else {
                    throw new Exception(
                        'There was an error related to the chart.'
                    );
                }
                $excel = CreateXlsx::getInstance();
                $this->_tempFileXLSX[self::$intIdWord] =
                    tempnam($this->_tempDir, 'documentxlsx');
                if (
                    $excel->createXlsx(
                        $this->_tempFileXLSX[self::$intIdWord],
                        $dats['data'], $dats['type']
                    ) != false
                ) {
                    $this->_zipDocx->addFile(
                        $this->_tempFileXLSX[self::$intIdWord],
                        'word/embeddings/datos' . self::$intIdWord . '.xlsx'
                    );

                    $chartRels = CreateChartRels::getInstance();
                    $chartRels->createRelationship(self::$intIdWord);
                    $this->_zipDocx->addFromString(
                        'word/charts/_rels/chart' . self::$intIdWord .
                        '.xml.rels',
                        (string) $chartRels
                    );
                }
            } else {
                throw new Exception(
                    'Images must have "data" and "type" values.'
                );
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add a graphic image
     *
     * @access public
     * @param array $dats Parameters of graphic
     * Values: 'color' (1, 2, 3, 4, 5), 'cornerX' (20, 30...), 
     * 'data' (array of values), sizeX' (10, 11, 12...),
     * 'sizeY' (10, 11, 12...), 'title',  'type' (barChart, bar3DChart, 
     * colChart, col3DChart, pieChart, pie3DChart)
     */
    public function addGraphicImg($dats)
    {
        self::$log->debug('Create image chart.');
        try {
            if (isset($dats['data']) && isset($dats['type'])) {
                $graphic = CreateGraphicImg::getInstance();
                if ($graphic->createGraphicImg($dats)) {
                    
                }
                else {
                    throw new Exception(
                        'Unsupported chart type.'
                    );
                }
            } else {
                throw new Exception(
                    'Images must have "data" and "type" values.'
                );
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add a graphic template. Replace placeholder with a graphic
     *
     * @access public
     * @param array $dats Parameters of graphic
     *  Values: same as charts
     */
    public function addGraphicTemplate($dats)
    {
        try {
            if (isset($dats['data']) && isset($dats['type'])) {
                self::$intIdWord++;
                self::$log->debug(
                    'New ID ' . self::$intIdWord . ' . Image template.'
                );
                $graphic = CreateGraphic::getInstance();
                if ($graphic->createGraphic(self::$intIdWord, $dats) != false) {
                    $this->graphicTemplate['arrChartXML'][self::$intIdWord] =
                        $graphic->getXmlChart();
                    $this->graphicTemplate['arrRelationships'][self::$intIdWord] =
                        $this->generateRELATIONSHIPTemplate(
                            'rId' . self::$intIdWord, 'chart',
                            'charts/chart' . self::$intIdWord . '.xml'
                        );
                    $this->graphicTemplate['arrGraphics'][self::$intIdWord] =
                        (string) $graphic;
                    $this->graphicTemplate['arrGenerateDEFAULT'][self::$intIdWord] =
                        '<Default Extension="' . 'xlsx' . '" ContentType="' .
                        'application/octet-stream' . '"> </Default>';
                    $this->graphicTemplate['arrGenerateOVERRIDE'][self::$intIdWord] =
                        '<Override PartName="' . '/word/charts/chart' .
                        self::$intIdWord . '.xml' . '" ContentType="' .
                        'application/vnd.openxmlformats-officedocument.' .
                        'drawingml.chart+xml' . '"> </Override>';
                } else {
                    throw new Exception(
                        'There was an error related to the chart.'
                    );
                }
                $excel = CreateXlsx::getInstance();
                $this->_tempFileXLSX[self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                if (
                    $excel->createXlsx(
                        $this->_tempFileXLSX[self::$intIdWord], $dats['data'],
                        $dats['type']
                    ) != false
                ) {
                    $this->fileGraphicTemplate['datos' . self::$intIdWord . '.xlsx'] =
                        'word/embeddings/datos' . self::$intIdWord . '.xlsx';

                    $objChartRels = CreateChartRels::getInstance();
                    $objChartRels->createRelationship(self::$intIdWord);
                    $this->fileGraphicTemplate['word/charts/_rels/chart' . self::$intIdWord . '.xml.rels'] =
                        (string) $objChartRels;
                }
            } else {
                throw new Exception(
                    'Charts must have "data" and "type" values.
                ');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add a header.
     *
     * @access public
     * @example ../examples/easy/Header.php
     * @example ../examples/intermediate/HeaderAndFooter.php
     * @example ../examples/advanced/Report.php
     * @param string $text Text to add
     * @param array $paramsHeader Parameters of header
     *  Values: 'border'(1, 2, 3...), 'borderDiscontinuous' (0, 1),
     *  'font' (Arial, Times New Roman...), 'jc' (center, left, right),
     *  'name', 'scaling' (50, 100), 'sizeX' (10, 11, 12...), 'sizeY'
     *  (10, 11, 12...), spacingTop (10, 11...), spacingBottom (10, 11...),
     *  spacingLeft (10, 11...), spacingRight (10, 11...), 'textWrap'
     *  (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom),
     *  5 (clear))
     */
    public function addHeader($text = 'Header', $paramsHeader = '')
    {
        $this->headerAdded = true;
        $this->removeHeaders();
        $header = CreateHeader::getInstance();
        $header->createHeader($text, $paramsHeader);
        self::$log->info('Add header to header document.');
        $this->_wordHeaderC .= (string) $header;
        if (!empty($paramsHeader['name'])) {
            self::$log->info('Add image to header document.');
            $xmlHeaderImg = $this->addImageHeader($paramsHeader);
            $this->_wordHeaderC = str_replace(
                '__GENERATEHDRIMG__', $xmlHeaderImg, $this->_wordHeaderC
            );
        }
        $this->generateOVERRIDE(
            '/word/header.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.' .
            'header+xml'
        );
        
    }

    /**
     * Add HTML to the document. Embed HTML.
     *
     * @access public
     * @example ../examples/easy/HTML.php
     * @example ../examples/intermediate/HTML.php
     * @param string $html HTML to add
     */
    public function addHTML($html = '')
    {
        try {
            if ($this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            $wordHTML = EmbedHTML::getInstance();
            $wordHTML->embed();
            self::$log->info('Embed HTML to word document.');
            $this->_wordDocumentC .= (string)$wordHTML;
            $this->_zipDocx->addFromString('word/html' . $wordHTML->getId() .
                                           '.htm', '<html>' . $html . '</html>');
            $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                'rHTMLId' . $wordHTML->getId(), 'aFChunk', 'html' .
                                                           $wordHTML->getId() . '.htm', 'TargetMode="Internal"');
            $this->generateDEFAULT('htm', 'application/xhtml+xml');
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Add an image
     *
     * @access public
     * @example ../examples/easy/Image.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @param array $dats Image to add and paramaters to use
     *  Values: 'border'(1, 2, 3...), 'borderDiscontinuous' (0, 1),
     *  'font' (Arial, Times New Roman...), 'jc' (center, left, right),
     *  'name', 'scaling' (50, 100), 'sizeX' (10, 11, 12...), 'sizeY'
     *  (10, 11, 12...), dpi (dots per inch), spacingTop (10, 11...), spacingBottom (10, 11...),
     *  spacingLeft (10, 11...), spacingRight (10, 11...), 'textWrap'
     *  (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom),
     *  5 (clear))
     */
    public function addImage($dats = '')
    {
        self::$log->debug('Create image.');
        try {
            if (isset($dats['name']) && file_exists($dats['name']) == 'true') {
                $attrImage = getimagesize($dats['name']);
                try {
                    if ($attrImage['mime'] == 'image/jpg' ||
                        $attrImage['mime'] == 'image/jpeg' ||
                        $attrImage['mime'] == 'image/png' ||
                        $attrImage['mime'] == 'image/gif'
                    ) {
                        self::$intIdWord++;
                        self::$log->debug(
                            'New ID ' . self::$intIdWord . ' . Image.'
                        );
                        $image = CreateImage::getInstance();
                        $dats['rId'] = self::$intIdWord;
                        $image->createImage($dats);
                        $this->_wordDocumentC .= (string) $image;
                        $dir = $this->parsePath($dats['name']);
                        self::$log->info(
                            'Add image word/media/image' .
                            self::$intIdWord . '.' . $dir['extension'] .
                            '.xml to DOCX.'
                        );
                        $this->_zipDocx->addFile(
                            $dats['name'], 'word/media/image' .
                            self::$intIdWord . '.' .
                            $dir['extension']
                        );
                        $this->generateDEFAULT(
                            $dir['extension'], $attrImage['mime']
                        );
                        if ((string) $image != '')
                            $this->_wordRelsDocumentRelsC .=
                                $this->generateRELATIONSHIP(
                                    'rId' . self::$intIdWord, 'image',
                                    'media/image' . self::$intIdWord . '.'
                                    . $dir['extension']
                                );
                    } else {
                        throw new Exception('Image format is not supported.');
                    }
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                    exit();
                }
            } else {
                throw new Exception('Image does not exist.');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add a link
     * 
     * @access public
     * @example ../examples/easy/Link.php
     * @example ../examples/advanced/Report.php
     * @param string $text Text to use as link
     * @param string $link URL link
     * @param string $font Type of font
     *  Values: 'Arial', 'Times New Roman'...
     *
     */
    public function addLink($text = '', $textLink = '', $font = '')
    {
        $link = CreateLink::getInstance();
        $link->createLink($text, $textLink, $font);
        self::$log->info('Add link to word document.');
        $this->_wordDocumentC .= (string) $link;
    }

    /**
     * Add a list
     *
     * @access public
     * @example ../examples/easy/List.php
     * @example ../examples/easy/ListBullets.php
     * @example ../examples/intermediate/List.php
     * @example ../examples/intermediate/List_nested.php
     * @example ../examples/intermediate/Multidocument.php
     * @param array $dats Values of the list
     * @param array $paramsList Parameters to use
     *  Values: 'font' (Arial, Times New Roman...),
     *  'val' (0 (clear), 1 (inordinate), 2(numerical)),
     *  array 'bullets' (1 (), 2 (o), 3 ())
     *
     */
    public function addList($dats, $paramsList = '')
    {
        $list = CreateList::getInstance();
        $list->createList($dats, $paramsList);
        self::$log->info('Add list to word document.');
        $this->_wordDocumentC .= (string) $list;
        
        if (!empty($paramsList['bullets']) 
            && is_array($paramsList['bullets']) 
            && $paramsList['val'] == 1
        ) {
            for ($i = 0; $i <= CreateList::MAXDEPTH; $i++) {
                $bullets = $paramsList['bullets'];
                        if (isset($bullets[$i])) {
                            $styleId = $bullets[$i];
                        } else {
                            $styleId = $i;
                        }
                $list->createListStyles($i, $styleId);
                self::$log->info('Add list styles to word document.');
                $this->_wordDocumentStyles .= (string) $list;
            }
        }
    }

    /**
     * Add a macro from a DOC
     *
     * @access public
     * @param string $path Path to a file with macro
     */
    public function addMacroFromDoc($path)
    {
        $package = new ZipArchive();
        self::$log->info('Open document with a macro.');
        $package->open($path);
        self::$log->info('Add macro files to DOCX file.');
        $this->_zipDocx->addFromString(
            'word/vbaData.xml',
            $package->getFromName('word/vbaData.xml')
        );
        $this->_zipDocx->addFromString(
            'word/vbaProject.bin',
            $package->getFromName('word/vbaProject.bin')
        );
        $this->_zipDocx->addFromString(
            'word/_rels/vbaProject.bin.rels',
            $package->getFromName('word/_rels/vbaProject.bin.rels')
        );
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . Macro.');
        if (self::$intIdWord != 1) {
            $this->_wordDocumentC = str_replace(
                'rId1', 'rId' . self::$intIdWord, $this->_wordDocumentC
            );
            $this->_relsRelsC = str_replace(
                'rId1',
                'rId' . self::$intIdWord, $this->_relsRelsC
            );
        }
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId1', 'vbaProject', 'vbaProject.bin'
        );
        $this->generateDEFAULT('bin', 'application/vnd.ms-office.vbaProject');
        $this->generateOVERRIDE(
            '/word/vbaData.xml', 'application/vnd.ms-word.vbaData+xml'
        );
        $package->close();
    }

    /**
     * Convert a math eq to DOCX
     *
     * @access public
     * @example ../examples/easy/Math.php
     * @param string $path Path to a file with math eq
     */
    public function addMathDocx($path)
    {
        $package = new ZipArchive();
        self::$log->info('Open document with an existing math eq.');
        $package->open($path);
        $document = $package->getFromName('word/document.xml');
        $eqs = preg_split('/<[\/]*m:oMathPara>/', $document);
        self::$log->info('Add math eq to word document.');
        $this->addMathEq('<m:oMathPara>' . $eqs[1] . '</m:oMathPara>');
        $package->close();
    }

    /**
     * Add an existing math eq to DOCX
     *
     * @access public
     * @example ../examples/easy/Math.php
     * @param string $eq Math eq
     */
    public function addMathEq($eq)
    {
        self::$log->info('Add existing math eq to word document.');
        $this->_wordDocumentC .= '<' . CreateDocx::NAMESPACEWORD . ':p>' .
            (string) $eq . '</' . CreateDocx::NAMESPACEWORD . ':p>';
    }

    /**
     * Convert a math MML eq to DOCX
     *
     * @access public
     * @param string $eq Math eq
     */
    public function addMathMML($eq)
    {
        $math = CreateMath::getInstance();
        self::$log->debug('Convert MathMML eq.');
        $math->createMath($eq);
        self::$log->info('Add converted MathMML eq to word document.');
        $this->addMathEq('<m:oMathPara>' . (string) $math . '</m:oMathPara>');
    }

    /**
     * Add a MHT file.
     *
     * @access public
     * @example ../examples/easy/MHT.php
     * @param string $pathMHT Path to MHT
     */
    public function addMHT($pathMHT)
    {
        try {
            if (file_exists($pathMHT)) {
                $wordMHT = EmbedMHT::getInstance();
                $wordMHT->embed();
                self::$log->info('Add MHT file to word document.');
                $this->_wordDocumentC .= (string) $wordMHT;
                $this->_zipDocx->addFile($pathMHT, 'word/mht' . $wordMHT->getId() .
                '.mht');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                    'rMHTId' . $wordMHT->getId() , 'aFChunk', 'mht' .
                    $wordMHT->getId() . '.mht', 'TargetMode="Internal"');
                $this->generateDEFAULT('mht', 'message/rfc822');
            } else {
                throw new Exception('File does not exist.');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Add an existing object (XLS or PPTX)
     *
     * @access public
     * @example ../examples/easy/Object.php
     * @param string $path Object path
     * @param string $type Object type
     *   Values: xlsx, pptx
     */
    public function addObject($path, $type)
    {
        try {
            if (file_exists($path) && ($type == 'xls' || $type == 'pptx')) {
                self::$intIdWord++;
                self::$log->debug('New ID ' . self::$intIdWord . ' . Object.');
                if ($type == 'xls') {
                    $this->generateDEFAULT(
                        'xls', 'application/vnd.ms-excel'
                    );
                    $this->_wordRelsDocumentRelsC .=
                        $this->generateRELATIONSHIP('rId' . self::$intIdWord,
                            'oleObject',
                            'embeddings/Microsoft_Office_Excel_97-2003_'
                            + 'Worksheet' + self::$intIdWord + '.xls'
                        );
                } elseif ($type == 'pptx') {
                    $this->generateDEFAULT(
                        'pptx', 'application/vnd.openxmlformats-officedocument'
                        + '.presentationml.presentation'
                    );
                    $this->_wordRelsDocumentRelsC .=
                        $this->generateRELATIONSHIP('rId' . self::$intIdWord,
                            'package',
                            'embeddings/Microsoft_Office_PowerPoint_'
                            + 'PresentationWorksheet' + self::$intIdWord
                            + '.pptx'
                    );
                }
                $this->generateDEFAULT(
                        'emf', 'image/x-emf'
                );
                $object = CreateObject::getInstance();
                $object->createObject($path, $type);
                self::$log->info('Add object to word document.');
                $this->_wordDocumentC .= (string) $object;
            } else {
                throw new Exception('File does not exist or format is not
                    supported.');
            }
        }
        catch (Exception $e) {
            self::$log->warn($e->getMessage());
            throw new Exception('File does not exist or format is not supported.');
        }
    }
    
    /**
     * Add properties to document
     * 
     * @access public
     * @param array $values
     * @example ../examples/easy/Properties.php
     * @param array $values Parameters to use
     *  Values: 'title', 'subject', 'creator', 'keywords', 'description', 
     *  'category', 'contentStatus', 'Manager','Company', 'custom' ('name' => array('type' => 'value'))
     */
    
    public function addProperties($values)
    {
        $prop = CreateProperties::getInstance();
        if (!empty($values['title']) 
                || !empty($values['subject'])
                || !empty($values['creator'])
                || !empty($values['keywords'])
                || !empty($values['description'])
                || !empty($values['category'])
                || !empty($values['contentStatus'])) {
            $prop->createProperties($values, $this->_baseTemplateFilesPath);
        }
        if($values['contentStatus'] == 'Final'){
            $prop->createPropertiesCustom(array('_MarkAsFinal' => array('boolean' => 'true')), $this->_baseTemplateFilesPath);
            //We now write the new Override node associated to the new custon.xml file en [Content_Types].xml
            
            $this->generateOVERRIDE(
            '/docProps/custom.xml',
            'application/vnd.openxmlformats-officedocument.' .
            'custom-properties+xml'
            );   
        }
        if (!empty($values['Manager']) || !empty($values['Company'])) {
            $prop->createPropertiesApp($values, $this->_baseTemplateFilesPath);
        }
        if (!empty($values['custom']) && is_array($values['custom'])) {
            $prop->createPropertiesCustom($values['custom'], $this->_baseTemplateFilesPath);
                
            //We now write the new Override node associated to the new custon.xml file en [Content_Types].xml
            $this->generateOVERRIDE(
            '/docProps/custom.xml',
            'application/vnd.openxmlformats-officedocument.' .
            'custom-properties+xml'
            );
                
        }
        self::$log->info('Adding properties to word document.');
    }
    
    /**
     * Add a raw WordML
     *
     * @access public
     * @param string $wml WordML to add
     * @deprecated See addWordML
     */
    public function addRawWordML($wml)
    {
        self::$log->info('Add raw WordML');
        $this->_wordDocumentC .= $wml;
    }
    
    /**
     * Modify page layout
     *
     * @access public
     * @example ../examples/intermediate/modifyPageLayout.php
     * @param array paperType (A4, A3, letter, legal, A4-landscape, A3-landscape, letter-landscape, legal-landscape, custom)
     * @param array options (width, height, numberCols, orient, marginTop, marginRight, marginBottom , marginLeft, marginHeader, marginFooter, gutter).
     */
    public function modifyPageLayout($paperType = 'letter', $options = array())
    {   
        $paperTypes = array('A4', 
                            'A3', 
                            'letter', 
                            'legal', 
                            'A4-landscape', 
                            'A3-landscape', 
                            'letter-landscape', 
                            'legal-landscape',
                            'custom');
        
        $layoutOptions = array( 'width',
                                'height',
                                'numberCols',
                                'orient',
                                'code',
                                'marginTop',
                                'marginRight',
                                'marginBottom',
                                'marginLeft',
                                'marginHeader',
                                'marginFooter',
                                'gutter');
        $referenceSizes = array(
                'A4' => array(
                            'width' => '11906',
                            'height' => '16838',
                            'numberCols' => '1',
                            'orient' => 'portrait',
                            'code' => '9',
                            'marginTop' => '1417',
                            'marginRight' => '1701',
                            'marginBottom' => '1417',
                            'marginLeft' => '1701',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
                'A4-landscape' => array(
                            'width' => '16838',
                            'height' => '11906',
                            'numberCols' => '1',
                            'orient' => 'landscape',
                            'code' => '9',
                            'marginTop' => '1701',
                            'marginRight' => '1417',
                            'marginBottom' => '1701',
                            'marginLeft' => '1417',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
                'A3' => array(
                            'width' => '16839',
                            'height' => '23814',
                            'numberCols' => '1',
                            'orient' => 'portrait',
                            'code' => '8',
                            'marginTop' => '1417',
                            'marginRight' => '1701',
                            'marginBottom' => '1417',
                            'marginLeft' => '1701',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
                'A3-landscape' => array(
                            'width' => '23814',
                            'height' => '16839',
                            'numberCols' => '1',
                            'orient' => 'landscape',
                            'code' => '8',
                            'marginTop' => '1701',
                            'marginRight' => '1417',
                            'marginBottom' => '1701',
                            'marginLeft' => '1417',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
                'letter' => array(
                            'width' => '12240',
                            'height' => '15840',
                            'numberCols' => '1',
                            'orient' => 'portrait',
                            'code' => '1',
                            'marginTop' => '1417',
                            'marginRight' => '1701',
                            'marginBottom' => '1417',
                            'marginLeft' => '1701',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'        
                        ),
                'letter-landscape' => array(
                            'width' => '15840',
                            'height' => '12240',
                            'numberCols' => '1',
                            'orient' => 'landscape',
                            'code' => '1',
                            'marginTop' => '1701',
                            'marginRight' => '1417',
                            'marginBottom' => '1701',
                            'marginLeft' => '1417',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
                'legal' => array(
                            'width' => '12240',
                            'height' => '20160',
                            'numberCols' => '1',
                            'orient' => 'portrait',
                            'code' => '5',
                            'marginTop' => '1417',
                            'marginRight' => '1701',
                            'marginBottom' => '1417',
                            'marginLeft' => '1701',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'        
                        ),
                'legal-landscape' => array(
                            'width' => '20160',
                            'height' => '12240',
                            'numberCols' => '1',
                            'orient' => 'landscape',
                            'code' => '5',
                            'marginTop' => '1701',
                            'marginRight' => '1417',
                            'marginBottom' => '1701',
                            'marginLeft' => '1417',
                            'marginHeader' => '708',
                            'marginFooter' => '708',
                            'gutter' => '0'       
                        ),
            );

            try{
            if(!in_array($paperType, $paperTypes)){
              throw new Exception('You have used an invalid paper size');  
             }
            }
            catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception('You have used an invalid paper size');
            }
            
            
            $layout = array();
            foreach($layoutOptions as $opt){
                if (isset($options[$opt])){
                   $layout[$opt] = $options[$opt];
                }else if (isset($referenceSizes[$paperType][$opt])){
                   $layout[$opt] = $referenceSizes[$paperType][$opt]; 
                }
            }
            
            
            if (isset($layout['width'])){
                $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:w', $layout['width']);
            }
            if (isset($layout['height'])){
                $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:h', $layout['height']);
            }
            if (isset($layout['orient'])){
                $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:orient', $layout['orient']);
            }
            if (isset($layout['code'])){
                $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:code', $layout['code']);
            }
            if (isset($layout['marginTop'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:top', $layout['marginTop']);
            }
            if (isset($layout['marginRight'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:right', $layout['marginRight']);
            }
            if (isset($layout['marginBottom'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:bottom', $layout['marginBottom']);
            }
            if (isset($layout['marginLeft'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:left', $layout['marginLeft']);
            }
            if (isset($layout['marginHeader'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:header', $layout['marginHeader']);
            }
            if (isset($layout[$paperType]['marginFooter'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:footer', $layout['marginFooter']);
            }
            if (isset($layout['gutter'])){
                $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:gutter', $layout['gutter']);
            }    
            //Now we look at the case of numberCols 
            if (isset($layout['numberCols'])){               
                if ($this->_sectPr->getElementsByTagName('cols')->length > 0){
                   $this->_sectPr->getElementsByTagName('cols')->item(0)->setAttribute('w:num', $layout['numberCols']); 
                }else{
                   $colsNode = $this->_sectPr->createDocumentFragment();
                   $colsNode->appendXML('<w:cols w:num="'.$layout['numberCols'].'" />');                  
                   $this->_sectPr->documentElement->appendChild($colsNode);
                }
            }
        
    }
    
    
    /**
     * Add a RTF file. Keep content and styles.
     *
     * @access public
     * @example ../examples/easy/RTF.php
     * @param string $pathRTF Path to RTF
     */
    public function addRTF($pathRTF)
    {
        try {
            if ($this->_compatibilityMode) {
              throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            if (file_exists($pathRTF)) {
                $wordRTF = EmbedRTF::getInstance();
                $wordRTF->embed();
                self::$log->info('Add RTF file to word document.');
                $this->_wordDocumentC .= (string) $wordRTF;
                $this->_zipDocx->addFile($pathRTF, 'word/rtf' . $wordRTF->getId() .
                '.rtf');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
                    'rRTFId' . $wordRTF->getId() , 'aFChunk', 'rtf' .
                    $wordRTF->getId() . '.rtf', 'TargetMode="Internal"');
                $this->generateDEFAULT('rtf', 'application/rtf');
            } else {
                throw new Exception('File does not exist.');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Add a section
     *
     * @access public
     * @example ../examples/easy/Section.php
     * @param array $dats Parameters to use
     *  Values: 'bottom' (4000, 4001...), 'columns' (1, 2, 3),
     *  'left' (4000, 4001...), 'orient' (landscape), 'right' (4000, 4001),
     *  'titlePage' (1), 'top' (4000, 4001)
     */
    public function addSection($dats = '')
    {
        $page = CreatePage::getInstance();
        $page->createSection($dats);
        self::$log->info('Add section to word document.');
        $this->_wordDocumentC .= (string) $page;
    }
    
    /**
     * Add a shape
     *
     * @access public
     * @example ../examples/easy/Shape.php
     * @param string $type Type of shape to draw
     *  Values: line
     * @param array $style Style of text
     *  Values: 'width' (300, 400...), 'height' (300, 400...)
     */
    public function addShape($type, $style = '')
    {
        $shape = CreateShape::getInstance();
        $shape->createShape($type, $style);
        self::$log->info('Add a shape to word document.');
        $this->_wordDocumentC .= (string) $shape;
    }
    
    /**
     * Add a table.
     *
     * @access public
     * @example ../examples/easy/Table.php
     * @example ../examples/intermediate/Table.php
     * @example ../examples/intermediate/TableStyled.php
     * @example ../examples/advanced/Report.php
     * @param array $dats Values to add
     * @param array $parameters Parameters to use
     *  Values:
     *  'border' (none, single, double);
     *  'border_color' (ffffff, ff0000);
     *  'border_spacing' (0, 1, 2...);
     *  'border_sz' (10, 11...);
     *  'font' (Arial, Times New Roman...);
     *  'jc' (center, left, right);
     *  'size_col': column width fix (1200, 1300...),
     *              column width variable (array);
     *  'TBLSTYLEval' (Cuadrculamedia3-nfasis1,
     *                Sombreadomedio1 Tablaconcuadrcula, TableGrid);
     */
    public function addTable($dats, $parameters = '')
    {
        $table = CreateTable::getInstance();
        $table->createTable($dats, $parameters);
        self::$log->info('Add table to word document.');
        $this->_wordDocumentC .= (string) $table;
    }

    /**
     * Add a table of contents (TOC)
     *
     * @access public
     * @example ../examples/easy/TableContents.php
     * @param string $font Set font type
     *  Values: 'Arial', 'Times New Roman'...
     */
    public function addTableContents($font = '')
    {
        $tableContents = CreateTableContents::getInstance();
        $tableContents->createTableContents($font);
        self::$log->info('Add table of contents to word document.');
        $this->_wordDocumentC .= (string) $tableContents;
    }

    /**
     * Add a DOCX file as a template
     *
     * @access public
     * @example ../examples/easy/Template.php
     * @param string $path Path to the file
     */
    public function addTemplate($path)
    {
        $template = CreateTemplate::getInstance();
        try {
            if (file_exists($path)) {
                self::$log->info('Open template ' . $path . '.');
                $template->openTemplate($path);
            } else {
                throw new Exception('File ' . $path . ' not exists');
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Replace a variable with a chart.
     *
     * @access public
     * @example ../examples/easy/Template_chart.php
     * @param string $var Value of the variable
     * @param string $chart Chart to add
     *  Values: same as charts.
     */
    public function addTemplateChart($var, $chart)
    {
        $template = CreateTemplate::getInstance();
        self::$log->info('Assign as chart variable ' . $var . ' in template.');
        $this->addGraphicTemplate($chart);
        self::$log->info('Replace chart variable ' . $var . ' in template.');
        $template->replaceChart($var);
    }

    /**
     * Replace a variable with a checkbox.
     *
     * @access public
     * @param string $var Value of the variable. String
     * @param string $value
     */
    public function addTemplateCheckBox($var, $value = false)
    {
        $template = CreateTemplate::getInstance();
        self::$log->info(
            'Replace text variable ' . $var . ' with a checkbox.'
        );
        $template->replaceCheckBox($var, $value);
    }

    /**
     * Replace a variable image template with a new image.
     *
     * @access public
     * @example ../examples/easy/Template_image.php
     * @param string $var Value of the variable
     * @param string $pathImage Path of the new image
     */
    public function addTemplateImage($var, $pathImage)
    {
        $template = CreateTemplate::getInstance();
        self::$log->info(
            'Assign and replace image variable ' . $var . ' in template.'
        );
        $template->replaceImage($var, $pathImage);
    }

    /**
     * Replace a variable with a text, HTML, DOCX, RTF, MHT.
     *
     * @access public
     * @example ../examples/easy/Template.php
     * @example ../examples/easy/TemplateDOCX.php
     * @example ../examples/easy/TemplateEmbedHTML.php
     * @example ../examples/easy/TemplateHTML.php
     * @example ../examples/easy/TemplateImage.php
     * @example ../examples/easy/TemplateMHT.php
     * @example ../examples/easy/TemplateRTF.php
     * @example ../examples/intermediate/Template.php
     * @param mixed $var Value of the variable. String, html or array of values
     * @param string $value Text value, type of value, CSS or text array
     *  Values type of template: list, table,
     * @param mixed $settings Template settings or content type
     *  Content types: html, rtf, docx, mht, embedHtml
     */
    public function addTemplateVariable($var, $value = '', $settings = '')
    {
        $template = CreateTemplate::getInstance();
        self::$log->info(
            'Assign and replace text variable ' . $var . ' in template.'
        );
        $template->replaceVariable($var, $value, $settings);
    }

    /**
     * Add a text
     *
     * @access public
     * @example ../examples/easy/Text.php
     * @example ../examples/easy/Text_linespacing.php
     * @example ../examples/easy/TextCursive.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/FooterPager.php
     * @example ../examples/intermediate/Multidocument.php
     * @example ../examples/intermediate/Text.php
     * @example ../examples/advanced/Report.php
     * @param mixed $value Text string or array of texts to add
     *  Values array: 'b' (single), 'cell_color' (ffffff, ff0000...),
     *  'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...), 'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off), 'sz' (1, 2, 3...), 'text'
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off), 'wordWrap' (on, off)
     * @param array $style Style of text
     *  Values: 'b' (single), 'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...), 'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off), 'sz' (1, 2, 3...),
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off), 'wordWrap' (on, off),
     *  'lineSpacing' 120, 240 (standard), 480...,
     *  'indent_left' 100..., 'indent_right' 100...
     */
    public function addText($value, $style = '')
    {
        $text = CreateText::getInstance();
        $text->createText($value, $style);
        self::$log->info('Add text to word document.');
        $this->_wordDocumentC .= (string) $text;
    }

    /**
     * Add a textbox
     *
     * @access public
     * @example ../examples/easy/TextBox.php
     * @param mixed $text Text string to add using params or not
     * @param array $style Style of text
     *  Values: 'align' (center, left, right), 'fillcolor' (black, red...),
     *  'font' (Arial, Times New Roman...), 'height' (300, 400...),
     *  'jc' (square, through), 'margin_bottom' (100, 110...),
     *  'margin_left' (100, 110...), 'margin_right' (100, 110...),
     *  'margin_top' (100, 110...)
     */
    public function addTextBox($text, $style = '')
    {
        $textBox = CreateTextBox::getInstance();
        $textBox->createTextBox($text, $style);
        self::$log->info('Add textbox to word document.');
        $this->_wordDocumentC .= (string) $textBox;
    }

    /**
     * Add a title
     *
     * @access public
     * @example ../examples/easy/Title.php
     * @example ../examples/intermediate/Multidocument.php
     * @example ../examples/advanced/Report.php
     * @param string $text Text to add
     * @param array $style Style of title
     *  Values: 'b' (single), 'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...), 'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off), 'sz' (1, 2, 3...),
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off), 'wordWrap' (on, off)
     */
    public function addTitle($text, $style = '')
    {
        $title = CreateText::getInstance();
        $title->createTitle($text, $style);
        self::$log->info('Add title to word document.');
        $this->_wordDocumentC .= (string) $title;
    }

    /**
     * Add WordML to the document. Embed WordML.
     *
     * @access public
     * @example ../examples/easy/WordML.php
     * @param string $wordML WordML to add
     */
    public function addWordML($wordML = '')
    {
        $this->_wordDocumentC .= $wordML;
    }

    /**
     * Clear all blocks
     *
     * @access public
     */
    public function clearBlocks()
    {
        self::$log->info('Clear all blocks.');
        CreateTemplate::deleteAllBlocks();
    }

    /**
     * Generate a new DOCX file
     *
     * @access public
     * @example ../examples/easy/Text.php
     * @example ../examples/advanced/Report.php
     * @param string $args[0] File name
     * @param string $args[1] Page style
     *  Values: 'bottom' (4000, 4001...), 'columns' (1, 2, 3), 'left' (4000, 4001...),
     *  'orient' (landscape), 'right' (4000, 4001), 'titlePage' (1),
     *  'top' (4000, 4001)
     */
    public function createDocx()
    {
        $args = func_get_args();
        if (!empty($args[0])) {
            $fileName = $args[0];
        } else {
            $fileName = 'document';
        }
        self::$log->info('Set DOCX name to: ' . $fileName . '.');
        if (!CreateTemplate::getBlnTemplate()) {
            self::$log->debug('DOCX is a new file, not a template.');
            try {
               GenerateDocx::beginDocx();
            }
            catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
            }
            
            //TODO: This is a temporary solution not to break the old addHeader method
           if (!empty($this->_wordHeaderC) && $this->headerAdded) {
                self::$log->debug('There is a new header, add it.');
                $headerId = $this->generateTemplateWordHeader();
                self::$log->info('Add word/header.xml content to DOCX file.');
                $headerHandler = fopen($this->_baseTemplateFilesPath.'/word/header.xml', "w+");

                if (self::$_encodeUTF) {
                    fwrite($headerHandler, utf8_encode($this->_wordHeaderT));
                } else {
                    fwrite($headerHandler, $this->_wordHeaderT);
                }

                fclose($headerHandler);
                
                $headerNode = $this->_sectPr->createDocumentFragment();
                $headerNode->appendXML('<w:headerReference w:type="default" r:id="'.$headerId.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"  xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" />');                  
                $this->_sectPr->documentElement->appendChild($headerNode);
                
                if (!empty($this->_wordRelsHeaderRelsC)) {
                    self::$log->debug('There is a header rels, add it.');
                    $this->generateTemplateWordRelsHeaderRels();
                    self::$log->info(
                        'Add word/_rels/header.xml.rels content to DOCX file.'
                    );
                    $headerRelsHandler = fopen($this->_baseTemplateFilesPath.'/word/_rels/header.xml.rels', "w+");
                    fwrite($headerRelsHandler, $this->_wordRelsHeaderRelsT);
                    fclose($headerRelsHandler);

                }
           }
           
           //TODO: This is a temporary solution not to break the old addFooter method
           if (!empty($this->_wordFooterC) && $this->footerAdded) {
                self::$log->debug('There is a new footer, add it.');
                $footerId = $this->generateTemplateWordFooter();
                self::$log->info('Add word/footer.xml content to DOCX file.');
                $footerHandler = fopen($this->_baseTemplateFilesPath.'/word/footer.xml', "w+");

                if (self::$_encodeUTF) {
                    fwrite($footerHandler, utf8_encode($this->_wordFooterT));
                } else {
                    fwrite($footerHandler, $this->_wordFooterT);
                }

                fclose($footerHandler);
                
                $footerNode = $this->_sectPr->createDocumentFragment();
                $footerNode->appendXML('<w:footerReference w:type="default" r:id="'.$footerId.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"  xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" />');                  
                $this->_sectPr->documentElement->appendChild($footerNode);
                
                if (!empty($this->_wordRelsFooterRelsC)) {
                    self::$log->debug('There is a footer rels, add it.');
                    $this->generateTemplateWordRelsFooterRels();
                    self::$log->info(
                        'Add word/_rels/footer.xml.rels content to DOCX file.'
                    );
                    $footerRelsHandler = fopen($this->_baseTemplateFilesPath.'/word/_rels/footer.xml.rels', "w+");
                    fwrite($footerRelsHandler, $this->_wordRelsFooterRelsT);
                    fclose($footerRelsHandler);

                }
           }
            
            //We copy the rels content into the respective file
            $relsHandler = fopen($this->_baseTemplateFilesPath.'/word/_rels/document.xml.rels', "w+");
            fwrite($relsHandler, $this->_wordRelsDocumentRelsT->saveXML());
            fclose($relsHandler);
            //We also copy the contents of the [Content_types].xml file
            $contentTypesHandler = fopen($this->_baseTemplateFilesPath.'/[Content_Types].xml', "w+");
            fwrite($contentTypesHandler, $this->_contentTypeT->saveXML());
            fclose($contentTypesHandler);
 
            $arrArgsPage = array();
            if (isset($args[1])) {
                self::$log->debug('Modify page layout.');
                $this->modifyPageLayout('custom', $args[1]);
            }
            
            $this->generateTemplateWordDocument($arrArgsPage);
            if ($this->_debug->getActive() == 1) {
                self::$log->debug('Debug is active, add messages to objDebug.');
                libxml_use_internal_errors(true);
                simplexml_load_string(
                    $this->_wordDocumentT, 'SimpleXMLElement', LIBXML_NOWARNING
                );
                $xmlErrors = libxml_get_errors();
                if (is_array($xmlErrors)) {
                    $this->_debug->addMessage($xmlErrors);
                    libxml_clear_errors();
                }
            }

            self::$log->info('Add word/document.xml content to DOCX file.');
            
            $documentHandler = fopen($this->_baseTemplateFilesPath.'/word/document.xml', "w+");
            if (self::$_encodeUTF) { 
                //TODO: sot out encoding problems
                fwrite($documentHandler, utf8_encode($this->_wordDocumentT));
            } else {
                fwrite($documentHandler, $this->_wordDocumentT);
            }
            fclose($documentHandler);
            
            if($this->_wordFootnotesC != ''){
                self::$log->info('Add word/footnote.xml content to DOCX file.');
                $footnoteHandler = fopen($this->_baseTemplateFilesPath.'/word/footnote.xml', "w+");
                    if (self::$_encodeUTF) { 
                    //TODO: sot out encoding problems
                        fwrite($footnoteHandler, utf8_encode($this->_wordFootnotesT));
                    } else {
                        fwrite($footnoteHandler, $this->_wordFootnotesT);
                    }
                fclose($documentHandler);
                }
            
            

            self::$log->info('Close the zip.');
            
            $this->recursiveInsert($this->_zipDocx, $this->_baseTemplateFilesPath, $this->_baseTemplateFilesPath);
            
            $this->_zipDocx->close();

            $arrpathFile = pathinfo($fileName);
            self::$log->info('Copy DOCX file using a new name.');
            copy(
                $this->_tempFile,
                $fileName . '.' . $this->_extension
            );

            if ($this->_debug->getActive() == 1) {
                self::$log->debug('Debug is active, show messages.');
                echo $this->_debug;
            }
        } else {
            self::$log->debug('DOCX is a template.');
            self::$log->info(
                'Create a temp copy of the file, to generate a new DOCX.'
            );
            $finalFileName = $fileName . '.' . $this->_extension;
            $fileName = tempnam($this->_tempDir, $fileName);
            copy(CreateTemplate::$path, $fileName);
            $zipDocxTemplate = new ZipArchive();
            try {
                if ($zipDocxTemplate->open($fileName)) {
                    if (count(CreateTemplate::getFooter()) > 0) {
                        $footers = CreateTemplate::getFooter();
                        $i = 1;
                        foreach ($footers as $footer) {
                            self::$log->info('Footer exists, replace word/footer' . $i . '.xml with a new footer.');
                            $zipDocxTemplate->addFromString('word/footer' . $i . '.xml', $footer);
                            $i++;
                        }
                    }
                    if (count(CreateTemplate::getHeader()) > 0) {
                        $headers = CreateTemplate::getHeader();
                        $i = 1;
                        foreach ($headers as $header) {
                            self::$log->info('Footer exists, replace word/header' . $i . '.xml with a new header.');
                            $zipDocxTemplate->addFromString('word/header' . $i . '.xml', $header);
                            $i++;
                        }
                    }
                    if (CreateTemplate::getRelsDocumentXMLRels() != '') {
                        self::$log->info(
                            'Document.xml.rels exists, replace word/_rels/' .
                            'document.xml.rels with a new document.'
                        );
                        $zipDocxTemplate->addFromString(
                            'word/_rels/document.xml.rels',
                            CreateTemplate::getRelsDocumentXMLRels()
                        );
                    }
                    if (count(CreateTemplate::$placeholderImages) > 0) {
                        self::$log->info(
                            'There is one or more images as placeholders, . ' .
                            'replace them.'
                        );
                        CreateTemplate::replaceImages($zipDocxTemplate);
                    }
                    if (CreateTemplate::$totalTemplateCharts > 0) {
                        self::$log->info(
                            'There is one or more charts as placeholders, . ' .
                            'replace them.'
                        );
                        for ($i = CreateTemplate::$ridInitTemplateCharts + 1;
                            $i <= self::$intIdWord; $i++
                        ) {
                            $zipDocxTemplate->addFromString(
                                'word/charts/chart' . $i . '.xml',
                                $this->graphicTemplate['arrChartXML'][$i]
                            );
                            CreateTemplate::replaceVariableChart(
                                $this->graphicTemplate['arrGraphics'][$i],
                                $i
                            );
                            $zipDocxTemplate->addFile(
                                $this->_tempFileXLSX[$i],
                                $this->fileGraphicTemplate['datos' . $i . '.xlsx']
                            );
                            $zipDocxTemplate->addFromString(
                                'word/charts/_rels/chart' . $i . '.xml.rels',
                                $this->fileGraphicTemplate['word/charts/_rels/chart' . $i . '.xml.rels']
                            );

                            CreateTemplate::addContentTypes(
                                $this->graphicTemplate['arrGenerateOVERRIDE'][$i]
                            );
                            CreateTemplate::addContentTypes(
                                $this->graphicTemplate['arrGenerateDEFAULT'][$i]
                            );
                            CreateTemplate::addRelationship(
                                $this->graphicTemplate['arrRelationships'][$i]
                            );
                        }

                    }
                    self::$log->info(
                        'Replace [Content_Types].xml with a new document.'
                    );
                    $zipDocxTemplate->addFromString(
                        '[Content_Types].xml',
                        CreateTemplate::getContentTypes()
                    );
                    self::$log->info(
                        'Replace word/_rels/document.xml.rels with a new ' .
                        'document.'
                    );
                    $zipDocxTemplate->addFromString(
                        'word/_rels/document.xml.rels',
                        CreateTemplate::getRelsDocumentXMLRels()
                    );
                    self::$log->info(
                        'Replace word/document.xml with a new document.'
                    );
                    if (self::$_encodeUTF) {
                        $zipDocxTemplate->addFromString(
                            'word/document.xml',
                            utf8_encode(CreateTemplate::getDocument())
                        );
                    } else {
                        $zipDocxTemplate->addFromString(
                            'word/document.xml', CreateTemplate::getDocument()
                        );
                    }
                    self::$log->info(
                        'Add embedded files.'
                    );
                    foreach (CreateTemplate::$embedFiles as $files) {
                        if (isset($files['src_string'])) {
                            $zipDocxTemplate->addFromString(
                                'word/' . $files['dest_file'], $files['src_string']
                            );
                        } elseif (isset($files['src_file'])) {
                            $zipDocxTemplate->addFile($files['src_file'], 'word/' . $files['dest_file']);
                        }
                    }
                    self::$log->info('End of file, close it.');
                    $zipDocxTemplate->close();
                    copy($fileName, $finalFileName);
                } else {
                    throw new Exception('Unable to create DOCX file.');
                }
                CreateTemplate::reset();
            }
            catch (Exception $e) {
                self::$log->fatal($e->getMessage());
                exit();
            }
        }
    }

    /**
     * Generate and download a new DOCX file
     *
     * @access public
     * @param string $args[0] Name of file
     * @param string $args[1] Page style
     *  Values: 'bottom' (4000, 4001...), 'left' (4000, 4001...),
     *  'orient' (landscape), 'right' (4000, 4001), 'titlePage' (1),
     *  'top' (4000, 4001)
     */
    public function createDocxAndDownload()
    {
        $args = func_get_args();

        try {
            $this->createDocx($args[0], $args[1]);
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }

        if (!empty($args[0])) {
            $fileName = $args[0];
            $completeName = explode("/",$args[0]);           
            $fileNameDownload = array_pop($completeName);
        } else {
            $fileName = 'document';
            $fileNameDownload = 'document';
        }

        self::$log->info(
            'Download file ' . $fileNameDownload . '.' . $this->_extension . '.'
        );
        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.' .
            'wordprocessingml.document'
        );
        header(
            'Content-Disposition: attachment; filename="' . $fileNameDownload .
            '.' . $this->_extension . '"'
        );
        header('Content-Transfer-Encoding: binary');
        readfile($fileName . '.' . $this->_extension);
    }

    /**
     * Delete a block
     *
     * @access public
     * @param string $blockName Block to remove
     */
    public function deleteTemplateBlock($blockName)
    {
        self::$log->info('Delete block ' . $blockName . '.');
        CreateTemplate::deleteBlock($blockName);
    }

    /**
     * Disable debug mode
     *
     * @access public
     * @deprecated
     */
    public function disableDebug()
    {
        self::$log->info('Disable debug.');
        $this->_debug->disableDebug();
    }

    /**
     * Enable compatibility mode for OpenOffice and MS Office 2003 CP. Avoid using unsupported methods
     *
     * @access public
     */
    public function enableCompatibilityMode()
    {
        self::$log->info('Enable compatibility mode.');
        $this->_compatibilityMode = true;
    }

    /**
     * Enable debug mode
     *
     * @access public
     * @deprecated
     */
    public function enableDebug()
    {
        self::$log->info('Enable debug.');
        $this->_debug->enableDebug();
    }

  /**
     * Return template variables (placeholders)
     *
     * @example ../examples/easy/TemplateVariables.php
     *
     * @access public
     * @return array
     */
    public function getTemplateVariables()
    {
        $template = CreateTemplate::getInstance();
        self::$log->info(
            'Return template variables.'
        );
        return $template->returnVariables();
    }

    /**
     * Return the XML of the given Template
     *
     * @access public
     * @return array
     */
    public function getTemplateXML()
    {
        $templateXML = CreateXML::getInstance();
        self::$log->info(
            'Return the XML of the given Template.'
        );
        self::getTemplateVariables();

        $templateXML->XML();

        return $templateXML->getXML();
    }

    /**
     * Merge WordML into a DOCX. WordML is added at the end of the document
     *
     * @access public
     * @param string $path Path to DOCX file
     * @param string $newXML WordML to add
     */
    public function mergeDOCX($path, $newXML)
    {
        $template = CreateTemplate::getInstance();
        self::$log->info('Merge DOCX file.');
        $template->mergeFiles($path, $newXML);
    }

    /**
     * Parse DOCX file and extract content
     *
     * @access public
     * @param string $path Path to DOCX file
     */
    public function readDOCX($path)
    {
        $parser = Parser::getInstance();
        try {
            $parser->readFile($path);
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            throw new Exception('File does not exist');
        }
        self::$log->info('Read DOCX file.');
    }

    /**
     * Change the default font
     *
     * @access public
     * @param string $font The new font
     *  Values: 'Arial', 'Times New Roman'...
     */
    public function setDefaultFont($font)
    {
        $this->_defaultFont = $font;
        //Let's get the original theme as a DOMdocument             
        try{
            $themeHandler = fopen($this->_baseTemplateFilesPath.'/word/theme/theme1.xml', 'r');
            $themeXML = fread($themeHandler, filesize($this->_baseTemplateFilesPath.'/word/theme/theme1.xml'));
            fclose($themeHandler);
            if ($themeXML == '') {
                throw new Exception('Error while extracting the theme file from the base template to stablish default font');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $themeDocument = new DomDocument();
        $themeDocument->loadXML($themeXML);
        $latinNode = $themeDocument->getElementsByTagName('latin');
        $latinNode->item(0)->setAttribute('typeface', $font);
        $latinNode->item(1)->setAttribute('typeface', $font);
        
        $themeXML = $themeDocument->saveXML();
        try{
            $themeFile=fopen($this->_baseTemplateFilesPath.'/word/theme/theme1.xml', 'w');
            if ($themeFile == false) {
                throw new Exception('Error while opening the base template theme1.xml file');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        try{
            $writeTheme = fwrite($themeFile, $themeXML);
            if ($writeTheme == 0) {
                throw new Exception('There was an error while trying to set the default font');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        self::$log->info('Change default font.');
        
    }

    /**
     * Transform to UTF-8 charset
     *
     * @access public
     */
    public function setEncodeUTF8()
    {
        self::$_encodeUTF = 1;
    }

    /**
     * Change default language.
     * @param $lang Locale: en-US, es-ES...
     * @access public
     */
    public function setLanguage($lang = DEFAULT_LANGUAGE)
    {
        //Let's get the original styles as a DOMdocument             
        try{
            $styleHandler = fopen($this->_baseTemplateFilesPath.'/word/styles.xml', 'r');
            $styleXML = fread($styleHandler, 10000000);
            fclose($styleHandler);
            $this->_wordStylesT = $styleXML;
            if ($styleXML == '') {
                throw new Exception('Error while extracting the style file from the base template to stablish default language');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $stylesDocument = new DomDocument();
        $stylesDocument->loadXML($this->_wordStylesT);
        $langNode = $stylesDocument->getElementsByTagName('lang');
        $langNode->item(0)->setAttribute('w:val', $lang);
        $langNode->item(0)->setAttribute('w:eastAsia', $lang);
        
        $this->_wordStylesT = $stylesDocument->saveXML();
        try{
            $stylesFile=fopen($this->_baseTemplateFilesPath.'/word/styles.xml', 'w');
            if ($stylesFile == false) {
                throw new Exception('Error while opening the base template styles.xml file');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        try{
            $writeStyles = fwrite($stylesFile,$this->_wordStylesT );
            if ($writeStyles == 0) {
                throw new Exception('There was an error while trying to set the default language');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        self::$log->info('The language has been set.');
    }

    /**
     * Mark the document as final
     *
     * @access public
     */
    public function setMarkAsFinal()
    {
        $this->_markAsFinal = 1;
        $this->addProperties(array('contentStatus' => 'Final'));
        $this->generateOVERRIDE(
            '/docProps/custom.xml',
            'application/vnd.openxmlformats-officedocument.' .
            'custom-properties+xml'
        );
    }

    /**
     * Set the symbol surrounding varibles (placeholders)
     *
     * @access public
     * @param string $symbol The new symbol. Optional, $ as default
     */
    public function setTemplateSymbol($symbol = '$')
    {
        self::$log->info('Change template symbol.');
        CreateTemplate::setTemplateSymbol($symbol);
    }

    /**
     * Transform DOCX to PDF, ODT, SXW, RTF, DOC, TXT, HTML or WIKI
     *
     * @access public
     * @param $docSource
     * @param $docDestination
     * @return void
     */
    public function transformDocx($docSource, $docDestination) {
        try {
            if (!$this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            $convert = new TransformDocAdv();
            $convert->transformDocument($docSource, $docDestination);
        } catch (Exception $e) {
            self::$log->fatal($e->getMessage());
        }
    }

    /*** End old API ***/

    /**
     * Add default endnote
     *
     * @access private
     */
    private function addDefaultEndnote()
    {
        $endnote = CreateEndnote::getInstance();
        $endnote->createInitEndnote(array('type' => 'separator'));
        $this->_wordEndnotesC .= (string) $endnote;
        $endnote = CreateEndnote::getInstance();
        $endnote->createInitEndnote(array('type' => 'continuationSeparator'));
        $this->_wordEndnotesC .= (string) $endnote;
    }

    /**
     * Add default footnote
     *
     * @access private
     */
    private function addDefaultFootnote()
    {
        $footnote = CreateFootnote::getInstance();
        $footnote->createInitFootnote(array('type' => 'separator'));
        $this->_wordFootnotesC .= (string) $footnote;
        $footnote = CreateFootnote::getInstance();
        $footnote->createInitFootnote(
            array('type' => 'continuationSeparator')
        );
        $this->_wordFootnotesC .= (string) $footnote;
    }

    /**
     * Add an image in the footer
     *
     * @param array $dats Image to add and paramaters to use
     * @return string
     */
    private function addImageFooter($dats = '')
    {
        try {
            if (isset($dats['name']) && file_exists($dats['name'])) {
                $arrAtributos = getimagesize($dats['name']);
                try {
                    if (
                        $arrAtributos['mime'] == 'image/jpg' ||
                        $arrAtributos['mime'] == 'image/jpeg' ||
                        $arrAtributos['mime'] == 'image/png' ||
                        $arrAtributos['mime'] == 'image/gif'
                    ) {
                        self::$intIdWord++;
                        self::$log->debug(
                            'New ID ' . self::$intIdWord . ' . Image footer.'
                        );
                        $img = CreateImage::getInstance();
                        $dats['rId'] = self::$intIdWord;
                        $img->createImage($dats);
                        $xmlHeaderImg = (string) $img;
                        $dir = $this->parsePath($dats['name']);
                        self::$log->info(
                            'Add image header word/media/image' . 
                            self::$intIdWord . '.' . $dir['extension'] .
                            '.xml to DOCX.'
                        );
                        $this->_zipDocx->addFile(
                            $dats['name'], 'word/media/image' . 
                            self::$intIdWord . '.' . $dir['extension']
                        );
                        $this->generateDEFAULT(
                            $dir['extension'], $arrAtributos['mime']
                        );
                        if ((string) $img != '') {
                            $this->_wordRelsFooterRelsC .=
                                $this->generateRELATIONSHIPTemplate(
                                    'rId' . self::$intIdWord, 'image',
                                    'media/image' . self::$intIdWord .
                                    '.' . $dir['extension']
                                );
                            return $xmlHeaderImg;
                        } else {
                            throw new Exception(
                                'Image format is not supported.'
                            );
                        }
                    } else {
                        throw new Exception('Image does not exist.');
                    }
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                    exit();
                }
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add an image in the header
     *
     * @param array $dats Image to add and paramaters to use
     * @return string
     */
    private function addImageHeader($dats = '')
    {
        try {
            if (isset($dats['name']) && file_exists($dats['name'])) {
                $attrImage = getimagesize($dats['name']);
                try {
                    if (
                        $attrImage['mime'] == 'image/jpg' ||
                        $attrImage['mime'] == 'image/jpeg' ||
                        $attrImage['mime'] == 'image/png' ||
                        $attrImage['mime'] == 'image/gif'
                    ) {
                        self::$intIdWord++;
                        self::$log->debug(
                            'New ID ' . self::$intIdWord . ' . Image header.'
                        );
                        $img = CreateImage::getInstance();
                        $dats['rId'] = self::$intIdWord;
                        $img->createImage($dats);
                        $xmlHeaderImg = (string) $img;
                        $dir = $this->parsePath($dats['name']);
                        self::$log->info(
                            'Add image header word/media/image' .
                            self::$intIdWord . '.' . 
                            $dir['extension'] . '.xml to DOCX.'
                        );
                        $this->_zipDocx->addFile(
                            $dats['name'],
                            'word/media/image' . self::$intIdWord . '.' .
                            $dir['extension']
                        );
                        $this->generateDEFAULT(
                            $dir['extension'], $attrImage['mime']
                        );
                        if ((string) $img != '') {
                            $this->_wordRelsHeaderRelsC .=
                                $this->generateRELATIONSHIPTemplate(
                                    'rId' . self::$intIdWord, 'image',
                                    'media/image' . self::$intIdWord . '.' .
                                    $dir['extension']
                                );
                            return $xmlHeaderImg;
                        } else {
                            throw new Exception(
                                'Image format is not supported.'
                            );
                        }
                    } else {
                        throw new Exception('Image does not exist.');
                    }
                }
                catch (Exception $e) {
                    self::$log->fatal($e->getMessage());
                    exit();
                }
            }
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Add settings
     *
     * @access private
     */
    private function addSettings()
    {
        $settings = CreateSettings::getInstance();
        $settings->generateSettings();
        self::$log->info('Add settings to settings document.');
        $this->_wordSettingsC .= (string) $settings;
    }

    /**
     * Add style
     *
     * @param string lang Language
     * @access private
     */
    private function addStyle($lang = 'en-US')
    {
        $style = CreateStyle::getInstance();
        $style->createStyle($lang);
        self::$log->info('Add styles to styles document.');
        $this->_wordStylesC .= (string) $style;
    }

    /**
     * Add theme
     *
     * @access private
     */
    private function addTheme($strFont)
    {
        $theme = CreateTheme1::getInstance();
        $theme->createTheme($strFont);
        self::$log->info('Add theme to theme document.');
        $this->_wordThemeThemeC .= (string) $theme;
    }

    /**
     * Add websettings
     *
     * @access private
     */
    private function addWebSettings()
    {
        $webSettings = CreateWebSettings::getInstance();
        $webSettings->generateWebSettings();
        self::$log->info('Add web settings to web settings document.');
        $this->_wordWebSettingsC .= (string) $webSettings;
    }

    /**
     * Clean template
     *
     * @access private
     */
    private function cleanTemplate()
    {
        self::$log->debug('Remove existing template tags.');
        $this->_wordDocumentT = preg_replace(
            '/__[A-Z]+__/',
            '',
            $this->_wordDocumentT
        );
    }

    /**
     * Generate content type
     *
     * @access private
     */
    private function generateContentType()
    {
        try {
            GenerateDocx::beginDocx();
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        $this->generateDEFAULT(
            'rels', 'application/vnd.openxmlformats-package.relationships+xml'
        );
        $this->generateDEFAULT('xml', 'application/xml');
        $this->generateDEFAULT('htm', 'application/xhtml+xml');
        $this->generateDEFAULT('rtf', 'application/rtf');
        $this->generateDEFAULT('zip', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
        $this->generateDEFAULT('mht', 'message/rfc822');
        $this->generateDEFAULT('wml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
        $this->generateOVERRIDE(
            '/word/numbering.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.' .
            'numbering+xml'
        );
        $this->generateOVERRIDE(
            '/word/styles.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' .
            '.styles+xml'
        );
        $this->generateOVERRIDE(
            '/docProps/app.xml',
            'application/vnd.openxmlformats-officedocument.extended-' .
            'properties+xml'
        );
        $this->generateOVERRIDE(
            '/docProps/custom.xml',
            'application/vnd.openxmlformats-officedocument.' .
            'custom-properties+xml'
        );
        $this->generateOVERRIDE(
            '/word/settings.xml', 'application/' .
            'vnd.openxmlformats-officedocument.wordprocessingml.settings+xml'
        );
        $this->generateOVERRIDE(
            '/word/theme/theme1.xml',
            'application/vnd.openxmlformats-officedocument.theme+xml'
        );
        $this->generateOVERRIDE(
            '/word/fontTable.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.' .
            'fontTable+xml'
        );
        $this->generateOVERRIDE(
            '/word/webSettings.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' .
            '.webSettings+xml'
        );
        if ($this->_wordFooterC != '' || $this->_wordHeaderC != '') {
            $this->generateOVERRIDE(
                '/word/header.xml',
                'application/vnd.openxmlformats-officedocument.' .
                'wordprocessingml.header+xml'
            );
            $this->generateOVERRIDE(
                '/word/footer.xml',
                'application/vnd.openxmlformats-officedocument.' .
                'wordprocessingml.footer+xml'
            );
            $this->generateOVERRIDE(
                '/word/footnotes.xml',
                'application/vnd.openxmlformats-officedocument.' .
                'wordprocessingml.footnotes+xml'
            );
            $this->generateOVERRIDE(
                '/word/endnotes.xml',
                'application/vnd.openxmlformats-officedocument.' .
                'wordprocessingml.endnotes+xml'
            );
        }
        $this->generateOVERRIDE(
            '/docProps/core.xml',
            'application/vnd.openxmlformats-package.core-properties+xml'
        );
    }

    /**
     * Generate DEFAULT
     *
     * @access private
     */
    private function generateDEFAULT($extension, $contentType)
    {
        $strContent = $this->_contentTypeT->saveXML();     
        if (
            strpos($strContent, 'Extension="' . $extension)
            === false
        ) {
            $strContentTypes = '<Default Extension="'.$extension .'" ContentType="'. $contentType .'"> </Default>';
            $tempNode = $this->_contentTypeT->createDocumentFragment();
            $tempNode->appendXML($strContentTypes);                  
            $this->_contentTypeT->documentElement->appendChild($tempNode);
        }
    }

    /**
     *
     *
     * @access private
     */
    private function generateDefaultFonts()
    {
        $font = array(
            'name' => 'Calibri', 'pitch' => 'variable', 'usb0' => 'A00002EF',
            'usb1' => '4000207B', 'usb2' => '00000000', 'usb3' => '00000000',
            'csb0' => '0000009F', 'csb1' => '00000000', 'family' => 'swiss',
            'charset' => '00', 'panose1' => '020F0502020204030204'
        );
        $this->addFont($font);
        $font = array(
            'name' => 'Times New Roman', 'pitch' => 'variable',
            'usb0' => 'E0002AEF', 'usb1' => 'C0007841', 'usb2' => '00000009',
            'usb3' => '00000000', 'csb0' => '000001FF', 'csb1' => '00000000',
            'family' => 'roman', 'charset' => '00',
            'panose1' => '02020603050405020304'
        );
        $this->addFont($font);
        $font = array(
            'name' => 'Cambria', 'pitch' => 'variable', 'usb0' => 'A00002EF',
            'usb1' => '4000004B', 'usb2' => '00000000', 'usb3' => '00000000',
            'csb0' => '0000009F', 'csb1' => '00000000', 'family' => 'roman',
            'charset' => '00', 'panose1' => '02040503050406030204'
        );
        $this->addFont($font);
    }

    /**
     * Generate DefaultWordRels
     *
     * @access private
     */
    private function generateDefaultWordRels()
    {
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . numbering.xml.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'numbering', 'numbering.xml'
        );
        self::$intIdWord++;
        self::$log->debug(
            'New ID ' . self::$intIdWord . ' . theme/theme1.xml.'
        );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'theme', 'theme/theme1.xml'
        );
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . numbering.xml.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'webSettings', 'webSettings.xml'
        );
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . webSettings.xml.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'fontTable', 'fontTable.xml'
        );
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . fontTable.xml.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'settings', 'settings.xml'
        );
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . settings.xml.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'styles', 'styles.xml'
        );
    }

    /**
     * Generate OVERRIDE
     *
     * @access private
     * @param string $partName
     * @param string $contentType
     */
    private function generateOVERRIDE($partName, $contentType)
    {
        $strContent = $this->_contentTypeT->saveXML();     
        if (
            strpos($strContent, 'PartName="' . $partName . '"')
            === false
        ) {
            $strContentTypes = '<Override PartName="'.$partName.'" ContentType="'.$contentType.'" />';
            $tempNode = $this->_contentTypeT->createDocumentFragment();
            $tempNode->appendXML($strContentTypes);                  
            $this->_contentTypeT->documentElement->appendChild($tempNode);
        }
    }

    /**
     * Gnerate RELATIONSHIP
     *
     * @access private
     */
    private function generateRELATIONSHIP()
    {
        $arrArgs = func_get_args();
        if ($arrArgs[1] == 'vbaProject') {
            $type =
            'http://schemas.microsoft.com/office/2006/relationships/vbaProject';
        } else {
            $type =
            'http://schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships/' . $arrArgs[1];
        }
            
        if (!isset($arrArgs[3])) {
            $nodeWML = '<Relationship Id="' . $arrArgs[0] . '" Type="' . $type .
               '" Target="' . $arrArgs[2] . '"></Relationship>';
            
        } else {
            $nodeWML = '<Relationship Id="' . $arrArgs[0] . '" Type="' . $type .
               '" Target="' . $arrArgs[2] . '" ' . $arrArgs[3] . 
               '></Relationship>';
        }
      $relsNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
       $relsNode->appendXML($nodeWML);
       $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNode);
 
       
    }
    
        /**
     * Gnerate RELATIONSHIP
     *
     * @access private
     */
    private function generateRELATIONSHIPTemplate()
    {
        $arrArgs = func_get_args();
        if ($arrArgs[1] == 'vbaProject') {
            $type =
            'http://schemas.microsoft.com/office/2006/relationships/vbaProject';
        } else {
            $type =
            'http://schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships/' . $arrArgs[1];
        }
            
        if (!isset($arrArgs[3])) {
            $nodeWML = '<Relationship Id="' . $arrArgs[0] . '" Type="' . $type .
               '" Target="' . $arrArgs[2] . '"></Relationship>';
            
        } else {
            $nodeWML = '<Relationship Id="' . $arrArgs[0] . '" Type="' . $type .
               '" Target="' . $arrArgs[2] . '" ' . $arrArgs[3] . 
               '></Relationship>';
        }
       /*$relsNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
       $relsNode->appendXML($nodeWML);
       $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNode);*/
       
       return $nodeWML;
    }

    /**
     * Generate SECTPR
     *
     * @access private
     * @param array $args Section style
     */
    private function generateSECTPR($args = '')
    {
        $page = CreatePage::getInstance();
        $page->createSECTPR($args);
        $this->_wordDocumentC .= (string) $page;
    }
    
    /**
     * Generate ContentType XML template
     *
     * @access private
     */
    private function generateTemplateContentType()
    {
        $this->_wordContentTypeT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' .
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/' .
            'content-types">' . $this->_contentTypeC . '</Types>';
    }

    /**
     * Generate DocPropsApp XML template
     *
     * @access private
     */
    private function generateTemplateDocPropsApp()
    {
        $this->_docPropsAppT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Properties xmlns="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/extended-properties" xmlns:vt="' .
            'http://schemas.openxmlformats.org/officeDocument/2006/' .
            'docPropsVTypes"><Template>Normal.dotm</Template><TotalTime>' .
            '0</TotalTime><Pages>1</Pages><Words>1</Words><Characters>1'
            . '</Characters><Application>Microsoft Office Word</Application>' .
            '<DocSecurity>4</DocSecurity><Lines>1</Lines><Paragraphs>1' .
            '</Paragraphs><ScaleCrop>false</ScaleCrop>';
        if ($this->_docPropsAppC) {
            $this->_docPropsAppT .= $this->_docPropsAppC;
        } else {
            $this->_docPropsAppT .= '<Company>Company</Company>';
        }
        $this->_docPropsAppT .= '<LinksUpToDate>false</LinksUpToDate>' .
            '<CharactersWithSpaces>1</CharactersWithSpaces><SharedDoc>' .
            'false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged>' .
            '<AppVersion>12.0000</AppVersion></Properties>';
    }

    /**
     * Generate DocPropsCore XML template
     *
     * @access private
     */
    private function generateTemplateDocPropsCore()
    {
        date_default_timezone_set('UTC');
        if ($this->_markAsFinal) {
            $this->_docPropsCoreT =
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
                '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats' .
                '.org/package/2006/metadata/core-properties" ' .
                'xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms' .
                '="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl' .
                '.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XML' .
                'Schema-instance"><dc:title>Title</dc:title><dc:subject>' .
                'Subject</dc:subject><dc:creator>2mdc</dc:creator>' .
                '<dc:description>Description</dc:description>' .
                '<cp:lastModifiedBy>user</cp:lastModifiedBy><cp:revision>1' .
                '</cp:revision><dcterms:created xsi:type="dcterms:W3CDTF">' .
                date('c') . '</dcterms:created><dcterms:modified ' .
                'xsi:type="dcterms:W3CDTF">' . date('c') .
                '</dcterms:modified><cp:contentStatus>Final' .
                '</cp:contentStatus></cp:coreProperties>';
        } else {
            $this->_docPropsCoreT =
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> ' .
                '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats' .
                '.org/package/2006/metadata/core-properties" ' .
                'xmlns:dc="http://purl.org/dc/elements/1.1/" ' .
                'xmlns:dcterms="http://purl.org/dc/terms/" ' .
                'xmlns:dcmitype="http://purl.org/dc/dcmitype/" ' .
                'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
                '<dc:title>Title</dc:title><dc:subject>Subject</dc:subject>' .
                '<dc:creator>2mdc</dc:creator><dc:description>Description' .
                '</dc:description><cp:lastModifiedBy>user' .
                '</cp:lastModifiedBy><cp:revision>1</cp:revision>' .
                '<dcterms:created xsi:type="dcterms:W3CDTF">' . date('c') .
                '</dcterms:created><dcterms:modified xsi:type="dcterms:W3CDTF' .
                '">' . date('c') . '</dcterms:modified></cp:coreProperties>';
        }
    }

    /**
     * Generate DocPropsCustom XML template
     *
     * @access private
     */
    private function generateTemplateDocPropsCustom()
    {
        $this->_docPropsCustomT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Properties xmlns="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/custom-properties" xmlns:vt="http://' .
            'schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">' .
            '<property fmtid="{D5CDD505-2E9C-101B-9397-08002B2CF9AE}" ' .
            'pid="2" name="_MarkAsFinal"><vt:bool>true</vt:bool></property>' .
            '</Properties>';
    }

    /**
     * Generate RelsRels XML template
     *
     * @access private
     */
    private function generateTemplateRelsRels()
    {
        $this->_relsRelsT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/' .
            '2006/relationships">' .
            $this->generateRELATIONSHIP(
                'rId3', 'extended-properties', 'docProps/app.xml'
            ) .
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats' .
            '.org/package/2006/relationships/metadata/core-properties"' .
            ' Target="docProps/core.xml"/>' .
            $this->generateRELATIONSHIP(
                'rId1', 'officeDocument', 'word/document.xml'
            );
        if ($this->_markAsFinal) {
            $this->_relsRelsT .=
                '<Relationship Id="rId4" Type="http://schemas' .
                '.openxmlformats.org/officeDocument/2006/relationships/' .
                'custom-properties" Target="docProps/custom.xml"/>';
        }
        $this->_relsRelsT .= '</Relationships>';
    }

    /**
     * Generate WordDocument XML template
     *
     * @access private
     */
    private function generateTemplateWordDocument()
    {
        $arrArgs = func_get_args();
        //$this->generateSECTPR($arrArgs[0]);
        $this->_wordDocumentC .= $this->_sectPr->saveXML($this->_sectPr->documentElement);//FIXME: I am insertying by hand the sections of the base template
        if (!empty($this->_wordHeaderC)) {
            $this->_wordDocumentC = str_replace(
                '__GENERATEHEADERREFERENCE__',
                '<' . CreateDocx::NAMESPACEWORD . ':headerReference ' .
                CreateDocx::NAMESPACEWORD . ':type="default" r:id="rId' .
                $this->_idWords['header'] . '"></' .
                CreateDocx::NAMESPACEWORD . ':headerReference>',
                $this->_wordDocumentC
            );
        }
        if (!empty($this->_wordFooterC)) {
            $this->_wordDocumentC = str_replace(
                '__GENERATEFOOTERREFERENCE__',
                '<' . CreateDocx::NAMESPACEWORD . ':footerReference ' .
                CreateDocx::NAMESPACEWORD . ':type="default" r:id="rId' .
                $this->_idWords['footer'] . '"></' .
                CreateDocx::NAMESPACEWORD . ':footerReference>',
                $this->_wordDocumentC
            );
        }
        $this->_wordDocumentT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<' . CreateDocx::NAMESPACEWORD . ':document xmlns:ve=' .
            '"http://schemas.openxmlformats.org/markup-compatibility/2006" ' .
            'xmlns:o="urn:schemas-microsoft-com:office:office"' .
            ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006' .
            '/relationships" xmlns:m="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml"'.
            ' xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/' .
            'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' .
            'office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas' .
            '.microsoft.com/office/word/2006/wordml">' .
            $this->_background.
            '<' . CreateDocx::NAMESPACEWORD . ':body>' .
            $this->_wordDocumentC .
            '</' . CreateDocx::NAMESPACEWORD . ':body>' .
            '</' . CreateDocx::NAMESPACEWORD . ':document>';
        $this->cleanTemplate();
    }

    /**
     * Generate WordEndnotes XML template
     *
     * @access private
     */
    private function generateTemplateWordEndnotes()
    {
        $this->_wordEndnotesT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<' . CreateDocx::NAMESPACEWORD . ':endnotes xmlns:ve' .
            '="http://schemas.openxmlformats.org/markup-compatibility/2006" ' .
            'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="' .
            'http://schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships" xmlns:m="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:' .
            'vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006' .
            '/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' .
            'office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas' .
            '.microsoft.com/office/word/2006/wordml">' .
            $this->_wordEndnotesC .
            '</' . CreateDocx::NAMESPACEWORD . ':endnotes>';
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . Endnotes.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'endnotes', 'endnotes.xml'
        );
        $this->generateOVERRIDE(
            '/word/endnotes.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' .
            '.endnotes+xml'
        );
    }

    /**
     * Generate WordFontTable XML template
     *
     * @access private
     */
    private function generateTemplateWordFontTable()
    {
        $this->_wordFontTableT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' .
            '<' . CreateDocx::NAMESPACEWORD . ':fonts xmlns:r="http://' .
            'schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main">' . $this->_wordFontTableC .
            '</' . CreateDocx::NAMESPACEWORD . ':fonts>';
    }

    /**
     * Generate WordFooter XML template
     *
     * @access private
     */
    private function generateTemplateWordFooter()
    {
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . Footer.');
        $this->_idWords['footer'] = self::$intIdWord;
        $this->_wordFooterT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <' . CreateDocx::NAMESPACEWORD . ':ftr xmlns:ve' .
            '="http://schemas.openxmlformats.org/markup-compatibility/' .
            '2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns' .
            ':r="http://schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships" xmlns:m="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml' .
            '" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/' .
            'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' .
            'office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas' .
            '.microsoft.com/office/word/2006/wordml">' . $this->_wordFooterC .
            '</' . CreateDocx::NAMESPACEWORD . ':ftr>';
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'footer', 'footer.xml'
        );
    }

    /**
     * Generate WordFootnotes XML template
     *
     * @access private
     */
    private function generateTemplateWordFootnotes()
    {
        $this->_wordFootnotesT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<' . CreateDocx::NAMESPACEWORD . ':footnotes xmlns:ve="' .
            'http://schemas.openxmlformats.org/markup-compatibility/2006" ' .
            'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="' .
            'http://schemas.openxmlformats.org/officeDocument/2006/' .
            'relationships" xmlns:m="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:' .
            'vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006' .
            '/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' .
            'office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft' .
            '.com/office/word/2006/wordml">' . $this->_wordFootnotesC .
            '</' . CreateDocx::NAMESPACEWORD . ':footnotes>';
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . Footnotes.');
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'footnotes', 'footnotes.xml'
        );
        $this->generateOVERRIDE(
            '/word/footnotes.xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' .
            '.footnotes+xml'
        );
    }

    /**
     * Generate WordHeader XML template
     *
     * @access private
     */
    private function generateTemplateWordHeader()
    {
        self::$intIdWord++;
        self::$log->debug('New ID ' . self::$intIdWord . ' . Header.');
        $this->_idWords['header'] = self::$intIdWord;
        $this->_wordHeaderT = '<?xml version="1.0" encoding="UTF-8" ' .
            'standalone="yes"?>' .
            '<' . CreateDocx::NAMESPACEWORD .
            ':hdr xmlns:ve="http://schemas.openxmlformats.org/markup' .
            '-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:' .
            'office:office" xmlns:r="http://schemas.openxmlformats.org/' .
            'officeDocument/2006/relationships" xmlns:m="http://schemas' .
            '.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:' .
            'schemas-microsoft-com:vml" xmlns:wp="http://schemas' .
            '.openxmlformats.org/drawingml/2006/wordprocessingDrawing" ' .
            'xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="' .
            'http://schemas.openxmlformats.org/wordprocessingml/2006/' .
            'main" xmlns:wne="http://schemas.microsoft.com/office/word/' .
            '2006/wordml"> ' . $this->_wordHeaderC .
            '</' . CreateDocx::NAMESPACEWORD . ':hdr>';
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP(
            'rId' . self::$intIdWord, 'header', 'header.xml'
        );
        return 'rId' . self::$intIdWord;
    }

    /**
     * Generate WordNumbering XML template
     *
     * @access private
     */
    private function generateTemplateWordNumbering()
    {
        $this->_wordNumberingT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<w:numbering xmlns:ve="http://schemas.openxmlformats' .
            '.org/markup-compatibility/2006" xmlns:o="urn:schemas-' .
            'microsoft-com:office:office" xmlns:r="http://schemas' .
            '.openxmlformats.org/officeDocument/2006/relationships" ' .
            'xmlns:m="http://schemas.openxmlformats.org/officeDocument/' .
            '2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:' .
            'wp="http://schemas.openxmlformats.org/drawingml/2006/' .
            'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com' .
            ':office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas.' .
            'microsoft.com/office/word/2006/wordml"><w:abstractNum w:'
            . 'abstractNumId="0"><w:nsid w:val="713727AE"/><w:multiLevelType' .
            ' w:val="hybridMultilevel"/><w:tmpl w:val="F0B4B6B8"/>' .
            '<w:lvl w:ilvl="0" w:tplc="0C0A0001"><w:start w:val="1"/>' .
            '<w:numFmt w:val="bullet"/><w:lvlText w:val=""/><w:lvlJc ' .
            'w:val="left"/><w:pPr><w:ind w:left="720" w:hanging="360"/>' .
            '</w:pPr><w:rPr><w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" ' .
            'w:hint="default"/></w:rPr></w:lvl><w:lvl w:ilvl="1" ' .
            'w:tplc="0C0A0003" w:tentative="1"><w:start w:val="1"/>' .
            '<w:numFmt w:val="bullet"/><w:lvlText w:val="o"/><w:lvlJc ' .
            'w:val="left"/><w:pPr><w:ind w:left="1440" w:hanging="360"/>' . '
                </w:pPr><w:rPr><w:rFonts w:ascii="Courier New" w:hAnsi=' .
            '"Courier New" w:cs="Courier New" w:hint="default"/></w:rPr>' .
            '</w:lvl><w:lvl w:ilvl="2" w:tplc="0C0A0005" w:tentative="1">' .
            '<w:start w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText ' .
            'w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind w:left="2160" ' .
            'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Wingdings" ' .
            'w:hAnsi="Wingdings" w:hint="default"/></w:rPr></w:lvl><w:lvl ' .
            'w:ilvl="3" w:tplc="0C0A0001" w:tentative="1"><w:start ' .
            'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val=""/>' .
            '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="2880" w:hanging=' .
            '"360"/></w:pPr><w:rPr><w:rFonts w:ascii="Symbol" w:hAnsi=' .
            '"Symbol" w:hint="default"/></w:rPr></w:lvl><w:lvl w:ilvl="4" ' .
            'w:tplc="0C0A0003" w:tentative="1"><w:start w:val="1"/>' .
            '<w:numFmt w:val="bullet"/><w:lvlText w:val="o"/><w:lvlJc ' .
            'w:val="left"/><w:pPr><w:ind w:left="3600" w:hanging="360"/>' .
            '</w:pPr><w:rPr><w:rFonts w:ascii="Courier New" w:hAnsi=' .
            '"Courier New" w:cs="Courier New" w:hint="default"/></w:rPr>' .
            '</w:lvl><w:lvl w:ilvl="5" w:tplc="0C0A0005" w:tentative="1">' .
            '<w:start w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText ' .
            'w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind w:left="4320" ' .
            'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Wingdings" ' .
            'w:hAnsi="Wingdings" w:hint="default"/></w:rPr></w:lvl><w:lvl ' .
            'w:ilvl="6" w:tplc="0C0A0001" w:tentative="1"><w:start ' .
            'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val=""/>' .
            '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="5040" ' .
            'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Symbol" ' .
            'w:hAnsi="Symbol" w:hint="default"/></w:rPr></w:lvl><w:lvl ' .
            'w:ilvl="7" w:tplc="0C0A0003" w:tentative="1"><w:start ' .
            'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val="o"/>' .
            '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="5760" ' .
            'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Courier New" ' .
            'w:hAnsi="Courier New" w:cs="Courier New" w:hint="default"/>' .
            '</w:rPr></w:lvl><w:lvl w:ilvl="8" w:tplc="0C0A0005" ' .
            'w:tentative="1"><w:start w:val="1"/><w:numFmt w:val="bullet"' .
            '/><w:lvlText w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind ' .
            'w:left="6480" w:hanging="360"/></w:pPr><w:rPr><w:rFonts ' .
            'w:ascii="Wingdings" w:hAnsi="Wingdings" w:hint="default"/>' .
            '</w:rPr></w:lvl></w:abstractNum><w:num w:numId="1">' .
            '<w:abstractNumId w:val="0"/></w:num></w:numbering>';
    }

    /**
     * Generate WordNumbering XML template
     *
     * @access private
     */
    private function generateTemplateWordNumberingStyles()
    {
        $this->_wordNumberingT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<w:numbering xmlns:ve="http://schemas.openxmlformats' .
            '.org/markup-compatibility/2006" xmlns:o="urn:schemas-' .
            'microsoft-com:office:office" xmlns:r="http://schemas' .
            '.openxmlformats.org/officeDocument/2006/relationships" ' .
            'xmlns:m="http://schemas.openxmlformats.org/officeDocument/' .
            '2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:' .
            'wp="http://schemas.openxmlformats.org/drawingml/2006/' .
            'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com' .
            ':office:word" xmlns:w="http://schemas.openxmlformats.org/' .
            'wordprocessingml/2006/main" xmlns:wne="http://schemas.' .
            'microsoft.com/office/word/2006/wordml"><w:abstractNum w:'
            . 'abstractNumId="0"><w:nsid w:val="713727AE"/><w:multiLevelType' .
            ' w:val="hybridMultilevel"/><w:tmpl w:val="F0B4B6B8"/>' .
            $this->_wordDocumentStyles . '</w:abstractNum><w:num w:numId="1">' .
            '<w:abstractNumId w:val="0"/></w:num></w:numbering>';
    }

    /**
     * Generate WordRelsDocumentRels XML template
     *
     * @access private
     */
    private function generateTemplateWordRelsDocumentRels()
    {
        $this->_wordRelsDocumentRelsT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/' .
            'package/2006/relationships">' . $this->_wordRelsDocumentRelsC .
            '</Relationships>';
    }

    /**
     * Generate WordRelsFooterRels XML template
     *
     * @access private
     */
    private function generateTemplateWordRelsFooterRels()
    {
        $this->_wordRelsFooterRelsT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/' .
            'package/2006/relationships">' . $this->_wordRelsFooterRelsC .
            '</Relationships>';
    }

    /**
     * Generate WordRelsHeaderRels XML template
     *
     * @access private
     */
    private function generateTemplateWordRelsHeaderRels()
    {
        $this->_wordRelsHeaderRelsT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/' .
            'package/2006/relationships">' . $this->_wordRelsHeaderRelsC .
            '</Relationships>';
    }

    /**
     * Generate WordSettings XML template
     *
     * @access private
     */
    private function generateTemplateWordSettings()
    {
        $this->_wordSettingsT = $this->_wordSettingsC;
    }

    /**
     * Generate WordStyles XML template
     *
     * @access private
     */
    private function generateTemplateWordStyles()
    {
        $this->_wordStylesT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><' .
            CreateDocx::NAMESPACEWORD . ':styles xmlns:r="http://' .
            'schemas.openxmlformats.org/officeDocument/2006/relationships' .
            '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/' .
            '2006/main">' . $this->_wordStylesC .
            '</' . CreateDocx::NAMESPACEWORD . ':styles>';
    }

    /**
     * Generate WordThemeTheme1 XML template
     *
     * @access private
     */
    private function generateTemplateWordThemeTheme1()
    {
        $this->addTheme($this->_defaultFont);
        $this->_wordThemeThemeT =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><' .
            CreateTheme1::NAMESPACEWORD . ':theme xmlns:a="http://' .
            'schemas.openxmlformats.org/drawingml/2006/main" name="' .
            'Tema de Office">' . $this->_wordThemeThemeC .
            '</' . CreateTheme1::NAMESPACEWORD . ':theme>';
    }

    /**
     * Generate WordWebSettings XML template
     *
     * @access private
     */
    private function generateTemplateWordWebSettings()
    {
        $this->_wordWebSettingsT = $this->_wordWebSettingsC;
    }
    
    /**
     * To add support of sys_get_temp_dir for PHP versions under 5.2.1
     * 
     * @access private
     * @return string
     */
    private function getTempDir() {
        if ( !function_exists('sys_get_temp_dir')) {
            function sys_get_temp_dir() {
                if ($temp = getenv('TMP')) {
                    return $temp;
                }
                if ($temp = getenv('TEMP')) {
                    return $temp;
                }
                if ($temp = getenv('TMPDIR')) {
                    return $temp;
                }
                $temp = tempnam(__FILE__,'');
                if (file_exists($temp)) {
                    unlink($temp);
                    return dirname($temp);
                }
                return null;
            }
        } else {
            return sys_get_temp_dir();
        }
    }

    /**
     * Parse path dir
     *
     * @access private
     * @param string $dir Directory path
     */
    private function parsePath($dir)
    {
        $slash = 0;
        $path = '';
        if (($slash = strrpos($dir, '/')) !== false) {
            $slash += 1;
            $path = substr($dir, 0, $slash);
        }
        $punto = strpos(substr($dir, $slash), '.');

        $nombre = substr($dir, $slash, $punto);
        $extension = substr($dir, $punto + $slash + 1);
        return array(
            'path' => $path, 'nombre' => $nombre, 'extension' => $extension
        );
    }
     /**
     * Delete a file or recursively delete a directory
     *
     * @param string $str path to file or directory
     */
    private function recursiveDelete($str){
        if(is_file($str)){
            return @unlink($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                $this->recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }
    

     /** 
     * 
     * Adds directory contents recursively into a zip. 
     * 
     * @param string $dirName. The path to the dir to add. 
     * 
     * @param string $myZip. The zip where the contents of the dir should be added. 
     * 
     */  
    private function recursiveInsert($myZip, $fileName, $basePath){
         $length = strlen($basePath);
         if(is_dir($fileName)){
            $dirName = substr($fileName, $length);
            $myZip->addEmptyDir($dirName); 
            $contentsDir = scandir($fileName);
            foreach($contentsDir as $element){                       
              if($element != "." && $element !=".."){
                 $this->recursiveInsert($myZip, $fileName."/".$element, $basePath);              
                 }
            }
         }else{
            $newName = substr($fileName, $length + 1);
            $myZip->addFile($fileName, $newName); 
         }
     }
     
     /** 
     * 
     * Includes data in the setting.xml file.  
     * 
     * @param array $settings. The string with the nodes that should be included in the settings.xml file. 
     * 
     */  
    private function includeSettings($data){
        try{
            $baseSettings = $this->_baseTemplateZip->getFromName('word/settings.xml');
            if ($baseSettings == '') {
        throw new Exception('Error while extracting the settings.xml file from the base template');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        } 
        
        $settingsDoc = new DOMDocument();
        $settingsDoc->loadXML($baseSettings);
        $settings = $settingsDoc->documentElement;

        foreach($data as $key => $value){
            $newNode = $settingsDoc->createDocumentFragment();
            $newNode->appendXML($value);
            $settings->appendChild($newNode);
        }

        $settingsHandler = fopen($this->_baseTemplateFilesPath.'/word/settings.xml', "w+");
        fwrite($settingsHandler, $settingsDoc->saveXML());
        fclose($documentHandler);        
     }
     
     /** 
     * 
     * Removes headers and footers.  
     *  
     */  
    public function removeHeadersAndFooters(){
        $this->removeHeaders();
        $this->removeFooters();
    }

     /** 
     * 
     * Removes existing headers.  
     *  
     */  
    public function removeHeaders(){
        //var_dump($this->_relsHeader);
        foreach($this->_relsHeader as $key => $value){
            //Let us first remove the actual header files
            $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/'.$value);
            $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/_rels/'.$value.'.rels');
            
            //Now we should modify the rels file
            $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
            $counter = $relationships->length -1;
            for($j=$counter; $j > -1; $j--){
                $target = $relationships->item($j)->getAttribute('Target');
                if($target == $value){
                  $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                }
            }
            //We should also remove the corresponding override tags from [Content_Types].xml
            $overrides = $this->_contentTypeT->getElementsByTagName('Override');
            $counter = $overrides->length -1;
            for($j=$counter; $j > -1; $j--){
                $target = $overrides->item($j)->getAttribute('PartName');
                if($target == '/word/'.$value){
                  $this->_contentTypeT->documentElement->removeChild($overrides->item($j));
                }
            }
        }
        
        
        //Finally we should change the section properties
        $headers = $this->_sectPr->getElementsByTagName('headerReference');
        $counter = $headers->length -1;
        for($j=$counter; $j > -1; $j--){
            $this->_sectPr->documentElement->removeChild($headers->item($j));
        }
    }
    
      /** 
     * 
     * Removes existing footers.  
     *  
     */  
    public function removeFooters(){
        //var_dump($this->_relsFooter);
        foreach($this->_relsFooter as $key => $value){
            //Let us first remove the actual header files
            $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/'.$value);
            $this->recursiveDelete($this->_baseTemplateFilesPath.'/word/_rels/'.$value.'.rels');
            
            //Now we should modify the rels file
            $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
            $counter = $relationships->length -1;
            for($j=$counter; $j > -1; $j--){
                $target = $relationships->item($j)->getAttribute('Target');
                if($target == $value){
                  $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                }
            }  
            //We should also remove the corresponding override tags from [Content_Types].xml
            $overrides = $this->_contentTypeT->getElementsByTagName('Override');
            $counter = $overrides->length -1;
            for($j=$counter; $j > -1; $j--){
                $target = $overrides->item($j)->getAttribute('PartName');
                if($target == '/word/'.$value){
                  $this->_contentTypeT->documentElement->removeChild($overrides->item($j));
                }
            }
        }
        //Finally we should change the section properties
        $footers = $this->_sectPr->getElementsByTagName('footerReference');
        $counter = $footers->length -1;
        for($j=$counter; $j > -1; $j--){
            $this->_sectPr->documentElement->removeChild($footers->item($j));
        }
    }   
    
     /** 
     * 
     * Inserts new headers and/or footers from a word file.  
     * 
     * @example ../examples/easy/importHeaderAndFooter.php 
     * @param string $path. Path to the docx from which we wish to import the header and/or footer
     * @param string $type. Declares if we want to import only the header, only the footer or both.
     * Values: header, footer, headerAndFooter (default value)
     */  
    public function importHeadersAndFooters($path, $type='headerAndFooter'){
        
        
        switch ($type){
            case 'headerAndFooter':
                $this->removeHeadersAndFooters();
                break;
            case 'header':
                $this->removeHeaders();
                break;
            case 'footer':
                $this->removeFooters();
                break;         
        }
        //Let us first get parse and extract the relevant files from the docx with the new headers/footers
        try {
            $baseHeadersFooters = new ZipArchive();
            $openHeadersFooters = $baseHeadersFooters->open($path);                 
            if ($openHeadersFooters !== true){
        throw new Exception('Error while opening the docx to extract the header and/or footer');
        }                                           
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        //We now extract its contents into a temp dir for further manipulation 
        try {  
        $baseHeadersFootersFilesPath = $this->_tempDir.'/'.uniqid(true);    
        $extractHeadersFooters =$baseHeadersFooters->extractTo($baseHeadersFootersFilesPath);
    if ($extractHeadersFooters !== true) {
            throw new Exception('Error while extracting the contents of the docx file that containes the header and/or footer: there may be problems writing in the default tmp folder');
            }
    }
        catch (Exception $e) {
          self::$log->fatal($e->getMessage());
          exit();
        }
        //We need to extract the different roles: default, even or first played by the different headers and footers
        //In order to do that we should first parse the node sectPr from the document.xml file
        
        try{
            $docHeadersFooters = $baseHeadersFooters->getFromName('word/document.xml');
            if ($docHeadersFooters == '') {
        throw new Exception('Error while extracting the document.xml file from the docx from which we want to get the feaders and/or footers');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        
        $docHeadersFootersContent = new DOMDocument();
        $docHeadersFootersContent ->loadXML($docHeadersFooters);
        
        //We now extract the first sectPr element in the document
        
        $docSectPr = $docHeadersFootersContent->getElementsByTagName('sectPr')->item(0);
        
        $headerTypes = array();
        $footerTypes = array();
        
        foreach($docSectPr->childNodes as $value){
            if($value->nodeName == 'w:headerReference'){
                $headerTypes[$value->getAttribute('r:id')] = $value->getAttribute('w:type');
            }else if($value->nodeName == 'w:footerReference'){
                $footerTypes[$value->getAttribute('r:id')] = $value->getAttribute('w:type');
            }
     
        }
      
        //Let us read the _rels/document.xml.rels to see which footers and headers we do have to get
        try{
            $baseHeadersFootersRelsT = $baseHeadersFooters->getFromName('word/_rels/document.xml.rels');
            if ($baseHeadersFootersRelsT == '') {
        throw new Exception('Error while extracting the document.xml.rels file from the docx from which we want to get the feaders and/or footers');
            }               
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
        

        //Let us analyze its structure
        //In order to do that we should parse word/_rels/document.xml.rels
        
        $wordHeadersFootersRelsT = new DOMDocument();
        $wordHeadersFootersRelsT->loadXML($baseHeadersFootersRelsT);
        $relationships = $wordHeadersFootersRelsT->getElementsByTagName('Relationship');
 
        $counter = $relationships->length -1;

        for($j=$counter; $j > -1; $j--){
            $rId = $relationships->item($j)->getAttribute('Id');
            $completeType = $relationships->item($j)->getAttribute('Type');
            $target = $relationships->item($j)->getAttribute('Target');
            $myType = array_pop(explode('/', $completeType));

            switch($myType){
                case 'header':
                    $relsHeader[$rId]= $target;
                    break;
                case 'footer':
                    $relsFooter[$rId]= $target;
                    break;  
            }
        }

            //Let start the looping over the $relsHeader and/or $relsFooter arrays

            if($type == 'headerAndFooter' || $type == 'header'){

                foreach ($relsHeader as $key => $value){
                  //First we check if there is a rels file for each header
                    if(file_exists($baseHeadersFootersFilesPath.'/word/_rels/'.$value.'.rels')){
                         try{
                            $headersRelsT = $baseHeadersFooters->getFromName('word/_rels/'.$value.'.rels');
                            if ($headersRelsT == '') {
                                throw new Exception('Error while extracting the rels file for the header');
                            }               
                        }
                        catch (Exception $e) {
                            self::$log->fatal($e->getMessage());
                            exit();
                        }                       
                    
                       //Now we should parse the corresponding rels file to copy and rename the images included in the header
                        $wordHeadersRelsT = new DOMDocument();
                        $wordHeadersRelsT->loadXML($headersRelsT);
                        $relations = $wordHeadersRelsT->getElementsByTagName('Relationship');

                        $countrels = $relations->length -1;

                        for($j=$countrels; $j > -1; $j--){
                            $completeType = $relations->item($j)->getAttribute('Type');
                            $target = $relations->item($j)->getAttribute('Target');
                            $myType = array_pop(explode('/', $completeType));

                            switch($myType){
                                case 'image':
                                    $refExtension = array_pop(explode('.', $target));
                                    $refImage = 'media/image'.uniqid(true).'.'.$refExtension;
                                    //We change the attibute to the new name
                                    $relations->item($j)->setAttribute('Target', $refImage);
                                    //We copy the image in the base template with the new name
                                    if(!file_exists($this->_baseTemplateFilesPath.'/word/media')){
                                       $test2 = mkdir($this->_baseTemplateFilesPath.'/word/media');
                                    }
                                    copy($baseHeadersFootersFilesPath.'/word/'.$target, $this->_baseTemplateFilesPath.'/word/'.$refImage);
                                    //We copy the associated rels file
                                    $newRels = fopen($this->_baseTemplateFilesPath.'/word/_rels/'.$value.'.rels', 'w+');
                                    fwrite($newRels, $wordHeadersRelsT->saveXML());
                                    //We should make sure that the corresponding image types are included in [Content_Types].xml
                                    $imageTypeFound = false;
                                    foreach($this->_contentTypeT->documentElement->childNodes as $node){
                                        if ($node->nodeName == 'Default' && $node->getAttribute('Extension') == $refExtension){
                                            $imageTypeFound = true;
                                        }                                 
                                    }
                                    if(!$imageTypeFound){
                                        $newDefaultNode = '<Default Extension="'.$refExtension.'" ContentType="image/'.$refExtension.'" />';
                                        $newDefault = $this->_contentTypeT->createDocumentFragment();
                                        $newDefault->appendXML($newDefaultNode);
                                        $baseDefaultNode =$this->_contentTypeT->documentElement;
                                        $baseDefaultNode->appendChild($newDefault);
                                    }
                                    break;
                            }
                        }
                    }
                    
                    //Now we copy the corresponding header xml files
                    copy($baseHeadersFootersFilesPath.'/word/'.$value, $this->_baseTemplateFilesPath.'/word/'.$value);
                    //We should now modify the /_rels/document.xml.rels of the base template to include the new element
                    $newId = uniqid(true);
                    $newHeaderNode = '<Relationship Id="rId';
                    $newHeaderNode .= $newId.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header"'; 
                    $newHeaderNode .= ' Target="'.$value.'" />';
                    $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                    $newNode->appendXML($newHeaderNode);
                    $baseNode =$this->_wordRelsDocumentRelsT->documentElement;
                    $baseNode->appendChild($newNode);
                    
                    //As well as the section DOMNode                 
                    $newSectNode = '<w:headerReference w:type="'.$headerTypes[$key].'" r:id="rId'.$newId.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                    $sectNode = $this->_sectPr->createDocumentFragment();
                    $sectNode->appendXML($newSectNode);
                    $refNode =$this->_sectPr->documentElement->childNodes->item(0);
                    $refNode->parentNode->insertBefore($sectNode, $refNode);
                    
                    //And we have to include the corresponding <Override> in [Content_Types].xml
                    $newOverrideNode = '<Override PartName="/word/'.$value.'" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml" />';
                    $newOverride = $this->_contentTypeT->createDocumentFragment();
                    $newOverride->appendXML($newOverrideNode);
                    $baseOverrideNode =$this->_contentTypeT->documentElement;
                    $baseOverrideNode->appendChild($newOverride);


                }
            }
            if($type == 'headerAndFooter' || $type == 'footer'){

                foreach ($relsFooter as $key => $value){
                  //First we check if there is a rels file for each header
                    if(file_exists($baseHeadersFootersFilesPath.'/word/_rels/'.$value.'.rels')){
                         try{
                            $footersRelsT = $baseHeadersFooters->getFromName('word/_rels/'.$value.'.rels');
                            if ($footersRelsT == '') {
                                throw new Exception('Error while extracting the rels file for the footer');
                            }               
                        }
                        catch (Exception $e) {
                            self::$log->fatal($e->getMessage());
                            exit();
                        }                       
                    
                       //Now we should parse the corresponding rels file to copy and rename the images included in the header
                        $wordFootersRelsT = new DOMDocument();
                        $wordFootersRelsT->loadXML($footersRelsT);
                        $relations = $wordFootersRelsT->getElementsByTagName('Relationship');

                        $countrels = $relations->length -1;

                        for($j=$countrels; $j > -1; $j--){
                            $completeType = $relations->item($j)->getAttribute('Type');
                            $target = $relations->item($j)->getAttribute('Target');
                            $myType = array_pop(explode('/', $completeType));

                            switch($myType){
                                case 'image':
                                    $refExtension = array_pop(explode('.', $target));
                                    $refImage = 'media/image'.uniqid(true).'.'.$refExtension;
                                    //We change the attibute to the new name
                                    $relations->item($j)->setAttribute('Target', $refImage);
                                    //We copy the image in the base template with the new name
                                    if(!file_exists($this->_baseTemplateFilesPath.'/word/media')){
                                       $test2 = mkdir($this->_baseTemplateFilesPath.'/word/media');
                                    }
                                    copy($baseHeadersFootersFilesPath.'/word/'.$target, $this->_baseTemplateFilesPath.'/word/'.$refImage);
                                    //We copy the associated rels file
                                    $newRels = fopen($this->_baseTemplateFilesPath.'/word/_rels/'.$value.'.rels', 'w+');
                                    fwrite($newRels, $wordFootersRelsT->saveXML());
                                    //We should make sure that the corresponding image types are included in [Content_Types].xml
                                    $imageTypeFound = false;
                                    foreach($this->_contentTypeT->documentElement->childNodes as $node){
                                        if ($node->nodeName == 'Default' && $node->getAttribute('Extension') == $refExtension){
                                            $imageTypeFound = true;
                                        }                                 
                                    }
                                    if(!$imageTypeFound){
                                        $newDefaultNode = '<Default Extension="'.$refExtension.'" ContentType="image/'.$refExtension.'" />';
                                        $newDefault = $this->_contentTypeT->createDocumentFragment();
                                        $newDefault->appendXML($newDefaultNode);
                                        $baseDefaultNode =$this->_contentTypeT->documentElement;
                                        $baseDefaultNode->appendChild($newDefault);
                                    }
                                    break;
                            }
                        }
                    }
                    
                    //Now we copy the corresponding header xml files
                    copy($baseHeadersFootersFilesPath.'/word/'.$value, $this->_baseTemplateFilesPath.'/word/'.$value);
                    //We should now modify the /_rels/document.xml.rels of the base template to include the new element
                    $newId = uniqid(true);
                    $newFooterNode = '<Relationship Id="rId';
                    $newFooterNode .= $newId.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer"'; 
                    $newFooterNode .= ' Target="'.$value.'" />';
                    $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                    $newNode->appendXML($newFooterNode);
                    $baseNode =$this->_wordRelsDocumentRelsT->documentElement;
                    $baseNode->appendChild($newNode);
                    
                    //As well as the section DOMNode                 
                    $newSectNode = '<w:footerReference w:type="'.$footerTypes[$key].'" r:id="rId'.$newId.'" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                    $sectNode = $this->_sectPr->createDocumentFragment();
                    $sectNode->appendXML($newSectNode);
                    $refNode =$this->_sectPr->documentElement->childNodes->item(0);
                    $refNode->parentNode->insertBefore($sectNode, $refNode);
                    
                    //And we have to include the corresponding <Override> in [Content_Types].xml
                    $newOverrideNode = '<Override PartName="/word/'.$value.'" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml" />';
                    $newOverride = $this->_contentTypeT->createDocumentFragment();
                    $newOverride->appendXML($newOverrideNode);
                    $baseOverrideNode =$this->_contentTypeT->documentElement;
                    $baseOverrideNode->appendChild($newOverride);


                }
            }
    
    }
}
