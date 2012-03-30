<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update extends CI_Controller {

    /**
     * Constructor, we load our default helpers here
     */
    public function __construct()
    {
        require_once 'utilities/PHPExcel/PHPExcel/IOFactory.php';
        parent::__construct();
        //load html helper
        $this->load->helper('html');
    }

    /**
     * Index Page for this controller.
     *
     * Update several question sets
     * Use this only once!!
     */
    public function index() {
        /** test pagina */

        /** test db connectivity */
        $data['peilingen'] = $this -> Sms_model -> get_last_ten_entries();

        $this -> load -> view('tools/update', $data);
    }

    public function otp() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/otp.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        $new_id = 9795; //set to new id!!!!!!!
        foreach ($objWorksheet->getRowIterator() as $row) {

            $answer = array();
            $rownr = $row -> getRowIndex();
            if ($rownr == 1)
                continue;
            $rubriek = $objWorksheet -> getCell('A' . $rownr) -> getValue();
            $question = $objWorksheet -> getCell('B' . $rownr) -> getValue();
            $answer[0] = $objWorksheet -> getCell('C' . $rownr) -> getValue();
            $answer[1] = $objWorksheet -> getCell('D' . $rownr) -> getValue();
            $answer[2] = $objWorksheet -> getCell('E' . $rownr) -> getValue();
            $answer[3] = $objWorksheet -> getCell('F' . $rownr) -> getValue();
            $answer[4] = $objWorksheet -> getCell('G' . $rownr) -> getValue();
            $answer[5] = $objWorksheet -> getCell('H' . $rownr) -> getValue();
            $answer[6] = $objWorksheet -> getCell('I' . $rownr) -> getValue();
            $answer[7] = $objWorksheet -> getCell('J' . $rownr) -> getValue();
            $answer[8] = $objWorksheet -> getCell('K' . $rownr) -> getValue();
            $question_id = $objWorksheet -> getCell('N' . $rownr) -> getValue();
            $duplicate_ids[0] = $objWorksheet -> getCell('O' . $rownr) -> getValue();
            $duplicate_ids[1] = $objWorksheet -> getCell('P' . $rownr) -> getValue();
            $duplicate_ids[2] = $objWorksheet -> getCell('Q' . $rownr) -> getValue();
            $duplicate_ids[3] = $objWorksheet -> getCell('R' . $rownr) -> getValue();
            $duplicate_ids[4] = $objWorksheet -> getCell('S' . $rownr) -> getValue();
            $duplicate_ids[5] = $objWorksheet -> getCell('T' . $rownr) -> getValue();
            $duplicate_ids[6] = $objWorksheet -> getCell('U' . $rownr) -> getValue();
            $duplicate_ids[7] = $objWorksheet -> getCell('V' . $rownr) -> getValue();
            $duplicate_ids[8] = $objWorksheet -> getCell('W' . $rownr) -> getValue();
            $duplicate_ids[9] = $objWorksheet -> getCell('X' . $rownr) -> getValue();
            $duplicate_ids[10] = $objWorksheet -> getCell('Y' . $rownr) -> getValue();
            $duplicate_ids[11] = $objWorksheet -> getCell('Z' . $rownr) -> getValue();
            $duplicate_ids[12] = $objWorksheet -> getCell('AA' . $rownr) -> getValue();
            $duplicate_ids[13] = $objWorksheet -> getCell('AB' . $rownr) -> getValue();
            $duplicate_ids[14] = $objWorksheet -> getCell('AC' . $rownr) -> getValue();
            $duplicate_ids[15] = $objWorksheet -> getCell('AD' . $rownr) -> getValue();
            $duplicate_ids[16] = $objWorksheet -> getCell('AE' . $rownr) -> getValue();
            $duplicate_ids[17] = $objWorksheet -> getCell('AF' . $rownr) -> getValue();
            $duplicate_ids[18] = $objWorksheet -> getCell('AG' . $rownr) -> getValue();
            $duplicate_ids[19] = $objWorksheet -> getCell('AH' . $rownr) -> getValue();
            $data['excel'][$rownr]['rubriek'] = $rubriek;
            $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek));
            if (count($vraag_groep) >0 ){
                $data['excel'][$rownr]['vraag_groep_id'] = $vraag_groep[0]->id;
            }
            $data['excel'][$rownr]['question'] = $question;
            if (intval($question_id) > 0)
            {
                $db_question = $this->Sms_model->get_question_by_id($question_id);
                $db_answers = $this->Sms_model->get_answers_by_question_type_id($db_question[0]->vraag_type_id);
                $answer[0] = isset($db_answers[0]) ? $db_answers[0]->description : '';
                $answer[1] = isset($db_answers[1]) ? $db_answers[1]->description : '';
                $answer[2] = isset($db_answers[2]) ? $db_answers[2]->description : '';
                $answer[3] = isset($db_answers[3]) ? $db_answers[3]->description : '';
                $answer[4] = isset($db_answers[4]) ? $db_answers[4]->description : '';
                $answer[5] = isset($db_answers[5]) ? $db_answers[5]->description : '';
                $answer[6] = isset($db_answers[6]) ? $db_answers[6]->description : '';
                $answer[7] = isset($db_answers[7]) ? $db_answers[7]->description : '';
                $answer[8] = isset($db_answers[8]) ? $db_answers[8]->description : '';
                $answer[9] = isset($db_answers[9]) ? $db_answers[9]->description : '';
                $data['excel'][$rownr]['short_description'] = $db_question[0]->short_description;
                $data['excel'][$rownr]['base_type_id'] = $db_question[0]->base_type_id;
                $data['excel'][$rownr]['vraag_groep_id'] = $db_question[0]->vraag_groep_id;
                if ($db_question[0]->base_type_id == 0){
                    $new_id++;
                    $data['excel'][$rownr]['new_id'] = $new_id;
                }
            }
            $data['excel'][$rownr]['answer'] = $answer;
            $data['excel'][$rownr]['question_id'] = $question_id;
            $pattern = '/(^\d+\s+)/i';
            $question_no_number = preg_replace($pattern, '', $question);
            $data['excel'][$rownr]['question_no_number'] = $question_no_number;
            $duplicates = array();
            foreach ($duplicate_ids as $duplicate_id) {
                if (intval($duplicate_id) >0) {
                    $db_question = $this->Sms_model->get_question_by_id($duplicate_id);
                    if (count($db_question) > 0) 
                    {
                        $question = str_replace('_SPACE_',' ',$db_question[0]->description);
                    } else {
                        $question = 'Vraag niet gevonden in database!!!!!!!';
                    }
                    $db_answers = $this->Sms_model->get_answers_by_question_type_id($db_question[0]->vraag_type_id);
                    
                    array_push($duplicates, 
                        array(
                            'question_id' => $duplicate_id, 
                            'question' => $question, 
                            'question_no_number' => $question_no_number, 
                            'answer' => array(
                                isset($db_answers[0]) ? $db_answers[0]->description : '',
                                isset($db_answers[1]) ? $db_answers[1]->description : '',
                                isset($db_answers[2]) ? $db_answers[2]->description : '',
                                isset($db_answers[3]) ? $db_answers[3]->description : '',
                                isset($db_answers[4]) ? $db_answers[4]->description : '',
                                isset($db_answers[5]) ? $db_answers[5]->description : '',
                                isset($db_answers[6]) ? $db_answers[6]->description : '',
                                isset($db_answers[7]) ? $db_answers[7]->description : '',
                                isset($db_answers[8]) ? $db_answers[8]->description : '',
                                isset($db_answers[9]) ? $db_answers[9]->description : '',
                            )
                        )
                    );
                }
            }
            $data['excel'][$rownr]['duplicates'] = $duplicates;
        }
        $new_id++;
        $data['$new_id'] = $new_id;
        $this -> load -> view('tools/update_otp', $data);
    }

    public function ptp() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ptp.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        $vraag_group_id = 0;
        $new_id = 9926;
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) ==0){
                $rubriek = $objWorksheet -> getCell('G' . $rownr) -> getValue();
                if (($rubriek != '') && ($rubriek != 'vraag')){
                    $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek));
//                    print $rubriek.'<br>';
                    if (count($vraag_groep) >0 ){
                        $vraag_group_id = $vraag_groep[0]->id;
                        print $vraag_group_id.'<br>';
                    }
                }
            }

            if (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) !=0){
                $id1 = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $id2 = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                $id3 = $objWorksheet -> getCell('D' . $rownr) -> getValue();
                $id4 = $objWorksheet -> getCell('E' . $rownr) -> getValue();
                $id5 = $objWorksheet -> getCell('F' . $rownr) -> getValue();
                $question = $objWorksheet -> getCell('G' . $rownr) -> getValue();
                $new_id++;
                print "insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id)
                (select $new_id, abstract, '$question', short_description, $vraag_group_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 3
                    from vraag where vraag.id=$id1);<br>";
                          
                
            }

        }
        $new_id++;
        print "update sequence set sequence_no=$new_id where table_name='vraag';<br>";

        $this -> load -> view('welcome_message');
    }

    public function ltp() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ltp.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        $vraag_group_id = 0;
        $new_id=10240;
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            print intval($objWorksheet -> getCell('B' . $rownr) -> getValue())."<br>";
            if (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) ==0){
                $rubriek = $objWorksheet -> getCell('G' . $rownr) -> getValue();
                print "$rubriek<br>";
                if (($rubriek != '') && ($rubriek != 'vraag')){
                    $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek));
                    var_dump($vraag_groep);
//                    print $rubriek.'<br>';
                    if (count($vraag_groep) >0 ){
                        $vraag_group_id = $vraag_groep[0]->id;
                        print $vraag_group_id.'<br>';
                    }
                }
            }

            if (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) !=0){
                $id1 = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $id2 = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                $id3 = $objWorksheet -> getCell('D' . $rownr) -> getValue();
                $question = $objWorksheet -> getCell('E' . $rownr) -> getValue();
                    $new_id++;
                    //TODO::juiste vraag_group_id 
                    print "insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id)
                    (select $new_id, abstract, '$question', short_description, 1612, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 3
                        from vraag where vraag.id=$id1);<br>";
                
                          
                
            }

        }
        $new_id++;
        print "update sequence set sequence_no=$new_id where table_name='vraag';<br>";

        $this -> load -> view('welcome_message');
    }

}
