<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parse extends CI_Controller {
    /**
     *
     * This controller parses a template with an xml source
     */

     
     /**
     * Constructor, we load our default helpers here
     */
    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'nl_NL');
        require_once ('features/percentages.php');
        require_once ('features/scores.php');
        require_once ('features/reportmark.php');
        require_once ('features/satisfaction.php');
        require_once ('features/mostimportant.php');
        require_once ('features/satisfactionPriorityScatter.php');
        require_once ('features/satisfactionTop.php');
        require_once ('features/questionProperties.php');
        require_once ('features/scoresAndPercentages.php');
        
        require 'pro/classes/CreateDocx.inc';
        //load url helper
        $this -> load -> helper('url');
    }
    
    
    public function doc($template = false, $xml_source = false, $output_file = false) {
        $template = urldecode($template);
        $xml_source = urldecode($xml_source);
        $output_file = urldecode($output_file);
        $temp           = 'temp/';
        if (!$template) {
            die("Geef een template op!\n");
        }
        if (!$xml_source) {
            die("Geef een xml source op!\n");
        }
        if (!$output_file) {
            die("Geef een uitvoer bestand op!\n");
        }
        echo "Building report with template: " . $template . " , xml source: " . $xml_source . " and output to: " . $output_file . "\n";

        $this -> load -> library('simplexml');

        $xmlRaw = file_get_contents($xml_source);

        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);

//        $percentages = new percentages();
//        $percentage_docx = $percentages -> render($xmlData);
//        unset($percentages);
        
//        $scores = new scores();
//        $scores_docx = $scores -> render($xmlData);
//        unset($scores);
        
        $percentageExample = new percentages();
        $percentage_example_docx = $percentageExample -> render($xmlData, "", 3);
        unset($percentageExample);
        
        $scoresExample = new scores();
        $scores_example_docx = $scoresExample -> render($xmlData, "", 3);
        unset($scoresExample);

                $reportmark = new reportmark();
        $reportmark_docx = $reportmark -> render($xmlData);
        unset($reportmark);
        
        $satisfaction = new satisfaction();
        $satisfaction_docx = $satisfaction -> render($xmlData, 'satisfaction');
        unset($satisfaction);

        $importance = new satisfaction();
        $importance_docx = $importance -> render($xmlData, 'importance');
        unset($importance);
                
        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData);
        unset($satisfactionPriorityScatter);        
        
        $mostimportant = new mostimportant();
        $mostimportant_docx = $mostimportant -> render($xmlData);
        unset($mostimportant);
        
//        $satisfactionTop = new satisfactionTop();
//        $satisfactionTop_docx = $satisfactionTop -> render($xmlData);
//        unset($satisfactionTop);        
        
        $scoresAndPercentages = new scoresAndPercentages();
        $scoresAndPercentages_docx = $scoresAndPercentages -> render($xmlData);
        unset($scoresAndPercentages);
               
        
        $docx = new CreateDocx();

        $docx->setTemplateSymbol('TTT');

        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();

        foreach ($variables['document'] as $template_variable) {
            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
            print "got variable type: " . $type . " for variable: " . $variable . "\n";
            if ($type == 'xml') {
                //get direct from xml
                $docx -> addTemplateVariable($template_variable, $xmlData[$variable]);
            } elseif ($type == "proc") {
                //process
                $docx -> addTemplateVariable('proc:datum', strftime("%e %B %Y"));
            } elseif ($type == "class") {
                //get class to process
                //scores and percentages
                if ($variable == "percentages") {
                    $docx -> addTemplateVariable('class:percentages', $percentage_docx, 'docx');
                }
                if ($variable == "scores") {
                    $docx -> addTemplateVariable('class:scores', $scores_docx, 'docx');
                }
                if ($variable == "scoreExample") {
                    $docx -> addTemplateVariable('class:scoreExample', $scores_example_docx, 'docx');
                }
                if ($variable == "percentageExample") {
                    $docx -> addTemplateVariable('class:percentageExample', $percentage_example_docx, 'docx');
                }
                if ($variable == "reportmark") {
                    $docx -> addTemplateVariable('class:reportmark', $reportmark_docx, 'docx');
                }
                if ($variable == "satisfaction") {
                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');
                }
                if ($variable == "satisfactionimportance") {
                    $docx -> addTemplateVariable('class:satisfactionimportance', $importance_docx, 'docx');
                }
                if ($variable == "satisfactionPriorityScatter") {
                    $docx -> addTemplateVariable('class:satisfactionPriorityScatter', $satisfactionPriorityScatter_docx, 'docx');
                }
                if ($variable == "mostimportance") {
                    $docx -> addTemplateVariable('class:mostimportance', $mostimportant_docx, 'docx');
                }
                if ($variable == "satisfactionTop") {
//                    $docx -> addTemplateVariable('class:satisfactionTop', $satisfactionTop_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }
            }

        }

        $questionProperties = new questionProperties();
        $questionProperties->process($xmlData, $docx);
        $mostimportant = new mostimportant();
        $mostimportant_docx = $mostimportant -> process($xmlData, $docx);

        $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));
        //remove unwanted .docx extension, this will be autogenerated by PHPDocx
        $output_file = preg_replace('/\.docx$/','',$output_file);
        $docx -> createDocx($output_file);

        echo "Done\n";

    }

    public function test($template = false, $xml_source = false, $output_file = false) {
        $template = urldecode($template);
        $xml_source = urldecode($xml_source);
        $output_file = urldecode($output_file);
        $temp           = 'temp/';
        if (!$template) {
            die("Geef een template op!\n");
        }
        if (!$xml_source) {
            die("Geef een xml source op!\n");
        }
        if (!$output_file) {
            die("Geef een uitvoer bestand op!\n");
        }
        echo "Building report with template: " . $template . " , xml source: " . $xml_source . " and output to: " . $output_file . "\n";

        $this -> load -> library('simplexml');

        $xmlRaw = file_get_contents($xml_source);

        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);

//        $percentages = new percentages();
//        $percentage_docx = $percentages -> render($xmlData);
//        unset($percentages);
        
//        $scores = new scores();
//        $scores_docx = $scores -> render($xmlData);
//        unset($scores);

//        $percentageExample = new percentages();
//        $percentage_example_docx = $percentageExample -> render($xmlData, "", 3);
//        unset($percentageExample);
        
//        $scoresExample = new scores();
//        $scores_example_docx = $scoresExample -> render($xmlData, "", 3);
//        unset($scoresExample);

//        $reportmark = new reportmark();
//        $reportmark_docx = $reportmark -> render($xmlData);
//        unset($reportmark);
        
//        $importance = new satisfaction();
//        $importance_docx = $importance -> render($xmlData, 'importance');
//        unset($importance);

//        $satisfaction = new satisfaction();
//        $satisfaction_docx = $satisfaction -> render($xmlData, 'satisfaction');
//        unset($satisfaction);
       
        
//        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
//        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData);
///        unset($satisfactionPriorityScatter);
               
//        $satisfactionTop = new satisfactionTop();
//        $satisfactionTop_docx = $satisfactionTop -> render($xmlData);
///        unset($satisfactionTop);
               
        $mostimportant = new mostimportant();
        $mostimportant_docx = $mostimportant -> render($xmlData);
        unset($mostimportant);
               
//        $scoresAndPercentages = new scoresAndPercentages();
//        $scoresAndPercentages_docx = $scoresAndPercentages -> render($xmlData);
//        unset($scoresAndPercentages);
               
        $docx = new CreateDocx();

        $docx->setTemplateSymbol('TTT');
        
        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();
        print "start test: \n";

        foreach ($variables['document'] as $template_variable) {
            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
            print "got variable type: " . $type . " for variable: " . $variable . "\n";
            if ($type == 'xml') {
                //get direct from xml
                $docx -> addTemplateVariable($template_variable, $xmlData[$variable]);
            } elseif ($type == "proc") {
                //process
                $docx -> addTemplateVariable('proc:datum', strftime("%e %B %Y"));
            } elseif ($type == "class") {
                //get class to process
                //scores and percentages
                if ($variable == "percentages") {
//                    $docx -> addTemplateVariable('class:percentages', $percentage_docx, 'docx');
                }
                if ($variable == "scores") {
//                    $docx -> addTemplateVariable('class:scores', $scores_docx, 'docx');
                }
                if ($variable == "scoreExample") {
//                    $docx -> addTemplateVariable('class:scoreExample', $scores_example_docx, 'docx');
                }
                if ($variable == "percentageExample") {
//                    $docx -> addTemplateVariable('class:percentageExample', $percentage_example_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
//                    $docx -> addTemplateVariable('class:scores', $scores_docx, 'docx');
                }
                if ($variable == "reportmark") {
//                    $docx -> addTemplateVariable('class:reportmark', $reportmark_docx, 'docx');
                }
                if ($variable == "satisfactionPriorityScatter") {
//                    $docx -> addTemplateVariable('class:satisfactionPriorityScatter', $satisfactionPriorityScatter_docx, 'docx');
                }
                if ($variable == "satisfaction") {
//                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');
                }
                if ($variable == "importance") {
//                    $docx -> addTemplateVariable('class:importance', $importance_docx, 'docx');
                } 
                if ($variable == "mostimportance") {
                    $docx -> addTemplateVariable('class:mostimportance', $mostimportant_docx, 'docx');
                }
                if ($variable == "satisfactionTop") {
//                    $docx -> addTemplateVariable('class:satisfactionTop', $satisfactionTop_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
//                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }
            }

        }
//        $questionProperties = new questionProperties();
//        $questionProperties->process($xmlData, $docx);
//        $mostimportant = new mostimportant();
//        $mostimportant_docx = $mostimportant -> process($xmlData, $docx);

                $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));
        //remove unwanted .docx extension, this will be autogenerated by PHPDocx
        $output_file = preg_replace('/\.docx$/','',$output_file);
        $docx -> createDocx($output_file);

        echo "Done\n";

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
