<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 29 May 2021 @12:29 pm
 */

class Graph extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Country_model');
    }

    public function index()
    {
        
        $data = $this->Country_model->graph();

//        dd( $data );
        
        $this->viewAdminContent('whatsapp/graph/index', $data);
    }
}
