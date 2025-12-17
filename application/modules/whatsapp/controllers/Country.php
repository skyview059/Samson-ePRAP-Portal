<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 29 May 2021 @12:29 pm
 */

class Country extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Country_model');
        $this->load->helper('whatsapp');
    }

    public function index()
    {
        
        $countrys = $this->Country_model->get_limit_data();
        $data = array(
            'start' => 0,
            'countrys' => $countrys,            
        );
        $this->viewAdminContent('whatsapp/country/index', $data);
    }
}
