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

    function get_answers_by_question_id($question_type_id) {
        $this -> db -> from ('vraag_type_definition')-> where('vraag_type_id', $question_type_id);
        $query = $this -> db -> get();
        return $query -> result();

    }

    function get_all_questions($type) {
        //maak functie waarbij alle vragen opgehaald worden
        $this -> db -> select('vraag.*, vraag.id as question_id, vraag.description as question_description, vraag_group.description as category_name, vraag_group.description as category_id') -> from('vraag') -> join('base_type', 'vraag.base_type_id = base_type.id') -> join('vraag_group', 'vraag_group.id=vraag_groep_id') -> where('base_type.desc_code', strtoupper($type))->group_by('vraag_groep_id');
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
        //maak functie waarbij alle eerdere peilingen opgehaald worden
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

    public function insert_questionaire($questionaire_xml) {
        try {
            $xmlobject = new SimpleXMLElement($questionaire_xml);
            if ($xmlobject == false) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        
        //TODO::do stuf with $xmlobject
        
        
        return true;
    }

}

/********
 *
 * DB aanpassingen:
 * alter table vraag add base_type_id int(11) default 0;
 * create table base_type(
 *   id int(11) auto_increment,
 *   desc_code varchar(100),
 *   description varchar(255),
 *   PRIMARY KEY (id)
 * );
 * insert into base_type set desc_code='OTP', description='Ouder tevredenheid vragen';
 * insert into base_type set desc_code='LTP', description='Leerling tevredenheid vragen';
 * insert into base_type set desc_code='PTP', description='Personeel tevredenheid vragen';
 * update  vraag,report_type_definition set base_type_id=1 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =1;
 * update  vraag,report_type_definition set base_type_id=2 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =266;
 * update  vraag,report_type_definition set base_type_id=10 where vraag.id=report_type_definition.question_id and report_type_definition.report_type_id =10;
 *
 *
 */
