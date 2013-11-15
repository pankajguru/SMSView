<?php

class questionList {

    function render($data, $ref) {

        require_once("./features/utils.php");
        $temp = 'temp/';
        $datastring     = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        //add graphic to docx
        $question_list_docx = new CreateDocx();
        $paramsText = array(
                'val' => 2,
                'sz' => 10,
                'font' => 'Century Gothic'
            );
        $paramsTitle = array(
                    'sz' => 10,
                    'color' => 'F78E1E',
                    'b' => 'double',
                    'font' => 'Century Gothic'
                )       ;     
        //create array iso object
        $all_questions_array = array();
        foreach($all_questions as $question_number=>$question){
            $all_questions_array[intval($question_number)] = $question;
        };
        
        ksort($all_questions_array);
        $first = TRUE;
        foreach($all_questions_array as $question_number=>$question){
            if (($first or ($question->{'group_name'} != $old_group_name))){

                    //create group heading
                $question_list_docx->addText(filter_text($question->{'group_name'}),$paramsTitle );
                $question_count = 0;
                
                $first = false;
                $old_group_name = $question->{'group_name'};
            }            
            $question_list_docx->addText($question_number.". ".filter_text($question->{'description'}),$paramsText);
//                $question_list_docx->addBreak('line');
            
        };
            
		$filename = $temp . 'questionList'.randchars(12);
        $question_list_docx -> createDocx($filename);
        unset($question_list_docx);
        return $filename . '.docx';

    }

    function _error_dump($object){
        ob_start();
        //var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

