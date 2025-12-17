<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-02-10
 */

class Centre extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Centre_model');
        $this->load->helper('centre');
        $this->load->library('form_validation');
    }

    public function index(){
        $start      = intval($this->input->get('start'));
        $exam_id    = intval($this->input->get('id'));
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'centre', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'centre', 'start');

        $config['per_page'] = 50;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Centre_model->total_rows($exam_id);
        $centres = $this->Centre_model->get_limit_data($config['per_page'], $start, $exam_id);
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'centres' => $centres,            
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'id' => $exam_id,
        );

        $this->viewAdminContent('centre/centre/index', $data);
    }

    
    public function preview($id)
    {
       $data['exams'] = $this->Centre_model->get_exam_schedule( $id );
       $data['start'] = 0;
       $data['id'] = $id;
//       dd( $data );
       $this->viewAdminContent('centre/centre/preview', $data);
         
    }
    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'centre/create_action'),
            'id' => set_value('id'),
            'exam_id' => $this->input->get('id'),
            'name' => set_value('name'),
            'address' => set_value('address'),
            'country_id' => set_value('country_id'),
        );
        $this->viewAdminContent('centre/centre/create', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $exam_id = $this->input->post('exam_id', TRUE);
            $data = array(
                'exam_id'   => $exam_id,
                'name'      => $this->input->post('name', TRUE),
                'address'   => $this->input->post('address', TRUE),
                'country_id' => $this->input->post('country_id', TRUE),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            
            $this->Centre_model->insert($data);
            $this->session->set_flashdata('msgs', 'Centre Added Successfully');
            redirect(site_url(Backend_URL . "centre?id={$exam_id}"));
        }
    }

    public function update($id)
    {
        $row = $this->Centre_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'centre/update_action'),
                'id' => set_value('id', $row->id),
                'exam_id' => set_value('exam_id', $row->exam_id),
                'name' => set_value('name', $row->name),
                'address' => set_value('address', $row->address),
                'country_id' => set_value('country_id', $row->country_id),
            );
            $this->viewAdminContent('centre/centre/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Centre Not Found');
            redirect(site_url(Backend_URL . 'centre'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $exam_id = $this->input->post('exam_id', TRUE);
            $data = array(
                'exam_id' => $exam_id,
                'name' => $this->input->post('name', TRUE),
                'address' => $this->input->post('address', TRUE),
                'country_id' => (int) $this->input->post('country_id', TRUE),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->Centre_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Centre Updated Successlly');
            redirect(site_url(Backend_URL . "centre?id={$exam_id}"));
        }
    }
    
    public function marge($id)
    {
        $row = $this->Centre_model->get_by_id($id);

        if ($row) {
            $data = array(
                'action' => site_url(Backend_URL . 'centre/marge_action'),
                'old_id' => set_value('id', $row->id),
                'mock_id' => $this->input->get('id'),
                'id' => $this->input->get('id'),
            );
            $this->viewAdminContent('centre/centre/marge', $data);
        } else {
            $this->session->set_flashdata('msge', 'Centre Not Found');
            redirect(site_url(Backend_URL . 'centre'));
        }
    }

    public function marge_action()
    {
        
        $old_id = (int) $this->input->post('old_centre_id', TRUE);
        $new_id = (int) $this->input->post('new_centre_id', TRUE);       
        $mock_id = (int) $this->input->post('mock_id', TRUE);       
                
        $this->db->trans_start();
        
            $this->db->set('exam_centre_id', $new_id );
            $this->db->where('exam_centre_id', $old_id );
            $this->db->update('students');
            
            $this->db->set('exam_centre_id', $new_id );
            $this->db->where('exam_centre_id', $old_id );
            $this->db->update('exam_schedules');            
            
            $this->db->where('id', $old_id );
            $this->db->delete('exam_centres');
            
        $this->db->trans_complete();
            
        $this->session->set_flashdata('msgs', 'Centre Updated Successlly');
        redirect(site_url(Backend_URL . "centre?id={$mock_id}"));        
    }

    public function delete($id)
    {
        $row = $this->Centre_model->get_by_id($id);

        if ($row) {
            $exam_id = $row->exam_id;
            $this->Centre_model->delete($id);
            $this->session->set_flashdata('msgs', 'Centre Deleted Successfully');
            redirect(site_url(Backend_URL . "centre?id={$exam_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Centre Not Found');
            redirect(site_url(Backend_URL . 'centre'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim');
        $this->form_validation->set_rules('country_id', 'country id', 'trim|required|is_natural_no_zero');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    

    public function _menu(){
        $menus = [
            'module' => 'Centre',
            'icon' => 'fa-edit',
            'href' => 'centre',
            'children' => $this->_get_course_names('centre')
        ];
//        $menus['children'][] = [
//            'title' => ' &nbsp;Add New Centre',
//            'icon' => 'fa fa-plus',
//            'href' => "centre/create"
//        ];
        
        return buildMenuForMoudle($menus);
    }
}