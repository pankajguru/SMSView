<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require (APPPATH . '/libraries/REST_Controller.php');

class Questions extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this -> load -> library('tank_auth');
    }

    /**
     * Index Page for this controller.
     *
     * Create XML files with questions from sms database used by oqsurvey
     */
    public function all_questions_get($type) {
        if (!$this -> tank_auth -> is_logged_in()) {                                 // logged in
            //$this->response($this->_log_in_first(), 200);
        }

        $questions = $this -> Sms_model -> get_all_questions($type);
        foreach ($questions as $question) {
            $question -> answers = $this -> Sms_model -> get_question_properties($question -> vraag_type_id);
        }
        if ($questions && $type) {
            $this -> response($questions, 200);
            // 200 being the HTTP response code
        } else {
            $this -> response(NULL, 404);
        }
    }

    public function category_questions_get($category_id) {
        if (!$category_id) {
            $this -> response(NULL, 400);
        }

        $questions = $this -> Sms_model -> get_category_questions($category_id);
        $this -> response($questions, 200);
    }

    public function school_questions_get($type, $school_id) {
        if (!$school_id) {
            $this -> response(NULL, 400);
        }

        $questionaires = $this -> Sms_model -> get_all_questionaires_by_school($type, $school_id);
        foreach ($questionaires as $questionaire) {
            $questionaire -> questions = $this -> Sms_model -> get_all_questions_by_peiling_type($questionaire -> type_id);
        }
        if ($questionaires) {
            $this -> response($questionaires, 200);
            // 200 being the HTTP response code
        } else {
            $this -> response(NULL, 404);
        }
    }

    public function category_get($category_id = NULL) {
        if (!isset($category_id)) {
            $categories = $this -> Sms_model -> get_all_categories();
            $this -> response($categories, 200);
        }

        $categories = $this -> Sms_model -> get_category_details($category_id);
        if ($categories) {
            $this -> response($categories, 200);
            // 200 being the HTTP response code
        } else {
            $this -> response(NULL, 404);
        }

    }

    public function template_get($type = NULL) {
        $base_questions = $this -> Sms_model -> get_all_questions_by_peiling_type_desc_code($type);
        $base_question_ids = array();
        foreach ($base_questions as $base_question) {
            $base_question_ids[] = $base_question -> question_id;
        }

        if (count($base_question_ids)>0) {
            $this -> response($base_question_ids, 200);
        } else {
            $this -> response(NULL, 404);
        }

    }

    public function school_id_get() {
        $data['school_id'] = 200;
        $this -> response($data, 200);
    }

    public function questionaire_post() {
        $questionaire_json = $this -> post('data');
        $questionaire_object = json_decode($questionaire_json);
        $result = $this -> Sms_model -> insert_questionaire($questionaire_object);
        
        if ($result['success'] === FALSE) {
            $this -> response(array('status' => 'failed', 'responseText' => $result['status']));
        } else {
            $base_dir = "/home/foo/production/sms";
            $peiling_type_id = $result['peiling_type_id'];
            system("cp -r $base_dir/utilities/scan $base_dir/report/special/scan/MUIS_$peiling_type_id");
            $questions = array();
            exec("$base_dir/tasks/foo/create-form-abstract.pl MUIS_$peiling_type_id", $questions);
            $questions_string = implode("\n",$questions);
            $template = file_get_contents("$base_dir/utilities/abstract.pl.template.muis");
            $template = preg_replace('/TTTquestionsTTT/', $questions_string, $template);
            $template = preg_replace('/TTTsequenceTTT/', $peiling_type_id, $template);
            file_put_contents("$base_dir/report/special/scan/MUIS_$peiling_type_id/abstract.pl", $template);
            $base_type = $questionaire_object[0]->basetype;
            $qt_result = $this -> _questiontool_set_questionaire($result['peiling_type_id'], $base_type);
            $this -> response(array('status' => 'success', 'responseText' => $result['status'] . ' '. $qt_result)); 
        }

    }

    private function _log_in_first() {
        $data['message'] = "U bent niet ingelogd!";
        return $data;
    }

    private function _questiontool_set_questionaire($peiling_type_id, $base_type) {
        //get qestions id's from formulier_type_definition
        $question_ids = $this -> Sms_model -> get_all_questions_by_peiling_type($peiling_type_id);
        $otp_questions = $this -> Sms_model -> get_all_questions_by_peiling_type(1);
        $ltp_questions = $this -> Sms_model -> get_all_questions_by_peiling_type(2);
        $ptp_questions = $this -> Sms_model -> get_all_questions_by_peiling_type(3);
        $base_question_ids = array();
        foreach ($otp_questions as $otp_question) {
            $base_question_ids[] = $otp_question -> question_id;
        }
        foreach ($ltp_questions as $ltp_question) {
            $base_question_ids[] = $ltp_question -> question_id;
        }
        foreach ($ptp_questions as $ptp_question) {
            $base_question_ids[] = $ptp_question -> question_id;
        }
        //foreach question, add to xml
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><xml/>");
        $peiling_type_details = $this -> Sms_model -> get_peiling_type_details( $peiling_type_id );
        $xml->addChild('peiling_type', $peiling_type_details[0] -> desc_code);
        $xml->addChild('base_type', $base_type);
        $xml_questions = $xml->addChild('questions');
        $sort_order = 0;
        foreach ($question_ids as $question_id) {
            $question = $this -> Sms_model -> get_question_by_id($question_id -> question_id);
            $answers = $this -> Sms_model -> get_answers_by_question_type_id($question[0] -> vraag_type_id);
            $question_type = $this -> Sms_model -> get_question_type_by_id($question[0] -> vraag_type_id);
            $xml_question = $xml_questions->addChild('question');
            $xml_question->addChild('question', $question[0]->description);
            $xml_question->addChild('sort_order', $sort_order++);
            $category = $this -> Sms_model -> get_category_details($question[0]->vraag_groep_id);
            $xml_question->addChild('category', htmlentities($category[0]->description, null , 'UTF-8')); 
            $xml_question->addChild('category_explanation', $category[0]->description); 
            $xml_question->addChild('required', true);  //TODO:get required
            $xml_question->addChild('inputnote',''); //TODO
            $priority = ($question_type[0]->DESC_CODE == 'BELANGRIJK') ? 1 : 0;
            $xml_question->addChild('priority',$priority); 
            $question_type_description = (count($answers) > 0) ? 'answerlist':'open';
            $xml_question->addChild('questiontype', $question_type_description); 
            //$this->_error_dump($question_type);
            $standard = (
                (strpos($question_type[0]->DESC_CODE, 'MUIS_') === 0) || 
                (strpos($question_type[0]->DESC_CODE, 'AVL') === 0)
                ) ? 0 : 1;
            $standard = (in_array($question_id -> question_id, $base_question_ids)) ? 1 : 0;
            $xml_question->addChild('standard', $standard);  
            //add answers
            $xml_answers = $xml_question->addChild('answers');
            foreach ($answers as $answer){
                $xml_answer = $xml_answers->addChild('answer');
                $xml_answer->addChild('answer', $answer->description);
                $xml_answer->addChild('order', $answer->value);
                $xml_answer->addChild('value', $answer->value);  //TODO:0-100
            }
        }
        $xml = $xml->asXML();
        $xml = html_entity_decode($xml, ENT_NOQUOTES, 'UTF-8');
//        $this->_error_dump($xml);
        //send xml to QT
        $url = 'http://www.questiontool.nl/qt/customer/sms/muis.php';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);     
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'xml='.$xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        $response = curl_exec($ch);
        curl_close($ch);

        //return OK/NOK from QT
        return $response;
    }

    function _error_dump($object) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}
