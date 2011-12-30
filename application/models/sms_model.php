<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sms_model extends CI_Model {

    /**
     * SMS model, DB model for SMS database.
     *
     * Create web interface for report downloads 
     */
    function __construct()
    {
        parent::__construct();
    }
    
    function get_last_ten_entries()
    {
        $query = $this->db->get('peiling', 10);
        return $query->result();
        
    }

    function get_question_by_id($id)
    {
        $query = $this->db->get_where('vraag', array('id' => $id));
        return $query->result();
        
    }
    
    function get_answers_by_question_id($question_type_id)
    {
        $query = $this->db->get_where('vraag_type_definition', array('vraag_type_id' => $question_type_id));
        return $query->result();
        
    }
    
    function get_all_questions($type)
    {
        //maak functie waarbij alle vragen opgehaald worden
        $this->db->select('vraag.*')->from('vraag')->join('base_type','vraag.base_type_id = base_type.id')->where('base_type.desc_code', strtoupper($type));
        
        $query = $this->db->get();
        return $query->result();
        
    }
    
    function get_all_questionaires_by_school()
    {
        //maak functie waarbij alle eerdere peilingen opgehaald worden
        
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