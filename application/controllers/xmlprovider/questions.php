<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require (APPPATH . '/libraries/REST_Controller.php');

class Questions extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this -> load -> library('tank_auth');
    }

    public function save_questionaire_post() {
        $questionaire_json = $this -> post('data');
		$questionaire_object = json_decode($questionaire_json);
		$filename = $questionaire_object[0]->{'filename'};
		$id = $this->tank_auth->get_user_id();
		$directory = BASEPATH.'/../json'.'/'.$id;
		$filename = $this -> _sanitize_filename($filename);
		if (!is_dir($directory)){
			//create the directory
			mkdir($directory);
		}
		file_put_contents($directory.'/'.$filename.'.json', $questionaire_json);
		$this -> response(array('status' => 'success', 'responseText' => $filename));
    }

	public function saved_questionaires_get(){
		$dirs = array();
		$id = $this->tank_auth->get_user_id();
		$directory = BASEPATH.'/../json'.'/'.$id;
		if ($handle = opendir($directory)) {
    		while (false !== ($entry = readdir($handle))) {
        		if ($entry != "." && $entry != "..") {
        			$entry = str_replace('.json','',$entry);
            		array_push($dirs,$entry);
        		}
    		}
    		closedir($handle);
		}
		
		$this -> response($dirs, 200);
		
	}
	public function saved_questionaire_get($filename){
		$dirs = array();
		$id = $this->tank_auth->get_user_id();
		$directory = BASEPATH.'/../json'.'/'.$id.'/';
		$questionaire = file_get_contents($directory.$filename.'.json');
		
		$this -> response($questionaire, 200);
		
	}

	public function accounts_admin_get(){
		$this->load->model('tank_auth/users');
		$accounts =	$this -> users -> get_users();
		$users = Array();
		foreach ($accounts as $key => $value) {
			$directory = BASEPATH.'/../json'.'/'.$value->{'id'};
			if (is_dir($directory)){
				$users[] = $value;
			}
		}
		$this -> response($users, 200);
		
	}

	public function saved_questionaires_admin_get($id){
		$dirs = array();
		$directory = BASEPATH.'/../json'.'/'.$id;
		if ($handle = opendir($directory)) {
    		while (false !== ($entry = readdir($handle))) {
        		if ($entry != "." && $entry != "..") {
        			$entry = str_replace('.json','',$entry);
            		array_push($dirs,$entry);
        		}
    		}
    		closedir($handle);
		}
		
		$this -> response($dirs, 200);
		
	}

	public function saved_questionaire_admin_get($filename, $id){
		$dirs = array();
		$directory = BASEPATH.'/../json'.'/'.$id.'/';
		$questionaire = file_get_contents($directory.$filename.'.json');
		
		$this -> response($questionaire, 200);
		
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
//            $question -> benchmark = $this -> Sms_model -> get_question_benchmark($question->question_id); TOO SLOW
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
        $this->_error_dump($questionaire_json);
        $questionaire_object = json_decode($questionaire_json);
        $result = $this -> Sms_model -> insert_questionaire($questionaire_object);
        
        if ($result['success'] === FALSE) {
            $this -> response(array('status' => 'failed', 'responseText' => $result['status']));
        } else {
            $base_dir = "/home/foo/production/sms";
            $peiling_type_id = $result['peiling_type_id'];
            system("mkdir $base_dir/report/special/scan/MUIS_$peiling_type_id");
            system("cp -r $base_dir/utilities/scan/* $base_dir/report/special/scan/MUIS_$peiling_type_id/");
            $questions = array();
            exec("$base_dir/tasks/foo/create-form-abstract.pl MUIS_$peiling_type_id", $questions);
            $questions_string = implode("\n",$questions);
            $template = file_get_contents("$base_dir/utilities/abstract.pl.template.muis");
            $template = preg_replace('/TTTquestionsTTT/', $questions_string, $template);
            $template = preg_replace('/TTTsequenceTTT/', $peiling_type_id, $template);
            file_put_contents("$base_dir/report/special/scan/MUIS_$peiling_type_id/abstract.pl", $template);
            $base_type = $questionaire_object[0]->basetype;
            $qt_result = $this -> _questiontool_set_questionaire($result['peiling_type_id'], $base_type);
            $this -> response(array('status' => 'success', 'responseText' => $result['status'])); 
        }

    }

    public function questionaire_repost_get($peiling_type_id,$base_type){
        $qt_result = $this -> _questiontool_set_questionaire($peiling_type_id, $base_type);
        echo $peiling_type_id.' '.$base_type;
        echo $qt_result;
    }

    private function _log_in_first() {
        $data['message'] = "U bent niet ingelogd!";
        return $data;
    }

	/**
	 * Function: sanitize
	 * Returns a sanitized string, typically for URLs.
	 *
	 * Parameters:
	 *     $string - The string to sanitize.
	 *     $force_lowercase - Force the string to lowercase?
	 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
	 */
	function _sanitize_filename($string, $force_lowercase = true, $anal = false) {
	    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
	                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
	                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
	    $clean = trim(str_replace($strip, "", strip_tags($string)));
	    $clean = preg_replace('/\s+/', "-", $clean);
	    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	    return ($force_lowercase) ?
	        (function_exists('mb_strtolower')) ?
	            mb_strtolower($clean, 'UTF-8') :
	            strtolower($clean) :
	        $clean;
	}



    function _error_dump($object) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}
