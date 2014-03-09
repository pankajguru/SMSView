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
        require_once ('features/reportmarkBestuur.php');
        require_once ('features/satisfaction.php');
        require_once ('features/satisfactionBestuur.php');
        require_once ('features/satisfactionPerCategoryBestuur.php');
        require_once ('features/mostimportant.php');
        require_once ('features/satisfactionPriorityScatter.php');
        require_once ('features/satisfactionPriorityScatterBestuur.php');
        require_once ('features/satisfactionTop.php');
        require_once ('features/satisfactionTopBestuur.php');
        require_once ('features/questionProperties.php');
        require_once ('features/scoresAndPercentages.php');
        require_once ('features/percentiles.php');
        require_once ('features/previous.php');
        require_once ('features/previousBestuur.php');
        require_once ('features/summary.php');
        require_once ('features/summaryBestuur.php');
        require_once ('features/questionList.php');
        require_once ('features/satisfactionSummaryBestuur.php');
        require_once ('features/satisfactionSummary.php');
        require_once ('features/satisfactionImportance.php');
        require_once ('features/satisfactionImportanceBestuur.php');
        require_once ('features/scoresPercentagesBestuur.php');
        require_once ('features/scoresBestuur.php');
        require_once ('features/percentagesBestuur.php');
        require_once ('features/responsBestuur.php');
        
        
        
        
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
        $verbose = false;
        
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
        
        if ($verbose) { print 'percentages';}
        $percentageExample = new percentages();
        $percentage_example_docx = $percentageExample -> render($xmlData, $ref, "", '', TRUE, TRUE);
        unset($percentageExample);
        
        if ($verbose) { print 'scores';}
        $scoresExample = new scores();
        $scores_example_docx = $scoresExample -> render($xmlData, $ref, "", '', TRUE);
        unset($scoresExample);

        if ($verbose) { print 'reportmark';}
        $reportmark = new reportmark();
        $reportmark_docx = $reportmark -> render($xmlData, $ref);
        unset($reportmark);
        
        if ($verbose) { print 'reportmark bestuur';}
        $reportmarkBestuur = new reportmarkBestuur();
        $reportmarkBestuur_docx = $reportmarkBestuur -> render($xmlData, $ref);
        unset($reportmarkBestuur);

        if ($verbose) { print 'satisfaction';}
        $satisfaction = new satisfaction();
        $satisfaction_docx = $satisfaction -> render($xmlData, $ref, 'satisfaction');
        unset($satisfaction);
        
        if ($verbose) { print 'satisfactionBestuur';}
        $satisfactionBestuur = new satisfactionBestuur();
        $satisfactionBestuur_docx = $satisfactionBestuur -> render($xmlData, $ref, 'satisfaction');
        unset($satisfactionBestuur);
        
        if ($verbose) { print 'satisfactionPerCategoryBestuur';}
        $satisfactionPerCategoryBestuur = new satisfactionPerCategoryBestuur();
        $satisfactionPerCategoryBestuur_docx = $satisfactionPerCategoryBestuur -> render($xmlData, $ref, 'satisfaction');
        unset($satisfactionPerCategoryBestuur);
        
        if ($verbose) { print 'importance';}
        $importance = new satisfaction();
        $importance_docx = $importance -> render($xmlData, $ref, 'importance');
        unset($importance);
                
        if ($verbose) { print 'satisfactionPriorityScatter';}
        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData, $ref, $this->config);
        unset($satisfactionPriorityScatter);        
        
        if ($verbose) { print 'satisfactionPriorityScatterBestuur';}
        $satisfactionPriorityScatterBestuur = new satisfactionPriorityScatterBestuur();
        $satisfactionPriorityScatterBestuur_docx = $satisfactionPriorityScatterBestuur -> render($xmlData, $ref, $this->config);
        unset($satisfactionPriorityScatterBestuur);        
        
        if ($verbose) { print 'mostimportant';}
        $mostimportant = new mostimportant();
        $mostimportant_docx = $mostimportant -> render($xmlData, $ref);
        unset($mostimportant);
        
        if ($verbose) { print 'satisfactionTop';}
        $satisfactionTopGood = new satisfactionTop();
        $satisfactionTopGood_docx = $satisfactionTopGood -> render($xmlData, $ref, TRUE);
        unset($satisfactionTopGood);        
        
        if ($verbose) { print 'satisfactionTopBestuur';}
        $satisfactionTopGoodBestuur = new satisfactionTopBestuur();
        $satisfactionTopGoodBestuur_docx = $satisfactionTopGoodBestuur -> render($xmlData, $ref, TRUE);
        unset($satisfactionTopGoodBestuur);        
        
        if ($verbose) { print 'satisfactionTopBestuur';}
        $satisfactionTopBadBestuur = new satisfactionTopBestuur();
        $satisfactionTopBadBestuur_docx = $satisfactionTopBadBestuur -> render($xmlData, $ref, FALSE);
        unset($satisfactionTopBadBestuur);        
        
        if ($verbose) { print 'satisfactionTop';}
        $satisfactionTopBad = new satisfactionTop();
        $satisfactionTopBad_docx = $satisfactionTopBad -> render($xmlData, $ref, FALSE);
        unset($satisfactionTopBad);        
        
        if ($verbose) { print 'scoresAndPercentages';}
        $scoresAndPercentages = new scoresAndPercentages();
        $scoresAndPercentages_docx = $scoresAndPercentages -> render($xmlData, $ref);
        unset($scoresAndPercentages);

        if ($verbose) { print 'percentiles';}
        //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
            $percentiles_good = new percentiles();
            $percentiles_good_docx = $percentiles_good -> render($xmlData, $ref, 'green');
            unset($percentiles_good);
               
            $percentiles_bad = new percentiles();
            $percentiles_bad_docx = $percentiles_bad -> render($xmlData, $ref, 'red');
            unset($percentiles_bad);
        //}
               
        if ($verbose) { print 'previous';}
        $previous = new previous();
        $previous_docx = $previous -> render($xmlData, $ref);
        unset($previous);

        if ($verbose) { print 'previousBestuur';}
        $previousBestuur = new previousBestuur();
        $previousBestuur_docx = $previousBestuur -> render($xmlData, $ref);
        unset($previousBestuur);
        
        if ($verbose) { print 'summary';}               
        $summary = new summary();
        $summary_docx = $summary -> render($xmlData, $ref, $this->config);
        unset($summary);
        
        if ($verbose) { print 'summaryBestuur';}               
        $summaryBestuur = new summaryBestuur();
        $summaryBestuur_docx = $summaryBestuur -> render($xmlData, $ref, $this->config);
        unset($summary);
        
        if ($verbose) { print 'questionList';}
        $questionList = new questionList();
        $questionList_docx = $questionList -> render($xmlData, $ref);
        unset($questionList);

        if ($verbose) { print 'satisfactionSummary';}
        $satisfactionSummary = new satisfactionSummary();
        $satisfactionSummary_docx = $satisfactionSummary -> render($xmlData, $ref, $this->config);
        unset($satisfactionSummary);
        
        if ($verbose) { print 'satisfactionSummaryBestuur';}
        $satisfactionSummaryBestuur = new satisfactionSummaryBestuur();
        $satisfactionSummaryBestuur_docx = $satisfactionSummaryBestuur -> render($xmlData, $ref, $this->config);
        unset($satisfactionSummaryBestuur);
        
        if ($verbose) { print 'satisfacitonImportance';}           
        $satisfactionImportance = new satisfactionImportance();
        $satisfactionImportance_docx = $satisfactionImportance -> render($xmlData, $ref, $this->config);
        unset($satisfactionImportance);
               
        if ($verbose) { print 'satisfacitonImportanceBestuur';}           
        $satisfactionImportanceBestuur = new satisfactionImportanceBestuur();
        $satisfactionImportanceBestuur_docx = $satisfactionImportanceBestuur -> render($xmlData, $ref, $this->config);
        unset($satisfactionImportanceBestuur);
               
        if ($verbose) { print "scoresPercentagesBestuur\n";}
        $scoresPercentagesBestuur = new scoresPercentagesBestuur();
        $scoresPercentagesBestuur_docx = $scoresPercentagesBestuur -> render($xmlData, $ref);
        unset($scoresPercentagesBestuur);
               
        if ($verbose) { print "responsBestuur\n";}
        $responsBestuur = new responsBestuur();
        $responsBestuur_docx = $responsBestuur -> render($xmlData, $ref);
        unset($responsBestuur);
               
        $docx = new CreateDocx();
        
        $docx->setTemplateSymbol('TTT');

        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();

        foreach ($variables['document'] as $template_variable) {
//            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
            if ($verbose) {print "got variable type: " . $type . " for variable: " . $variable . "\n";}
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
                    if ($verbose) { print "scoreExample\n";}
                    $docx -> addTemplateVariable('class:scoreExample', $scores_example_docx, 'docx');
                }
                if ($variable == "percentageExample") {
                    if ($verbose) { print "percentageExample\n";}
                    $docx -> addTemplateVariable('class:percentageExample', $percentage_example_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
                    if ($verbose) { print "scoresAndPercentages\n";}
                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }
                if ($variable == "reportmark") {
                    if ($verbose) { print "reportmark\n";}
                    $docx -> addTemplateVariable('class:reportmark', $reportmark_docx, 'docx');
                }
                if ($variable == "satisfaction") {
                    if ($verbose) { print "satisfaction\n";}
                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');
                }
                if (($satisfactionBestuur_docx !==0) && ($variable == "satisfactionBestuur")) {
                    if ($verbose) { print "satisfactionBestuur\n";}
                    $docx -> addTemplateVariable('class:satisfactionBestuur', $satisfactionBestuur_docx, 'docx');
                }
                if ($variable == "satisfactionPerCategoryBestuur") {
                    if ($verbose) { print "satisfactionPerCategoryBestuur\n";}
                    $docx -> addTemplateVariable('class:satisfactionPerCategoryBestuur', $satisfactionPerCategoryBestuur_docx, 'docx');
                }
                
                if (($importance_docx !== 0) && ($variable == "satisfactionimportance")) {
                    if ($verbose) { print "satisfactionimportance $importance_docx\n";}
                    $docx -> addTemplateVariable('class:satisfactionimportance', $importance_docx, 'docx');
                }
                if (($satisfactionPriorityScatter_docx !== 0) && ($variable == "satisfactionPriorityScatter")) {
                    if ($verbose) { print "satisfactionPriorityScatter $satisfactionPriorityScatter_docx\n";}
                    $docx -> addTemplateVariable('class:satisfactionPriorityScatter', $satisfactionPriorityScatter_docx, 'docx');
                }
                if (($satisfactionPriorityScatterBestuur_docx !== 0) && ($variable == "satisfactionPriorityScatterBestuur")) {
                    if ($verbose) { print "satisfactionPriorityScatter $satisfactionPriorityScatterBestuur_docx\n";}
                    $docx -> addTemplateVariable('class:satisfactionPriorityScatterBestuur', $satisfactionPriorityScatterBestuur_docx, 'docx');
                }
                if ($variable == "mostimportance") {
                    if ($verbose) { print "mostimportance\n";}
                    $docx -> addTemplateVariable('class:mostimportance', $mostimportant_docx, 'docx');
                }
                if ($variable == "satisfactionTop") {
                    if ($verbose) { print "satisfactionTop\n";}
                    $docx -> addTemplateVariable('class:satisfactionTop:good', $satisfactionTopGood_docx, 'docx');
                    $docx -> addTemplateVariable('class:satisfactionTop:bad', $satisfactionTopBad_docx, 'docx');
                }
                if ($variable == "scoresAndPercentages") {
                    if ($verbose) { print "scoresAndPercentages\n";}
                    $docx -> addTemplateVariable('class:scoresAndPercentages', $scoresAndPercentages_docx, 'docx');
                }

                //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
                    if ($variable == "percentiles") {
                        if ($verbose) { print "percentiles\n";}
                        $docx -> addTemplateVariable('class:percentiles:good', $percentiles_good_docx, 'docx');
                    }
                    if ($variable == "percentiles") {
                        if ($verbose) { print "percentiles\n";}
                        $docx -> addTemplateVariable('class:percentiles:bad', $percentiles_bad_docx, 'docx');
                    }
                //}
                if (($previous_docx !== 0) && ($variable == "previous")) {
                    if ($verbose) { print "previous $previous_docx \n";}
                    $docx -> addTemplateVariable('class:previous', $previous_docx, 'docx');
                }
                if (($previousBestuur_docx !== 0) && ($variable == "previousBestuur")) {
                    if ($verbose) { print "previousBestuur $previous_docx \n";}
                    $docx -> addTemplateVariable('class:previousBestuur', $previousBestuur_docx, 'docx');
                }
                if ($variable == "summary") {
                    if ($verbose) { print "summary\n";}
                    $docx -> addTemplateVariable('class:summary', $summary_docx, 'docx');
                }

                if ($variable == "questionLists") {
                    if ($verbose) { print "questionLists\n";}
                    $docx -> addTemplateVariable('class:questionLists', $questionList_docx, 'docx');
                }
              
                if ($variable == "satisfactionImportanceBestuur") {
                    if ($verbose) { print "satisfactionImportanceBestuur\n";}
                    $docx -> addTemplateVariable('class:satisfactionImportanceBestuur', $satisfactionImportanceBestuur_docx, 'docx');
                }
                if ($variable == "satisfactionImportance") {
                    if ($verbose) { print "satisfactionImportance\n";}
                    $docx -> addTemplateVariable('class:satisfactionImportance', $satisfactionImportance_docx, 'docx');
                }
                if (($reportmarkBestuur_docx !== 0 ) && ($variable == "reportmarkBestuur")) {
                    if ($verbose) { print "reportmarkBestuur\n";}
                    $docx -> addTemplateVariable('class:reportmarkBestuur', $reportmarkBestuur_docx, 'docx');
                }
                if (($satisfactionTopGoodBestuur_docx !== 0 ) && ($variable == "satisfactionTopBestuur")) {
                    if ($verbose) { print "satisfactionTopBestuur\n";}
                    $docx -> addTemplateVariable('class:satisfactionTopBestuur:good', $satisfactionTopGoodBestuur_docx, 'docx');
                    $docx -> addTemplateVariable('class:satisfactionTopBestuur:bad', $satisfactionTopBadBestuur_docx, 'docx');
                }
                if (($summaryBestuur_docx !== 0 ) && ($variable == "summaryBestuur")) {
                    if ($verbose) { print "summary\n";}
                    $docx -> addTemplateVariable('class:summaryBestuur', $summaryBestuur_docx, 'docx');
                }
                if (($satisfactionSummary_docx !== 0 ) && ($variable == "satisfactionSummary")) {
                    if ($verbose) { print "satisfactionSummary\n";}
                    $docx -> addTemplateVariable('class:satisfactionSummary', $satisfactionSummary_docx, 'docx');
                }
                if (($satisfactionSummaryBestuur_docx !== 0 ) && ($variable == "satisfactionSummaryBestuur")) {
                    if ($verbose) { print "satisfactionSummaryBestuur\n";}
                    $docx -> addTemplateVariable('class:satisfactionSummaryBestuur', $satisfactionSummaryBestuur_docx, 'docx');
                }
                if (($scoresPercentagesBestuur_docx !== 0 ) && ($variable == "scoresPercentagesBestuur")) {
                    if ($verbose) { print "scoresPercentagesBestuur\n";}
                    $docx -> addTemplateVariable('class:scoresPercentagesBestuur', $scoresPercentagesBestuur_docx, 'docx');
                }
                if (($responsBestuur_docx !== 0 ) && ($variable == "responsBestuur")) {
                    if ($verbose) { print "responsBestuur\n";}
                    $docx -> addTemplateVariable('class:responsBestuur', $responsBestuur_docx, 'docx');
                }

            }

        }

        $questionProperties = new questionProperties();
        $questionProperties->process($xmlData, $docx);
        $mostimportant = new mostimportant();
        $mostimportant -> process($xmlData, $docx);
        $reportmark = new reportmark();
        $reportmark -> process($xmlData, $docx);
        $reportmarkBestuur = new reportmarkBestuur();
        $reportmarkBestuur -> process($xmlData, $docx);

        if ($verbose) { print "laststeps docx\n";}
        $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));
        $docx->modifyPageLayout('A4'); 
        //remove unwanted .docx extension, this will be autogenerated by PHPDocx
        $output_file = preg_replace('/\.docx$/','',$output_file);
		$text = 'Lorem ipsum dolor sit amet.';
		$docx -> addText($text);
        if(php_sapi_name() == "cli") {
            //In cli-mode
                if ($verbose) { print "createdocx cli\n";}
                $docx -> createDocx($output_file);
        	echo "Done\n";
        } else {
            //Not in cli-mode
                if ($verbose) { print "createdocx web\n";}
               $docx -> createDocxAndDownload();
        }
		//opruimen:
        if (file_exists($percentage_example_docx)) unlink($percentage_example_docx);
        if (file_exists($scores_example_docx)) unlink($scores_example_docx);
        if (file_exists($reportmark_docx)) unlink($reportmark_docx);
        if (file_exists($reportmarkBestuur_docx)) unlink($reportmarkBestuur_docx);
        if (file_exists($satisfaction_docx)) unlink($satisfaction_docx);
        if (file_exists($satisfactionBestuur_docx)) unlink($satisfactionBestuur_docx);
        if (file_exists($importance_docx)) unlink($importance_docx);
        if (file_exists($satisfactionPriorityScatter_docx)) unlink($satisfactionPriorityScatter_docx);
        if (file_exists($satisfactionPriorityScatterBestuur_docx)) unlink($satisfactionPriorityScatterBestuur_docx);
        if (file_exists($mostimportant_docx)) unlink($mostimportant_docx);
        if (file_exists($satisfactionTopGood_docx)) unlink($satisfactionTopGood_docx);
        if (file_exists($satisfactionTopBad_docx)) unlink($satisfactionTopBad_docx);
        if (file_exists($satisfactionTopGoodBestuur_docx)) unlink($satisfactionTopGoodBestuur_docx);
        if (file_exists($satisfactionTopBadBestuur_docx)) unlink($satisfactionTopBadBestuur_docx);
        if (file_exists($scoresAndPercentages_docx)) unlink($scoresAndPercentages_docx);
        //if ($xmlData['basetype'] != 2) { //ltp has no percentiles
            if (file_exists($percentiles_good_docx)) unlink($percentiles_good_docx);
            if (file_exists($percentiles_bad_docx)) unlink($percentiles_bad_docx);
        //}
        if (file_exists($previous_docx)) unlink($previous_docx);
        if (file_exists($previousBestuur_docx)) unlink($previousBestuur_docx);
        if (file_exists($summary_docx)) unlink($summary_docx);
        if (file_exists($satisfactionSummary_docx)) unlink($satisfactionSummary_docx);
        if (file_exists($satisfactionSummaryBestuur_docx)) unlink($satisfactionSummaryBestuur_docx);
        if (file_exists($satisfactionImportance_docx)) unlink($satisfactionImportance_docx);
        if (file_exists($satisfactionImportanceBestuur_docx)) unlink($satisfactionImportanceBestuur_docx);
        if (file_exists($scoresPercentagesBestuur_docx)) unlink($scoresPercentagesBestuur_docx);
        if (file_exists($responsBestuur_docx)) unlink($responsBestuur_docx);
        if (file_exists($questionList_docx)) unlink($questionList_docx);
        
				
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
        
//        $reportmarkBestuur = new reportmarkBestuur();
//        $reportmarkBestuur_docx = $reportmarkBestuur -> render($xmlData, $ref);
//        unset($reportmarkBestuur);
        
//        $importance = new satisfaction();
//        $importance_docx = $importance -> render($xmlData, $ref, 'importance');
//        unset($importance);

//        $satisfactionPerCategoryBestuur = new satisfactionPerCategoryBestuur();
//        $satisfactionPerCategoryBestuur_docx = $satisfactionPerCategoryBestuur -> render($xmlData, $ref, 'satisfaction');
//        unset($satisfactionPerCategoryBestuur);
        

//        $satisfaction = new satisfaction();
//        $satisfaction_docx = $satisfaction -> render($xmlData, $ref, 'satisfaction');
//        unset($satisfaction);
       
        $satisfactionBestuur = new satisfactionBestuur();
        $satisfactionBestuur_docx = $satisfactionBestuur -> render($xmlData, $ref, 'satisfaction');
        unset($satisfactionBestuur);
       
               
//        $satisfactionPriorityScatter = new satisfactionPriorityScatter();
//        $satisfactionPriorityScatter_docx = $satisfactionPriorityScatter -> render($xmlData, $ref);
//        unset($satisfactionPriorityScatter);
               
//        $satisfactionPriorityScatterBestuur = new satisfactionPriorityScatterBestuur();
//        $satisfactionPriorityScatterBestuur_docx = $satisfactionPriorityScatterBestuur -> render($xmlData, $ref, $this->config);
//        unset($satisfactionPriorityScatterBestuur);
               
//        $satisfactionTopGood = new satisfactionTop();
//        $satisfactionTopGood_docx = $satisfactionTopGood -> render($xmlData, $ref, TRUE);
//        unset($satisfactionTopGood);        
        
//        $satisfactionTopBad = new satisfactionTop();
//        $satisfactionTopBad_docx = $satisfactionTopBad -> render($xmlData, $ref, FALSE);
//        unset($satisfactionTopBad);        
        
//        $satisfactionTopGoodBestuur = new satisfactionTopBestuur();
//        $satisfactionTopGoodBestuur_docx = $satisfactionTopGoodBestuur -> render($xmlData, $ref, TRUE);
//        unset($satisfactionTopGoodBestuur);        
        
//        $satisfactionTopBadBestuur = new satisfactionTopBestuur();
//        $satisfactionTopBadBestuur_docx = $satisfactionTopBadBestuur -> render($xmlData, $ref, FALSE);
//        unset($satisfactionTopBadBestuur);        
        
                       
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
               
        $previousBestuur = new previousBestuur();
        $previousBestuur_docx = $previousBestuur -> render($xmlData, $ref);
        unset($previousBestuur);
               
//        $summary = new summary();
//        $summary_docx = $summary -> render($xmlData, $ref);
//        unset($summary);
               
//        $summaryBestuur = new summaryBestuur();
//        $summaryBestuur_docx = $summaryBestuur -> render($xmlData, $ref);
//        unset($summaryBestuur);
               
//        if ($verbose) { print 'satisfactionSummary';}
//        $satisfactionSummary = new satisfactionSummary();
//        $satisfactionSummary_docx = $satisfactionSummary -> render($xmlData, $ref, $this->config);
//        unset($satisfactionSummary);
        
//        $satisfactionSummaryBestuur = new satisfactionSummaryBestuur();
//        $satisfactionSummaryBestuur_docx = $satisfactionSummaryBestuur -> render($xmlData, $ref, $this->config);
//        unset($satisfactionSummaryBestuur);
        
               
//        $questionList = new questionList();
//        $questionList_docx = $questionList -> render($xmlData, $ref);
//        unset($questionList);

//        $satisfactionImportance = new satisfactionImportance();
//        $satisfactionImportance_docx = $satisfactionImportance -> render($xmlData, $ref);
//        unset($satisfactionImportance);

//        $satisfactionImportanceBestuur = new satisfactionImportanceBestuur();
//        $satisfactionImportanceBestuur_docx = $satisfactionImportanceBestuur -> render($xmlData, $ref, $this->config);
//        unset($satisfactionImportanceBestuur);
               
//        $scoresPercentagesBestuur = new scoresPercentagesBestuur();
//        $scoresPercentagesBestuur_docx = $scoresPercentagesBestuur -> render($xmlData, $ref);
//        unset($scoresPercentagesBestuur);
               
        $responsBestuur = new responsBestuur();
        $responsBestuur_docx = $responsBestuur -> render($xmlData, $ref);
        unset($responsBestuur);
               
                                                                                          
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
                if ($variable == "reportmarkBestuur") {
//                    $docx -> addTemplateVariable('class:reportmarkBestuur', $reportmarkBestuur_docx, 'docx');
                }
                if ($variable == "satisfactionPriorityScatter") {
//                    $docx -> addTemplateVariable('class:satisfactionPriorityScatter', $satisfactionPriorityScatter_docx, 'docx');
                }
                if ($variable == "satisfactionPriorityScatterBestuur") {
//                    $docx -> addTemplateVariable('class:satisfactionPriorityScatterBestuur', $satisfactionPriorityScatterBestuur_docx, 'docx');
                }
                if ($variable == "satisfaction") {
//                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');
                }
                if (($satisfactionBestuur_docx !== 0) && ($variable == "satisfactionBestuur")) {
                    $docx -> addTemplateVariable('class:satisfactionBestuur', $satisfactionBestuur_docx, 'docx');
                }
                if ($variable == "satisfactionPerCategoryBestuur") {
//                    $docx -> addTemplateVariable('class:satisfactionPerCategoryBestuur', $satisfactionPerCategoryBestuur_docx, 'docx');
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
                if ($variable == "satisfactionTopBestuur") {
//                    $docx -> addTemplateVariable('class:satisfactionTopBestuur:good', $satisfactionTopGoodBestuur_docx, 'docx');
                    //$docx -> addTemplateVariable('class:satisfactionTopBestuur:bad', $satisfactionTopBadBestuur_docx, 'docx');
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
//                if (($previous_docx != 0) && ($variable == "previous")) {
//                    $docx -> addTemplateVariable('class:previous', $previous_docx, 'docx');
//                }
                if (($previousBestuur_docx !== 0) && ($variable == "previousbestuur")) {
var_dump($previousBestuur_docx);
                    echo "prev";
                    $docx -> addTemplateVariable('class:previousbestuur', $previousBestuur_docx, 'docx');
                }
                if ($variable == "summary") {
//                    $docx -> addTemplateVariable('class:summary', $summary_docx, 'docx');
                }
                if ($variable == "summaryBestuur") {
//                    $docx -> addTemplateVariable('class:summaryBestuur', $summaryBestuur_docx, 'docx');
                }
                if ($variable == "questionLists") {
//                    $docx -> addTemplateVariable('class:questionLists', $questionList_docx, 'docx');
                }
              
                if ($variable == "satisfactionSummary") {
//                    $docx -> addTemplateVariable('class:satisfactionSummary', $satisfactionSummary_docx, 'docx');
                }
                if ($variable == "satisfactionSummaryBestuur") {
//                    $docx -> addTemplateVariable('class:satisfactionSummaryBestuur', $satisfactionSummaryBestuur_docx, 'docx');
                }
                if ($variable == "satisfactionImportance") {
//                    $docx -> addTemplateVariable('class:satisfactionImportance', $satisfactionImportance_docx, 'docx');
                }
                if ($variable == "satisfactionImportanceBestuur") {
//                    $docx -> addTemplateVariable('class:satisfactionImportanceBestuur', $satisfactionImportanceBestuur_docx, 'docx');
                }
                if ($variable == "scoresPercentagesBestuur") {
//                    $docx -> addTemplateVariable('class:scoresPercentagesBestuur', $scoresPercentagesBestuur_docx, 'docx');
                }
                if (($responsBestuur_docx !== 0 ) && ($variable == "responsBestuur")) {
var_dump($responsBestuur_docx);
                    $docx -> addTemplateVariable('class:responsBestuur', $responsBestuur_docx, 'docx');
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
