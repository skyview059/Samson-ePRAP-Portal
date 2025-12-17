<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-09-29
 */

class Sms extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Sms_model');
        $this->load->helper('sms');
    }

    public function index(){
        $q      = urldecode_fk($this->input->get('q', TRUE));        
        $page   = intval($this->input->get('p'));
        $limit  = 25;
        $start  = startPointOfPagination($limit,$page);
        $target = build_pagination_url(Backend_URL . 'sms', 'p', true);
                
        $total_rows = $this->Sms_model->total_rows($q);
        $smss = $this->Sms_model->get_limit_data($limit, $start, $q);
       
        $data = array(
            'smss' => $smss,
            'q' => $q,
            'p' => $page,
            'pagination' => getPaginator($total_rows, $page, $target, $limit ),            
            'start' => $start,
        );
        $this->viewAdminContent('sms/sms/index', $data);
    }
 

    public function delete($id){
        $row = $this->Sms_model->get_by_id($id);

        if ($row) {
            $this->Sms_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Sms Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'sms'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Sms Not Found</p>');
            redirect(site_url( Backend_URL. 'sms'));
        }
    }
    

    public function _menu(){
        return add_main_menu('Sms', 'admin/sms', 'sms', 'fa-envelope-o"');        
    }

}