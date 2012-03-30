<?php

/**
 * Generate a DOCX file
 *
 * @category   Phpdocx
 * @package    create
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.1
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.0
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once dirname(__FILE__) . '/../AutoLoader.inc';
AutoLoader::load();
/**
 * Main class. Methods and vars to generate a DOCX file
 *
 * @category   Phpdocx
 * @package    create
 * @copyright  Copyright (c) 2009-2011 Narcea ProduCiones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2.1
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 1.0
 */
class CreatePdf extends CreateDocument {

    /**
     * Directory path used for temporary files
     *
     * @access private
     * @var string
     */
    private $_tempDir;
    /**
     * Determine if a temporaly folder is created
     *
     * @access private
     * @var boolean
     */
    private $_tempDirExits;
    /**
     * @access private
     * @var iniziate
     */
    private $_iniziate;
    /**
     * @access private
     * @var bToc
     */
    private $_bToc;

    /**
     * @access private
     * @var creacion
     */
    private $_creacion;
    /**
     *
     * @access private
     * @var Debug
     */
    private $_debug;
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
     * @var Document
     */
    private $_document;
    /**
     *
     * @access private
     * @var DocumentName
     */
    private $_documentName;
    /**
     *
     * @access private
     * @var Object pdf
     */
    private $_pdf;
    /**
     * @access private
     * @var String
     */
    private $_header;
    /**
     * @access private
     * @var String
     */
    private $_footer;
    /**
     * @access private
     * @var String
     */
    private $_idFooter;

    


    public function __construct() {
        $this->_iniziate = false;
        $this->_bToc = false;
        $this->_tempDirExits = file_exists(getcwd(). '/tmp');

        $this->_creacion = false;
        $this->_debug = Debug::getInstance();
        $this->_header = '';
        $this->_tempDir = self::getTempDir();
        $this->_footer = '';
        $this->_document = '';
        $this->_idFooter = 1;
        $this->_documentName = 'prueba.pdf';
        $this->_pdf = new MYPDF();
        /*if(!$this->_creacion)
            $this->_pdf->AddPage();*/
        Logger::configure(dirname(__FILE__) . '/../conf/log4php.properties');
        self::$log = Logger::getLogger('phppdf_error');
        self::$log->info(
                'Create the pdf object'
        );
        try {
            GenerateDocx::beginDocx();
        }
        catch (Exception $e) {
            self::$log->fatal($e->getMessage());
            exit();
        }
    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct() {

    }

    /**
     * @access public
     * @return string Return current pdf
     */
    public function __toString() {

    }

    /**
     * Add a text
     *
     * @access public
     * @example ../examples/easy/Text.php
     * @example ../examples/intermediate/Text.php
     * @param mixed $value Text string or array of texts to add
     *  Values array: 'b' (single),
     * 'cell_color' (ffffff, ff0000...),
     *  'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...),
     * 'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off),
     * 'sz' (1, 2, 3...), 'text'
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off), 'wordWrap' (on, off)
     * @param array $style Style of text
     *  Values: 'b' (single), 'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...), 'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off),
     * 'sz' (1, 2, 3...),
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off), 'wordWrap' (on, off)
     */
    public function addText($value, $style = '') {
        /*if(is_array($value)){
            foreach($value as $ind => $val){
                $this->addText($val['text'], $val);
            }
        }else{*/
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        if($style['font']){
            $FontSize = $this->_pdf->getFontSize();
            $FontFamily = $this->_pdf->getFontFamily();
            $FontStyle = $this->_pdf->getFontStyle();
            $this->_pdf->SetFont($style['font'], '', 14);
            //var_dump('OLDDDD tipo_fuente : '. $FontFamily.'|'.$FontSize.'|' . $FontStyle, 'NUEVO tipo_fuente : '. $style['font']);
        }
        //var_dump($this->_pdf->getFontFamily(), $this->_pdf->getFontStyle(), $this->_pdf->getFontSize());
        $text = CreateTextPdf::getInstance();
        $text->createText($value, $style);
        self::$log->info('Add text to word document.');
        //var_dump((string) $text);
        if($this->_creacion)
            $this->_document .= (string) $text;
        else
            $this->_pdf->writeHTML((string) $text);
        if($style['font']){
            $this->_pdf->SetFont($FontFamily, $FontStyle, (isset($FontSize) && $FontSize > 1?$FontSize:8));
        }
        //}
    }
    /**
     * Add a title
     *
     * @access public
     * @example ../examples/easy/Title.php
     * @param string $text Text to add
     * @param array $style Style of title
     *  Values: 'b' (single),
     *  'color' (ffffff, ff0000...),
     *  'font' (Arial, Times New Roman...),
     *  'i' (single),
     *  'jc' (both, center, distribute, left, right),
     *  'pageBreakBefore' (on, off),
     *  'sz' (1, 2, 3...),
     *  'u' (dash, dotted, double, single, wave, words),
     *  'widowControl' (on, off),
     *  'wordWrap' (on, off)
     */
    public function addTitle($text, $style = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        if($this->_bToc){
            $this->_pdf->Bookmark($text, $style['val'], 0);
        }
        $title = CreateTextPdf::getInstance();
        $title->createTitle($text, $style);
        self::$log->info('Add title to pdf document.');
        if($this->_creacion)
            $this->_document .= (string) $title;
        else
            $this->_pdf->writeHTML((string) $title);
    }


    /**
     * Add a break
     *
     * @access public
     * @example ../examples/easy/PageBreak.php
     * @param string $type Break type.
     *  Values: 'line', 'page'
     */
    public function addBreak($type = '')
    {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        self::$log->info('Add break to pdf document.');
        $out = '';
        if($type == '' || $type == 'line'){
            if($this->_creacion){
                $this->_document .= '<br/>';
            }else{
                $this->_pdf->writeHTML('<br/>');
            }
        }else{
            $this->_pdf->AddPage();
        }       
    }
    
    /**
     * Add a section
     *
     * @access public
     * @example ../examples/easy/Section.php
     * @param array $data Parameters to use
     *  Values: 'bottom' (4000, 4001...), 'left' (4000, 4001...),
     *  'orient' (landscape), 'right' (4000, 4001), 'titlePage' (1),
     *  'top' (4000, 4001)
     */
    public function addSection($data = '')
    {
        $this->_iniziate = true;
        self::$log->info('Add section to word document.');
        if(!empty($data['orient']) && $data['orient'] == 'landscape')
            $orient = 'L';
        else
            $orient = 'P';
        $this->_pdf->AddPage($orient);
        if($data['top'] && is_numeric($data['top']))
            $this->_pdf->SetTopMargin($data['top']);
        if($data['left'] && is_numeric($data['left']))
            $this->_pdf->SetLeftMargin($data['left']);
        if($data['right'] && is_numeric($data['right']))
            $this->_pdf->SetRightMargin($data['right']);
        /*$this->_pdf->SetTopMargin(0);
        $this->_pdf->SetLeftMargin(0);
        $this->_pdf->SetRightMargin(0);*/
    }

    /**
     * Add a header.
     *
     * @access public
     * @example ../examples/easy/Header.php
     * @example ../examples/intermediate/HeaderAndFooter.php
     * @param string $text Text to add
     * @param array $paramsHeader Parameters of header
     *  Values: 'border'(1, 2, 3...),
     *  'borderDiscontinuous' (0, 1),
     *  'font' (Arial, Times New Roman...),
     *  'jc' (center, left, right),
     *  'name',
     *  'scaling' (50, 100),
     *  'sizeX' (10, 11, 12...), 'sizeY'
     *  (10, 11, 12...), spacingTop (10, 11...), spacingBottom (10, 11...),
     *  spacingLeft (10, 11...), spacingRight (10, 11...), 'textWrap'
     *  (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom),
     *  5 (clear))
     */
    public function addHeader($text = 'Header', $paramsHeader = '') {
        $header = CreateHeaderPdf::getInstance();
        $header->createHeader($text, $paramsHeader);
        self::$log->info('Add text header to header document.');
        if(!empty($paramsHeader['name'])) {
            $img = CreateImagePdf::getInstance();
            $parametros = $img->createImage2($paramsHeader, true);
            $parametros['y'] = 5;
            self::$log->info('Create image header.');
            $this->_pdf->setImgHeader($parametros);
        }else{
            $this->_pdf->setImgHeader(array());
            self::$log->info('Set image header to null.');
        }
        $this->_header .= (string) $header;
        $this->_pdf->setStrHeader((string) $header);
    }

    /**
     * Add a footer
     *
     * @access public
     * @param string $dat Text to add
     * @param array $paramsFooter Parameters of footer
     *  Values: 'name', 'pager' (true, false),
     *  'pagerAlignment' (left, right, false)
     */
    public function addFooter($dat = '', $paramsFooter = '') {

        $footer = CreateFooterPdf::getInstance();
        $footer->createFooter($dat, $paramsFooter);
        self::$log->info('Add footer to footer document.');
        if(!empty($paramsFooter['name'])) {
            $img = CreateImagePdf::getInstance();
            $parametros = $img->createImage2($paramsFooter, true);
            $parametros['y'] = 280;
            self::$log->info('Create image header.');
            //var_dump($parametros);
            $this->_pdf->setImgFooter($parametros);
        }
        //var_dump((string) $footer);
        $this->_footer = (string) $footer;
        if($paramsFooter['pager'] == true) {
            $this->_pdf->setPageFooter(true);
            if(in_array($paramsFooter['pagerAlignment'], array('center', 'left', 'right')))
                $this->_pdf->setPageAlignFooter($paramsFooter['pagerAlignment']);
        }
        $this->_pdf->setStrFooter((string) $footer);
    }


    /**
     * Add a table.
     *
     * @access public
     * @example ../examples/easy/Table.php
     * @example ../examples/intermediate/Table.php
     * @param array $dats Values to add
     * @param array $parameters Parameters to use
     *  Values: 'border' (none, single, double),
     *  'border_color' (ffffff, ff0000),
     *  'border_spacing' (0, 1, 2...),
     *  'border_sz' (10, 11...),
     *  'font' (Arial, Times New Roman...),
     *  'jc' (center, left, right),
     *  'size_col' (1200, 1300...),
     *  'TBLSTYLEval' (Cuadrculamedia3-nfasis1, Sombreadomedio1,
     *  Tablaconcuadrcula, TableGrid)
     */
    public function addTable($dats, $parameters = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        $table = CreateTablePdf::getInstance();
        $table->createTable($dats, $parameters);
        self::$log->info('Add table to word document.');
        if($this->_creacion)
            $this->_document .= (string) $table;
        else
            $this->_pdf->writeHTML((string) $table);
    }

    /**
     * Add a link
     *
     * @access public
     * @example ../examples/easy/Link.php
     * @param string $text Text to use as link
     * @param string $link URL link
     * @param string $font Type of font
     *  Values: 'Arial', 'Times New Roman'...
     *
     */
    public function addLink($text = '', $textLink = '', $font = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        $link = CreateLinkPdf::getInstance();
        $link->createLink($text, $textLink, $font);
        self::$log->info('Add link to word document.');
        $this->_pdf->writeHTML((string) $table);

        if($this->_creacion)
            $this->_document .= (string) $link;
        else
            $this->_pdf->writeHTML((string) $link);
    }

    /**
     * Add a list
     *
     * @access public
     * @example ../examples/easy/List.php
     * @example ../examples/intermediate/List.php
     * @example ../examples/intermediate/List_nested.php
     * @param array $dats Values of the list
     * @param array $paramsList Parameters to use
     *  Values: 'font' (Arial, Times New Roman...),
     *  'val' (0 (clear, 1 (inordinate), 2(numerical))
     *
     */
    public function addList($dats, $paramsList = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        $list = CreateListPdf::getInstance();
        $list->createList($dats, $paramsList);
        self::$log->info('Add list to word document.');
        //var_dump((string) $list);
        if($this->_creacion)
            $this->_document .= (string) $list;
        else
            $this->_pdf->writeHTML((string) $list);
    }

    /**
     * Add an image
     *
     * @access public
     * @example ../examples/easy/Image.php
     * @param array $dats Image to add and paramaters to use
     *  Values: 'border'(1, 2, 3...),
     *  'borderDiscontinuous' (0, 1),
     *  'jc' (center, left, right),
     *  'name', 'scaling' (50, 100),
     *  'sizeX' (10, 11, 12...),
     *  'sizeY' (10, 11, 12...),
     *  spacingTop (10, 11...),
     *  spacingBottom (10, 11...),
     *  spacingLeft (10, 11...),
     *  spacingRight (10, 11...),
     *  'textWrap' (0 (inline), 1 (square), 2 (front), 3 (back), 4 (up and bottom), 5 (clear))
     */

    public function addImage2($dats = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        self::$log->info('Create image2.');
        if(strpos(strtolower($dats['name']), '.svg') !== false){
            self::$log->info('Create image2 SVG.');
            $this->ImagenesSVG($dats['name'], $this->_pdf->GetX(), $this->_pdf->GetY(), (!empty($dats['sizeX']) && is_numeric($dats['sizeX'])?$dats['sizeX']:0),(!empty($dats['sizeY']) && is_numeric($dats['sizeY'])?$dats['sizeY']:0), '', $dats['jc'], '', $dats['border'], true);
            $this->_pdf->Ln($dats['sizeY']);
        }else{
            $img = CreateImagePdf::getInstance();
            $parametros = $img->createImage2($dats);
            $this->_pdf->image($parametros['name'], (!empty($parametros['x'])?$parametros['x']:$this->_pdf->GetX()), (!empty($parametros['y'])?$parametros['y']:$this->_pdf->GetY()), $parametros['w'], $parametros['h']);
        }         
    }

    public function addImage($dats = '') {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }

        self::$log->info('Create image.');
        //echo((string) $img);
        if(strpos(strtolower($dats['name']), '.svg') !== false){
            self::$log->info('Create image SVG.');
            $this->ImagenesSVG($dats['name'], $this->_pdf->GetX(), $this->_pdf->GetY(), (!empty($dats['sizeX']) && is_numeric($dats['sizeX'])?$dats['sizeX']:0),(!empty($dats['sizeY']) && is_numeric($dats['sizeY'])?$dats['sizeY']:0), '', $dats['jc'], '', $dats['border'], true);
            $this->_pdf->Ln($dats['sizeY']);
        }else{
            $img = CreateImagePdf::getInstance();
            $img->createImage($dats);        
            if($this->_creacion) {
                $this->_document .= (string) $img;
            }else {
                $this->_pdf->writeHTML((string) $img);
            }
        }        
    }

    /**
     * Add a graphic image
     *
     * @access public
     * @example ../examples/easy/Chart.php
     * @example ../examples/intermediate/Chart.php
     * @param array $dats Parameters of graphic
     * Values: 'color' (1, 2, 3, 4, 5),
     * 'cornerX' (20, 30...),
     * 'data' (array of values), 'showPercent' (0, 1), 'sizeX' (10, 11, 12...),
     * 'sizeY' (10, 11, 12...), 'title',  'type' (barChart, bar3DChart,
     * colChart, col3DChart, pieChart, pie3DChart)
     *
     *
     *   Values:
     *  'color' (1, 2, 3...),
     *  'cornerP' (20, 30...),
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
     *  'type' (barChart, bar3DChart, colChart, col3DChart, pieChart, pie3DChart)
     */
    public function addGraphic($dats){
        $this->addChart($dats);
    }

    public function addChart($dats)
    {
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        self::$log->debug('Create image chart.');
        try {
            if (isset($dats['data']) && isset($dats['type'])) {
            	$graphic = CreateGraphicPdf::getInstance();
                $sImgGraphic = $graphic->createGraphic($this->getTemporaryDirectory(), $dats);
                $this->ImagenesSVG($sImgGraphic, -1, -1, $dats['sizeX'], $dats['sizeY'], '', (in_array(strtolower($dats['jc']), array('center', 'left', 'right'))?strtoupper(substr($dats['jc'],0,1)):''), '', 0, true);
                $this->_pdf->Ln($dats['sizeY']);
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


    public function AddPage($nombrePdf = 'prueba.pdf') {
        $this->_pdf->AddPage();
    }

    /**
     * Add an element to embedd in other elements
     *
     * @access public
     * @example ../examples/intermediate/List.php
     * @param string $type Element type.
     *  Values: 'addImage', 'addGraphic', 'addLink', 'addTextBox'
     * @param array $parrams Set of parameters.
     *  Values: same as elements
     * @return Element object
     */
    public function addElement($type, $params = '') {
        $tipo = str_replace('add', 'Create', $type).'Pdf';
        //var_dump($tipo);
        $element = CreateFactory::createObject($tipo);
        //var_dump($element);
        $type = str_replace('add', 'init', $type);
        //var_dump($type);
        $element->$type($params);
        if (in_array($type, array('initImage', 'initGraphic'))) {
            self::$log->debug('Create embedded image.');
        }
        if ($type == 'initGraphic') {
            if(!$this->_tempDirExits){
                self::$log->debug('Create a temporaly folder.');
                $this->_tempDirExits = true;
                mkdir(getcwd(). '/tmp');
                chmod(getcwd(). '/tmp', 0775);
                $this->_tempDirExits = true;
            }
            self::$log->debug('Create embedded chart image.');
            $element->setName($this->getTemporaryDirectory());
        }
        
        return $element;
    }

    public function createPdf($nombrePdf = 'prueba.pdf') {
        //if($this->_tempDirExits)
        if(file_exists(getcwd(). '/tmp')){
                $this->rmdirtree(getcwd(). '/tmp');
        }
        if($this->_bToc){
            $this->_pdf->addTOCPage();
            $this->_pdf->SetFont('times', 'B', 16);
            $this->_pdf->MultiCell(0, 0, 'Table Of Content', 0, 'C', 0, 1, '', '', true, 0);
            $this->_pdf->Ln();
            $this->_pdf->SetFont('helvetica', '', 10);
            $bookmark_templates = array();
            $bookmark_templates[0] = '<table border="0" cellpadding="0" cellspacing="0" style="background-color:#EEFAFF"><tr><td width="155mm"><span style="font-family:times;font-weight:bold;font-size:12pt;color:black;">#TOC_DESCRIPTION#</span></td><td width="25mm"><span style="font-family:courier;font-weight:bold;font-size:12pt;color:black;" align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>';
            $bookmark_templates[1] = '<table border="0" cellpadding="0" cellspacing="0"><tr><td width="5mm">&nbsp;</td><td width="150mm"><span style="font-family:times;font-size:11pt;color:green;">#TOC_DESCRIPTION#</span></td><td width="25mm"><span style="font-family:courier;font-weight:bold;font-size:11pt;color:green;" align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>';
            $bookmark_templates[2] = '<table border="0" cellpadding="0" cellspacing="0"><tr><td width="10mm">&nbsp;</td><td width="145mm"><span style="font-family:times;font-size:10pt;color:#666666;"><i>#TOC_DESCRIPTION#</i></span></td><td width="25mm"><span style="font-family:courier;font-weight:bold;font-size:10pt;color:#666666;" align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>';

            $this->_pdf->addHTMLTOC($page=1, $toc_name='INDEX', $bookmark_templates, $correct_align=true);
            // end of TOC page
            $this->_pdf->endTOCPage();
        }
        if($this->_creacion) {
            $this->_pdf->AddPage();
            $this->_pdf->writeHTML($this->_document);
            $this->_pdf->Output($nombrePdf , 'I');
        }else {
            $this->_pdf->Output($nombrePdf , 'I');
        }
        
    }

    private function ImagenesSVG($file, $x=-1, $y=-1, $w, $h, $link, $align, $palign, $border, $fitonpage){
        if($x == -1)
            $x = $this->_pdf->GetX();
        if($y == -1)
            $y = $this->_pdf->GetY();
        $this->_pdf->ImageSVG($file, $x, $y, $w, $h, $link, $align, $palign, $border, $fitonpage);
    }


    function rmdirtree($dirname) {
       
       if (is_dir($dirname)) {    //Operate on dirs only
           $result=array();
           if (substr($dirname,-1)!='/') {$dirname.='/';}    //Append slash if necessary
           $handle = opendir($dirname);
           while (false !== ($file = readdir($handle))) {
               if ($file!='.' && $file!= '..' && $file != '.svn') {    //Ignore . and ..
                   $path = $dirname.$file;
                   if (is_dir($path)) {    //Recurse if subdir, Delete if file
                       $result=array_merge($result,rmdirtree($path));
                   }else{
                       unlink($path);
                       $result[].=$path;
                   }
               }
           }
           closedir($handle);
           rmdir($dirname);    //Remove dir
           return $result;    //Return array of deleted items
       }else{
           return false;    //Return false if attempting to operate on a file
       }
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
     * To add support of sys_get_temp_dir for PHP versions under 5.2.1
     *
     * @access private
     * @return string
     */
    private function getTempDir() {
        if ( !function_exists('sys_get_temp_dir')) {
            function sys_get_temp_dir() {
                if( $temp=getenv('TMP') ) {
                        return $temp;
                }
                if( $temp=getenv('TEMP') ) {
                        return $temp;
                }
                if( $temp=getenv('TMPDIR') ) {
                        return $temp;
                }
                $temp=tempnam(__FILE__,'');
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
     * Add a table of contents (TOC)
     *
     * @access public
     * @example ../examples/easy/TableContents.php
     * @param string $font Set font type
     *  Values: 'Arial', 'Times New Roman'...
     */
    public function addTableContents()
    {
        $this->_bToc = true;
    }

    /**
     * Add a new font
     *
     * @access public
     * @param array $fonts Fonts to add
     * $family, $style, $size
     */
    public function addFont($fonts)
    {
        self::$log->info('change font to '.$args[0]['family'].'.');
        $args = func_get_args();
        
        if(in_array($args[0]['family'], array('times', 'timesb', 'timesi', 'timesbi', 'helvetica', 'helveticab', 'helveticai', 'helveticabi', 'courier', 'courierb', 'courieri', 'courierbi', 'symbol', 'zapfdingbats')))
            $family = $args[0]['family'];
        else
            return ;
        if(in_array($args[0]['style'], array('', 'B', 'I', 'U', 'D', 'O')))
            $style = $args[0]['style'];
        else{
            $this->_pdf->SetFont($family);
            return;
        }
        if(!empty($args[0]['size']) && is_numeric($args[0]['size'])){
            $size = $args[0]['size'];
            $this->_pdf->SetFont($family, $style, $size);
        }else
            $this->_pdf->SetFont($family, $style);
    }

    /**
     * Add a footnote
     *
     * @access public
     * @example ../examples/easy/Footnote.php
     * @example ../examples/intermediate/ChartFootnote.php
     * @example ../examples/intermediate/EndnoteAndFootnote.php
     * @param array $paramsFootnote Parameters of footnote
     *  Values: 'font', 'textDocument', 'textEndNote'
     */
    public function addFootnote($paramsFootnote = '')
    {
        //$footnote = CreateFootnotePdf::getInstance();
        /*$text = CreateTextPdf::getInstance();
            $text->createText($value, $style);*/
        if(!$this->_iniziate) {
            $this->_iniziate = true;
            $this->_pdf->AddPage();
        }
        if($this->_creacion)
            $this->_document .= $paramsFootnote['textDocument'] . '<span style="font-size:0.5em; vertical-align:top">('.$this->_idFooter.')</span>';
        else
            $this->_pdf->writeHTML($paramsFootnote['textDocument'] . '<span style="font-size:0.5em; vertical-align:top">('.$this->_idFooter.')</span>');
        
        self::$log->info('Add footnote to pdf document.');
        $this->_pdf->setFootNote('<span style="font-size:0.5em; vertical-align:top">('.$this->_idFooter++.')</span>' . $paramsFootnote['textEndNote']);
    }
}
