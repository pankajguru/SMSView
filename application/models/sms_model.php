<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms_model extends CI_Model {

    /**
     * SMS model, DB model for SMS database.
     *
     * Create web interface for report downloads
     */
    function __construct() {
        parent::__construct();
    }

    function get_last_ten_entries() {
        $query = $this -> db -> get('peiling', 10);
        return $query -> result();

    }

    function get_question_by_id($id) {
        $query = $this -> db -> get_where('vraag', array('id' => $id));
        return $query -> result();
    }

    function get_answers_by_question_type_id($question_type_id) {
        $this -> db -> from (
        'vraag_type_definition')-> where('vraag_type_id', $question_type_id);
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_peiling_type_details($peiling_type_id) {
        $this -> db -> from (
        'peiling_type')-> where('id', $peiling_type_id);
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_question_type_by_id($question_type_id) {
        $this -> db -> from (
        'vraag_type')-> where('id', $question_type_id);
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_vraag_group_by_description($description) {
        $this -> db -> from (
        'vraag_group')-> like('description', $description);
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_all_questions($type) {
        //maak functie waarbij alle vragen opgehaald worden
        $this -> db -> select('vraag.*, vraag.id as question_id, vraag.description as question_description, vraag_group.description as category_name, vraag_group.id as category_id') -> from('vraag') -> join('base_type', 'vraag.base_type_id = base_type.id') -> join('vraag_group', 'vraag_group.id=vraag_groep_id') -> where('base_type.desc_code', strtoupper($type));
        $query = $this -> db -> get();
        return $query -> result();
    }

    function get_question_properties($question_type_id) {
        $this -> db -> from('vraag_type_definition') -> where('vraag_type_id', $question_type_id);
        $query = $this -> db -> get();
        return $query -> result();
    }

    function get_all_questionaires_by_school($type, $school_id) {
        //maak functie waarbij alle eerdere peilingen opgehaald worden
        $this -> db -> distinct() -> select('type_id') -> from('peiling') -> join('peiling_type', 'peiling_type.id=peiling.type_id') -> where('school_id', $school_id) -> like('peiling_type.desc_code', $type) -> order_by('type_id');
        $query = $this -> db -> get();
        return $query -> result();
    }

    function get_all_questions_by_peiling_type($type_id) {
        //maak functie waarbij alle vragen uit een peiling opgehaald worden
        $this -> db -> select('formulier_type_definition.question_id') -> from('formulier_type_definition') -> join('formulier_type', 'formulier_type_definition.formulier_type_id= formulier_type.id') -> where('peiling_type_id', $type_id);
        $query = $this -> db -> get();
        return $query -> result();
    }

    function get_all_categories() {

        $query = $this -> db -> get('vraag_group');
        return $query -> result();

    }

    function get_category_questions($category_id) {
        $this -> db -> from('vraag') -> where('vraag_groep_id', $category_id);
        $query = $this -> db -> get();
        return $query -> result();
    }

    function get_category_details($category_id) {
        $this -> db -> from('vraag_group') -> where('id', $category_id);
        $query = $this -> db -> get();
        return $query -> result();

    }

    public function insert_questionaire($questionaire_object) {
        $response = array('success' => false, 'status' => '');
        //$this->_error_dump($questionaire_object);
        if ($questionaire_object == false) {
            $response['status'] = 'Malformed json received';
            return $response;
        }
        //$this->_error_dump($questionaire_object);
        
        $peiling_type_id = $this->_get_new_id('peiling_type');
        $peiling_type = array(
            'id' => $peiling_type_id,
            'desc_code' => 'MUIS_'.$peiling_type_id,
            'description' => 'Muis generated report'
        );
        $this->db->insert('peiling_type', $peiling_type); 
        foreach ($questionaire_object as $question) {
            if (!isset($question->{"id"})){
                continue;
            }
            if ($question->{"id"} == 'new'){
                //new question, store question and answers in db and use newly created id
                $text = $question->{"new_question"}; 
                $new_question = json_decode($text);
                $new_question_object = array();
                //transform array to usefull array
                foreach ($new_question as $value) {
                    $new_question_object[$value->{'name'}] = $value->{'value'};
                }
                $category = $new_question_object['new_question_category'];
                $new_question_text = $new_question_object['new_question_text'];
                $answer_type = $new_question_object['answer_type']; // 'multiple choice' en 'open vraag'
                $answers = array();
                $count = 1;
                //transform answers to usefull array
                while (isset($new_question_object['multiple_choice_answer_'.$count])){
                    array_push($answers,$new_question_object['multiple_choice_answer_'.$count]);
                    $count++;
                }
                $question->{"id"} = $this->_store_question($category, $new_question_text, $answer_type, $answers, $peiling_type_id);
            }
        }
                 
        //store new questionaire and use newly created name
        $report_type_id = $this->_get_new_id('report_type');
        $report_type = array(
            'id' => $report_type_id,
            'peiling_type_id' => $peiling_type_id,
            'desc_code' => 'MUIS_'.$peiling_type_id,
            'description' => 'Muis generated report'
        );
        $this->db->insert('report_type', $report_type); 
        
        $report_question_id = 0;
        foreach ($questionaire_object as $question) {
            if (!isset($question->{"id"})){
                continue;
            }
            $report_type_definition_id = $this->_get_new_id('report_type_definition');
            $report_type_definition = array(
                'id' => $report_type_definition_id,
                'report_type_id' => $report_type_id,
                'question_id' => $question->{"id"}, 
                'report_question_id' => $report_question_id++
            );
            $this->db->insert('report_type_definition', $report_type_definition); 
        }        
        $formulier_type_id = $this->_get_new_id('formulier_type');
        $formulier_type = array(
            'id' => $formulier_type_id,
            'peiling_type_id' => $peiling_type_id,
            'desc_code' => 'MUIS_'.$peiling_type_id,
            'description' => 'Muis generated report'
        );
        $this->db->insert('formulier_type', $formulier_type); 
        $formulier_question_id = 0;
        foreach ($questionaire_object as $question) {
            if (!isset($question->{"id"})){
                continue;
            }
            $formulier_type_definition_id = $this->_get_new_id('formulier_type_definition');
            $formulier_type_definition = array(
                'id' => $formulier_type_definition_id,
                'formulier_type_id' => $formulier_type_id,
                'question_id' => $question->{"id"}, 
                'formulier_question_id' => $formulier_question_id++
            );
            $this->db->insert('formulier_type_definition', $formulier_type_definition); 
        }        
        $response['status'] = 'MUIS_'.$peiling_type_id;
        $response['peiling_type_id'] = $peiling_type_id;
        $response['success'] = true;
        return $response;
    }
    
    function _store_question($category_id, $new_question_text, $answer_type, $answers, $peiling_type_id){
        //create new vraag type
        //store in vraag_type
        //store answers    
        //get max id van vraag
        $vraag_type_id = $this->_get_new_id('vraag_type');
        $value = 0;
        $label_lo = '';
        $label_hi = '';
        foreach($answers as $answer){
            $label_lo = $answers[0]; //if there are answers, the foirst one exists
            $label_hi = $answer; //label_hi will at last be set with the last answer
            //store answers
            $vraag_type_definition_id = $this->_get_new_id('vraag_type_definition');
            $vraag_type_definition = array(
                'id' => $vraag_type_definition_id,
                'vraag_type_id' => $vraag_type_id,
                'value' => $value++, 
                'description' => $answer               
            );
            $this->db->insert('vraag_type_definition', $vraag_type_definition); 
        }
        $vraag_type = array(
            'id' => $vraag_type_id,
            'DESC_CODE' => 'MUIS_CUSTOM_'.$peiling_type_id.'_'.$vraag_type_id,
            'description' => 'answers '.$new_question_text,
            'min_value' => 1,
            'max_value' => $value,
            'has_unknown' => 0,
            'unknown_value' => null,
            'label_lo' => $label_lo,
            'label_hi' => $label_hi
        );
        $this->db->insert('vraag_type', $vraag_type); 
        //store in vraag
        //get category
        $query = $this->db->get_where('vraag_group', array('id'=>$category_id));
        $row = $query->row(); 
        $category = $row->{'description'};
        //get max id van vraag
        $vraag_id = $this->_get_new_id('vraag');
        $vraag = array(
            'abstract' => $category,
            'description' => $new_question_text,
            'short_description' => substr($new_question_text,0,100),
            'vraag_groep_id' => $category_id,
            'vraag_type_id' => $vraag_type_id,
            'exclusive' => true,
            'strict' => 1,
            'id' => $vraag_id,
            'neutral_description' => $new_question_text,
            'infant_description_pos' => $new_question_text,
            'infant_description_neg' => $new_question_text
        );
        $this->db->insert('vraag', $vraag); 
        return $vraag_id;
    }

    function _get_new_id($table){
        $query = $this->db->get_where('sequence', array('table_name'=>$table));
        if ($query->num_rows() > 0){
            $row = $query->row();
            $use_id = $row->sequence_no;
            $new_id = $use_id + 1;
            //update vraag id reference
            $sequence = array(
                'table_name' => $table,
                'sequence_no' => $new_id
            );
            $this -> db -> where ('table_name',$table)-> update ('sequence', $sequence);
            return $use_id;
        } else {
            return false;
        }
    }
    
    function _error_dump($object){
        ob_start();
        //var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

/********
 *
 * DB aanpassingen:
 alter table vraag add base_type_id int(11) default 0;
 create table base_type(
   id int(11) auto_increment,
   desc_code varchar(100),
   description varchar(255),
   PRIMARY KEY (id)
 );

 insert into base_type set desc_code='OTP', description='Ouder tevredenheid vragen';
 insert into base_type set desc_code='LTP', description='Leerling tevredenheid vragen';
 insert into base_type set desc_code='PTP', description='Personeel tevredenheid vragen';
 update  vraag,report_type_definition set base_type_id=1 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =1;
 update  vraag,report_type_definition set base_type_id=2 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =266;
 update  vraag,report_type_definition set base_type_id=3 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =10;

 insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9682, abstract, 'Hoe tevreden bent u over de veiligheid in het gebouw?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5740);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9685, abstract, 'Ervaart u het schoolgebouw als veilig?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4469);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9686, abstract, 'Ervaart u de omgeving als veilig?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4470);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9688, abstract, 'Hoe tevreden bent u over de begeleiding van de basisschool naar het voorgezet onderwijs?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2049);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9690, abstract, 'Hoe tevreden bent u over de voorbereiding van de leerlingen op het voortgezet onderwijs?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3111);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9691, abstract, 'Hoe tevreden bent u over het aanbieden van de leerstof in verschillende niveaus?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4500);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9692, abstract, 'Hoe tevreden bent u over het taakgerichte (zelfstandige) werken met weektaken binnen de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4504);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9693, abstract, 'Hoe tevreden bent u met het handelingsplan van uw kind?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5742);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9694, abstract, 'Hoe tevreden bent u over de mate waarin u betrokken bent bij het opstellen van het handelingsplan?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5743);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9696, abstract, 'Hoe tevreden bent u over de groepsgrootte?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2308);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9698, abstract, 'Hoe tevreden bent u over de materialen en methodes waarmee uw kind werkt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3165);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9700, abstract, 'Hoe tevreden bent u over de aandacht voor muziek?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4346);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9701, abstract, 'Hoe tevreden bent u over de aandacht voor cultuur?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2043);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9702, abstract, 'Hoe tevreden bent u over de aandacht voor Bijbelkennis?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2788);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9703, abstract, 'Hoe tevreden bent u over het zwemonderwijs?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4399);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9704, abstract, 'Hoe tevreden bent u over het systeem dat de school hanteert voor het zelfstandig werken van de kinderen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2040);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9705, abstract, 'Hoe tevreden bent u over de aandacht voor zelfstandig werken?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2140);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9706, abstract, 'Hoe tevreden bent u over de vieringen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2312);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9707, abstract, 'Hoe tevreden bent u over de invulling van het sinterklaasfeest op school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5098);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9708, abstract, 'Hoe tevreden bent u over de invulling van het kerstfeest op school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2353);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9709, abstract, 'Hoe tevreden bent u over het bevorderen van zelfstandigheid?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3243);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9710, abstract, 'Hoe tevreden bent u over de aandacht voor de diverse talenten van de kinderen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4390);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9711, abstract, 'Hoe tevreden bent u over de inzet van computers en ICT middelen in het onderwijs?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6895);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9713, abstract, 'Hoe tevreden bent u over de lengte van de middagpauze?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4088);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9715, abstract, 'Zou u het waardevol vinden als de school overgaat op een continurooster? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6943);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9717, abstract, 'Hoe tevreden bent u over de hoeveelheid huiswerk die uw kind meekrijgt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4199);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9719, abstract, 'Vindt u dat de school preventief handelt op het gebied van pestgedrag? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6306);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9720, abstract, 'Wanneer er pestgedrag plaatsvindt, handelt de school dan adequaat? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3238);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9722, abstract, 'Hoe tevreden bent u over het team?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2318);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9723, abstract, 'Hoe tevreden bent u over de tijd die de leerkracht aan u besteedt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2615);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9724, abstract, 'Hoe tevreden bent u over de mate waarin de leerkracht afspraken nakomt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2616);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9725, abstract, 'Hoe tevreden bent u over de extra individuele zorg die de leerkracht geeft aan kinderen die dat nodig hebben?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2623);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9726, abstract, 'Hoe tevreden bent u over de manier waarop de leerkracht na een toets of opdracht feedback geeft aan uw kind?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3431);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9727, abstract, 'Hoe tevreden bent u over het contact met de leerkracht(en)? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4513);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9728, abstract, 'Hoe tevreden bent u over de aanwezigheid van stagiaires?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4584);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9729, abstract, 'Hoe tevreden bent u over de betrokkenheid van de leerkrachten bij de leerlingen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4935);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9731, abstract, 'Vindt u dat duo-leerkrachten elkaar voldoende informeren over gesprekken die met u plaats hebben gevonden? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8199);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9732, abstract, 'Vindt u dat de teamleden goed samenwerken?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3075);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9733, abstract, 'Is de leerkracht voldoende in staat om uw kind persoonlijke aandacht te geven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8198);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9735, abstract, 'Hoe tevreden bent u over de manier waarop de school met u communiceert?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2324);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9736, abstract, 'Hoe tevreden bent u over de gelegenheid om met de groepsleerkracht van u kind(eren) te praten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2037);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9737, abstract, 'Hoe tevreden bent u over de algemene ouderavonden?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8418);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9738, abstract, 'Hoe tevreden bent u over de 10 minuten gesprekken?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2768);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9739, abstract, 'Hoe tevreden bent u over het aantal 10 minuten gespekken met de leerkracht van uw kind?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2293);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9740, abstract, 'Hoe tevreden bent u over de rapporten/verslagen van uw kind?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2033);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9741, abstract, 'Hoe tevreden bent u over het aantal rapporten dat uw kind krijgt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2296);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9742, abstract, 'Hoe tevreden bent u over de informatie over uw kind in het rapport?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3720);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9743, abstract, 'Hoe tevreden bent u over de gelegenheid om met de intern begeleider te praten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2297);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9744, abstract, 'Hoe tevreden bent u over de bereikbaarheid van de directeur?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2134);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9745, abstract, 'Hoe tevreden bent u over de manier waarop de school omgaat met vragen en opmerkingen van ouders?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2044);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9746, abstract, 'Hoe tevreden bent u over het open staan van de school voor klachten / positieve kritiek?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2075);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9747, abstract, 'Hoe tevreden bent u over de afhandeling van opmerkingen/klachten door de directie? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3117);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9748, abstract, 'Hoe tevreden bent u over de afhandeling van opmerkingen/klachten door de leerkrachten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3118);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9749, abstract, 'Hoe tevreden bent u over de mate waarin de school zich aan de met u gemaakte afspraken houdt.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5424);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9750, abstract, 'Hoe tevreden bent u over de mate waarin de leerkracht naar u luistert in het geval van een probleem?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8197);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9751, abstract, 'Hoe tevreden bent u over de mate waarin de school rekening houdt met de kenmerken van uw kind.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5425);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9752, abstract, 'Hoe tevreden bent u over de mate waarin het handelingsplan met u wordt besproken.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5417);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9753, abstract, 'Hoe tevreden bent u over de mate waarin de school gebruik maakt van uw kennis over uw kind.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5420);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9754, abstract, 'Hoe tevreden bent u over de mate waarin de school adequate hulp biedt indien nodig.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5423);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9755, abstract, 'Hoe tevreden bent u over de manier waarop de school ouders informeert over haar doelstellingen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3432);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9756, abstract, 'Hoe tevreden bent u over de informatie die u krijgt over de resultaten van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3440);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9757, abstract, 'Hoe tevreden bent u over de informatie van de Medezeggenschapsraad? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2305);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9758, abstract, 'Hoe tevreden bent u over de afhandeling van opmerkingen/klachten door de Medezeggenschapsraad?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3119);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9759, abstract, 'Bent u tevreden over de informatie die u vanuit de Medezeggenschapsraad krijgt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4537);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9760, abstract, 'Hoe tevreden bent u over het werk van de Ouderraad? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2048);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9762, abstract, 'Vindt u de koffieochtenden een waardevolle aanvulling in het contact met de school? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4749);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9763, abstract, 'Vindt u het bijwonen van een les een waardevolle aanvulling in het contact met de school? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8162);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9764, abstract, 'Vindt u dat u genoeg informatie krijgt over de medezeggenschapsraad? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8081);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9765, abstract, 'Weet u wie de vertrouwenspersoon is op school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2776);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9766, abstract, 'Vindt u dat leerkrachten en directie voldoende open staan voor kritiek?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3112);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9768, abstract, 'Hoe tevreden bent u over de inbreng en de mogelijkheid om mee te denken in de school? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2302);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9769, abstract, 'Hoe tevreden bent u over de maatschappelijke betrokkenheid van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4759);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9771, abstract, 'Op deze school krijgen kinderen veel persoonlijke aandacht.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8553);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9772, abstract, 'Op deze school gaan kinderen met veel plezier naar school.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8554);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9773, abstract, 'De kwaliteit van de communicatie van de school is goed.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8555);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9774, abstract, 'De school heeft een goede, positieve uitstraling.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8556);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9775, abstract, 'De school staat open voor de mening van ouders.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8557);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9776, abstract, 'De school onderscheidt zich duidelijk van andere scholen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8558);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9777, abstract, 'De school heeft een goede reputatie in de wijk / buurt.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8559);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9778, abstract, 'Buurtbewoners of andere betrokkenen praten enthousiast over de school.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8560);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9779, abstract, 'Dit is een school waar veel activiteiten georganiseerd worden.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8563);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9780, abstract, 'Dit is een school waar modern onderwijs gegeven wordt.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8564);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9781, abstract, 'Dit is een school waar leerlingen in kleine groepen les krijgen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8565);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9782, abstract, 'Dit is een school waar leerlingen veel vrijheid krijgen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8567);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9783, abstract, 'Dit is een school waar goede resultaten behaald worden.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8568);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9784, abstract, 'Dit is een school die een verzorgde en opgeruimde indruk maakt.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8569);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9785, abstract, 'Dit is een school die naar buiten gericht is (o.a. wijk/buurt).', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8570);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9786, abstract, 'Dit is een school waar niet/weinig gepest wordt.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8571);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9787, abstract, 'Dit is een school die gericht is op prestaties.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8572);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9788, abstract, 'Dit is een school die gericht is op het bijbrengen van normen en waarden.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8573);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9789, abstract, 'Dit is een school die gericht is op het bijbrengen van kennis.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8574);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9790, abstract, 'Dit is een school die gericht is op het zelfstandig maken van leerlingen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8575);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9791, abstract, 'Dit is een school die gericht is op het sociaal vaardig maken van leerlingen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8576);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9792, abstract, 'Dit is een school waar je veel leert en goed presteert.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8577);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9793, abstract, 'Dit is een school waar leerlingen zich veilig en prettig voelen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8578);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9794, abstract, 'Dit is een school waar leerlingen uitgedaagd worden om zich optimaal te ontwikkelen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8579);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9795, abstract, 'Dit is een school waar leerlingen vooral leren hoe ze zich in de wereld moeten gedragen.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8580);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9797, abstract, 'Wij hebben gekozen voor deze school omdat het dezelfde school is als van vriendjes/vriendinnetjes.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3174);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9798, abstract, 'Wij hebben gekozen voor deze school omdat de school goed bekend staat.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2383);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9799, abstract, 'Wij hebben gekozen voor deze school omdat we een goede indruk van de school hebben gekregen tijdens het kennismakingsbezoek.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2735);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9800, abstract, 'Wij hebben gekozen voor deze school omdat de sfeer goed is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2731);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9801, abstract, 'Wij hebben gekozen voor deze school omdat hij dicht bij mijn/ons huis is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2385);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9802, abstract, 'Wij hebben gekozen voor deze school omdat de school goede resultaten heeft.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4291);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9803, abstract, 'Wij hebben gekozen voor deze school omdat de zorgstructuur goed is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3177);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9804, abstract, 'Wij hebben gekozen voor deze school omdat andere ouders hem hebben aangeraden.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2736);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9805, abstract, 'Wij hebben gekozen voor deze school omdat er een continurooster is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4497);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9806, abstract, 'Wij hebben gekozen voor deze school omdathet een brede school is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2734);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9807, abstract, 'Wij hebben gekozen voor deze school omdater veel activiteiten zijn na schooltijd.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2733);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9808, abstract, 'Wij hebben gekozen voor deze school omdat er goede voorlichting is gegeven.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2384);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9809, abstract, 'Wij hebben gekozen voor deze school omdat er goede opvangmogelijkheden zijn.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4294);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9810, abstract, 'Wij hebben gekozen voor deze school omdat de school veilig te bereiken is?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4316);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9811, abstract, 'Wij hebben gekozen voor deze school omdat het een Jenaplanschool is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4827);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9812, abstract, 'Wij hebben gekozen voor deze school omdat het een Daltonschool is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3179);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9813, abstract, 'Wij hebben gekozen voor deze school omdat het een Montessorischool is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3048);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9814, abstract, 'Wij hebben gekozen voor deze school omdat het een buurtschool is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3047);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9815, abstract, 'Wij hebben gekozen voor deze school omdat de school een katholieke identiteit heeft.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4564);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9816, abstract, 'Wij hebben gekozen voor deze school omdat het een openbare school is.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3180);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9817, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door de school zelf.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4058);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9818, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door een gesprek met de directeur.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4573);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9819, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door de website van de school.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3053);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9820, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door de schoolgids/folder.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4575);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9821, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door kwaliteitskaarten van de inspectie.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3055);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9822, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door kranten.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3054);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9823, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door de gemeente.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4061);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9824, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door andere ouders.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3052);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9825, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door peuterspeelzalen/kinderdagverblijven.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4063);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9826, abstract, 'Bij het maken van de schoolkeuze hebben wij informatie over de school gekregen door de open dag.', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3051);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9827, abstract, 'Vindt u een wereldschool aantrekkelijk voor uw kind(eren)? (extra aandacht voor andere culturen, landen, volken en geschiedenis)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3555);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9828, abstract, 'Vindt u een sportieve school aantrekkelijk voor uw kind(eren)? (extra aandacht voor sport, bewegen en gezondheid)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3556);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9829, abstract, 'Vindt u een kunstzinnige school aantrekkelijk voor uw kind(eren)? (extra aandacht voor kunst en cultuur)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3557);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9830, abstract, 'Vindt u een wetenschap en techniek school aantrekkelijk voor uw kin(eren)? (extra aandacht voor techniek en onderzoek)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3558);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9831, abstract, 'Vindt u een talenschool aantrekkelijk voor uw kind(eren)? (extra aandacht voor taalontwikkeling Nederlands en buitenlandse talen)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3559);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9832, abstract, 'Vindt u een muziek en dans school aantrekkelijk voor uw kinderen? (extra aandacht voor muzikaliteit, dansen en performance)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3560);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9833, abstract, 'Vindt u een dorpsschool aantrekkelijk voor uw kind(eren)? (kleinschalig, toegankelijk, persoonlijk en degelijk onderwijs)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3561);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9834, abstract, 'Vindt u een kantoortijdenschool aantrekkelijk voor uw kind(eren)? (opvang en onderwijs van 07.00 uur tot 19.00 uur)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6929);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9835, abstract, 'Vindt u een Brede school aantrekkelijk voor uw kind(eren)? (aanbod, via school, van buitenschoolse activiteiten in samenwerking met organisaties voor sport, cultuur en welzijn)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6930);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9836, abstract, 'Vindt u een educatief centrum aantrekkelijk voor uw kind(eren)? (aanbod van kennis, cursussen en ontwikkeling voor kinderen en ouders)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6931);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9837, abstract, 'Vindt u een kindercampus aantrekkelijk voor uw kind(eren)? (voor alle kinderen tussen 0 en 12 jaar, inclusief consultatiebureau, peuterspeelzaal, kinderopvang en basisonderwijs)', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6932);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9838, abstract, 'Als u weer voor de keuze zou staan om een school voor uw kind te kiezen, zou u dan weer voor onze school kiezen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2739);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9840, abstract, 'Hoe tevreden bent u over de begeleiding van de basisschool naar het voorgezet onderwijs?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2045);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9842, abstract, 'Voelt uw kind zich veilig op school? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6309);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9844, abstract, '', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3490);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9845, abstract, 'Hoe tevreden bent u over de inhoud en leesbaarheid van de schoolgids?', short_description, 109, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2034);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9846, abstract, 'Hoe tevreden bent u over de website van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2009);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9847, abstract, 'Hoe tevreden bent u over de telefonische bereikbaarheid van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2036);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9848, abstract, 'Hoe tevreden bent u over de nieuwsbrief?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5901);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9850, abstract, 'Gebruikt u de website van de school om actuele informatie over de school te krijgen? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3159);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9851, abstract, 'Gebruikt u de schoolgids als naslagwerk? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3505);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9852, abstract, 'Bezoekt u de website van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1825);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9854, abstract, 'Hoe tevreden bent u over de begeleiding bij de voorschoolse opvang?', short_description, 235, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4237);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9856, abstract, 'Hoe tevreden bent u over hygiÃ«ne en netheid op de voorschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4238);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9858, abstract, 'Hoe tevreden bent u over het (uur)tarief van de voorschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4241);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9860, abstract, 'Hoe belangrijk vindt u voorschoolse opvang voor een goede school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1958);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9862, abstract, 'Hoe tevreden bent u over de begeleiding bij het overblijven?', short_description, 158, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4243);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9863, abstract, 'Hoe tevreden bent u over de overblijfvoorzieningen op onze school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1960);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9864, abstract, 'Hoe tevreden bent u over het toezicht tijdens het buitenspelen tijdens het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2390);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9865, abstract, 'Hoe tevreden bent u over hygiÃ«ne en netheid tijdens het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4244);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9867, abstract, 'Hoe tevreden bent u over het bedrag dat u moet betalen voor het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4247);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9868, abstract, 'Hoe tevreden bent u over de overblijfkrachten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3197);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9869, abstract, 'Hoe tevreden bent u over de het overbliijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3721);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9870, abstract, 'Hoe tevreden is uw kind over het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4691);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9871, abstract, 'Hoe tevreden bent u over de invulling en activiteiten tijdens het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5354);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9873, abstract, 'Vindt uw kind het leuk om over te blijven? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4687);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9874, abstract, 'Voelt uw kind zich veilig tijdens het overblijven?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4684);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9875, abstract, 'Vindt u dat uw kind genoeg te doen heeft tijdens het overblijven? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4685);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9876, abstract, 'Vindt u dat er tijdens het overblijven voldoende op pestgedrag wordt gelet?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4686);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9877, abstract, 'Bent u bereid om eventueel meer te betalen om de kwaliteit van het overblijven te verhogen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2942);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9879, abstract, 'Hoe belangrijk vindt u het overblijven voor een goede school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1966);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9881, abstract, 'Hoe tevreden bent u over de begeleiding bij de naschoolse opvang?', short_description, 158, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4249);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9882, abstract, 'Hoe tevreden bent u over de voorzieningen die de naschoolse opvang biedt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1970);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9883, abstract, 'Hoe tevreden bent u over de locatie van de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4326);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9884, abstract, 'Hoe tevreden bent u over de opvangtijden van de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4325);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9885, abstract, 'Hoe tevreden bent u over opvang tijdens vakanties en studiedagen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4254);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9888, abstract, 'Hoe tevreden bent u over hygiÃ«ne en netheid op de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4250);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9891, abstract, 'Hoe tevreden bent u over het (uur)tarief van de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4253);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9893, abstract, 'Hoe tevreden bent u over het vervoer naar de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4327);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9894, abstract, 'Hoe tevreden bent u over de activiteiten die worden gedaan op de naschoolse opvang?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4251);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9902, abstract, 'Hoe belangrijk vindt u naschoolse opvang voor de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=1975);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9904, abstract, 'Hoe tevreden bent u over de manier waarop de school inhoud geeft aan haar identiteit?', short_description, 1647, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2041);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9905, abstract, 'Hoe tevreden bent u over de wijze waarop de identiteit tot uitdrukking komt bij de vieringen van feestdagen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2789);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9906, abstract, 'Hoe tevreden bent u over het uitdragen van de identiteit door het schoolteam van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2792);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9907, abstract, 'Hoe tevreden bent u over over de wijze waarop in de school de identiteit tot uitdrukking komt in de leermethoden?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8284);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9908, abstract, 'Hoe tevreden bent u over de manier waarop de identiteit tot uitdrukking komt in de omgangsvormen en gedrag?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=7350);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9909, abstract, 'Hoe tevreden bent u over het uitdragen van de identiteit door de personeelsleden van de school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=7058);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9910, abstract, 'Hoe tevreden bent u over de inhoud van het godsdienstonderwijs dat uw kind ontvangt?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=3104);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9911, abstract, 'Hoe tevreden bent u over de ondersteuning vanuit de school m.b.t. de voorbereiding op communie en vormsel?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2042);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9913, abstract, 'Hoe belangrijk vindt u de identiteit voor een goede school?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=2794);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9915, abstract, 'Hoe tevreden bent u over de bestemming van onze schoolreisjes?', short_description, 1646, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8094);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9916, abstract, 'Hoe tevreden bent u over de prijs van de huidige schoolreisjes?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=8095);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9918, abstract, 'Hoe tevreden bent u over de hoogte van de vrijwillige ouderbijdrage?', short_description, 130, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=5009);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9919, abstract, 'Zou u (structureel) meer ouderbijdrage willen betalen, zodat we meer activiteiten voor uw kinderen kunnen ondernemen?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=4352);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9921, abstract, 'Hoe tevreden bent u over het aantal buitenschoolse activiteiten dat de school momenteel aanbiedt?', short_description, 210, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6952);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9922, abstract, 'Hoe tevreden bent u over de kwaliteit en invulling van de buitenschoolse activiteiten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6958);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9923, abstract, 'Hoe tevreden bent u over Het aanbod aan buitenschoolse activiteiten?', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=7069);
insert into vraag (id,abstract, description, short_description, vraag_groep_id, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, base_type_id) (select 9925, abstract, 'Zou u het leuk vinden als er (meer) naschoolse activiteiten op school zouden plaatsvinden? ', short_description, 0, vraag_type_id, exclusive, strict, neutral_description, infant_description_pos, infant_description_neg, 1 from vraag where vraag.id=6918);

update vraag set description = "Welk rapportcijfer geeft u aan 'de school'? (1=laag, 10=hoog)" where id=68;
update vraag set description = "Voelt u zich thuis op deze school?" where id=67;
update vraag set description = "Gaat uw kind over het algemeen met plezier naar school?" where id=65;
update vraag set description = "Zou u de school (weer) kiezen om de richting van de school (openbaar, katholiek, christelijk ed)?" where id=60;
update vraag set description = "Praten de ouders over het algemeen enthousiast over de school?" where id=58;
update vraag set description = "Praten de leerkrachten over het algemeen enthousiast over de school?" where id=57;
update vraag set description = "Is de schriftelijke informatie van de school voldoende aantrekkelijk?" where id=56;
update vraag set description = "Komt voldoende duidelijk naar buiten wat de school te bieden heeft?" where id=55;
update vraag set description = "Helpt u uw kind thuis met werk van school?" where id=53;
update vraag set description = "Bent u op school actief als hulp-ouder of commissielid?" where id=50;
update vraag set description = "Hoe tevreden bent u over over de inzet en motivatie van de leerkracht?" where id=43;
update vraag set description = "Hoe belangrijk vindt u schoolregels, rust en orde voor een goede school?" where id=40;
update vraag set description = "Hoe tevreden bent u over de opvang bij afwezigheid van de leerkracht?" where id=37;
update vraag set description = "Hoe belangrijk vindt u de schooltijden voor een goede school?" where id=36;
update vraag set description = "Hoe belangrijk vindt u persoonlijke ontwikkeling voor een goede school?" where id=32;
update vraag set description = "Hoe tevreden bent u over de aandacht voor levensbeschouwing en/of godsdienst?" where id=28;
update vraag set description = "Hoe belangrijk vindt u de kennisontwikkeling voor een goede school?" where id=26;
update vraag set description = "Hoe tevreden bent u over de aandacht voor het halen van goede prestaties?" where id=25;
update vraag set description = "Hoe tevreden bent u over de aandacht voor werken met de computer?" where id=24;
update vraag set description = "Hoe tevreden bent u over de rust en orde in de klas?" where id=17;
update vraag set description = 'Hoe tevreden bent u over de aandacht voor wereldori&euml;ntatie (aardr/gesch)?' where id=23;
update vraag set description = 'Hoe tevreden bent u over de inzet en motivatie van de leerkracht?' where id=43;
update vraag set description = 'Tot welke bevolkingsgroep(en) behoren de ouders van het kind? (U mag maximaal 2 antwoorden geven)' where id=69;
  
 * 
 * 
 * 
 * 
 * 
 * 
 *
 */
