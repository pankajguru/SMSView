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
        'vraag_type_definition')-> where('vraag_type_id', $question_type_id) -> order_by('value');
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

    function get_vraag_group_by_description($description, $min=0) {
        $this -> db -> from (
        'vraag_group')-> where('id >=', $min)-> like('description', $description) ;
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_all_questions($type) {
        //maak functie waarbij alle vragen opgehaald worden
        $this -> db -> select('vraag_type.desc_code as question_type_desc_code, vraag.*, vraag.id as question_id, vraag.description as question_description, vraag_group.description as category_name, vraag_group.id as category_id') -> from('vraag') -> join('base_type', 'vraag.base_type_id = base_type.id') -> join('vraag_group', 'vraag_group.id=vraag_groep_id') -> join('vraag_type', 'vraag_type.id=vraag.vraag_type_id') -> where('base_type.desc_code', strtoupper($type));
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

    function get_all_questions_by_peiling_type_desc_code($type) {
        //maak functie waarbij alle vragen uit een peiling opgehaald worden
        $this -> db -> select('formulier_type_definition.question_id') -> from('formulier_type_definition') -> join('formulier_type', 'formulier_type_definition.formulier_type_id= formulier_type.id') -> where('formulier_type.desc_code', $type);
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
            'description' => 'muis '.$peiling_type_id
        );
        $this->db->insert('peiling_type', $peiling_type); 
        $web_peiling = array(
            'id' => $peiling_type_id,
            'web_type' => 'MUIS_'.$peiling_type_id
        );
        $this->db->insert('web_peiling', $web_peiling); 
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
            'description' => 'Muis '.$peiling_type_id
        );
        $this->db->insert('report_type', $report_type); 
        
        $report_question_id = 1;
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
            'description' => 'Muis '.$peiling_type_id
        );
        $this->db->insert('formulier_type', $formulier_type); 
        $formulier_question_id = 1;
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

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) collate utf8_bin NOT NULL default '0',
  `ip_address` varchar(16) collate utf8_bin NOT NULL default '0',
  `user_agent` varchar(150) collate utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
 
 insert into base_type set desc_code='OTP', description='Ouder tevredenheid vragen';
 insert into base_type set desc_code='LTP', description='Leerling tevredenheid vragen';
 insert into base_type set desc_code='PTP', description='Personeel tevredenheid vragen';
 update  vraag,report_type_definition set base_type_id=1 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =1;
 update  vraag,report_type_definition set base_type_id=2 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =266;
 update  vraag,report_type_definition set base_type_id=2, vraag_groep_id=1612 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =324;
 update  vraag,report_type_definition set base_type_id=3 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =10;


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
update vraag set description = 'Tot welke bevolkingsgroep(en) behoren de ouders van het kind?<br>(U mag maximaal 2 antwoorden geven)' where id=69;
 *  * 
 * 
 * 
 * 
 * 
 * 
 *
 */
