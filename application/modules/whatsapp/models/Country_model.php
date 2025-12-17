<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends Fm_model {

    public $table = 'countries';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }   

    // get data with limit and search
    function get_limit_data()
    {
        $this->db->select('count(*)');        
	$this->db->where('present_country_id', "{$this->table}.id", false );        
	$CurrentQty = $this->db->get_compiled_select('students');        
        $this->db->select('count(*)');
	$this->db->where('country_id', "{$this->table}.id", false );        
	$OriginQty = $this->db->get_compiled_select('students');
        
        
        $this->db->select('id,name');
	$this->db->select("({$CurrentQty}) as CurrentQty");
	$this->db->select("({$OriginQty}) as OriginQty");
        $this->db->having('CurrentQty !=', 0);
        $this->db->having('OriginQty !=', 0);
        $this->db->order_by('CurrentQty', 'DESC');
        $this->db->order_by('OriginQty', 'DESC');        
        return $this->db->get($this->table)->result();
    }
    
    function graph()
    {
        $this->db->select('c.name, count(country_id) as Qty');        
        $this->db->join('countries as c','c.id=students.country_id', 'LEFT');        
	$this->db->group_by('country_id' );        
	$OriginQty = $this->db->get('students')->result();  
        
        $this->db->select('c.name, count(present_country_id) as Qty');
        $this->db->join('countries as c','c.id=students.present_country_id', 'LEFT');        
	$this->db->group_by('present_country_id' );        
	$PresentQty = $this->db->get('students')->result();  

        return [
            'OriginQty' => $OriginQty,
            'PresentQty' => $PresentQty,
        ];        
    }
}
