<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	
    public function index() {
        /** test pagina */

        /** test db connectivity */
        $data['peilingen'] = $this -> Sms_model -> get_last_ten_entries();

        $this -> load -> view('tools/update', $data);
    }

    public function calculate_question_results() {
        $peilingen = $this -> Sms_model -> get_peilingen_not_calculated();

		foreach ($peilingen as $peiling){
			$peiling_id = $peiling->{'id'};
			$type_id = $peiling->{'type_id'};
			$questions = $this -> Sms_model -> get_all_questions_by_peiling_type($type_id);
			print "Peiling $peiling_id <br>\n";
			foreach ($questions as $question){
				$question_id = $question->{'question_id'};
				$result = $this -> Sms_model -> set_calculated_result($peiling_id, $question_id);
				print "Calculated $question_id as $result<br>\n";
			}
		}
		$this -> load -> view('tools/default');
    }
	
}