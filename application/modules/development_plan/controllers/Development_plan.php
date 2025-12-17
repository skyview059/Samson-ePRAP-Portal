<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-30
 */

class Development_plan extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Development_plan_model');
        $this->load->helper('development_plan');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = build_pagination_url(Backend_URL . 'development_plan/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'development_plan/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Development_plan_model->total_rows($q);
        $development_plans = $this->Development_plan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'dev_plans' => $development_plans,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
//        dd( $data );
        
        $this->viewAdminContent('development_plan/development_plan/index', $data);
    }

    public function details($id)
    {
        $plans = $this->Development_plan_model->get_by_student_id($id);
        if ($plans) {            
            $data = array(
                'id' => $id,
                'student_name' => $this->Development_plan_model->getStudentName($id),
                'plans' => $plans,
            );
            
//            dd( $data );
            $this->viewAdminContent('development_plan/development_plan/details', $data);
        } else {
            $this->session->set_flashdata('msge', 'Development Plan Not Found');
            redirect(site_url(Backend_URL . 'development_plan'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'development_plan/create_action'),
            'id' => set_value('id'),
            'student_id' => set_value('student_id'),
            'aims' => set_value('aims'),
            'goals' => set_value('goals'),
            'date_of_achievement' => set_value('date_of_achievement'),
            'achievement' => set_value('achievement'),
            'review_date' => set_value('review_date'),
            'future_plan' => set_value('future_plan'),
            'review' => set_value('review'),
            'note' => set_value('note')
        );
        $this->viewAdminContent('development_plan/development_plan/create', $data);
    }

    public function create_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'student_id' => $this->input->post('student_id', TRUE),
                'aims' => $this->input->post('aims', TRUE),
                'goals' => $this->input->post('goals', TRUE),
                'date_of_achievement' => $this->input->post('date_of_achievement', TRUE),
                'achievement' => $this->input->post('achievement', TRUE),
                'review_date' => $this->input->post('review_date', TRUE),
                'future_plan' => $this->input->post('future_plan', TRUE),
                'review' => $this->input->post('review', TRUE),
                'note' => $this->input->post('note', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            );

            $this->Development_plan_model->insert($data);
            $this->session->set_flashdata('msgs', 'Development Plan Added Successfully');
            redirect(site_url(Backend_URL . 'development_plan'));
        }
    }

    public function update($id)
    {
        $row = $this->Development_plan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'development_plan/update_action'),
                'id' => set_value('id', $row->id),
                'student_id' => set_value('student_id', $row->student_id),
                'aims' => set_value('aims', $row->aims),
                'goals' => set_value('goals', $row->goals),
                'date_of_achievement' => set_value('date_of_achievement', $row->date_of_achievement),
                'achievement' => set_value('achievement', $row->achievement),
                'review_date' => set_value('review_date', $row->review_date),
                'future_plan' => set_value('future_plan', $row->future_plan),
                'review' => set_value('review', $row->review),
                'note' => set_value('review', $row->note)
            );
            $this->viewAdminContent('development_plan/development_plan/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Development Plan Not Found');
            redirect(site_url(Backend_URL . 'development_plan'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'student_id' => $this->input->post('student_id', TRUE),
                'aims' => $this->input->post('aims', TRUE),
                'goals' => $this->input->post('goals', TRUE),
                'date_of_achievement' => $this->input->post('date_of_achievement', TRUE),
                'achievement' => $this->input->post('achievement', TRUE),
                'review_date' => $this->input->post('review_date', TRUE),
                'future_plan' => $this->input->post('future_plan', TRUE),
                'review' => $this->input->post('review', TRUE),
                'note' => $this->input->post('note', TRUE),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->Development_plan_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Development Plan Updated Successlly');
            redirect(site_url(Backend_URL . 'development_plan/update/' . $id));
        }
    }

    public function delete_action($id)
    {
        $row = $this->Development_plan_model->get_by_id($id);

        if ($row) {
            $this->Development_plan_model->delete($id);
            $this->session->set_flashdata('msgs', 'Development Plan Deleted Successfully');
            redirect(site_url(Backend_URL . 'development_plan'));
        } else {
            $this->session->set_flashdata('msge', 'Development Plan Not Found');
            redirect(site_url(Backend_URL . 'development_plan'));
        }
    }


    public function _menu()
    {
        // return add_main_menu('Development_plan', 'development_plan', 'development_plan', 'fa-hand-o-right');
        return buildMenuForMoudle([
            'module' => 'Individual Learning Plan',
            'icon' => 'fa-thumbs-o-up',
            'href' => 'development_plan',
            'children' => [
                [
                    'title' => 'All Plan',
                    'icon' => 'fa fa-bars',
                    'href' => 'development_plan'
                ], [
                    'title' => ' |_ Setup New Plan',
                    'icon' => 'fa fa-plus',
                    'href' => 'development_plan/create'
                ]
            ]
        ]);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('student_id', 'student id', 'trim|required|numeric');
        $this->form_validation->set_rules('aims', 'aims', 'trim');
        $this->form_validation->set_rules('goals', 'goals', 'trim');
        $this->form_validation->set_rules('date_of_achievement', 'date of achievement', 'trim');
        $this->form_validation->set_rules('achievement', 'achievement', 'trim');
        $this->form_validation->set_rules('review_date', 'review date', 'trim');
        $this->form_validation->set_rules('future_plan', 'future plan', 'trim');
        $this->form_validation->set_rules('review', 'review', 'trim');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }


}