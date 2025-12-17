<?php defined('BASEPATH') or exit('No direct script access allowed');

class Practices extends Frontend_controller
{
    // every thing coming form Frontend Controller

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('practice/practice');
        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }
    }

    public function index()
    {           
               
        $data = [
            'schedules' => $this->__get_data()
        ];                
        
        $this->viewMemberContent('practice/index', $data);
    }
    
    private function __get_data(){
        $today = date('Y-m-d');
        $student_id = (int) getLoginStudentData('student_id');
        $type       = getLoginStudentData('number_type');
        $dentist    = ($type == 'GDC') ? TRUE : FALSE;
        
        $sql = "SELECT practice_schedule_id FROM `practice_students` WHERE student_id = {$student_id}";
        
        $this->db->select("DATEDIFF(datetime, '{$today}') as days_left,duration");
        $this->db->select('ps.id,p.name as category,c.name as centre,label,seat');                
        $this->db->select('DATE_FORMAT(datetime, "%d/%m/%Y %h:%i %p") as schedule'); 
        $this->db->from('practice_schedules as ps');
        $this->db->join('practices as p', 'p.id=ps.practice_id', 'LEFT');
        $this->db->join('exam_centres as c', 'c.id=ps.exam_centre_id', 'LEFT');
        if($dentist) { 
            $this->db->where('ps.practice_id', 3 );
        } else {
            $this->db->where_in('ps.practice_id', [1,2] );
        }
        $this->db->where('ps.datetime >=', date('Y-m-d') );
        $this->db->where('ps.status', 'Published' );                
        $this->db->where_not_in('ps.id', $sql, FALSE );                
        $this->db->order_by('ps.datetime', 'ASC' );                
        return $this->db->get()->result(); 
    }

    public function booked()
    {   
        $student_id = (int) getLoginStudentData('student_id');
        $data['schedules'] = $this->__my_booked_practices( $student_id );
        $this->viewMemberContent('practice/booked', $data);
    }        
    
    private function __my_booked_practices( $student_id ){
        $today = date('Y-m-d H:i:s');
        $this->db->select("DATEDIFF(datetime, '{$today}') as days_left");
        $this->db->select("TIMESTAMPDIFF(HOUR,datetime, '{$today}') as days_hrs");
        $this->db->select('bp.id, bp.status, p.name as category,c.name as centre,label,seat');                
        $this->db->select('DATE_FORMAT(datetime, "%d/%m/%Y %h:%i %p") as schedule'); 
        
        $this->db->from('practice_schedules as ps');        
        $this->db->join('practice_students as bp', 'bp.practice_schedule_id=ps.id','LEFT');
        
        $this->db->join('practices as p', 'p.id=ps.practice_id', 'LEFT');
        $this->db->join('exam_centres as c', 'c.id=ps.exam_centre_id', 'LEFT');
        
        $this->db->where('student_id', $student_id );
//        $this->db->limit( 15 );
        return $this->db->get()->result(); 
    }


    public function book_my_seat() {
        ajaxAuthorized();
        
        $data = [
            'practice_schedule_id' => $this->input->post('practice_schedule_id'),
            'student_id' => getLoginStudentData('student_id'),
            'status' => 'Enrolled',
            'remarks' => '',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('practice_students', $data );
        $id = $this->db->insert_id();
        if($id){
            $this->practice_booking_mail( $id, 'onBookPracticeNotify2Student' );
            echo ajaxRespond("OK", "Seat booked for practice successfully");            
        } else {
            echo ajaxRespond("FAIL", "Booking Fail. Please try again..");
        }
    }
       
    
    public function cancel() {
        ajaxAuthorized();        
        
        $booked_id  = (int) $this->input->post('practice_booked_id');
        $remark     = $this->input->post('remark');
        
        if(empty($booked_id)){
            echo ajaxRespond("FAIL", "Course booked ID not found! $booked_id");
            exit;
        }
        

        $this->db->set('status', 'Cancelled' );
        $this->db->set('remarks', "CONCAT(`remarks`, '<br/>{$remark}')", false );
        $this->db->set('cancelled_at', date("Y-m-d H:i:s") );                
        $this->db->where('id', $booked_id );
        $this->db->update('practice_students');
        
        $updated_row = $this->db->affected_rows();

        if($updated_row){
            $this->practice_booking_mail( $booked_id, 'onCancelPracticeNotify2Student' );            
            $this->session->set_flashdata('msgs', 'Booked has been Cancelled.');    
            echo ajaxRespond("OK", "Booked Cancelled Successfully");
        } else {
            $this->session->set_flashdata('msge', " Couldn't booked cancel.");
            echo ajaxRespond("FAIL", "Couldn't booked cancel.");
        }
    }
    
    private function practice_booking_mail( $id, $template = 'onCancelPracticeNotify2Student' ){
        $this->load->model('practice/Practice_model', 'Practice_model');
        $cancel = $this->Practice_model->getCancelationMail($id);                
        if(!$cancel){ return false; }   
        $SystemInfo = $this->load->view('frontend/practice/cancel-email', $cancel, true); 
                
        $option      = [            
            'id'        => $cancel->student_id,
            'send_to'   => $cancel->email,
//            'send_bcc'  => getSettingItem('IncomingEmail'),
            'template'  => $template,            
            'variables' => [
                '%Name%'   => $cancel->fname,                
                '%SystemInfo%'   => $SystemInfo
            ]
        ];
        Modules::run('mail/send_mail', $option );   
    }    

}