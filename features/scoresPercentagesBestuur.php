<?php

class scoresPercentagesBestuur
{

    function render( &$data, $ref)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        if (!isset($data['all.questions.bestuur'])){
            return 0;
        };
        $datastring     = $data['all.questions.bestuur'];
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        $scoresAndPercentages_docx = new CreateDocx();
        $categories     = array();
        
        $paramsTitle = array(
                'val' => 2,
            );
        //create array iso object
        $all_questions_array = array();
        if (count($all_questions) == 0){
            return 0;
        }
        foreach($all_questions as $question_number=>$question){
            $all_questions_array[intval($question_number)] = $question;
        };
        ksort($all_questions_array);
        //get all categories
        foreach($all_questions_array as $question_number=>$question){
            $invalid_question_types = array('KIND_GROEP', 'KIND_GRP_BELGIE','JONGEN_MEIJSE', 'JONGEN_MEISJE' ,'BEVOLKINGSGROEP','OUDERS_SCHOOLOPLEIDING', 'PTP_GENDER', 'PTP_AGE','SCHOOLOPLEIDING_OUDERS_BELGIE', 'NATIONALITEIT_BELGIE', 'KIND_GROEP_BELGIE');
            if (in_array($question->{'question_type'}[0][1], $invalid_question_types)){
//                continue;
            }
            if (!in_array($question->{'group_name'}, $categories)){
                $categories[$question->{'group_name'}] = $question->{'group_name'};
            }
        }        
        
        $docx_array = array();
        //loop through categories to create docxs
        foreach($categories as $groupname => $category){            
            $percentages = new percentagesBestuur();
            $docx_array[$groupname." percentages"] = $percentages -> render($data, $ref, $category);
            unset($percentages);
            
            $scoresBestuur = new scoresBestuur();
            $docx_array[$groupname. " scores"] = $scoresBestuur -> render($data, $ref, $category, '','');
            unset($scoresBestuur);
        
        }
//        $this->_error_dump($docx_array);
        //loop through docxs
        $count = 0;
        foreach($docx_array as $groupname => $sap_docx){
            //create group heading
            if ($sap_docx != null){
                $scoresAndPercentages_docx->addText(array(array(
                    'text' => 'Rubriek '.$count.' '.$groupname, 
                    'b' => 'single', 
                    'color' => 'F78E1E',
                    'sz' => 10,
                    'font' => 'Century Gothic',
                )));
                $scoresAndPercentages_docx->addBreak('line');
                $scoresAndPercentages_docx->addDOCX($sap_docx);                   
                $scoresAndPercentages_docx->addBreak('page');
                if (preg_match('/scores$/', $groupname)){
                    $count++;
                }
            }
        }
		$filename = $temp.'scoresAndPercentagesBestuur'.randchars(12);
        $scoresAndPercentages_docx->createDocx($filename);
        unset($scoresAndPercentages_docx);
        foreach($docx_array as $groupname => $sap_docx){
            if ($sap_docx != null){
                unlink($sap_docx);
            }
        }
        return $filename.'.docx';
        
    }
    function _error_dump($object) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

    

}
