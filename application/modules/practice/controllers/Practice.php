<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-17
 */

class Practice extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Practice_model');
        $this->load->helper('practice');
        $this->load->helper('centre/centre');
        $this->load->library('form_validation');
        $this->load->helper('whatsapp/whatsapp');
    }

    public function index()
    {        
        $start = intval($this->input->get('start'));
        $id = intval($this->input->get('id'));
        $tab = ($this->input->get('tab'));
        if(empty($tab)){
            $tab = 'coming';
        }

        $config['base_url'] = build_pagination_url(Backend_URL . 'practice', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'practice', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Practice_model->total_rows($id,$tab);
        $practices = $this->Practice_model->get_limit_data($config['per_page'], $start, $id,$tab);
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'practices' => $practices,                  
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'id' => $id,
            'tab' => $tab,
            'coming' => $this->Practice_model->qty( 'coming', $id ),
            'past' => $this->Practice_model->qty( 'past', $id ),
        );
        $this->viewAdminContent('practice/practice/index', $data);
    }
   
    public function student($id)
    {
     
        $row = $this->Practice_model->get_by_id($id);
        if ($row) {
            
            $data = array(
                'id' => $row->id,                
                'practice_id'   => $row->practice_id,   
                'practice_name'   => $row->practice_name,   
                'datetime' => $row->datetime,
                'created_at' => $row->created_at,               
                'centre_name' => $row->centre_name,               
                'centre_address' => $row->centre_address,                
                'students' => $this->Practice_model->getEnrolledStudents($id),
                'start' => 0
            );       
            
            $this->viewAdminContent('practice/practice/student', $data);
        } else {
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice'));
        }
    }

    public function create()
    {
        $id = $this->input->get('id');
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'practice/create_action'),
            'id' => set_value('id'),
            'practice_id' => set_value('practice_id', $id),
            'exam_centre_id' => set_value('exam_centre_id'),            
            'date'  => set_value('date',    date('Y-m-d', strtotime('+5 Day'))),
            'hour'  => set_value('hour',    '10' ),
            'min'   => set_value('min',     '0'),
            'am_pm' => set_value('am_pm',   'AM'),
            
            'duration' => set_value('duration', 4 ),            
            'multidp' => set_value('multidp' ),            
            'seat' => set_value('seat', 10),            
            'label' => set_value('label'),            
            'created_at' => set_value('created_at'),
            'updated_at' => set_value('updated_at'),
        );
        $this->viewAdminContent('practice/practice/create', $data);
    }

    public function create_action()
    {              
       
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $practice_id = (int) $this->input->post('practice_id');
            $multidp    = $this->input->post('multidp');
            $dates      = explode(',', $multidp);
        
            if(!empty( $dates) && count( $dates )){
                $this->saveBatchPractices($dates);
            }
            
            $this->session->set_flashdata('msgs', 'Practice Added Successfully');
            redirect(site_url(Backend_URL . "practice?id={$practice_id}"));
        }
    }
    private function saveBatchPractices($dates){
        foreach($dates as $date ){
            $time = $this->input->post('hour', TRUE);
            $time .= ':'.$this->input->post('min', TRUE);
            $time .= ' '.$this->input->post('am_pm', TRUE);

            $data = array(
                'practice_id' => $this->input->post('practice_id', TRUE),
                'exam_centre_id' => $this->input->post('exam_centre_id', TRUE),                
                'datetime' => date('Y-m-d H:i:s', strtotime("{$date} {$time}" )),                                                
                'duration' => $this->input->post('duration'),                
                'seat' => $this->input->post('seat'),                
                'label' => $this->input->post('label'),                
                'status' => 'Published',                
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->Practice_model->insert($data);
            $practice_id = $this->db->insert_id();
            $this->saveRelationWA2Practice($practice_id);
        }
    }        

    private function saveRelationWA2Practice($practice_id){
        $wa_link_id = (int) $this->input->post('whatsapp_id'); 
        if($wa_link_id){
            $rel_tbl = Tools::_link_for('Practice');
            Modules::run('whatsapp/_save_relation', $wa_link_id, $rel_tbl, $practice_id  );
        }
    }

    public function update($id)
    {
        $row = $this->Practice_model->get_by_id($id);                
        if ($row) {
            
            $data = array(
                'button' => 'Update',
                'action'            => site_url(Backend_URL . 'practice/update_action'),
                'id'                => set_value('id', $row->id),
                'practice_id'           => set_value('practice_id', $row->practice_id),
                'exam_centre_id'    => set_value('exam_centre_id', $row->exam_centre_id),               
                
                'date'  => set_value('date',    date('Y-m-d', strtotime($row->datetime))),
                'hour'  => set_value('hour',    date('h', strtotime($row->datetime))),
                'min'   => set_value('min',     date('i', strtotime($row->datetime))),
                'am_pm' => set_value('am_pm',   date('a', strtotime($row->datetime))),
                
                'duration' => set_value('duration', $row->duration ),               
                'seat' => set_value('seat', $row->seat ),               
                'status' => set_value('status', $row->status ),               
                'label' => set_value('label', $row->label),
                                
                'created_at' => set_value('created_at', $row->created_at),
                'updated_at' => set_value('updated_at', $row->updated_at),
            );
            $this->viewAdminContent('practice/practice/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $date_time = $this->input->post('date', TRUE);
            $date_time .= ' '.$this->input->post('hour', TRUE);
            $date_time .= ':'.$this->input->post('min', TRUE);
            $date_time .= ' '.$this->input->post('am_pm', TRUE);
            $datetime = date('Y-m-d H:i:s', strtotime($date_time));

            $data = array(
                'practice_id'   => (int) $this->input->post('practice_id', TRUE),
                'exam_centre_id'=> (int) $this->input->post('exam_centre_id', TRUE),                
                'datetime'      => $datetime,                               
                'updated_at'    => date('Y-m-d H:i:s'),                
                'duration'      => $this->input->post('duration'),                
                'seat'          => $this->input->post('seat'),                
                'status'        => $this->input->post('status'),                
                'label'         => $this->input->post('label')                
            );

            $this->Practice_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Practice Updated Successlly');
            redirect(site_url(Backend_URL . 'practice/update/' . $id));
        }
    }

    public function delete($id)
    {
        $row = $this->Practice_model->get_by_id($id);
        if ($row) {            
            $students   = countPracticeStudent($id);
            $links      = $students;
    
            $data = array(
                'id'                => $row->id,
                'practice_name'     => $row->practice_name,   
                'centre_name'       => $row->centre_name,
                'centre_address'    => $row->centre_address,
                'datetime'          => $row->datetime,
                'created_at'        => $row->created_at,
                'updated_at'        => $row->updated_at,                
                'students'          => countPracticeStudent($id),
            );
            $data['warning'] = ($links) ? true : false;
            
            $this->viewAdminContent('practice/practice/delete', $data);
        } else {
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice'));
        }
    }


    public function delete_action($id)
    {
        $row = $this->Practice_model->get_by_id($id);
        if ($row) {
            $red_id = $row->practice_id;
            $this->Practice_model->delete($id);
            $this->session->set_flashdata('msgs', 'Practice Deleted Successfully');
            redirect(site_url(Backend_URL . "practice?id={$red_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice'));
        }
    }        
    
    protected function _get_practice_names( $prefix = 'practice'){
        
        
        $this->db->select('id,name');
        $practices = $this->db->get('practices')->result();
        
        $btn = [];
        foreach($practices as $p ){
            $qty = '';
            if($prefix == 'practice'){
                $count = Tools::getPractieSchedules($p->id);
                $qty = "<sup>({$count})</sup>";
            }
            $btn[] = [
                'title' => "{$p->name} {$qty}",
                'icon' => 'fa fa-circle-o',
                'href' => "{$prefix}?id={$p->id}"
            ];
        }
        return $btn;
    }
    
    
    public function _menu()
    {
        $menus = [
            'module' => 'Practices',
            'icon' => 'fa-industry',
            'href' => 'practice',
            'children' => $this->_get_practice_names()
        ];        
        return buildMenuForMoudle($menus);
    }

    public function _rules()
    {
        $id = $this->input->post('id', TRUE);
        $this->form_validation->set_rules('practice_id', 'practice course', 'trim|required|numeric');
        $this->form_validation->set_rules('exam_centre_id', 'practice centre', 'trim|required|numeric');        
        
        if($id){
            $this->form_validation->set_rules('date', 'dates', 'trim|required');
        }else{
            $this->form_validation->set_rules('multidp', 'dates', 'trim|required');
        }
        
        $this->form_validation->set_rules('hour', 'hour', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function practice_list_by_centre(){
        ajaxAuthorized();
        $center_id = (int) $this->input->post('centre_id');
        $html = getPracticeNameDropDownByCentre( $center_id, 0, true );        
        if ($html == '') {
            $html = '<option value="0">Practice not found!</option>';
        }
        echo ajaxRespond('OK', $html);
    }
    
//    public function get_practice_students($student_practice_id){
//        
//        $data       = [
//            'student_practice_id'   => $student_practice_id,
//            'student_practice_info'    => $this->Practice_model->get_student_practice_by_id( $student_practice_id )             
//        ];
//        $this->load->view('practice/practice/student_practice_info', $data);       
//    }    
    
    public function cancel($id) {
        
        $row = $this->Practice_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'                => $row->id,
                'practice_name'   => $row->practice_name,   
                'centre_name'       => $row->centre_name,
                'centre_address'    => $row->centre_address,
                'datetime'          => $row->datetime,
                'status'            => $row->status,
                'created_at'        => $row->created_at,
                'updated_at'        => $row->updated_at,               
            );
            
            $this->viewAdminContent('practice/practice/cancel', $data);
        } else {
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice?id='.$id));
        }
    }
    
    public function cancel_action($id)
    {
        $match = $this->Practice_model->get_by_id($id);
        if(!$match){
            $this->session->set_flashdata('msge', 'Practice Not Found');
            redirect(site_url(Backend_URL . 'practice'));
        }
            
        //Get assigned students by practice information
        $students = $this->Practice_model->getEnrolledStudents($id, true );

        $data = array(            
            'updated_at'    => date('Y-m-d H:i:s'),           
            'status'        => 'Cancelled'
        );
        $this->Practice_model->update($id, $data);

        if(!$students){
            $this->session->set_flashdata('msgs', 'Practice Canceled Successfully');
            redirect(site_url(Backend_URL . "practice/student/{$id}"));
        } else {

            $practice   = $this->Practice_model->getScheduledPractice($id);                                                           
            $SystemInfo = $this->load->view('frontend/practice/cancel-email', $practice, true); 

            $bcc = '';
            foreach($students as $s ){
                $bcc .= "{$s->email},";
            }                        
            $option      = [            
                'id'        => 0,
                'send_to'   => getSettingItem('IncomingEmail'),
                'send_bcc'  => rtrim_fk($bcc, ','),
                'template'  => 'onCancelPracticeNotify2Students',            
                'variables' => [
                    '%Name%'   => 'Candidate',                
                    '%SystemInfo%'   => $SystemInfo
                ]
            ];
            Modules::run('mail/send_mail', $option );
            $this->session->set_flashdata('msgs', 'Practice Canceled Successfully');
            redirect(site_url(Backend_URL . "practice/student/{$id}"));
        }
    }
    
    public function attendance() {
        ajaxAuthorized();
        $id = $this->input->post('id');
        $attendance = $this->input->post('attendance');
       
        $this->db->where('id', $id);
        $result = $this->db->update('practice_students', array('attendance' => $attendance));
        if($result){
            $this->session->set_flashdata('msgs', 'Attendance has been updated');
            echo ajaxRespond("OK", "Attendance has been updated");            
        } else {
            echo ajaxRespond("FAIL", "Attendance Check Fail. Please try again..");
        }
    }
    
}