<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2017-08-01
 */

class Login_history extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Login_history_model');
        $this->load->helper('login_history');
        $this->load->library('form_validation');
    }

    public function index() {
        $most_login = urldecode_fk($this->input->get('most_login', TRUE));
        $device = urldecode_fk($this->input->get('device', TRUE));
        $browser = urldecode_fk($this->input->get('browser', TRUE));

        $range = urldecode_fk($this->input->get('range', TRUE));
        $fd = ($this->input->get('fd', TRUE));
        $td = ($this->input->get('td', TRUE));

        $start = intval($this->input->get('start'));
        $most = ($this->input->get('most_login')) ? 'yes' : 'no';

        $config['base_url'] = build_pagination_url(Backend_URL . 'student/login_history/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'student/login_history/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Login_history_model->total_rows($most_login, $browser, $device, $range, $fd, $td);
        $logins = $this->Login_history_model->get_limit_data($config['per_page'], $start, $most_login, $browser, $device, $range, $fd, $td);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'logins' => $logins,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'most' => $most,
        );
        $this->viewAdminContent('login_history/index', $data);
    }

    public function graph() {

        $browsers = $this->db->select('browser, COUNT(id) as count')
            ->from('student_logs')
            ->group_by('browser')
            ->get()->result();

        $devices = $this->db->select('device, COUNT(id) as devices')
            ->from('student_logs')
            ->group_by('device')
            ->get()->result();

        $data['browsers'] = $browsers;
        $data['devices'] = $devices;


        $filter = $this->input->get('filter') ?: 'today';

        $combined_statistics = $this->db->select("
            COALESCE(countries.name, 'Unknown') AS country_name,
            COUNT(*) AS total_student,
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS today,
            SUM(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 ELSE 0 END) AS yesterday,
            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS last_week,
            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 1 MONTH THEN 1 ELSE 0 END) AS last_month,
            SUM(CASE WHEN YEAR(created_at) = YEAR(NOW()) - 1 THEN 1 ELSE 0 END) AS last_year,
            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 6 MONTH THEN 1 ELSE 0 END) AS last_6_months,
            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 12 MONTH THEN 1 ELSE 0 END) AS last_12_months,
            SUM(CASE WHEN YEAR(created_at) = YEAR(NOW()) THEN 1 ELSE 0 END) AS this_year
        ")
            ->from('students')
            ->having($filter . '>', 0)
            ->group_by('country_name')
            ->order_by($filter, 'DESC')
            ->join('countries', 'students.country_id = countries.id', 'LEFT')
            ->get()
            ->result();

        $total_student = 0;

        foreach ($combined_statistics as $statistics) {
            $total_student += $statistics->$filter;
        }

        if ($total_student > 0){
            foreach ($combined_statistics as $statistics) {
                $parcentage = round($statistics->$filter / $total_student * 100, 2);
                $statistics->country_name = $statistics->country_name . ' : ' . $parcentage . '%';
            }
        }

        $data['statistics'] = $combined_statistics;
        $this->viewAdminContent('login_history/graph_view', $data);
    }

    public function getChart() {
        $day = [];
        for ($i = -15; $i <= 0; $i++) {
            $day[] = date('d M y', strtotime("+$i days "));
        }
        return json_encode($day);
    }

    public function getChartVendor() {
        $data = [];
        for ($i = -15; $i <= 0; $i++) {

            $date = date('Y-m-d', strtotime("+$i day "));
            $data[] = $this->countLoginVendor(3, $date);
        }
        return json_encode($data);
    }

    private function countLoginVendor($date = '0000-00-00') {
        $this->db->where('login_time <=', $date . ' 23:59:59');
        $this->db->where('login_time <=', $date . ' 23:59:59');
        return $this->db->get('student_logs')->num_rows();
    }

    public function getChartCustomer() {
        $data = [];
        for ($i = -15; $i <= 0; $i++) {

            $date = date('Y-m-d', strtotime("+$i days "));
            $data[] = $this->countLoginVendor(4, $date);
        }
        return json_encode($data);
    }

    public function bulk_action() {

        ajaxAuthorized();

        $log_ids = $this->input->post('log_id', TRUE);
        $action = $this->input->post('action', TRUE);

        if (count($log_ids) == 0 or empty($action)) {
            $message = '<p class="ajax_error">Please select at least one item and action.</p>';
            echo ajaxRespond('Fail', $message);
            exit;
        }

        switch ($action) {
            case 'Delete':
                $this->deleteLogs($log_ids);
                $message = '<p class="ajax_success">Marked Log Deleted Successfully</p>';
                break;
        }
        echo ajaxRespond('OK', $message);
    }

    private function deleteLogs($log_ids = []) {
        foreach ($log_ids as $log_id) {
            $this->db->where('id', $log_id);
            $this->db->delete('student_logs');
        }
    }

    public function delete(){
        ajaxAuthorized();
        $id = (int) $this->input->post('id');
        $student_id = (int) $this->input->post('student');
        $most = $this->input->post('most');
        
        if($most == 'yes'){
            $this->db->where('student_id', $student_id );
        } else {
            $this->db->where('id', $id );
        }
        $this->db->delete('student_logs');

        echo ajaxRespond('OK', '<p class="ajax_success">Delete Record Success</p>');        
    }

}
