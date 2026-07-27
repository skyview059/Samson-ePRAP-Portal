<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-18
 */

class Ai extends Admin_controller
{
    
    function __construct()
    {
        parent::__construct();
        //$this->load->model('Student_model');        
    }

    public function index()
    {
                
        $students   = $this->search_student();        
        dd( $students );        
    }   
    
    function search_student()
    {
        $this->db->select('id,number_type,verified,occupation,photo,purpose_of_registration');        
        $this->db->where('status !=', 'Archive');                 
        $this->db->limit(25);
        return $this->db->get('students')->result();
    }

    public function search()
    {
        
        ajaxAuthorized();        
        $search     = $this->input->get('search', true);
        $page       = (int)$this->input->get('page');        
        $this->db->select("id, CONCAT( COALESCE(fname, ''), ' ', COALESCE(lname, ''), ' <', COALESCE(email, 'no@mail.xy'), '>' ) AS text");
        $this->db->where('status !=', 'Archive');          
        if (!empty($search)) {
            $this->db->group_start();            
            $this->db->like('email', $search, 'both');            
            $this->db->or_like('CONCAT( COALESCE(fname, ""), " ", COALESCE(lname, ""))', $search, 'both');
            $this->db->or_like('CONCAT( phone_code, phone)', $search, 'both');
            $this->db->or_like('CONCAT( whatsapp_code, whatsapp )', $search, 'both');            
            $this->db->group_end();
        }
                     
        $this->db->limit(25);
        $this->db->order_by('fname', 'ASC');
        $students = $this->db->get('students')->result();

        echo json_encode([
            'total' => 18063,
            'clients' => $students,
            // 'times' => ($time2 - $time),
            // 'page' => $page,
            // 'last_query' => $this->db->last_query()
        ]);

    }
    
}