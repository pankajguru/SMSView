<?php

/**
 * Embedd HTML as WordML
 *
 * @category   Phpdocx
 * @package    transform
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    2011.19.03
 * @link       http://www.phpdocx.com
 * @since      File available since Release 2.5
 */
class HTML2WordML {

    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $colors;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $linkImages;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $linkTargets;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $borders;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $borderStyles;
    /**
     *
     * @access public
     * @static
     * @var boolean
     */
    public static $openLinks;
    /**
     *
     * @access public
     * @static
     * @var boolean
     */
    public static $openPs;
    /**
     *
     * @access public
     * @static
     * @var integer
     */
    public static $openTable;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $openTags;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $tableGrid;
    /**
     *
     * @access public
     * @static
     * @var array
     */
    public static $text_align;
    /**
     *
     * @access public
     * @static
     * @var string
     */
    public static $WordML; 
    

    /**
     * Class constructor
     */
    public function __construct() {
        self::$openTags = array();
        self::$openPs = false;
        self::$tableGrid = array();
        self::$openTable = 0;
        self::$openLinks = false;
        self::$WordML = '';
        self::$linkTargets = array();
        self::$linkImages = array();
        self::$borders = array('top', 'left', 'bottom', 'right');
        self::$colors = array(
            'AliceBlue' => 'F0F8FF',
            'AntiqueWhite' => 'FAEBD7',
            'Aqua' => '00FFFF',
            'Aquamarine' => '7FFFD4',
            'Azure' => 'F0FFFF',
            'Beige' => 'F5F5DC',
            'Bisque' => 'FFE4C4',
            'Black' => '000000',
            'BlanchedAlmond' => 'FFEBCD',
            'Blue' => '0000FF',
            'BlueViolet' => '8A2BE2',
            'Brown' => 'A52A2A',
            'BurlyWood' => 'DEB887',
            'CadetBlue' => '5F9EA0',
            'Chartreuse' => '7FFF00',
            'Chocolate' => 'D2691E',
            'Coral' => 'FF7F50',
            'CornflowerBlue' => '6495ED',
            'Cornsilk' => 'FFF8DC',
            'Crimson' => 'DC143C',
            'Cyan' => '00FFFF',
            'DarkBlue' => '00008B',
            'DarkCyan' => '008B8B',
            'DarkGoldenRod' => 'B8860B',
            'DarkGray' => 'A9A9A9',
            'DarkGrey' => 'A9A9A9',
            'DarkGreen' => '006400',
            'DarkKhaki' => 'BDB76B',
            'DarkMagenta' => '8B008B',
            'DarkOliveGreen' => '556B2F',
            'Darkorange' => 'FF8C00',
            'DarkOrchid' => '9932CC',
            'DarkRed' => '8B0000',
            'DarkSalmon' => 'E9967A',
            'DarkSeaGreen' => '8FBC8F',
            'DarkSlateBlue' => '483D8B',
            'DarkSlateGray' => '2F4F4F',
            'DarkSlateGrey' => '2F4F4F',
            'DarkTurquoise' => '00CED1',
            'DarkViolet' => '9400D3',
            'DeepPink' => 'FF1493',
            'DeepSkyBlue' => '00BFFF',
            'DimGray' => '696969',
            'DimGrey' => '696969',
            'DodgerBlue' => '1E90FF',
            'FireBrick' => 'B22222',
            'FloralWhite' => 'FFFAF0',
            'ForestGreen' => '228B22',
            'Fuchsia' => 'FF00FF',
            'Gainsboro' => 'DCDCDC',
            'GhostWhite' => 'F8F8FF',
            'Gold' => 'FFD700',
            'GoldenRod' => 'DAA520',
            'Gray' => '808080',
            'Grey' => '808080',
            'Green' => '008000',
            'GreenYellow' => 'ADFF2F',
            'HoneyDew' => 'F0FFF0',
            'HotPink' => 'FF69B4',
            'IndianRed' => 'CD5C5C',
            'Indigo' => '4B0082',
            'Ivory' => 'FFFFF0',
            'Khaki' => 'F0E68C',
            'Lavender' => 'E6E6FA',
            'LavenderBlush' => 'FFF0F5',
            'LawnGreen' => '7CFC00',
            'LemonChiffon' => 'FFFACD',
            'LightBlue' => 'ADD8E6',
            'LightCoral' => 'F08080',
            'LightCyan' => 'E0FFFF',
            'LightGoldenRodYellow' => 'FAFAD2',
            'LightGray' => 'D3D3D3',
            'LightGrey' => 'D3D3D3',
            'LightGreen' => '90EE90',
            'LightPink' => 'FFB6C1',
            'LightSalmon' => 'FFA07A',
            'LightSeaGreen' => '20B2AA',
            'LightSkyBlue' => '87CEFA',
            'LightSlateGray' => '778899',
            'LightSlateGrey' => '778899',
            'LightSteelBlue' => 'B0C4DE',
            'LightYellow' => 'FFFFE0',
            'Lime' => '00FF00',
            'LimeGreen' => '32CD32',
            'Linen' => 'FAF0E6',
            'Magenta' => 'FF00FF',
            'Maroon' => '800000',
            'MediumAquaMarine' => '66CDAA',
            'MediumBlue' => '0000CD',
            'MediumOrchid' => 'BA55D3',
            'MediumPurple' => '9370D8',
            'MediumSeaGreen' => '3CB371',
            'MediumSlateBlue' => '7B68EE',
            'MediumSpringGreen' => '00FA9A',
            'MediumTurquoise' => '48D1CC',
            'MediumVioletRed' => 'C71585',
            'MidnightBlue' => '191970',
            'MintCream' => 'F5FFFA',
            'MistyRose' => 'FFE4E1',
            'Moccasin' => 'FFE4B5',
            'NavajoWhite' => 'FFDEAD',
            'Navy' => '000080',
            'OldLace' => 'FDF5E6',
            'Olive' => '808000',
            'OliveDrab' => '6B8E23',
            'Orange' => 'FFA500',
            'OrangeRed' => 'FF4500',
            'Orchid' => 'DA70D6',
            'PaleGoldenRod' => 'EEE8AA',
            'PaleGreen' => '98FB98',
            'PaleTurquoise' => 'AFEEEE',
            'PaleVioletRed' => 'D87093',
            'PapayaWhip' => 'FFEFD5',
            'PeachPuff' => 'FFDAB9',
            'Peru' => 'CD853F',
            'Pink' => 'FFC0CB',
            'Plum' => 'DDA0DD',
            'PowderBlue' => 'B0E0E6',
            'Purple' => '800080',
            'Red' => 'FF0000',
            'RosyBrown' => 'BC8F8F',
            'RoyalBlue' => '4169E1',
            'SaddleBrown' => '8B4513',
            'Salmon' => 'FA8072',
            'SandyBrown' => 'F4A460',
            'SeaGreen' => '2E8B57',
            'SeaShell' => 'FFF5EE',
            'Sienna' => 'A0522D',
            'Silver' => 'C0C0C0',
            'SkyBlue' => '87CEEB',
            'SlateBlue' => '6A5ACD',
            'SlateGray' => '708090',
            'SlateGrey' => '708090',
            'Snow' => 'FFFAFA',
            'SpringGreen' => '00FF7F',
            'SteelBlue' => '4682B4',
            'Tan' => 'D2B48C',
            'Teal' => '008080',
            'Thistle' => 'D8BFD8',
            'Tomato' => 'FF6347',
            'Turquoise' => '40E0D0',
            'Violet' => 'EE82EE',
            'Wheat' => 'F5DEB3',
            'White' => 'FFFFFF',
            'WhiteSmoke' => 'F5F5F5',
            'Yellow' => 'FFFF00',
            'YellowGreen' => '9ACD32'
        );

        self::$borderStyles = array(
            'none' => 'nil',
            'dotted' => 'dotted',
            'dashed' => 'dashed',
            'solid' => 'single',
            'double' => 'double',
            'groove' => 'threeDEngrave',
            'ridge' => 'single', //threeDEmboss: we have overriden this border style that is the one by default in HTML tables
            'inset' => 'inset',
            'outset' => 'outset'
        );

        self::$text_align = array(
            'left' => 'left',
            'center' => 'center',
            'right' => 'right',
            'justify' => 'both'
        );

        $this->isFile = false;
        $this->baseURL = '';
        $this->context = '';
        $this->parseDivsAsPs = false;
        $this->tableStyle = '';
        $this->paragraphStyle = '';
        $this->downloadImages = false;
    }

    /**
     * Class destructor
     */
    public function __destruct() {
        
    }

    
    
    /**
     * This is the function that launches the HTML parsing
     *
     * @access public
     * @param string $html
     * @param array $options
     * @param string $id
     * @return array
     */
    public function render($html, $options) {
        if (isset($options['isFile'])) {
            $this->isFile = $options['isFile'];
        }
        if (isset($options['baseURL'])) {
            $this->baseURL = $options['baseURL'];
        } else if (isset($options['isFile'])) {
            if ($html[strlen($html) - 1] == '/') {
                $this->baseURL = $html;
            } else {
                $parsedURL = parse_url($html);
                $pathParts = explode('/', $parsedURL['path']);
                $last = array_pop($pathParts);
                if (strpos($last, '.') > 0) {
                    //Do nothing
                } else {
                    $pathParts[] = $last;
                }
                $newPath = implode('/', $pathParts);
                $this->baseURL = $parsedURL['scheme'] . '://' . $parsedURL['host'] . $newPath . '/';
            }
        }
        if (isset($options['context'])) {
            $this->context = $options['context'];
        }
        if (isset($options['parseDivsAsPs'])) {
            $this->parseDivsAsPs = $options['parseDivsAsPs'];
        }
        if (isset($options['tableStyle'])) {
            $this->tableStyle = $options['tableStyle'];
        }
        if (isset($options['paragraphStyle'])) {
            $this->paragraphStyle = $options['paragraphStyle'];
        }
        if (isset($options['downloadImages'])) {
            $this->downloadImages = $options['downloadImages'];
        }

        $id = isset($options['id']) ? $options['id'] : '*';

        $dompdfTree = $this->renderDompdf($html, $this->isFile, $id);
        $this->_render($dompdfTree);
       
        return(array(self::$WordML, self::$linkTargets, self::$linkImages));
    }
    
    /**
     * Get the HTML DOM tree from DOMPDF
     *
     * @access private
     * @param string $html
     * @param boolean $isFile
     * @param string $id
     * @return array
     */
    private function renderDompdf($html, $isFile = false, $id = '*') {
        require_once(DIR_DOMPDF . "/dompdf_config.inc.php");
        require_once(DOMPDF_INC_DIR . '/dompdf_treeOut.php');
        $dompdf = new dompdf_treeOut();
        $aTemp = $dompdf->getDompdfTree($html, $isFile, $id);
        return($aTemp);
    }

    /**
     * This function renders the HTML DOM elements recursively
     *
     * @access private
     * @param array $nodo
     * @param integer $depth
     * @return array
     */
    private function _render($nodo, $depth=0) {

        $this->_level = $depth;
        $properties = isset($nodo['properties']) ? $nodo['properties'] : array();

        switch ($nodo['nodeName']) {
            case 'div':
                if (!$this->parseDivsAsPs) {
                    self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                    self::$openTags[$depth] = $nodo['nodeName'];
                    break;
                }
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'p':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    if (self::$openLinks) {
                        self::$WordML .= '</w:hyperlink>';
                        self::$openLinks = false;
                    }
                    self::$WordML .= '</w:p><w:p>';
                } else {
                    self::$WordML .= '<w:p>';
                    self::$openPs = true;
                }
                self::$WordML .= $this->generatePPr($properties);
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case 'dl':
            case 'ul':
            case 'ol':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case 'dt':
            case 'dd':
            case 'li':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    if (self::$openLinks) {
                        self::$WordML .= '</w:hyperlink>';
                        self::$openLinks = false;
                    }
                    self::$WordML .= '</w:p><w:p>';
                } else {
                    self::$WordML .= '<w:p>';
                    self::$openPs = true;
                }
                self::$openTags[$depth] = $nodo['nodeName'];
                self::$WordML .= $this->generateListPr($properties);
                break;
            case 'table':
                self::$openTable++;
                self::$tableGrid[self::$openTable] = array();
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    if (self::$openLinks) {
                        self::$WordML .= '</w:hyperlink>';
                        self::$openLinks = false;
                    }
                    self::$WordML .= '</w:p><w:tbl>';
                    self::$openPs = false;
                } else {
                    self::$WordML .= '<w:tbl>';
                }
                self::$WordML .= $this->generateTblPr($properties, $nodo['attributes']['border']);
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case 'tr':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                array_push(self::$tableGrid[self::$openTable], array());
                self::$WordML .= '<w:tr>';
                self::$WordML .= $this->generateTrPr($properties);
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case 'th':
            case 'td':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                $firstRow = $nodo['nodeName'] == 'th' ? true : false;

                //Now we have to deal with posible rowspans coming from previous rows             
                $row = count(self::$tableGrid[self::$openTable]) - 1;
                $column = count(self::$tableGrid[self::$openTable][$row]);
                $this->countEmptyColumns($row, $column);
                
                //Now we have to deal with the current td 
                $colspan = (int) $nodo['attributes']['colspan'];
                $rowspan = (int) $nodo['attributes']['rowspan'];
                self::$WordML .= '<w:tc>';
                for ($k = 0; $k < $colspan; $k++) {
                    array_push(self::$tableGrid[self::$openTable][count(self::$tableGrid[self::$openTable]) - 1], array($rowspan, $colspan - $k));
                }
                self::$WordML .= $this->generateTcPr($properties, $colspan, $rowspan, $firstRow);
                self::$openTags[$depth] = $nodo['nodeName'];

                break;
            case 'a':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                self::$openLinks = true;
                $aId = 'rId' . uniqid(true);
                if (self::$openPs) {
                    self::$WordML .= '<w:hyperlink r:id="' . $aId . '" w:history="1">';
                } else {
                    self::$WordML .= '<w:p><w:hyperlink r:id="' . $aId . '" w:history="1">';
                    self::$openPs = true;
                }
                if ($nodo['attributes']['href'] != '' && $nodo['attributes']['href'][0] != '#') {//FIXME: by the time being we do not parse anchors
                    self::$linkTargets[$aId] = htmlspecialchars($this->parseURL($nodo['attributes']['href']));
                }else{
                    self::$linkTargets[$aId] = '#';
                }
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case '#text':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    self::$WordML .= '<w:r>';
                } else {
                    self::$WordML .= '<w:p>';
                    //self::$WordML .= $this->generatePPr($properties);
                    self::$WordML .= '<w:r>';
                    self::$openPs = true;
                }
                self::$WordML .= $this->generateRPr($properties);
                self::$WordML .= '<w:t xml:space="preserve">' . htmlspecialchars($nodo['nodeValue']) . '</w:t>';
                self::$WordML .= '</w:r>';
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
            case 'br':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    self::$WordML .= '<w:r><w:br /><w:t></w:t></w:r>';
                } else {
                    self::$WordML .= '<w:p />';
                }
                break;
            case 'img': //By the time being we only parse images to avoid breaking the WordML when there are linked images
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                if (self::$openPs) {
                    self::$WordML .= '<w:r>';
                } else {
                    self::$WordML .= '<w:p>';
                    self::$WordML .= '<w:r>';
                    self::$openPs = true;
                }
                self::$WordML .= $this->generateRPr($properties);

                self::$openTags[$depth] = $nodo['nodeName'];
                
                $imgId = 'rId' . uniqid(true);
                $tempId = rand(99999, 99999999);
                $tempName = 'name' . uniqid(true);
                $descrArray = explode('/',$nodo['attributes']['src']);
                $descr = array_pop($descrArray);
                $descr = array_shift(explode('?', $descr));
                $size = getimagesize($this->parseURL($nodo['attributes']['src']));
                $width = $size[0];
                $height = $size[1];
                if (isset($nodo['attributes']['width']) && $nodo['attributes']['width'] > 1) {
                    $cx = $nodo['attributes']['width'] * 7200;
                } else {
                    $cx = $width * 7200;
                }
                if (isset($nodo['attributes']['height']) && $nodo['attributes']['height'] > 1) {
                    $cy = $nodo['attributes']['height'] * 7200;
                } else {
                    $cy = $height * 7200;
                }
                self::$WordML .= '<w:drawing>
                                     <wp:inline distT="0" distB="0" distL="0" distR="0">
                                     <wp:extent cx="' . $cx . '" cy="' . $cy . '" />
                                     <wp:docPr id="' . $tempId . '" name="' . $tempName . '" descr="' . $descr . '" />
                                     <wp:cNvGraphicFramePr>
                                      <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1" />
                                     </wp:cNvGraphicFramePr>
                                         <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                         <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                          <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                           <pic:nvPicPr>
                                            <pic:cNvPr id="0" name="' . $descr . '"/>
                                            <pic:cNvPicPr/>
                                           </pic:nvPicPr>
                                           <pic:blipFill>';
                if ($this->downloadImages) {
                    self::$WordML .= '<a:blip r:embed="' . $imgId . '" cstate="print"/>';
                } else {
                    self::$WordML .= '<a:blip r:link="' . $imgId . '" cstate="print"/>';
                }
                self::$WordML .= '<a:stretch>
                                             <a:fillRect/>
                                            </a:stretch>
                                           </pic:blipFill>
                                           <pic:spPr>
                                            <a:xfrm>
                                             <a:off x="0" y="0"/>
                                             <a:ext cx="' . $cx . '" cy="' . $cy . '" />
                                            </a:xfrm>
                                            <a:prstGeom prst="rect">
                                             <a:avLst/>
                                            </a:prstGeom>
                                            <a:ln w="0">
                                             <a:noFill/>
                                            </a:ln>
                                           </pic:spPr>
                                          </pic:pic>
                                         </a:graphicData>
                                        </a:graphic>
                                    </wp:inline>
                                </w:drawing>
                                ';
                self::$WordML .= '</w:r>';
                self::$linkImages[$imgId] = $this->parseURL($nodo['attributes']['src']);
            case 'close':
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                break;
            default:
                self::$WordML .= $this->closePreviousTags($depth, $nodo['nodeName']);
                self::$openTags[$depth] = $nodo['nodeName'];
                break;
        }
        ++$depth;
        /*
        echo '---------------------------------';
        echo $nodo['nodeName'] . '>>' . $depth;
        var_dump($nodo['attributes']);
        echo '---------------------------------';
        var_dump($properties);
         */

        if (isset($nodo['children'])) {
            foreach ($nodo['children'] as $child) {
                $this->_render($child, $depth);
            }
        }
    }
    
    /**
     * This function takes care that all nodes are properly closed
     *
     * @access private
     * @param integer $depth
     * @param string $currentTag
     */
    private function closePreviousTags($depth, $currentTag = '') {
        $sRet = '';

        $counter = count(self::$openTags);
        for ($j = $counter; $j >= $depth - 1; $j--) {
            $tag = array_pop(self::$openTags);

            switch ($tag) {
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'h6':
                case 'p':
                case 'div':
                    if (!$this->parseDivsAsPs)
                        break;
                case 'dt':
                case 'dd':
                case 'li':
                    if (self::$openPs) {
                        if (self::$openLinks) {
                            $sRet.= '</w:hyperlink>';
                            self::$openLinks = false;
                        }
                        $sRet .= '</w:p>';
                        self::$openPs = false;
                    }
                    break;
                case 'table':
                    if (self::$openPs) {
                        if (self::$openLinks) {
                            $sRet.= '</w:hyperlink>';
                            self::$openLinks = false;
                        }
                        $sRet .= '</w:p></w:tbl>';
                        self::$openPs = false;
                    } else {
                        if (self::$openTable > 1) {
                            //This is to fix a Word bug that does not allow to close a table and write just after a </w:tc>
                            $sRet .= '</w:tbl><w:p />';
                        } else {
                            $sRet .= '</w:tbl>';
                        }
                    }
                    self::$openTable--;
                    break;
                case 'tr':
                    //Before closing a row we should make sure that there are no lacking cells due to a previous rowspan
                    $row = count(self::$tableGrid[self::$openTable]) - 1;
                    $column = count(self::$tableGrid[self::$openTable][$row]);
                    $sRet .= $this->closeTr($row, $column);
                    if (strpos(self::$WordML, '#<w:gridCol/>#') !== false)
                        self::$WordML = str_replace('#<w:gridCol/>#', str_repeat('<w:gridCol/>', ($column + $numberRows)), self::$WordML);
                    //We now may close the tr tag
                    $sRet .= '</w:tr>';
                    break;
                case 'td':
                case 'th':
                    if (self::$openPs) {
                        if (self::$openLinks) {
                            $sRet.= '</w:hyperlink>';
                            self::$openLinks = false;
                        }
                        $sRet .= '</w:p></w:tc>';
                        self::$openPs = false;
                    } else {  
                            if($currentTag != 'close'){
                                $sRet .= '<w:p /></w:tc>';//We add the <w:p /> as a fix becasuse WordML requires to have at least a closed p tag inside a cell                              
                            }else{
                                $sRet .= '</w:tc>';//FIXME: we still get a problem if the last open tag in the HTML is an empty table cell
                            }
                    }
                    break;
                case 'a':
                    if (self::$openLinks) {
                        $sRet.= '</w:hyperlink>';
                        self::$openLinks = false;
                    }
                    break;
                case '#text':
                    if ($currentTag == 'close') {
                        if (self::$openLinks) {
                            $sRet.= '</w:hyperlink>';
                            self::$openLinks = false;
                        }
                        $sRet .= '</w:p>';
                        self::$openPs = false;
                    }
                    break;
                default:
                    if ($currentTag == 'close') {
                        if (self::$openLinks) {
                            $sRet.= '</w:hyperlink>';
                            self::$openLinks = false;
                        }
                        if (self::$openPs) {
                            $sRet .= '</w:p>';
                            self::$openPs = false;
                        }
                    }
            }
        }

        return($sRet);
    }
    
    /**
     * This function returns the default style for paragraphs inside a list
     *
     * @access private
     * @return string
     */
    private function listStyle() {
        return 'ListParagraphPHPDOCX';
    }
    
    /**
     * This function returns the default types of lists
     *
     * @access private
     * @param array $tipo
     * @return string
     */
    private function listType($tipo = array(1, 2)) {
        $counter = count(self::$openTags);
        for ($j = $counter; $j >= ($this->_level - 1); $j--) {
            if (@self::$openTags[$j] == 'ul') {
                $num = $tipo[0];
                break;
            } else if (@self::$openTags[$j] == 'ol') {
                $num = $tipo[1];
                break;
            }
        }
        if (isset($num)) {
            return $num;
        } else {
            return $tipo[0];
        }
    }
    
    /**
     * This function returns the WordML formatting for a paragraph
     *
     * @access private
     * @param array $properties
     * @return string
     */
    private function generatePPr($properties) {
        $stringPPr = '<w:pPr>';
        if ($this->paragraphStyle == '') {
            $stringPPr .= '<w:pBdr>';
            foreach (self::$borders as $key => $value) {
                if (@$properties['border_' . $value . '_style'] != 'none') {
                    $stringPPr .= '<w:' . $value . ' w:val="' . $this->getBorderStyles($properties['border_' . $value . '_style']) . '"  w:color="' . $this->wordMLColor($properties['border_' . $value . '_color']) . '" w:sz="' . $this->wordMLLineWidth($properties['border_' . $value . '_width']) . '" />';
                }
            }
            $stringPPr .= '</w:pBdr>';
            if (isset($properties['background_color']) && is_array($properties['background_color'])) {
                $color = $properties['background_color'];
                $color = $this->wordMLColor($color);
                $stringPPr .='<w:shd w:val="solid" w:color="' . $color . '" />';
            }
            if (isset($properties['text_align'])) {
                $stringPPr .= '<w:jc w:val="' . self::$text_align[$properties['text_align']] . '" />';
            }

            $stringPPr .= '</w:pPr>';
        } else {
            $stringPPr .= '<w:pStyle w:val="' . $this->paragraphStyle . '"/>';
        }
        return $stringPPr;
    }
    
    /**
     * This function returns the WordML formatting for a run of text
     *
     * @access private
     * @param array $properties
     * @return string
     */
    private function generateRPr($properties) {
        $stringRPr = '<w:rPr>';
        if (isset($properties['font_family']) && $properties['font_family'] != 'serif') {
            $arrayCSSFonts = explode(',', $properties['font_family']);
            $font = trim($arrayCSSFonts[0]);
            $stringRPr .= '<w:rFonts w:ascii="' .$font. '" w:hAnsi="' .$font. '" w:cs="' .$font. '" /> ';
        }
        //By the time being we only parse the color and if the font is bold or italic
        if (@$properties['font_weight'] == 'bolder') {
            $stringRPr .='<w:b />';
        }
        if (@$properties['font_style'] == 'italic') {
            $stringRPr .='<w:i />';
        }
        if (@$properties['color'] != '' && is_array($properties['color'])) {
            $color = $properties['color'];
            $color = $this->wordMLColor($color);
            $stringRPr .='<w:color w:val="' . $color . '" />';
        }
        if (@$properties['font_size'] != '') {
            $stringRPr .='<w:sz w:val="' . round($properties['font_size'] * 2) . '" />';
        }
        if (self::$openLinks) {
            $stringRPr .='<w:u w:val="single" />';
        }
        $stringRPr .= '</w:rPr>';
        return $stringRPr;
    }
    
    /**
     * This function returns the WordML formatting for a table
     *
     * @access private
     * @param array $properties
     * @param integer $border
     * @return string
     */
    private function generateTblPr($properties, $border) {
        $stringTblPr = '<w:tblPr>';
        if ($this->tableStyle == '') {
            if (isset($border) && ((int) $border) >= 1) {
                $stringTblPr .= '<w:tblStyle w:val="TableGridPHPDOCX" />';
            } else {
                $stringTblPr .= '<w:tblStyle w:val="NormalTablePHPDOCX" />';
            }
        } else {
            $stringTblPr .= '<w:tblStyle w:val="' . $this->tableStyle . '" />';
        }
        $stringTblPr .= '</w:tblPr>';
        $stringTblPr .= '<w:tblGrid>#<w:gridCol/>#</w:tblGrid>';
        return $stringTblPr;
    }
    
    /**
     * This function returns the WordML formatting for a table row
     *
     * @access private
     * @param array $properties
     * @return string
     */
    private function generateTrPr($properties) {
        $stringTrPr = '<w:trPr>';
        $stringTrPr .= '</w:trPr>';
        return $stringTrPr;
    }
    
    /**
     * This function returns the WordML formatting for a table cell
     *
     * @access private
     * @param array $properties
     * @param integer $colspan
     * @param integer $rowspan
     * @param boolean $firstRow
     * @return string
     */
    private function generateTcPr($properties, $colspan, $rowspan, $firstRow) {
        $stringTcPr = '<w:tcPr>';
        if ($colspan > 1) {
            $stringTcPr .= '<w:gridSpan w:val="' . $colspan . '" />';
        }
        if ($rowspan > 1) {
            $stringTcPr .= '<w:vMerge w:val="restart" />';
        }
        if ($this->tableStyle == '') {
            $stringTcPr .= '<w:tcBorders>';
            foreach (self::$borders as $key => $value) {
                if (@$properties['border_' . $value . '_style'] != 'none') {
                    $stringTcPr .= '<w:' . $value . ' w:val="' . $this->getBorderStyles($properties['border_' . $value . '_style']) . '"  w:color="' . $this->wordMLColor($properties['border_' . $value . '_color']) . '" w:sz="' . $this->wordMLLineWidth($properties['border_' . $value . '_width']) . '" />';
                }
            }
            $stringTcPr .= '</w:tcBorders>';
            if (isset($properties['background_color']) && is_array($properties['background_color'])) {
                $color = $properties['background_color'];
                $color = $this->wordMLColor($color);
                $stringTcPr .='<w:shd w:val="solid" w:color="' . $color . '" />';
            }
            /*$stringTcPr .= '<w:tcMar>';
            foreach (self::$borders as $key => $value) {
                if (isset($properties['padding_' . $value])) {
                    $stringTcPr .= '<w:' . $value . ' w:w="' . $this->getMargins($properties['padding_' . $value]) . '"  w:type="dxa" />';
                }
            }
            $stringTcPr .= '</w:tcMar>';*/
        }
        $stringTcPr .= '</w:tcPr>';
        return $stringTcPr;
    }
    /**
     * This function returns the WordML formatting for a list
     *
     * @access private
     * @param array $properties
     * @return string
     */
    private function generateListPr($properties) {
        $stringListPr = '<w:pPr><w:pStyle w:val="';
        $stringListPr .= $this->listStyle();
        $stringListPr .= '"/><w:numPr><w:ilvl w:val="';
        $this->countTags = array_count_values(self::$openTags);
        $stringListPr .= max((@$this->countTags['ul'] + @$this->countTags['ol'] - 1), 0);
        $stringListPr .= '"/><w:numId w:val="';
        $stringListPr .= $this->listType();
        $stringListPr .= '"/></w:numPr>';
        $stringListPr .= '</w:pPr>';
        return $stringListPr;
    }

    /**
     * This function is used to take care of rowspans and colspans
     *
     * @access private
     * @param integer $row
     * @param integer $column
     * @return integer
     */
    private function countEmptyColumns($row, $column) {
        if (isset(self::$tableGrid[self::$openTable][$row - 1][$column]) && self::$tableGrid[self::$openTable][$row - 1][$column][0] > 1) {
            $merge = array(self::$tableGrid[self::$openTable][$row - 1][$column][0], self::$tableGrid[self::$openTable][$row - 1][$column][1]);
            if ($merge[0] > 1) {
                    self::$WordML .= '<w:tc><w:tcPr><w:gridSpan  w:val="' . $merge[1] . '" /><w:vMerge w:val="continue" /></w:tcPr><w:p /></w:tc>';
                    for ($k = 0; $k < $merge[1]; $k++) {
                        array_push(self::$tableGrid[self::$openTable][count(self::$tableGrid[self::$openTable]) - 1], array(self::$tableGrid[self::$openTable][$row - 1][$column][0] - 1, $merge[1] - $k));
                    }
                }
             $this->countEmptyColumns($row, $column + $merge[1]);
        } 
    }
    /**
     * This function is used to make sure that all table rows have the same grid
     *
     * @access private
     * @param integer $row
     * @param integer $column
     * @return integer
     */
    private function closeTr($row, $column, $colString=''){
        if (isset(self::$tableGrid[self::$openTable][$row - 1][$column]) && self::$tableGrid[self::$openTable][$row - 1][$column][0] > 1) {
            $merge = array(self::$tableGrid[self::$openTable][$row - 1][$column][0], self::$tableGrid[self::$openTable][$row - 1][$column][1]);
            if ($merge[0] > 1) {
                    $colString .= '<w:tc><w:tcPr><w:gridSpan  w:val="' . $merge[1] . '" /><w:vMerge w:val="continue" /></w:tcPr><w:p /></w:tc>';
                    for ($k = 0; $k < $merge[1]; $k++) {
                        array_push(self::$tableGrid[self::$openTable][count(self::$tableGrid[self::$openTable]) - 1], array(self::$tableGrid[self::$openTable][$row - 1][$column][0] - 1, $merge[1] - $k));
                    }
                }

             $colString = $this->closeTr($row, $column + $merge[1], $colString);
        } 
        return $colString;
    }

    /**
     * This function is used to make sure that the url has the desired format
     *
     * @access private
     * @param string $url
     * @return string
     */
    private function parseURL($url) {
        $urlParts = explode('//', $url);
        if ($urlParts[0] == 'http:' || $urlParts[0] == 'https:' || $urlParts[0] == 'file:') {
            return $url;
        }else if(($urlParts[0] == '' && count($urlParts) > 0 )){
            return 'http:'.$url;
        }else {
            if ($url[0] == '/') {
                $url = substr($url, 1);
            }
        }
        return $this->baseURL . $url;
    }

    /**
     * This function returns the width of a line in eigths of a point (the measure used in WordML)
     *
     * @access private
     * @param integer $size
     * @return integer
     */
    private function wordMLLineWidth($size) {
        return round($size * 5 / 0.75);
    }

    /**
     * This function returns the colour as is used by WordML
     *
     * @access private
     * @param string $color
     * @return string
     */
    private function wordMLColor($color) {
        return strtoupper(str_replace('#', '', $color["hex"]));
    }

    /**
     * This function returns the margin size in WordML format
     *
     * @access private
     * @param string $data
     * @return integer
     */
    private function getMargins($data) {
        return ((int) str_replace('px', '', $data)) * 20;
    }
    
    /**
     * This function returns the border style if it is correct CSS, else it returns nil
     *
     * @access private
     * @param string $borderStyle
     * @return string
     */
    private function getBorderStyles($borderStyle){
        if (array_key_exists($borderStyle, self::$borderStyles)){  
          return self::$borderStyles[$borderStyle];  
        }else{
            return 'nil';
        }
    
    }
    
}
