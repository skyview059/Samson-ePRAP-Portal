<?php

class Ajax extends Frontend_controller {
    function __construct() {
        parent::__construct();
        ajaxAuthorized();
    }

    public function clear_cache(){
        $this->db->cache_delete_all();
        echo ajaxRespond('OK', 'All DB Cache File Deleted');
    }

    public function send_query(){        
        $data = [            
            'send_to'   => $this->input->post('send_to'),                      
            'subject'   => $this->input->post('subject'),
            'message'   => nl2br_fk($this->input->post('message')),
        ];
        echo Modules::run('mail/send_query', $data);                
    }
    
    public function set_enroll_status(){ 
        ajaxAuthorized();
        
        $enroll_id  = (int) $this->input->post('id');
        $status     = $this->input->post('status');
        $this->getStudentDetailsAndEmail( $enroll_id, $status );      
        
        
        if($status == 'Delete'){                  
            $this->db->where('id', $enroll_id );
            $this->db->delete('student_exams');
            echo ajaxRespond('OK','Deleted');            
        } else {        
            $this->db->set('status', $status );
            $this->db->where('id', $enroll_id );
            $this->db->update('student_exams');
            echo ajaxRespond('OK','Updated');            
        }
    }
    
    private function getStudentDetailsAndEmail( $enroll_id, $status = 'Approve' ){
     
        $this->db->select('s.id,s.title,s.fname,s.lname,s.email');
        $this->db->select('e.name as exam_name');
        $this->db->from('student_exams as se');
        $this->db->join('students as s','s.id=se.student_id','LEFT');
        $this->db->join('exam_schedules as es','es.id=se.exam_schedule_id','LEFT');
        $this->db->join('exams as e','es.exam_id=e.id','LEFT');
        $this->db->where('se.id', $enroll_id );
        $s = $this->db->get()->row();        
           
        if(!$s){
            return false;
        }
        
        $option = [            
            'send_to'   => $s->email,                      
            'full_name' => "{$s->title} {$s->fname} {$s->lname}",                      
            'exam'      => $s->exam_name,            
            'id'        => $s->id,            
        ];
                                
        if($status == 'Enrolled'){
            $option['template'] = 'onApproveMockRequest';            
        } elseif( $status == 'Blocked' ){
            // This is Actully Blocking a Student to stop Enroll 
            $option['template'] = 'onBlockMockRequest';
        } else {            
            // Deleting the Booking and giving him/her book again
            $option['template'] = 'onCancelledMockRequest';
        }        
        Modules::run('mail/onMockRequestAction', $option);
    }

    public function getStudents(){
        $per_page = 15;
        $page = $this->input->get('page');
        $keyword = $this->input->get('search_keyword', true);

        // Start:: get total row count
        if ($keyword) {
            $this->db->like('CONCAT(fname, COALESCE(CONCAT(" ", mname), ""), " ", lname)', $keyword);
            $this->db->or_like('email', $keyword);
        }
        $this->db->from('students');
        $total_row =  $this->db->count_all_results();
        // End:: get total row count


        // Start:: get students
        $this->db->select('id, email');
        $this->db->select("CONCAT(fname, ' ', IFNULL(mname, ''), ' ', lname) as full_name", FALSE);
        $this->db->from('students');
        $this->db->limit($per_page, $per_page * ($page ? $page - 1 :0 ) );

        if ($keyword){
            $this->db->like('CONCAT(fname, COALESCE(CONCAT(" ", mname), ""), " ", lname)', $keyword);
            $this->db->or_like('email', $keyword);
        }

        $students = $this->db->get()->result();
        // End:: get students

        $data['current_page'] = $page ? $page : 1;
        $data['data'] = $students;

        $data['per_page'] = $per_page;
        $data['total'] = $total_row;

        echo json_encode( ['students' => $data] );
    }
}