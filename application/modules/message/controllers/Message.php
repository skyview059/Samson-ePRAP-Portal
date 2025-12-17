<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2018-08-14
 */

class Message extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Message_model');
        $this->load->helper('message');
        $this->load->helper('student/student');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q      = urldecode_fk(trim_fk($this->input->get('q', TRUE)));
        $start  = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = Backend_URL . 'message?q=' . urlencode_fk($q);
            $config['first_url'] = Backend_URL . 'message?q=' . urlencode_fk($q);
        } else {
            $config['base_url'] = Backend_URL . 'message';
            $config['first_url'] = Backend_URL . 'message';
        }

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Message_model->total_rows($q, 'user');
        $messages = $this->Message_model->get_limit_data($config['per_page'], $start, $q, 'user');
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $data = array(
            'messages' => $messages,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('message/message/index', $data);
    }
    
    public function open()
    {
        $data = array(
            'id' => (int) $this->input->get('id'),
            'parent_id' => 0,
            'student_id' => 0,
            'user_id' => 0,
            'subject' => '',
            'body' => ''                
        );
        $this->viewAdminContent('message/message/open', $data);
    }
    public function open_action()
    {
        $student_id = (int) $this->input->post('student_id');
        $data = array(
            'parent_id'     => 0,
            'student_id'    => $student_id,
            'user_id'       => $this->user_id,
            'subject'       => $this->input->post('subject'),
            'body'          => $_POST['message'],
            'open_at'       => date('Y-m-d H:i:s'),
            'opened_by'     => 'Admin',
        );
        $insert_id = $this->Message_model->insert($data);
        
        if($insert_id){
            $student_email = Tools::getStudentData($student_id, 'email');
            $option = [
                'send_to' => $student_email,
                'subject' => $this->input->post('subject'),
                'body' => $_POST['message'],
                'id' => $student_id,
            ];
            Modules::run('mail/sendNotifyForMessage', $option);            
        }
        
        $this->session->set_flashdata('msgs', 'Message Opened Successfully');
        redirect(site_url(Backend_URL . 'message'));
    }
    
    public function reply_action()
    {
        $id = $this->input->post('parent_id');
        $student_id = (int) $this->input->post('student_id');
        $data = array(
            'parent_id'     => (int) $this->input->post('parent_id'),
            'student_id'    => $student_id,
            'user_id'       => $this->user_id,
            'subject'       => 'Inherited',
            'body'          => $_POST['message'],
            'open_at'       => date('Y-m-d H:i:s'),
            'opened_by'     => 'Admin',
        );
        $insert_id = $this->Message_model->insert($data);
        
        if($insert_id){
            $student_email = Tools::getStudentData($student_id, 'email');
            $option = [
                'send_to' => $student_email,
                'subject' => 'Replied of Pervious Email',
                'body' => $_POST['message'],
                'id' => $student_id,
            ];
            Modules::run('mail/sendNotifyForMessage', $option);            
        }
        $this->session->set_flashdata('msgs', 'Message Opened Successfully');
        redirect(site_url(Backend_URL . "message/view/{$id}"));
    }
   
    public function view($id)
    {
        $row = $this->Message_model->get_by_id($id);                        
        if ($row) {
            $this->markAsSeen( $row->opened_by, $id);
            $data = array(
                'id'            => $row->id,
                'parent_id'     => $row->id,
                'student_id'    => $row->student_id,
                'subject'       => $row->subject,
                'body'          => $row->body,
                'status'        => $row->status,
                'open_at'       => globalDateTimeFormat($row->open_at),
                'opened_by'     => $row->opened_by,
                'replys'        => $this->Message_model->get_replys($id)
            );
            
            if($row->opened_by == 'Student'){
                $data['sender']     = getStudentName($row->student_id);
                $data['receiver']   = getUserNameByID($row->user_id);
            } else {
                $data['sender']     = getUserNameByID($row->user_id);
                $data['receiver']   = getStudentName($row->student_id);                
            }
            
            $this->viewAdminContent('message/message/view', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Message Not Found</p>');
            redirect(site_url(Backend_URL . 'message'));
        }
    }
   
   
    public function view_modal($id)
    {
        $row = $this->Message_model->get_by_id($id);                        
        if ($row) {            
            $data = array(
                'id'            => $row->id,
                'parent_id'     => $row->id,
                'student_id'    => $row->student_id,
                'subject'       => $row->subject,
                'body'          => $row->body,
                'status'        => $row->status,
                'open_at'       => globalDateTimeFormat($row->open_at),
                'opened_by'     => $row->opened_by,
                'replys'        => $this->Message_model->get_replys($id)
            );
            
            if($row->opened_by == 'Student'){
                $data['sender']     = getStudentName($row->student_id);
                $data['receiver']   = getUserNameByID($row->user_id);
            } else {
                $data['sender']     = getUserNameByID($row->user_id);
                $data['receiver']   = getStudentName($row->student_id);                
            }
            
            $this->load->view('message/message/view_modal', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Message Not Found</p>');
            redirect(site_url(Backend_URL . 'message'));
        }
    }
   
    public function _menu()
    {
        return add_main_menu('Message', 'admin/message', 'message', 'fa-envelope-o');
    }
    
    private function markAsSeen( $opened_by, $id )
    {
        if($opened_by == 'Admin'){
            return false;
        }
        $this->db->set('status','Seen');
        $this->db->group_start();
        $this->db->where('id',$id);
        $this->db->or_where('parent_id',$id);
        $this->db->group_end();
        $this->db->update('messages');
    }
    
    public function multi_delete( )
    {        
        $ids = $this->input->post('id');   
        if(empty($ids)){
            $this->session->set_flashdata('msge', 'Please Select Message to Delete');
            redirect(site_url(Backend_URL . 'message'));
            exit;
        }
        foreach($ids as $id){
            $this->db->where('parent_id', $id)->delete('messages');
            $this->db->where('id', $id)->delete('messages');
        }
        
        $this->session->set_flashdata('msgs', 'Message Deleted Successfully');
        redirect(site_url(Backend_URL . 'message'));
    }

    public function send_message_from_modal()
    {
        ajaxAuthorized();
        
        $student_id = (int) $this->input->post('student_id');
        $subject = $this->input->post('subject');
        $data = array(
            'parent_id'     => 0,
            'student_id'    => $student_id,
            'user_id'       => $this->user_id,
            'subject'       => $subject,
            'body'          => $_POST['message'],
            'open_at'       => date('Y-m-d H:i:s'),
            'opened_by'     => 'Admin',
        );
        
        $insert_id = $this->Message_model->insert($data);
        if($insert_id){
            $student_email = Tools::getStudentData($student_id, 'email');
            $option = [
                'send_to' => $student_email,
                'subject' => $subject,
                'body' => $_POST['message'],
                'id' => $student_id,
            ];
            Modules::run('mail/sendNotifyForMessage', $option);            
        }
        
        
        echo ajaxRespond('OK', '<p class="ajax_success">Message Opened Successfully</p>');
    }


}