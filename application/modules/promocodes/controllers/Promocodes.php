<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Sheraz Howlader
 * Date : 2023-10-06
 */

class Promocodes extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Promocodes_model');
        $this->load->helper('promocodes');
        $this->load->library('form_validation');
    }

    public function index(){
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'promocodes/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'promocodes/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Promocodes_model->total_rows($q);
        $promocodess = $this->Promocodes_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'promocodess' => $promocodess,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        foreach ($promocodess as $key => $promocode){
            $courses = array_column($this->db->from('promocode_course as pc')
                ->select('c.name')
                ->where('promocode_id', $promocode->id )
                ->join('courses as c', 'c.id = pc.course_id')
                ->get()->result_array(), 'name');


            $promocodess[$key]->courses = implode(', ' , $courses);
        }

        $this->viewAdminContent('promocodes/promocodes/index', $data);
    }

    public function read($id){
        $row = $this->Promocodes_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'            => $row->id,
                'amount'        => $row->amount,
                'code'          => $row->code,
                'created_on'    => $row->created_on,
                'discount_type' => $row->discount_type,
                'end_date'      => $row->end_date,
                'start_date'    => $row->start_date,
                'status'        => $row->status,
                'updated_on'    => $row->updated_on,
                'user_id'       => $row->user_id,
                'uses_limit'    => $row->uses_limit,
                'is_special'    => $row->is_special,
                'promoter_name' => $row->promoter_name,
	        );

            $user_info = $this->db->select("CONCAT(users.first_name,' ', users.last_name) as full_name")
                ->where('id', $row->user_id)
                ->get('users')
                ->row_array();

            $data['full_name'] = $user_info['full_name'];
            $this->viewAdminContent('promocodes/promocodes/read', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Promocodes Not Found</p>');
            redirect(site_url( Backend_URL. 'promocodes'));
        }
    }

    public function create(){
        $courses = $this->db->select('id,name')->get('courses')->result();
        $students = $this->db->select('id')
            ->select("CONCAT(fname, ' ', IF(mname IS NULL or mname = '', '', CONCAT(mname, ' ')), lname) as full_name")
            ->get('students')
            ->result();

        $data = array(
            'button'        => 'Create',
            'action'        => site_url( Backend_URL . 'promocodes/create_action'),
            'id'            => set_value('id'),

            'code'          => set_value('code'),
            'courseIds'     => set_value('courseIds'),
            'uses_limit'    => set_value('uses_limit'),
            'amount'        => set_value('amount'),
            'discount_type' => set_value('discount_type'),
            'start_date'    => set_value('start_date'),
            'end_date'      => set_value('end_date'),
            'status'        => set_value('status'),
            'use_multiple'  => set_value('uses_limit'),
            'promoter_name' => set_value('promoter_name'),
            'courses'       => $courses,
            'students'      => $students
	    );

        $this->viewAdminContent('promocodes/promocodes/create', $data);
    }
    
    public function create_action(){
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            return $this->create();
        }

        $courseIDs = $this->input->post('courseIds', TRUE);
        $studentIds = $this->input->post('studentIds', TRUE);
        $is_special = $this->input->post('is_special', TRUE);

        $promocode = $this->Promocodes_model->insert([
            'user_id' => $this->user_id,
            'code' => $this->input->post('code', TRUE),
            'discount_type' => $this->input->post('discount_type', TRUE),
            'amount' => $this->input->post('amount', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'uses_limit' => $this->input->post('uses_limit', TRUE),
            'use_multiple' => $this->input->post('use_multiple', TRUE) ? 'yes' : 'no',
            'status' => $this->input->post('status', TRUE),
            'is_special' => $is_special ? 1 : 0,
            'promoter_name' => $this->input->post('promoter_name', TRUE),
            'created_on'    => date('Y-m-d H:i:s'),
            'updated_on'    => date('Y-m-d H:i:s'),
        ]);

        // insert selected courses
        foreach ($courseIDs as $courseID) {
            $this->db->insert('promocode_course', [
                'promocode_id' => $promocode,
                'course_id' => $courseID,
            ]);
        }

        if ($is_special && $studentIds) {
            // insert selected courses
            foreach ($studentIds as $studentId) {
                $this->db->insert('promocode_student', [
                    'promocode_id' => $promocode,
                    'student_id' => $studentId,
                ]);
            }
        }

        $this->session->set_flashdata('message', '<p class="ajax_success">Promocodes Added Successfully</p>');
        redirect(site_url(Backend_URL . 'promocodes'));
    }
    
    public function update($id){
        $row = $this->Promocodes_model->get_by_id($id);

        $courses = $this->db->select('id,name')
            ->get('courses')
            ->result();

        $students = $this->db->select('id, email')
            ->select("CONCAT(students.fname, ' ', IF(students.mname IS NULL or students.mname = '', '', CONCAT(students.mname, ' ')), students.lname) as full_name")
            ->join('promocode_student as ps', 'students.id = ps.student_id', 'LEFT')
            ->where('ps.promocode_id', $id)
            ->get('students')
            ->result();

        $courseIds = implode(',', array_column($this->db
                ->select('course_id')
                ->where('promocode_id', $id)
                ->get('promocode_course')
                ->result_array(), 'course_id')
        );

        if ($row) {
            $data = array(
                'button'    => 'Update',
                'action'    => site_url( Backend_URL . 'promocodes/update_action'),
                'amount'    => set_value('amount', $row->amount),
                'code'      => set_value('code', $row->code),
                'discount_type' => set_value('discount_type', $row->discount_type),
                'end_date'   => set_value('end_date', $row->end_date),
                'id'         => set_value('id', $row->id),
                'user_id'    => set_value('user_id', $row->user_id),
                'start_date' => set_value('start_date', $row->start_date),
                'status'     => set_value('status', $row->status),
                'uses_limit' => set_value('uses_limit', $row->uses_limit),
                'use_multiple'  => set_value('use_multiple', $row->use_multiple),
                'is_special'    => $row->is_special,
                'promoter_name' => $row->promoter_name,
                'courses'       => $courses,
                'students'      => $students,
                'courseIds'     => $courseIds
	        );

            $this->viewAdminContent('promocodes/promocodes/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Promocodes Not Found</p>');
            redirect(site_url( Backend_URL. 'promocodes'));
        }
    }
    
    public function update_action(){
        $id = $this->input->post('id', TRUE);
        $courseIDs = $this->input->post('courseIds',TRUE);
        $studentIds = $this->input->post('studentIds',TRUE);
        $is_special = $this->input->post('is_special',TRUE);

        // delete all courses by this promo-code related
        $this->db->delete('promocode_course', ['promocode_id' => $id]);

        // insert selected courses
        foreach ($courseIDs as $courseID){
            $this->db->insert('promocode_course', [
                'promocode_id'  => $id,
                'course_id'     => $courseID,
            ]);
        }

        // delete all student by this promo-code related
        $this->db->delete('promocode_student', ['promocode_id' => $id]);

        if ($is_special && $studentIds){
            // insert selected courses
            foreach ($studentIds as $studentId){
                $this->db->insert('promocode_student', [
                    'promocode_id'  => $id,
                    'student_id'    => $studentId,
                ]);
            }
        }

        $this->Promocodes_model->update($id, [
            'amount'        => $this->input->post('amount',TRUE),
            'code'          => $this->input->post('code',TRUE),
            'discount_type' => $this->input->post('discount_type',TRUE),
            'end_date'      => $this->input->post('end_date',TRUE),
            'start_date'    => $this->input->post('start_date',TRUE),
            'status'        => $this->input->post('status',TRUE),
            'uses_limit'    => $this->input->post('uses_limit',TRUE),
            'use_multiple'  => $this->input->post('use_multiple',TRUE) ? 'yes' : 'no',
            'is_special'    => $is_special ? 1 : 0,
            'promoter_name' => $this->input->post('promoter_name',TRUE),
            'updated_on'    => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('message', '<p class="ajax_success"> Promocodes Updated Successlly </p>');
        redirect(site_url( Backend_URL. 'promocodes/update/'. $id ));
    }

    public function delete($id){
        $row = $this->Promocodes_model->get_by_id($id);
        if ($row) {
            $data = array(
		'amount'    => $row->amount,
		'code'      => $row->code,
		'course_id' => $row->course_id,
		'created_on'=> $row->created_on,
		'discount_type' => $row->discount_type,
		'end_date'      => $row->end_date,
		'id'            => $row->id,
		'start_date'    => $row->start_date,
		'status'        => $row->status,
		'updated_on'    => $row->updated_on,
		'user_id'       => $row->user_id,
		'uses_limit'    => $row->uses_limit,
	    );
            $this->viewAdminContent('promocodes/promocodes/delete', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Promocodes Not Found</p>');
            redirect(site_url( Backend_URL. 'promocodes'));
        }
    }

    public function delete_action($id){
        $row = $this->Promocodes_model->get_by_id($id);

        if ($row) {
            $this->Promocodes_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Promocodes Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'promocodes'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Promocodes Not Found</p>');
            redirect(site_url( Backend_URL. 'promocodes'));
        }
    }

    public function _menu(){
        // return add_main_menu('Promocodes', 'promocodes', 'promocodes', 'fa-hand-o-right');
        return buildMenuForMoudle([
            'module'    => 'Promocodes',
            'icon'      => 'fa-hand-o-right',
            'href'      => 'promocodes',                    
            'children'  => [
                [
                    'title' => 'All Promocodes',
                    'icon'  => 'fa fa-bars',
                    'href'  => 'promocodes'
                ],[
                    'title' => ' |_ Add New',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'promocodes/create'
                ]
            ]        
        ]);
    }

    public function _rules(){
        $this->form_validation->set_rules('code', 'code', 'trim|required');
        $this->form_validation->set_rules('discount_type', 'discount type', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        $this->form_validation->set_rules('end_date', 'end date', 'trim|required');
        $this->form_validation->set_rules('start_date', 'start date', 'trim|required');
        $this->form_validation->set_rules('uses_limit', 'uses limit', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}