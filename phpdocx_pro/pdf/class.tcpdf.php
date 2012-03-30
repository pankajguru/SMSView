<?php
include_once('tcpdf/tcpdf.php');

class MYPDF  extends TCPDF{
    private $bPageFooter;
    private $sPageAlignFooter;
    private $sFooter;
    private $iFooterFont;
    private $iFooterFontSize;
    private $imgHeader;
    private $imgFooter;
    private $textHeader;
    private $sFootNote;

    function __construct() {
        $this->bPageFooter = false;
        $this->sPageAlignFooter = 'right';
        $this->imgHeader = array();
        $this->imgFooter = array();
        $this->sFootNote = array();
        $this->textHeader = '';
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->SetCompression(FALSE);
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor("2dmc");
        $this->SetTitle("Lines");
        $this->SetSubject("TCPDF");
        $this->SetKeywords("line");
        //$this->SetHeaderData('../../../img/FGCSIC_marca_H.jpg' , 20, '    '.$oConv->title . ' Proyectos Cero', '    Convocatoria');
        //$this->setPrintFooter(false);
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array('', '', 10));
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(15);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$this->setLanguageArray($l);
        $this->AliasNbPages();
        //$this->AddPage();
        //$this->sFooter  = 'COPYRIGHT � 2009 FUNDACI&Oacute;N GENERAL CSIC. TODOS LOS DERECHOS RESERVADOS';
        $this->iFooterFont = PDF_FONT_NAME_MAIN ;
        $this->iFooterFontSize = 7 ;
    }

    public function setStrHeader($sHeader){
        $this->sHeader = $sHeader;
    }

    public function getStrHeader(){
        return $this->sHeader;
    }

    public function setImgHeader($imgHeader){
        $this->imgHeader = $imgHeader;
    }

    public function getImgHeader(){
        return $this->imgHeader;
    }

    public function setImgFooter($imgFooter){
        $this->imgFooter = $imgFooter;
    }

    public function getImgFooter(){
        return $this->imgFooter;
    }

    public function setFootNote($sFootNote){
        $this->sFootNote[] = $sFootNote;
    }

    public function getFootNote(){
        return $this->sFootNote;
    }

    public function setStrFooter($sFooter){
        $this->sFooter = $sFooter;
    }

    public function getStrFooter(){
        return $this->sFooter;
    }

    public function setStrFooterFont($sFooterFont){
        $this->sFooterFont = $sFooterFont;
    }

    public function getStrFooterFont(){
        return $this->sFooterFont;
    }

    public function setIntFooterFontSize($iFooterFontSize){
        $this->iFooterFontSize = $iFooterFontSize;
    }

    public function getIntFooterFontSize(){
        return $this->iFooterFontSize;
    }

    public function setPageFooter($bPageFooter){
        $this->bPageFooter = $bPageFooter;
    }

    public function getPageFooter(){
        return $this->bPageFooter;
    }
    
    public function setPageAlignFooter($sPageAlignFooter){
        $this->sPageAlignFooter = $sPageAlignFooter;
    }

    public function getPageAlignFooter(){
        return $this->sPageAlignFooter;
    }    
/*

    function Header(){
        //list($r, $b, $g) = $this->xheadercolor;
        $this->setY(0); // shouldn't be needed due to page margin, but helas, otherwise it's at the page top
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0 , 0, 0);
        $this->Cell(0,10, $this->textHeader, 0,1,'C', 1);
        $this->Text(15,16,$this->text2Header .'<hr>');
    }*/

    public function Header() {
        if(!empty($this->imgHeader['name']) ){
            //var_dump(getcwd() . '/' . $this->imgHeader['name']);
            $this->Image(getcwd() . '/' . $this->imgHeader['name'], $this->imgHeader['x'], $this->imgHeader['y'], $this->imgHeader['w'], $this->imgHeader['h']);
            
        }
        $this->SetFont('helvetica', 'B', 11);
	$this->SetTextColor(200 , 200, 200);
        $this->writeHTML($this->sHeader);
        $this->setY(25);
        if(!empty($this->sHeader) || !empty($this->imgHeader['name']))
            $this->writeHTML('<hr>', true, 0, true, 0);
        $this->ln(20);
    }

    /**
    * Overwrites the default footer
    * set the text in the view using
    * $fpdf->xfootertext = 'Copyright � %d YOUR ORGANIZATION. All rights reserved.';
    */
    function Footer(){
        $aux = $this->getFootNote();
        $num = -6;
        $tam = (-10+ (2*count($aux))) + (-6 * count($aux)) + ($this->bPageFooter?-5:0) + (!empty($this->sFooter)?-5:0);
        $this->SetY($tam);
        $this->writeHTML('<hr>', true);
        if(!empty($aux)){
            foreach($aux as $ind => $val)
                $this->writeHTML($val, true);
            $this->sFootNote = array();
        }
        
        if(!empty($this->imgFooter['name']) ){
            //var_dump($this->imgFooter);
            //var_dump(getcwd() . '/' . $this->imgHeader['name']);
            $this->Image(getcwd() . '/' . $this->imgFooter['name'], $this->imgFooter['x'], $this->imgFooter['y'], $this->imgFooter['w'], $this->imgFooter['h']);

        }
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->iFooterFont,'',$this->iFooterFontSize);
	$footer = '';
        if($this->bPageFooter)
            $footer = '<span style="text-align: '.$this->sPageAlignFooter.';">P&aacute;gina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages() .'</span><br/><br/>';
        if($this->sFooter)
            $footer .= $this->sFooter;
            //$footer .= '<span style="font-size:20px">' . $this->sFooter . '</span>';
        //var_dump($footer);
        $this->writeHTML($footer, true);
        //$this->MultiCell(0,8,  $footer,'','C',0,1, 20,300 + $tam,true,0, true);
    }
    
    function ImageSVG($file, $x, $y, $w, $h, $link, $align, $palign, $border, $fitonpage){
        //$this->AddPage();
        parent::ImageSVG($file, $x, $y, $w, $h, $link, $align, $palign, $border, $fitonpage);
    }
    //$docx->ImageSVG($file='../../files/img/pieChart.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);
}
?>