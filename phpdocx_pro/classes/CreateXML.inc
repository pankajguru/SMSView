<?php

/**
 * Use DOCX as templates
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    05.26.2011
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.7
 */

/**
 * Use DOCX as templates
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    05.26.2011
 * @link       http://www.phpdocx.com
 * @since      Class available since Release 1.7
 */
class CreateXML {

    /**
     * @access private
     * @var CreateTemplate
     * @static
     */
    private static $_instance = NULL;

    /**
     * @access private
     * @var string
     */
    private $_xml;

    /**
     * Construct
     *
     * @access public
     */
    private function __construct() {

    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct() {

    }

    /**
     * Magic method, returns current word XML
     *
     * @access public
     * @return string Return current word
     */
    public function __toString() {
        return $this->_xml;
    }

    /**
     *
     * @access public
     * @return CreateTemplate
     * @static
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateXML();
        }
        return self::$_instance;
    }

    /**
     *
     */
    public function getXML() {
        return $this->_xml;
    }

    /**
     *
     */
    public function setXML($xml) {
        $this->_xml = $xml;
    }

    /**
     * Return XML
     *
     * @access public
     * @return array
     */
    public function XML() {
        $templateVariables = CreateTemplate::getTemplateVariables();
        
        if (!empty($templateVariables) && is_array($templateVariables)) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;
            $forms = $dom->createElement( "forms" );
            $attr = $forms->setAttribute('id', '30439');
            $dom->appendChild( $forms );

            foreach ($templateVariables as $key => $section) {
                if (!empty($templateVariables[$key]) &&
                    is_array($templateVariables[$key])) {
                    $_blockOpen = false;
                    foreach ($section as $variable) {
                        switch ($variable['TAG']) {
                          case 'BLOCK':
                              if ($_blockOpen == false){
                                  $attr = array('ref' => '',
                                                'id' => $variable['NAME'],
                                                'mode' => 'block');
                                  $block = $this->createElement(
                                               $dom, "group", NULL, $attr);
                                  $label = $this->createElement($dom, "label");
                                  $cdata = $dom->createCDATASection("Group");
                                  
                                  $label->appendChild( $cdata );
                                  $block->appendChild( $label );
                                  $forms->appendChild( $block );

                                  //A group is opening
                                  $_blockOpen = true;
                              } else {
                                  //A group is closing
                                  $_blockOpen = false;
                              }
                              break;

                          case 'GROUP':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME']);
                              $group[$variable['GROUPID']] = $this->createElement(
                                           $dom, "group", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Group");

                              $label->appendChild( $cdata );
                              $group[$variable['GROUPID']]->appendChild( $label );
                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $group[$variable['GROUPID']] );
                              } elseif ($_blockOpen == true){
                                  $block->appendChild( $group[$variable['GROUPID']] );
                              } else{
                                  $forms->appendChild( $group[$variable['GROUPID']] );
                              }
                              break;

                          case 'TAB':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME'],
                                            'type' => 'tab');
                              $tab = $this->createElement(
                                           $dom, "group", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Tab");

                              $label->appendChild( $cdata );
                              $tab->appendChild( $label );
                              $forms->appendChild( $tab );
                              //A tab is opening
                              $_tabOpen = true;
                              break;

                          case 'HEADING':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME'],
                                            'longitud' => 255);
                              $heading = $this->createElement(
                                             $dom, "heading", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Heading");

                              $label->appendChild( $cdata );
                              $heading->appendChild( $label );

                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $heading );
                              } elseif ($_blockOpen == true) {
                                  $block->appendChild( $heading );
                              } elseif ($_tabOpen == true) {
                                  $tab->appendChild( $heading );
                              } else {
                                  $forms->appendChild( $heading );
                              }
                              break;

                          case 'TEXT':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME'],
                                            'longitud' => 255);
                              $text = $this->createElement(
                                          $dom, "text", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Text box");

                              $label->appendChild( $cdata );
                              $text->appendChild( $label );

                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $text );
                              } elseif ($_blockOpen == true) {
                                  $block->appendChild( $text );
                              } elseif ($_tabOpen == true) {
                                  $tab->appendChild( $text );
                              } else {
                                  $forms->appendChild( $text );
                              }
                              break;

                          case 'TEXTAREA':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME'],
                                            'longitud' => 65534);
                              $textarea = $this->createElement(
                                              $dom, "textarea", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Textarea");

                              $label->appendChild( $cdata );
                              $textarea->appendChild( $label );

                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $textarea );
                              } elseif ($_blockOpen == true) {
                                  $block->appendChild( $textarea );
                              } elseif ($_tabOpen == true) {
                                  $tab->appendChild( $textarea );
                              } else {
                                  $forms->appendChild( $textarea );
                              }
                              break;

//                      case 'SELECT':
//                          $this->_xml .= '<text ref="" id="' . $suffix . '">';
//                          break;
//

                          case 'DATE':
                              $attr = array('ref' => '',
                                            'id' => $variable['NAME']);
                              $date = $this->createElement(
                                          $dom, "date", NULL, $attr);
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Date");

                              $label->appendChild( $cdata );
                              $date->appendChild( $label );

                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $date );
                              } elseif ($_blockOpen == true) {
                                  $block->appendChild( $date );
                              } elseif ($_tabOpen == true) {
                                  $tab->appendChild( $date );
                              } else {
                                  $forms->appendChild( $date );
                              }
                              break;

                          case 'COMMENT':
                              $comment = $dom->createElement( "comment" );
                              $comment->setAttribute('ref', '');
                              $comment->setAttribute('id', $variable['NAME']);
                              $comment->setAttribute('longitud', 65534);
                              $dom->appendChild( $comment );
                              $label = $this->createElement($dom, "label");
                              $cdata = $dom->createCDATASection("Comment");

                              $label->appendChild( $cdata );
                              $comment->appendChild( $label );
                              $forms->appendChild( $comment );

                              if (!empty ($variable['GROUP'])) {
                                  $group[$variable['GROUP']]->appendChild( $comment );
                              } elseif ($_blockOpen == true) {
                                  $block->appendChild( $comment );
                              } elseif ($_tabOpen == true) {
                                  $tab->appendChild( $comment );
                              } else {
                                  $forms->appendChild( $comment );
                              }
                              break;
                          default:
                              break;
                        }
                    }
                }

            }
            $this->_xml = $dom->saveXML();
        }
    }

    function createElement($domObj, $tag_name, $value = NULL, $attributes = NULL)
    {
        $element = ($value != NULL ) ? $domObj->createElement($tag_name, $value)
            : $domObj->createElement($tag_name);

        if( $attributes != NULL )
        {
            foreach ($attributes as $attr=>$val)
            {
                $element->setAttribute($attr, $val);
            }
        }

        $domObj->appendChild( $element );

        return $element;
    }
}
