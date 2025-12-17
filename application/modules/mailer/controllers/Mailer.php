<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Author: Khairul Azam
 * Date : 11th March, 2021
 * Imported from OT Batch Mailer
 */

class Mailer extends Admin_controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('Mailer_model');
        $this->load->helper('mailer');        
    }
    
    public function index() {        
        $template       = $this->input->get('template');
        $template_id    = $this->input->get('template_id');
        $type           = $this->input->get('type');
        $get_templates   = $this->get_template();
        $data = [
            'template'      => $template,
            'template_id'   => $template_id,
            'type'          => $type,
            'template_list' => $get_templates,
        ] ;
        $this->viewAdminContent('mailer/mailer/index', $data);
    }
    
    public function compose() {        
                        
        $id    = (int)$this->input->get('id');        
        $template = $this->db->select('id, title as subject,template')
                ->where('id', $id)
                ->get('email_templates')
                ->row();
        
        if($template){
            $data['subject'] = $template->subject;
            $data['content'] = $template->template;            
        } else {
            $data['subject'] = '';
            $data['content'] = '';
        }        
        
        $this->viewAdminContent('mailer/mailer/compose', $data);
    }

    private function get_template(){
        $templates = $this->db->select('id, title as subject')
                ->get('email_templates')
                ->result();        
                
        $html = '<option value="0">Blank Template</option>';
        foreach($templates as $template){
           $html .= '<option value="'.$template->id.'"';
           $html .= '>'.$template->subject.'</option>'; 
        }        
        return  $html;
    }
    

    public function save_queue(){
         
        $subject        = $this->input->post('title');
        $template       = isset($_POST['template']) ? $_POST['template'] : '';
        
        
        $m_type         = $this->input->post('number_type');        
        $gender         = $this->input->post('gender');
        $contact_by_rm  = $this->input->post('contact_by_rm');
        $exam_id        = (int) $this->input->post('exam_id'); /* Mock Exam ID */
        $centre_id      = (int) $this->input->post('centre_id'); /* Mock Exam ID */
        $till_date      = $this->input->post('till_exam_date');
        $date_from      = $this->input->post('date_from');
        $date_to        = $this->input->post('date_to');
        $student_ids    = $this->input->post('students');
        $case           = $this->input->post('case');   
        
        //Job Notification
                        
        $gender            = $this->input->post('gender'); 
        $contact_by_rm     = $this->input->post('contact_by_rm'); 
        $job_profile       = $this->input->post('job_profile'); 
        $job_specialty_ids = $this->input->post('job_specialty_ids'); 
        
        if($this->input->post('type')=='general'){
            $students = $this->Mailer_model->get_student(
                        $m_type, $exam_id, $centre_id, $till_date,
                        $case, $date_from,$date_to, $student_ids
                    );   
        }else{
            
            $students = $this->Mailer_model->get_student_job_notification(
                    $m_type, $gender, $contact_by_rm, $job_profile, $job_specialty_ids
                );  
        }
        
        $created_on     = date('Y-m-d H:i:s');
        $mail_queues    = [];
        foreach($students as $s){ 
            $mail_queues[] = [
                'student_id' => $s->id,          
                'send_to'   => $s->email,          
                'subject'   => $subject,
                'body'      => $this->setStudentName($template, "{$s->fname} {$s->lname}"),
                'created_on' => $created_on,
            ];            
        }
        
        if($mail_queues){
            $this->db->insert_batch('mail_queues', $mail_queues );
            $qty = sizeof($mail_queues);
            $this->session->set_flashdata('message', "<p class='ajax_success'>{$qty} Email Added to Queue</p>");
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">No Email Added to Queue.</p>');
        }                            
        redirect(site_url( Backend_URL . 'mailer/queue' ) );
    }
    
    private function setStudentName($template, $name = 'Name') {                   
        return  str_replace('%name%', $name, $template);                          
    }
    
    public function get_students(){        
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $this->db->select('id,title,fname,lname,email');
        $this->db->from('students');
        $this->db->limit($limit, $start);
        $this->db->order_by('id','DESC');
        $students = $this->db->get()->result();
        
        $next = ($start + $limit );
        
        $html = '';
        foreach($students as $s){
           $html .= '<div class="checkbox">';
           $html .= '<label>';
           $html .= '<input name="students[]" type="checkbox" value="'.$s->id.'">';
           $html .= $s->title .'. ' . $s->fname .' ' .$s->lname;
           $html .= "<em><br/>&nbsp;|_ {$s->email}</em>";
           $html .= '</label>';
           $html .= '</div>';
        }
        $response = [
            'Status' => 'OK',
            'Msg' => $html,
            'Button' => "load_more({$next}, {$limit})",
        ];        
        echo json_encode($response);
        
    }    
            
    public function getStudents(){        
        $kewword = $this->input->get('keyword');
       
        $this->db->select('id,title,fname,lname,email');
        $this->db->from('students');
        $this->db->like('fname', $kewword, 'both');
        $this->db->or_like('lname', $kewword, 'both');
        $this->db->or_like('email', $kewword, 'both');
        $this->db->limit(50);
        $this->db->order_by('id','DESC');
        $students = $this->db->get()->result();
        
        
        $html = '';
        if($students){
        foreach($students as $s){
           $html .= '<div class="checkbox">';
           $html .= '<label>';
           $html .= '<input name="students[]" type="checkbox" value="'.$s->id.'">';
           $html .= $s->title .'. ' . $s->fname .' ' .$s->lname;
           $html .= "<em><br/>&nbsp;|_ {$s->email}</em>";
           $html .= '</label>';
           $html .= '</div>';
        }
        }else{
            $html .= 'No Record Found';
        }
        $response = [
            'Status' => 'OK',
            'Msg' => $html
        ];
        echo json_encode($response);
        
    }
    
    
    public function _menu() {
        return buildMenuForMoudle([
            'module' => 'Mail Queue',
            'icon' => 'fa-envelope',
            'href' => 'mailer',
            'children' => [
                [
                    'title' => 'New Queue',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'mailer'
                ],[
                    'title' => 'Pending Queue',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'mailer/queue'
                ]
            ]
        ]);
    }        
}
