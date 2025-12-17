<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 11th March, 2021
 * Imported from OT Batch Mailer
 */

class Queue extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Queue_model');
    }

    public function index(){
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'mailer/queue/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'mailer/queue/', 'start');
        

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Queue_model->total_rows($q);
        $queues = $this->Queue_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'queues' => $queues,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('mailer/queue/index', $data);
    }

    public function popup($id){
        $row = $this->Queue_model->get_by_id($id);

       
        $data = array(                                    
            'send_to'   => $row->send_to,
            'subject'   => $row->subject,
            'body'      => $row->body,
            'created_on' => globalDateTimeFormat($row->created_on),
            'status'    =>  $row->status,
            'sent_at'   => globalDateTimeFormat($row->sent_at),
        );
        $this->load->view('mailer/queue/popup', $data);        
    }   
}