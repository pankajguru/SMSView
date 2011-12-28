<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php'); 

class Questions extends REST_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
    }

	/**
	 * Index Page for this controller.
	 *
	 * Create XML files with questions from sms database used by oqsurvey 
	 */
	public function all_questions_get($type)
	{
        $questions = $this->Sms_model->get_all_questions($type);  
        if($questions && $type)  
        {  
            $this->response($questions, 200); // 200 being the HTTP response code  
        }  
  
        else  
        {  
            $this->response(NULL, 404);  
        }  
	}

    public function questions_get()
    {
        if(!$this->get('school_id'))  
        {  
            $this->response(NULL, 400);  
        }  
  
        $questions = $this->Sms_model->get_all_questionaires_by_school(  );  
  
        if($questions)  
        {  
            $this->response($questions, 200); // 200 being the HTTP response code  
        }  
  
        else  
        {  
            $this->response(NULL, 404);  
        }  
    }
}

