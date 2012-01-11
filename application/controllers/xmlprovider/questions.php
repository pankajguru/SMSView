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

    public function category_questions_get( $category_id ) {
        if ( !$category_id ) {
            $this -> response( NULL, 400 );
        }

        $questions = $this -> Sms_model -> get_category_questions( $category_id );
        $this -> response( $questions, 200 );
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
	    if ( !isset($category_id) ) {
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
	public function school_id_get() {
		$data['school_id'] = 200;
		$this -> response($data, 200);
	}

	private function _log_in_first() {
		$data['message'] = "U bent niet ingelogd!";
		return $data;
	}

}
