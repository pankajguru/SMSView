<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
        if (!$this -> tank_auth -> is_logged_in()) {                                 // logged in
            //$this->response($this->_log_in_first(), 200);
        }

        $questions = $this -> Sms_model -> get_all_questions($type);
        foreach ($questions as $question) {
            //$question -> answers = $this -> Sms_model -> get_question_properties($question -> vraag_type_id);
            print $question->description.'<br>';
        }
        
    }
}

