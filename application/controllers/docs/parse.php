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
        require_once ('features/percentiles.php');
        require_once ('features/previous.php');
        require_once ('features/summary.php');
        require_once ('features/questionList.php');
        require_once ('features/satisfactionSummary.php');
        require_once ('features/satisfactionImportance.php');
        require_once ('features/scoresPercentagesBestuur.php');
        require_once ('features/scoresBestuur.php');
        require_once ('features/percentagesBestuur.php');
        
        
        
        require 'phpdocx_pro/classes/CreateDocx.inc';
        //load url helper
        $this -> load -> helper('url');
        set_time_limit ( 3000 );
    }
    
    
    public function doc($template = false, $xml_source = false, $output_file = false) {
        $template = urldecode($template);
        $xml_source = urldecode($xml_source);
        $output_file = urldecode($output_file);
        $template = str_replace('___','/',$template);
        $xml_source = str_replace('___','/',$xml_source);
        $output_file = str_replace('___','/',$output_file);
        
        
        $temp           = 'temp/';

        if (!$xml_source){
            $xml_source = $this->input->post('xml');
            $xml_source = $this->config->item('report_dir').'/'.$xml_source;
        }

        $this -> load -> library('simplexml');

        $xmlRaw = file_get_contents($xml_source);

        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);
        
        $ref = array('alle_scholen' => TRUE, 'obb' => FALSE, 'question_based' => FALSE, 'locaties' => TRUE,
        'vorige_peiling' => TRUE);
        
        if (!$template){
            $template = $this->input->post('template');
            $template = $this->config->item('template_dir').'/'.$template;
        }
        $inputref = $this->input->post('ref');
        if (is_array($inputref)){
            foreach ($ref as $key => $reference){
                if (!in_array($key,$inputref)){
                    $ref[$key] = FALSE;
                } else {
                    $ref[$key] = TRUE;
                }
            }
        } else {
			$ref['alle_scholen'] = ($xmlData['peiling.ref_group_all'] == 1);
            if($xmlData['basetype'] == '1'){
                $ref['obb'] = TRUE;
            } else {
                $ref['obb'] = ($xmlData['peiling.ref_group_obb'] == 1);
            }
        }


        if (!$template) {
            die("Geef een template op!\n");
        }
        if (!$xml_source) {
            die("Geef een xml source op!\n");
        }
        if (!$output_file) {
//            die("Geef een uitvoer bestand op!\n");
        }
        error_log( "Building report with template: " . $template . " , xml source: " . $xml_source . " and output to: " . $output_file . "\n");

        //get std refs from site
        if($xmlData['report.type'] == 'OTP_B_0412'){
            $ref['bovenbouw'] = 'Lager onderwijs';
            $ref['onderbouw'] = 'Kleuteronderwijs';
        } else {
            $ref['bovenbouw'] = 'Bovenbouw';
            $ref['onderbouw'] = 'Onderbouw';
        }
        
//        $percentages = new percentages();
//        $percentage_docx = $percentages -> render($xmlData);
//        unset($percentages);
        
//        $scores = new scores();
//        $scores_docx = $scores -> render($xmlData);
//        unset($scores);
        
        $percentageExample = new percentages();
        $percentage_example_docx = $percentageExample -> render($xmlData, $ref, "", '', TRUE, TRUE);
        unset($percentageExample);
        
        $scoresExample = new scores();
        $scores_example_docx = $scoresExample -> render($xmlData, $ref, "", '', TRUE);
        unset($scoresExample);

        $reportmark = new reportmark();
        $reportmark_docx = $reportmark -> render($xmlData, $ref);
        unset($reportmark);
        
        $satisfaction = new satisfaction();
        $satisfaction_docx = $satisfaction -> render($xmlData, $ref, 'satisfaction');
        unset($satisfaction);

        $importance = new satisfaction();
        $importance_docx = $importance -> render($xmlData, $ref, 'importance');
        unset($importance);
                
        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData, $ref, $this->config);
        unset($satisfactionPriorityScatter);        
        
        $mostimportant = new mostimportant();
        $mostimportant_docx = $mostimportant -> render($xmlData, $ref);
        unset($mostimportant);
        
        $satisfactionTopGood = new satisfactionTop();
        $satisfactionTopGood_docx = $satisfactionTopGood -> render($xmlData, $ref, TRUE);
        unset($satisfactionTopGood);        
        
        $satisfactionTopBad = new satisfactionTop();
        $satisfactionTopBad_docx = $satisfactionTopBad -> render($xmlData, $ref, FALSE);
        unset($satisfactionTopBad);        
        
        $scoresAndPercentages = new scoresAndPercentages();
        $scoresAndPercentages_docx = $scoresAndPercentages -> render($xmlData, $ref);
        unset($scoresAndPercentages);

        //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
            $percentiles_good = new percentiles();
            $percentiles_good_docx = $percentiles_good -> render($xmlData, $ref, 'green');
            unset($percentiles_good);
               
            $percentiles_bad = new percentiles();
            $percentiles_bad_docx = $percentiles_bad -> render($xmlData, $ref, 'red');
            unset($percentiles_bad);
        //}
               
        $previous = new previous();
        $previous_docx = $previous -> render($xmlData, $ref);
        unset($previous);
                       
        $summary = new summary();
        $summary_docx = $summary -> render($xmlData, $ref, $this->config);
        unset($summary);

        $questionList = new questionList();
        $questionList_docx = $questionList -> render($xmlData, $ref);
        unset($questionList);

        $satisfactionSummary = new satisfactionSummary();
        $satisfactionSummary_docx = $satisfactionSummary -> render($xmlData, $ref, $this->config);
        unset($satisfactionSummary);
               
        $satisfactionImportance = new satisfactionImportance();
        $satisfactionImportance_docx = $satisfactionImportance -> render($xmlData, $ref, $this->config);
        unset($satisfactionImportance);
               
        $scoresPercentagesBestuur = new scoresPercentagesBestuur();
        $scoresPercentagesBestuur_docx = $scoresPercentagesBestuur -> render($xmlData, $ref);
        unset($scoresPercentagesBestuur);
               
        $docx = new CreateDocx();
        
        $docx->setTemplateSymbol('TTT');

        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();

        foreach ($variables['document'] as $template_variable) {
//            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
//            print "got variable type: " . $type . " for variable: " . $variable . "\n";
            if ($type == 'xml') {
                //get direct from xml
                if (isset($xmlData[$variable])){
                    $docx -> addTemplateVariable($template_variable, $xmlData[$variable]);
                }
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
                    $docx -> addTemplateVariable('class:scoreExample', $scores_example_docx, 'docx');
                }
                if ($variable == "percentageExample") {
                    $docx -> addTemplateVariable('class:percentageExample', $percentage_example_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
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
                    $docx -> addTemplateVariable('class:satisfactionTop:good', $satisfactionTopGood_docx, 'docx');
                    $docx -> addTemplateVariable('class:satisfactionTop:bad', $satisfactionTopBad_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }
                //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
                    if ($variable == "percentiles") {
                        $docx -> addTemplateVariable('class:percentiles:good', $percentiles_good_docx, 'docx');
                    }
                    if ($variable == "percentiles") {
                        $docx -> addTemplateVariable('class:percentiles:bad', $percentiles_bad_docx, 'docx');
                    }
                //}
                if ($variable == "previous") {
                    $docx -> addTemplateVariable('class:previous', $previous_docx, 'docx');
                }
                if ($variable == "summary") {
                    $docx -> addTemplateVariable('class:summary', $summary_docx, 'docx');
                }
                if ($variable == "questionList") {
                    $docx -> addTemplateVariable('class:questionList', $questionList_docx, 'docx');
                }
                if ($variable == "satisfactionSummary") {
                    $docx -> addTemplateVariable('class:satisfactionSummary', $satisfactionSummary_docx, 'docx');
                }
                if ($variable == "satisfactionImportance") {
                    $docx -> addTemplateVariable('class:satisfactionImportance', $satisfactionImportance_docx, 'docx');
                }
                if ($variable == "scoresPercentagesBestuur") {
                    $docx -> addTemplateVariable('class:scoresPercentagesBestuur', $scoresPercentagesBestuur_docx, 'docx');
                }
                
            }

        }

        $questionProperties = new questionProperties();
        $questionProperties->process($xmlData, $docx);
        $mostimportant = new mostimportant();
        $mostimportant -> process($xmlData, $docx);
        $reportmark = new reportmark();
        $reportmark -> process($xmlData, $docx);

        $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));
        $docx->modifyPageLayout('A4'); 
        //remove unwanted .docx extension, this will be autogenerated by PHPDocx
        $output_file = preg_replace('/\.docx$/','',$output_file);
		$text = 'Lorem ipsum dolor sit amet.';
		$docx -> addText($text);
        if(php_sapi_name() == "cli") {
            //In cli-mode
                $docx -> createDocx($output_file);
        	echo "Done\n";
        } else {
            //Not in cli-mode
                $docx -> createDocxAndDownload();
        }
		//opruimen:
        if (file_exists($percentage_example_docx)) unlink($percentage_example_docx);
        if (file_exists($scores_example_docx)) unlink($scores_example_docx);
        if (file_exists($reportmark_docx)) unlink($reportmark_docx);
        if (file_exists($satisfaction_docx)) unlink($satisfaction_docx);
        if (file_exists($importance_docx)) unlink($importance_docx);
        if (file_exists($satisfactionPriorityScatter_docx)) unlink($satisfactionPriorityScatter_docx);
        if (file_exists($mostimportant_docx)) unlink($mostimportant_docx);
        if (file_exists($satisfactionTopGood_docx)) unlink($satisfactionTopGood_docx);
        if (file_exists($satisfactionTopBad_docx)) unlink($satisfactionTopBad_docx);
        if (file_exists($scoresAndPercentages_docx)) unlink($scoresAndPercentages_docx);
        //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
            if (file_exists($percentiles_good_docx)) unlink($percentiles_good_docx);
            if (file_exists($percentiles_bad_docx)) unlink($percentiles_bad_docx);
        //}
        if (file_exists($previous_docx)) unlink($previous_docx);
        if (file_exists($summary_docx)) unlink($summary_docx);
        if (file_exists($satisfactionSummary_docx)) unlink($satisfactionSummary_docx);
        if (file_exists($satisfactionImportance_docx)) unlink($satisfactionImportance_docx);
				
/*
*/
    }

    public function test($template = false, $xml_source = false, $output_file = false) {
        $template = urldecode($template);
        $xml_source = urldecode($xml_source);
        $output_file = urldecode($output_file);
        $template = str_replace('___','/',$template);
        $xml_source = str_replace('___','/',$xml_source);
        $output_file = str_replace('___','/',$output_file);
        
        $temp           = 'temp/';
        $ref = array('alle_scholen' => TRUE, 'obb' => FALSE, 'question_based' => TRUE, 'vorige_peiling' => FALSE);
        
        if (!$template){
            $template = $this->input->post('template');
            $template = $this->config->item('template_dir').'/'.$template;
        }
        $inputref = $this->input->post('ref');
        if (is_array($inputref)){
            foreach ($inputref as $reference){
                $ref[$reference] = TRUE;
            }
            foreach ($ref as $reference){
                if (!isset($ref[$reference])){
                    $ref[$reference] = FALSE;
                }
            }
        }
        if (!$xml_source){
            $xml_source = $this->input->post('xml');
            $xml_source = $this->config->item('report_dir').'/'.$xml_source;
        }

        
        if (!$template) {
            die("Geef een template op!\n");
        }
        if (!$xml_source) {
            die("Geef een xml source op!\n");
        }
        if (!$output_file) {
//            die("Geef een uitvoer bestand op!\n");
        }
        echo "Building report with template: " . $template . " , xml source: " . $xml_source . " and output to: " . $output_file . "\n";

        $this -> load -> library('simplexml');

        $xmlRaw = file_get_contents($xml_source);

        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);

//        $percentages = new percentages();
//        $percentage_docx = $percentages -> render($xmlData, $ref);
//        unset($percentages);
        
//        $scores = new scores();
//        $scores_docx = $scores -> render($xmlData, $ref);
//        unset($scores);

//        $percentageExample = new percentages();
//        $percentage_example_docx = $percentageExample -> render($xmlData, $ref, "", '', TRUE, TRUE);
//        unset($percentageExample);
        
//        $scoresExample = new scores();
//        $scores_example_docx = $scoresExample -> render($xmlData, $ref, "", '', TRUE);
//        unset($scoresExample);

//        $reportmark = new reportmark();
//        $reportmark_docx = $reportmark -> render($xmlData, $ref);
//        unset($reportmark);
        
//        $importance = new satisfaction();
//        $importance_docx = $importance -> render($xmlData, $ref, 'importance');
//        unset($importance);

//        $satisfaction = new satisfaction();
//        $satisfaction_docx = $satisfaction -> render($xmlData, $ref, 'satisfaction');
//        unset($satisfaction);
       
        
//        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
//        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData, $ref);
//        unset($satisfactionPriorityScatter);
               
//        $satisfactionTopGood = new satisfactionTop();
//        $satisfactionTopGood_docx = $satisfactionTopGood -> render($xmlData, $ref, TRUE);
//        unset($satisfactionTopGood);        
        
//        $satisfactionTopBad = new satisfactionTop();
//        $satisfactionTopBad_docx = $satisfactionTopBad -> render($xmlData, $ref, FALSE);
//        unset($satisfactionTopBad);        
        
               
//        $mostimportant = new mostimportant();
//        $mostimportant_docx = $mostimportant -> render($xmlData, $ref);
//        unset($mostimportant);
               
//        $scoresAndPercentages = new scoresAndPercentages();
//        $scoresAndPercentages_docx = $scoresAndPercentages -> render($xmlData, $ref);
//        unset($scoresAndPercentages);
               
//        $percentiles_good = new percentiles();
//        $percentiles_good_docx = $percentiles_good -> render($xmlData, $ref, 'green');
//        unset($percentiles_good);
               
//        $percentiles_bad = new percentiles();
//        $percentiles_bad_docx = $percentiles_bad -> render($xmlData, $ref, 'red');
//        unset($percentiles_bad);
               
//        $previous = new previous();
//        $previous_docx = $previous -> render($xmlData, $ref);
//        unset($previous);
               
//        $summary = new summary();
//        $summary_docx = $summary -> render($xmlData, $ref);
//        unset($summary);
               
//        $satisfactionSummary = new satisfactionSummary();
//        $satisfactionSummary_docx = $satisfactionSummary -> render($xmlData, $ref);
//        unset($satisfactionSummary);
               
        $questionList = new questionList();
        $questionList_docx = $questionList -> render($xmlData, $ref);
        unset($questionList);

//        $satisfactionImportance = new satisfactionImportance();
//        $satisfactionImportance_docx = $satisfactionImportance -> render($xmlData, $ref);
//        unset($satisfactionImportance);
               
        $scoresPercentagesBestuur = new scoresPercentagesBestuur();
        $scoresPercentagesBestuur_docx = $scoresPercentagesBestuur -> render($xmlData, $ref);
        unset($scoresPercentagesBestuur);
               
                                                                           
        $docx = new CreateDocx();

        $docx->setTemplateSymbol('TTT');
        
        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();
        print "start test: \n";

        foreach ($variables['document'] as $template_variable) {
//            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
//            print "got variable type: " . $type . " for variable: " . $variable . "\n";
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
                    $docx -> addTemplateVariable('class:satisfactionPriorityScatter', $satisfactionPriorityScatter_docx, 'docx');
                }
                if ($variable == "satisfaction") {
//                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');
                }
                if ($variable == "importance") {
//                    $docx -> addTemplateVariable('class:importance', $importance_docx, 'docx');
                } 
                if ($variable == "mostimportance") {
//                    $docx -> addTemplateVariable('class:mostimportance', $mostimportant_docx, 'docx');
                }
                if ($variable == "satisfactionTop") {
//                    $docx -> addTemplateVariable('class:satisfactionTop:good', $satisfactionTopGood_docx, 'docx');
//                    $docx -> addTemplateVariable('class:satisfactionTop:bad', $satisfactionTopBad_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
//                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }
                if ($variable == "percentiles") {
//                    $docx -> addTemplateVariable('class:percentiles:good', $percentiles_good_docx, 'docx');
                }
                if ($variable == "percentiles") {
//                    $docx -> addTemplateVariable('class:percentiles:bad', $percentiles_bad_docx, 'docx');
                }
                if ($variable == "previous") {
//                    $docx -> addTemplateVariable('class:previous', $previous_docx, 'docx');
                }
                if ($variable == "summary") {
//                    $docx -> addTemplateVariable('class:summary', $summary_docx, 'docx');
                }
                if ($variable == "questionlist") {
                    $docx -> addTemplateVariable('class:questionList', $questionList_docx, 'docx');
                }
                if ($variable == "satisfactionSummary") {
//                    $docx -> addTemplateVariable('class:satisfactionSummary', $satisfactionSummary_docx, 'docx');
                }
                if ($variable == "satisfactionImportance") {
                    $docx -> addTemplateVariable('class:satisfactionImportance', $satisfactionImportance_docx, 'docx');
                }
                if ($variable == "scoresPercentagesBestuur") {
                    $docx -> addTemplateVariable('class:scoresPercentagesBestuur', $scoresPercentagesBestuur_docx, 'docx');
                }
            }

        }
//        $questionProperties = new questionProperties();
//        $questionProperties->process($xmlData, $docx);
//        $mostimportant = new mostimportant();
//        $mostimportant_docx = $mostimportant -> process($xmlData, $docx);

        $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));
        $docx->modifyPageLayout('A4');
        //remove unwanted .docx extension, this will be autogenerated by PHPDocx
        $output_file = preg_replace('/\.docx$/','',$output_file);
        $docx -> createDocx($output_file);

        echo "Done\n";

    }

    public function doc1($template = false, $xml_source = false, $output_file = false) {
        	$docx = new CreateDocx();
		$text = 'Lorem ipsum dolor sit amet.';
		$docx -> addText($text);
		$docx -> createDocxAndDownload('testest');
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
