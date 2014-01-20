<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Questions extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this -> load -> library('tank_auth');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Create web interface for questions
	 */
	public function all_questions_get($type) {
		if (!$this -> tank_auth -> is_logged_in()) {// logged in
			//$this->response($this->_log_in_first(), 200);
		}

		$questions = $this -> Sms_model -> get_all_questions($type);
		foreach ($questions as $question) {
			//$question -> answers = $this -> Sms_model -> get_question_properties($question -> vraag_type_id);
			print $question -> description . '<br>';
		}

	}

	public function all_questions_get_by_report_type_id($report_type_id) {
		if (!$this -> tank_auth -> is_logged_in()) {// logged in
			//$this->response($this->_log_in_first(), 200);
		}

		$questions = $this -> Sms_model -> get_all_questions_by_report_type_id($report_type_id);
		foreach ($questions as $question) {
			//$question -> answers = $this -> Sms_model -> get_question_properties($question -> vraag_type_id);
			print $question -> description . '<br>';
		}

	}

	public function get_questionaire_from_server() {
		$this -> load -> helper('form');
		$this -> load -> helper('url');

		$data['content'] = 'Kies de klant en de vragenlijst';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$filename = $_POST['filename'];
    		$id = $_POST['client'];
            //get rid of date after file
            $filename = rawurlencode(substr($filename,15));
			//get the json from the server
			$base_url = $this->config->item('vragenplanner_url');
            print $base_url.'/xmlprovider/questions/saved_questionaire_admin/' . $filename . '/' . $id;
			$questionaire_xml = file_get_contents($base_url.'/xmlprovider/questions/saved_questionaire_admin/' . $filename . '/' . $id);
print $questionaire_xml;
			$questionaire_xml_object = simplexml_load_string($questionaire_xml);
			$questionaire_json = $questionaire_xml_object->item[0];
			$questionaire_object = json_decode($questionaire_json);
			$result = $this -> Sms_model -> insert_questionaire($questionaire_object);

			if ($result['success'] === FALSE) {

			} else {
				$base_dir = "/home/foo/production/sms";
				$peiling_type_id = $result['peiling_type_id'];
				system("mkdir $base_dir/report/special/scan/MUIS_$peiling_type_id");
				system("cp -r $base_dir/utilities/scan/* $base_dir/report/special/scan/MUIS_$peiling_type_id/");
				$questions = array();
				exec("$base_dir/tasks/foo/create-form-abstract.pl MUIS_$peiling_type_id", $questions);
				$questions_string = implode("\n", $questions);
				$template = file_get_contents("$base_dir/utilities/abstract.pl.template.muis");
				$template = preg_replace('/TTTquestionsTTT/', $questions_string, $template);
				$template = preg_replace('/TTTsequenceTTT/', $peiling_type_id, $template);
				file_put_contents("$base_dir/report/special/scan/MUIS_$peiling_type_id/abstract.pl", $template);
				$base_type = $questionaire_object[0] -> basetype;
				$qt_result = $this -> _questiontool_set_questionaire($result['peiling_type_id'], $base_type);
			}

			$data['content'] = 'De vragenlijst ' . $filename . ' is toegevoegd als: ' . $result['status'];
		}
		$this -> load -> view('web/questions.php', $data);
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
            if ($question_id -> question_id == 0){
                continue;
            }
            $this->_error_dump($question_id);
            $question = $this -> Sms_model -> get_question_by_id($question_id -> question_id);
            $answers = $this -> Sms_model -> get_answers_by_question_type_id($question[0] -> vraag_type_id);
            $question_type = $this -> Sms_model -> get_question_type_by_id($question[0] -> vraag_type_id);
            $xml_question = $xml_questions->addChild('question');
            $xml_question->addChild('question', $question[0]->description);
            $xml_question->addChild('sort_order', $sort_order++);
            $category = $this -> Sms_model -> get_category_details($question[0]->vraag_groep_id);
            $xml_question->addChild('category', xmlentities(htmlentities($category[0]->description, null , 'UTF-8'))); 
            $xml_question->addChild('category_explanation', xmlentities(htmlentities($category[0]->description, null , 'UTF-8'))); 
            $xml_question->addChild('required', $question[0] -> strict);  
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
            
            $allowmoreanswers = ($question[0] -> exclusive == 0) ? 1 : 0;
            $xml_answers->addChild('allowmoreanswers', $allowmoreanswers);
            foreach ($answers as $answer){
                $xml_answer = $xml_answers->addChild('answer');
                $xml_answer->addChild('answer', $answer->description);
                $xml_answer->addChild('order', $answer->value);
                $xml_answer->addChild('value', $answer->value);  
            }
        }
        $xml = $xml->asXML();
        //hack for characters: TODO::upgrade production server
        $xml = html_entity_decode($xml, ENT_NOQUOTES || ENT_COMPAT, 'UTF-8');
        
        //send xml to QT
        $this->_error_dump($xml);
		file_put_contents('/tmp/testqt.xml',$xml);
        $url = 'http://www.questiontool.nl/qt/customer/sms/muis.php';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);     
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'xml='.$xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        //return OK/NOK from QT
        $this->_error_dump($response.' '.$curl_error);
        return $response;
    }

    function _error_dump($object) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log('error:'.$contents);
    }


}

function xmlentities($string) { 
   return str_replace ( array ( '&', '"', "'", '<', '>', '�' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $string ); 
} 
