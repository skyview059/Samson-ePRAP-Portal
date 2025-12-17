<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends Fm_model{

    public $table   = 'users';
    
    function __construct(){
        parent::__construct();
    }

    /**
     * @param $username string    
     * @return array
     */
    function find($username){
        return $this->db
                ->select('id,role_id,first_name,last_name,email,password,status')
                ->select('CONCAT("+",mobile_code,mobile_number) AS mobile_no')
                ->get_where($this->table, ['email' => $username] )
                ->row();
    }                     
}