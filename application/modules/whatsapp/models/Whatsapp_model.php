<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_model extends Fm_model {

    public $table   = 'whatsapp_links';
    public $id      = 'id';
    public $order   = 'DESC';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_by_id($id) {
        $this->db->select('wl.*,rel_id,rel_table');
        $this->db->from('whatsapp_links as wl');
        $this->db->join('whatsapp_link_relations as wlr','wlr.wa_link_id=wl.id','LEFT');
        $this->db->where('wl.id', $id);
        return $this->db->get()->row();
    }
    
    function total_rows($q = NULL)
    {
        if ($q) {  $this->db->like('title', $q);  }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        // sent_qty
        $this->db->select('count(*)');        
	$this->db->where('wa_link_id', 'whatsapp_links.id', false );
	$sent_qty = $this->db->get_compiled_select('whatsapp_link_sent');  

        $this->db->select('whatsapp_links.*,CONCAT(u.first_name, " ",u.last_name) AS full_name');
        $this->db->select("({$sent_qty}) as sent_qty");                
        
        $this->db->join('users as u','u.id=whatsapp_links.user_id', 'LEFT');
        $this->db->order_by($this->id, $this->order);
        if ($q){ $this->db->like('title', $q); }
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function getStudentEmails($ids)
    {
        $this->db->select('email');        
        $this->db->where_in('id', $ids );
        $students = $this->db->get('students')->result();
        $send_to  = '';
        foreach($students as $s ){
            $send_to .= "{$s->email},";
        }
        return rtrim_fk($send_to, ',');
    }
    
    function getLinkSentLog($id)
    {
        $this->db->select('CONCAT(fname," ",lname) AS student,email');        
        $this->db->select('DATE_FORMAT(timestamp, "%d/%m/%Y %h:%i %p") as sent_at');        
        $this->db->from('whatsapp_link_sent as ls' );        
        $this->db->join('students as s', 's.id=ls.student_id','LEFT');        
        $this->db->where('wa_link_id', $id );
        return $this->db->get()->result();        
    }
}
