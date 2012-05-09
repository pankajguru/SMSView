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
        $new_id =  $this -> Sms_model ->_get_new_id('vraag'); //set to new id!!!!!!!
        $new_anwer_id =  $this -> Sms_model ->_get_new_id('antwoord'); //set to new id!!!!!!!
        $vraag_groep_id = 0;
        $rubriek = 'Algemeen';
        foreach ($objWorksheet->getRowIterator() as $row) {

            $answer = array();
            $rownr = $row -> getRowIndex();
            if ($rownr == 1)
                continue;
            $rubriek = $objWorksheet -> getCell('A' . $rownr) -> getValue();
            $question = $objWorksheet -> getCell('B' . $rownr) -> getValue();
            $vraag_type_id = $objWorksheet -> getCell('C' . $rownr) -> getValue();
            $answer[0] = $objWorksheet -> getCell('D' . $rownr) -> getValue();
            $answer[1] = $objWorksheet -> getCell('E' . $rownr) -> getValue();
            $answer[2] = $objWorksheet -> getCell('F' . $rownr) -> getValue();
            $answer[3] = $objWorksheet -> getCell('G' . $rownr) -> getValue();
            $answer[4] = $objWorksheet -> getCell('H' . $rownr) -> getValue();
            $answer[5] = $objWorksheet -> getCell('I' . $rownr) -> getValue();
            $answer[6] = $objWorksheet -> getCell('J' . $rownr) -> getValue();
            $answer[7] = $objWorksheet -> getCell('K' . $rownr) -> getValue();
            $answer[8] = $objWorksheet -> getCell('L' . $rownr) -> getValue();
            $question_id = $objWorksheet -> getCell('O' . $rownr) -> getValue();
            $duplicate_ids[0] = $objWorksheet -> getCell('P' . $rownr) -> getValue();
            $duplicate_ids[1] = $objWorksheet -> getCell('Q' . $rownr) -> getValue();
            $duplicate_ids[2] = $objWorksheet -> getCell('R' . $rownr) -> getValue();
            $duplicate_ids[3] = $objWorksheet -> getCell('S' . $rownr) -> getValue();
            $duplicate_ids[4] = $objWorksheet -> getCell('T' . $rownr) -> getValue();
            $duplicate_ids[5] = $objWorksheet -> getCell('U' . $rownr) -> getValue();
            $duplicate_ids[6] = $objWorksheet -> getCell('V' . $rownr) -> getValue();
            $duplicate_ids[7] = $objWorksheet -> getCell('W' . $rownr) -> getValue();
            $duplicate_ids[8] = $objWorksheet -> getCell('X' . $rownr) -> getValue();
            $duplicate_ids[9] = $objWorksheet -> getCell('Y' . $rownr) -> getValue();
            $duplicate_ids[10] = $objWorksheet -> getCell('Z' . $rownr) -> getValue();
            $duplicate_ids[11] = $objWorksheet -> getCell('AA' . $rownr) -> getValue();
            $duplicate_ids[12] = $objWorksheet -> getCell('AB' . $rownr) -> getValue();
            $duplicate_ids[13] = $objWorksheet -> getCell('AC' . $rownr) -> getValue();
            $duplicate_ids[14] = $objWorksheet -> getCell('AD' . $rownr) -> getValue();
            $duplicate_ids[15] = $objWorksheet -> getCell('AE' . $rownr) -> getValue();
            $duplicate_ids[16] = $objWorksheet -> getCell('AF' . $rownr) -> getValue();
            $duplicate_ids[17] = $objWorksheet -> getCell('AG' . $rownr) -> getValue();
            $duplicate_ids[18] = $objWorksheet -> getCell('AH' . $rownr) -> getValue();
            $duplicate_ids[19] = $objWorksheet -> getCell('AI' . $rownr) -> getValue();
            $data['excel'][$rownr]['rubriek'] = $rubriek;
            $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek));
            if ((count($vraag_groep) >0) and ($rubriek <> '') ){
                if ($rubriek == 'Naschoolse opvang'){
                    $vraag_groep_id = 173;
                } elseif ($rubriek == 'Overblijven'){
                    $vraag_groep_id = 169;
                } else {
                    $vraag_groep_id = $vraag_groep[0]->id;
                }
            }
            $data['excel'][$rownr]['vraag_groep_id'] = $vraag_groep_id;
            $data['excel'][$rownr]['question'] = $question;
            $data['excel'][$rownr]['vraag_type_id'] = $vraag_type_id;
            if (intval($question_id) > 0)
            {
                $db_question = $this->Sms_model->get_question_by_id($question_id);
                $db_answers = $this->Sms_model->get_answers_by_question_type_id($db_question[0]->vraag_type_id);
                $answer[0] = isset($db_answers[0]) ? $db_answers[0]->value.' '.$db_answers[0]->description : '';
                $answer[1] = isset($db_answers[1]) ? $db_answers[1]->value.' '.$db_answers[1]->description : '';
                $answer[2] = isset($db_answers[2]) ? $db_answers[2]->value.' '.$db_answers[2]->description : '';
                $answer[3] = isset($db_answers[3]) ? $db_answers[3]->value.' '.$db_answers[3]->description : '';
                $answer[4] = isset($db_answers[4]) ? $db_answers[4]->value.' '.$db_answers[4]->description : '';
                $answer[5] = isset($db_answers[5]) ? $db_answers[5]->value.' '.$db_answers[5]->description : '';
                $answer[6] = isset($db_answers[6]) ? $db_answers[6]->value.' '.$db_answers[6]->description : '';
                $answer[7] = isset($db_answers[7]) ? $db_answers[7]->value.' '.$db_answers[7]->description : '';
                $answer[8] = isset($db_answers[8]) ? $db_answers[8]->value.' '.$db_answers[8]->description : '';
                $answer[9] = isset($db_answers[9]) ? $db_answers[9]->value.' '.$db_answers[9]->description : '';
                $data['excel'][$rownr]['short_description'] = $db_question[0]->short_description;
                $data['excel'][$rownr]['description'] = $db_question[0]->description;
                $data['excel'][$rownr]['base_type_id'] = $db_question[0]->base_type_id;
                //$data['excel'][$rownr]['vraag_groep_id'] = $db_question[0]->vraag_groep_id;
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
                                isset($db_answers[0]) ? $db_answers[0]->value.' '.$db_answers[0]->description : '',
                                isset($db_answers[1]) ? $db_answers[1]->value.' '.$db_answers[1]->description : '',
                                isset($db_answers[2]) ? $db_answers[2]->value.' '.$db_answers[2]->description : '',
                                isset($db_answers[3]) ? $db_answers[3]->value.' '.$db_answers[3]->description : '',
                                isset($db_answers[4]) ? $db_answers[4]->value.' '.$db_answers[4]->description : '',
                                isset($db_answers[5]) ? $db_answers[5]->value.' '.$db_answers[5]->description : '',
                                isset($db_answers[6]) ? $db_answers[6]->value.' '.$db_answers[6]->description : '',
                                isset($db_answers[7]) ? $db_answers[7]->value.' '.$db_answers[7]->description : '',
                                isset($db_answers[8]) ? $db_answers[8]->value.' '.$db_answers[8]->description : '',
                                isset($db_answers[9]) ? $db_answers[9]->value.' '.$db_answers[9]->description : '',
                            )
                        )
                    );
                }
            }
            $data['excel'][$rownr]['duplicates'] = $duplicates;
        }
        $new_id++;
        $data['new_id'] = $new_id;
        $data['new_answer_id'] = $new_anwer_id;
        $this -> load -> view('tools/update_otp', $data);
    }

    public function ptp() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ptp.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        $vraag_group_id = 100;
        $new_id =  $this -> Sms_model ->_get_new_id('vraag'); //set to new id!!!!!!!
        $new_answer_id =  $this -> Sms_model ->_get_new_id('antwoord'); //set to new id!!!!!!!
        print '<code>';
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) ==0){
                $rubriek = $objWorksheet -> getCell('G' . $rownr) -> getValue();
                if (($rubriek != '') && ($rubriek != 'vraag')){
                    $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek), 99);
//                    print $rubriek.'<br>';
                    if ($rubriek == 'LOOPBAANMANAGEMENT'){
                        $vraag_group_id = 108;
                    }elseif ($rubriek == 'MANAGEMENT'){
                        $vraag_group_id = 111;
                    }elseif (count($vraag_groep) >0 ){
                        $vraag_group_id = $vraag_groep[0]->id;
//                        print $vraag_group_id.'<br>';
                    }
                }
            }

            if ((intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) !=0) 
                    and (intval($objWorksheet -> getCell('B' . $rownr) -> getValue()) > 300)
                    and ($vraag_group_id != 968) ){
                $id1 = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $id2 = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                $id3 = $objWorksheet -> getCell('D' . $rownr) -> getValue();
                $id4 = $objWorksheet -> getCell('E' . $rownr) -> getValue();
                $id5 = $objWorksheet -> getCell('F' . $rownr) -> getValue();
                $question = $objWorksheet -> getCell('G' . $rownr) -> getValue();
                $new_id++;
                $question = preg_replace('/\'/', '&amp;#39;', $question);
                $question = preg_replace('/_ouml;/', '&amp;ouml;', $question);
                print "insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id)
                (select $new_id, abstract, '$question', short_description, $vraag_group_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 3
                    from vraag where vraag.id=$id1);<br>";
                if ($id1 != ''){
                    print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_answer_id, 0, 0, 0, formulier_id, ".$new_id.", value from antwoord where vraag_id=$id1);<br>";
                    $new_answer_id++;
                }
                if ($id2 != ''){
                    print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_answer_id, 0, 0, 0, formulier_id, ".$new_id.", value from antwoord where vraag_id=$id2);<br>";
                    $new_answer_id++;
                                    }
                if ($id3 != ''){
                    print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_answer_id, 0, 0, 0, formulier_id, ".$new_id.", value from antwoord where vraag_id=$id3);<br>";
                    $new_answer_id++;
                                    }
                if ($id4 != ''){
                    print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_answer_id, 0, 0, 0, formulier_id, ".$new_id.", value from antwoord where vraag_id=$id4);<br>";
                    $new_answer_id++;
                                    }
                if ($id5 != ''){
                    print "insert into antwoord (id, survey_id, peiling_id, locatie_id, formulier_id, vraag_id, value) (select $new_answer_id, 0, 0, 0, formulier_id, ".$new_id.", value from antwoord where vraag_id=$id5);<br>";
                    $new_answer_id++;
                }
                                          
                
            }

        }
        $new_id++;
        print "update vraag set description = SUBSTRING(description,locate(' ', description)+1) where base_type_id=3 and id < 308 and locate(' ', description)>0 and locate(' ', description)<6;";
        print "update vraag set description = concat(description,'?') where base_type_id=3 and id < 308 and locate('?', description)=0;";
        print "update vraag set vraag_type_id=1172 where vraag_type_id=1162 and id>5000;<br>";
        print "update sequence set sequence_no=$new_id where table_name='vraag';<br>";
        print "update sequence set sequence_no=$new_answer_id where table_name='antwoord';<br>";
        print "update vraag set description= concat('Hoe tevreden bent u over ',description) where vraag_type_id=107 and id>199 and id < 308 and locate('Hoe tevreden', description)=0;";
        print "update vraag set vraag_type_id=107 where id>308 and base_type_id=3 and locate('Hoe tevreden', description)=1;";
        print "update vraag set vraag_type_id=108 where id>308 and base_type_id=3 and locate('Hoe belangrijk', description)=1 and vraag_type_id in (1694,1257,649,617);";
        print "update vraag set description = 'Hoe tevreden bent u over de ondersteuning door de ICT-co_ouml_rdinator?' where description like '%rrrrrr%';";
        print "update vraag set vraag_type_id=1172 where description like 'Hoe belangrijk%' and base_type_id=3 and vraag_type_id=1537 and description not like '%inzetbaarheid%';";
        print "update vraag set description = 'Hoe belangrijk vindt u de inzetbaarheid van het digitale schoolbord bij het vak wereldori&amp;euml;ntatie?' where description ='Hoe belangrijk vindt u de inzetbaarheid van het digitale schoolbord bij het vak ntatie?'";
        print "update vraag set description = 'Hoe belangrijk vindt u de inzetbaarheid van de computers bij het vak wereldori&amp;euml;ntatie?' where description ='Hoe belangrijk vindt u de inzetbaarheid van de computers bij het vak ntatie?';";
        print '</code>';

        $this -> load -> view('welcome_message');
    }

    public function ltp() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ltp.xlsx");
        $objPHPExcel -> setActiveSheetIndex(1);
        
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        $vraag_group_id = 0;
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if ((intval($objWorksheet -> getCell('A' . $rownr) -> getValue()) ==0) and ($objWorksheet -> getCell('B' . $rownr) -> getValue() !='')){
                $rubriek = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                if (($rubriek != '') && ($rubriek != 'vraag')){
                    $vraag_groep = $this->Sms_model->get_vraag_group_by_description(trim($rubriek), 16);
                    //print $rubriek.'<br>';
                    if (count($vraag_groep) >0 ){
                        $vraag_group_id = $vraag_groep[0]->id;
                        //print $vraag_group_id.'<br>';
                    }
                }
            }
            if (intval($objWorksheet -> getCell('A' . $rownr) -> getValue()) !=0){
                $id = intval($objWorksheet -> getCell('A' . $rownr) -> getValue());
                print "update vraag set base_type_id=2, vraag_groep_id=$vraag_group_id, description = REPLACE(REPLACE(SUBSTRING(description,locate(' ', description)+1), '_SPACE_',' '),'_COLON_','&#58;') where id=$id;<br>";
            }
        }
        print "update vraag,report_type_definition set description = REPLACE(REPLACE(SUBSTRING(description,locate(' ', description)+1), '_SPACE_',' '),'_COLON_','&#58;') where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =324;<br>";
        print "update vraag,report_type_definition set short_description = REPLACE(REPLACE(SUBSTRING(short_description,locate(' ', short_description)+1), '_SPACE_',' '),'_COLON_','&#58;') where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =324;<br>";
        print "update vraag,report_type_definition set description = REPLACE(REPLACE(description, '_SPACE_',' '),'_COLON_','&#58;') where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =266;<br>";
        print "update vraag set short_description = REPLACE(short_description, '_SPACE_',' ') where base_type_id=2;";
        print "select id, description, short_description from vraag where base_type_id=2 and locate(' ', description)=1;";
        print "update vraag set vraag_groep_id = 28 where id=129;";
        print "update vraag set vraag_groep_id = 15 where id in (130,131,132,133,134,135,136,7696);";
        print "update vraag set description=substring(description,2) where base_type_id=2 and locate(' ',description) =1;";
        $this -> load -> view('welcome_message');
    }

    public function otp_shortdescription() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/otp_short_description.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if (intval($objWorksheet -> getCell('A' . $rownr) -> getValue()) !=0){
                $description = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $short_description = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                print "update vraag set short_description='$short_description' where description='$description';<br>";
            }

        }

        $this -> load -> view('welcome_message');
    }

    public function ltp_shortdescription() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ltp_short_description.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if (intval($objWorksheet -> getCell('A' . $rownr) -> getValue()) !=0){
                $id = $objWorksheet -> getCell('A' . $rownr) -> getValue();
                $description = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $short_description = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                print "update vraag set short_description='$short_description' where id=$id;<br>";
            }

        }

        $this -> load -> view('welcome_message');
    }

    public function ptp_shortdescription() {
        /** get PHPExcel_IOFactory object */
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader -> setReadDataOnly(true);
        $objPHPExcel = $objReader -> load("source_docs/ptp_short_description.xlsx");
        $objWorksheet = $objPHPExcel -> getActiveSheet();
        foreach ($objWorksheet->getRowIterator() as $row) {
            $answer = array();
            $rownr = $row -> getRowIndex();
            if (intval($objWorksheet -> getCell('A' . $rownr) -> getValue()) !=0){
                $id = $objWorksheet -> getCell('A' . $rownr) -> getValue();
                $description = $objWorksheet -> getCell('B' . $rownr) -> getValue();
                $short_description = $objWorksheet -> getCell('C' . $rownr) -> getValue();
                print "update vraag set short_description='$short_description' where description='$description';<br>";
            }

        }

        $this -> load -> view('welcome_message');
    }

}
