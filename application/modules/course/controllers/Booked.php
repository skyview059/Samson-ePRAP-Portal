<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 22 Apr 2021 @01:40 pm
 */

class Booked extends Admin_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Booked_model');
        $this->load->helper('course');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $course_date_id = (int)$this->input->get('course_date_id');
        $category_id    = intval($this->input->get('category_id'));
        $course_id      = intval($this->input->get('course_id'));
        $status         = ($this->input->get('status')) ?? 'Confirmed';
        $start          = intval($this->input->get('start'));

        $config['base_url']  = build_pagination_url(Backend_URL . 'course/booked', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'course/booked', 'start');

        $config['per_page']          = 50;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Booked_model->total_rows($category_id, $course_id, $status, $course_date_id);
        $bookeds                     = $this->Booked_model->get_limit_data($config['per_page'], $start, $category_id, $course_id, $status, $course_date_id);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'bookeds'        => $bookeds,
            'pagination'     => $this->pagination->create_links(),
            'total_rows'     => $config['total_rows'],
            'start'          => $start,
            'category_id'    => $category_id,
            'course_id'      => $course_id,
            'course_date_id' => $course_date_id,
            'status'         => $status,
        );

        $this->viewAdminContent('course/booked/index', $data);
    }

    public function practice()
    {
        $practice_id = intval($this->input->get('practice_id'));
        $status      = ($this->input->get('status')) ?? 'Confirmed';
        $gateway     = $this->input->get('gateway');

        $start = intval($this->input->get('start'));

        $config['base_url']  = build_pagination_url(Backend_URL . 'course/booked/practice', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'course/booked/practice', 'start');

        $config['per_page']          = 50;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Booked_model->total_rows_practice($practice_id, $status, $gateway);
        $bookeds                     = $this->Booked_model->get_limit_data_practice($config['per_page'], $start, $practice_id, $status, $gateway);

//        echo $this->db->last_query();

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'bookeds'     => $bookeds,
            'pagination'  => $this->pagination->create_links(),
            'total_rows'  => $config['total_rows'],
            'start'       => $start,
            'practice_id' => $practice_id,
            'status'      => $status,
            'gateway'     => $gateway
        );

        $this->viewAdminContent('course/booked/index_practice', $data);
    }

    public function create()
    {
//        Modules::run('paypal/bookingConfirmationMail', 7 );
//        exit;
        $view_data = [
            'student_id' => 0
        ];
        $this->viewAdminContent('course/booked/create', $view_data);
    }

    public function related_data($student_id)
    {

        ajaxAuthorized();
        $payment_id = 0;

        $this->db->select('cb.course_id');
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT');
        $this->db->where('cb.course_payment_id', $payment_id);
        $this->db->where('cp.student_id', $student_id);
        $this->db->where_in('cp.status', ['Paid', 'Due']);

        $this->db->group_by('cb.course_id');
        $pCourses = $this->db->get_compiled_select();


        $this->db->select('c.id,c.name as course,c.price,c.booking_limit');
        $this->db->select('c.category_id as index, cc.name as category, p_courses.course_id as p_course_id');
        $this->db->from('courses as c');
        $this->db->join('course_categories as cc', 'c.category_id=cc.id', 'LEFT');
        $this->db->join("($pCourses) as p_courses", 'p_courses.course_id=c.id', 'LEFT');
        $this->db->order_by('cc.id', 'ASC');
        $this->db->order_by('c.id', 'ASC');
        $courses = $this->db->get()->result();

        $data = [];
        foreach ($courses as $c) {
            $data[$c->index]['category']  = $c->category;
            $data[$c->index]['courses'][] = [
                'id'    => $c->id,
                'name'  => $c->course,
                'price' => $c->price,
                'limit' => $c->booking_limit,
                'dates' => $this->getDates($c->id, $c->booking_limit, $payment_id, $student_id),
            ];
        }
        $view_data = [
            'course_plans' => $data,
        ];

        $html = $this->load->view('course/booked/course_related', $view_data, true);
//        $html = $this->viewAdminContent('course/booked/course_related', $view_data, true );
        echo ajaxRespond('OK', $html);

    }

    private function getDates($course_id, $limit, $payment_id = 0, $student_id = 0)
    {

        $sql = "SELECT COUNT(*) FROM `course_booked` WHERE `course_date_id` = `course_dates`.`id`";

        $isAlreadyBooked = "SELECT course_date_id, COUNT(cb.id) as qty, p.status FROM `course_booked` as cb 
            LEFT JOIN `course_payments` as p on cb.course_payment_id=p.id
            WHERE `p`.`student_id` = {$student_id} and `p`.`status` = 'Paid' GROUP BY `course_date_id`";

        if ($payment_id) {
            $isInCart = "SELECT course_date_id as inCart FROM `course_booked` as cb 
                LEFT JOIN `course_payments` as p on cb.course_payment_id=p.id
                WHERE p.student_id = {$student_id} and p.id = {$payment_id} and p.status = 'Due' GROUP BY course_date_id";
        }

        $this->db->select('id, DATE_FORMAT(`start_date`, "%d/%m/%Y %h:%i %p") AS s_date, start_date');
        $this->db->select("({$limit} - ({$sql})) as AvailableSeat");
        $this->db->select('IFNULL(isBooked.qty, 0) AS qty, isBooked.status');
        $this->db->join("({$isAlreadyBooked}) as isBooked", 'course_dates.id=isBooked.course_date_id', 'LEFT');
        if ($payment_id) {
            $this->db->select('isInCart.inCart');
            $this->db->join("({$isInCart}) as isInCart", 'course_dates.id=isInCart.inCart', 'LEFT');
        }
        $this->db->where('course_id', $course_id);
        $this->db->where('start_date >=', date('Y-m-d 00:00:00'));
//        $this->db->where('start_date >=', 'now()', FALSE);
        $this->db->order_by('start_date');
        $dates = $this->db->get('course_dates')->result();
        return json_encode($dates);
    }

    public function create_action()
    {
        ajaxAuthorized();
        $error    = true; //inisial PHP error set true
        $postData = $this->input->post();

        if (empty($postData['id'])) {
            echo ajaxRespond("FAIL", "At least a course must be selected!");
            exit;
        }
        $totalPay   = 0;
        $totalItems = 0;
        foreach ($postData['id'] as $courseId => $courseDateID) {
            $this->db->select('id, price, name');
            $this->db->where('id', $courseId);
            $couseInfo = $this->db->get('courses')->row();
            $totalPay  += $couseInfo->price;
            ++$totalItems;
        }

        /*=====  trans_start  ======*/
        $this->db->trans_begin();

        $invoice_id    = time();
        $coursePayment = array(
            'student_id'     => $postData['student_id'],
            'purchased_at'   => date("Y-m-d H:i:s"),
            'total_items'    => $totalItems,
            'total_pay'      => $totalPay,
            'admin_comments' => $postData['admin_comments'],
            'invoice_id'     => $invoice_id,
            'status'         => $postData['payment_status']
        );
        $this->db->insert('course_payments', $coursePayment);
        $coursePaymentId = $this->db->insert_id();
        if (!$coursePaymentId) {
            $error = false;//Set PHP error flag false
        }

        $courseBookedData = [];
        $i                = 0;
        foreach ($postData['id'] as $courseId => $courseDateId) {
            $courseBookedData[$i]['course_payment_id'] = $coursePaymentId;
            $courseBookedData[$i]['course_id']         = $courseId;
            $courseBookedData[$i]['course_date_id']    = $courseDateId;
            $courseBookedData[$i]['status']            = 'Pending';
            $i++;
        }

        if (!$this->db->insert_batch('course_booked', $courseBookedData)) {
            $error = false;//Set PHP error flag false
        }

        if (($this->db->trans_status() === FALSE) || ($error === false)) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('msge', "Couldn't booking new course(s)");
            echo ajaxRespond("FAIL", "Couldn't booking new course(s)");
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('msgs', 'New Course(s) has been booked.');
            echo ajaxRespond('OK', $coursePaymentId);
        }
    }

    public function create_practice()
    {
        $practice_id = intval($this->input->get('practice_id'));

        $practices = $this->db->where('status', 'Active')->select('id, name')->get('exams')->result();
        $packages  = $this->db->where('status', 'Active')->where('exam_id', $practice_id)->get('practice_packages')->result();
        $data      = [
            'practices'   => $practices,
            'practice_id' => $practice_id,
            'packages'    => $packages
        ];
        $this->viewAdminContent('course/booked/create_practice', $data);
    }

    public function create_practice_action()
    {
        $practice_id   = intval($this->input->post('practice_id'));
        $student_id    = intval($this->input->post('student_id'));
        $package_id    = intval($this->input->post('package_id'));
        $package_price = floatval($this->input->post('package_price'));
        $expiry_date   = $this->input->post('expiry_date');

        $packageData = $this->db->select('price, duration')->where('id', $package_id)->get('practice_packages')->row();

        $cart = [
            'student_id'     => $student_id,
            'purchased_at'   => date('Y-m-d H:i:s'),
            'total_items'    => 1,
            'total_pay'      => $package_price,
            'invoice_id'     => time(),
            'admin_comments' => $this->input->post('admin_comments'),
            'gateway'        => $this->input->post('payment_gateway')
        ];
        $this->db->insert('course_payments', $cart);
        $course_payment_id = $this->db->insert_id();


        if(empty($expiry_date)){
            $expiry_date = date('Y-m-d', strtotime('+' . $packageData->duration));
        }

        $courseBookedData = [
            'course_payment_id'   => $course_payment_id,
            'course_id'           => $practice_id, // practice id is same as course id
            'practice_package_id' => $package_id,
            'booked_price'        => $packageData->price,
            'status'              => $this->input->post('payment_status'),
            'type'                => 'practice',
            'expiry_date'         => $expiry_date
        ];
        $this->db->insert('course_booked', $courseBookedData);

        $this->session->set_flashdata('msgs', 'Practice has been booked.');
        redirect(site_url('admin/course/booked/practice'));
    }

    public function cancel()
    {
        ajaxAuthorized();

        $postData = $this->input->post();
        if (empty($postData['course_booked_id'])) {
            echo ajaxRespond("FAIL", "Course booked ID not found!");
            exit;
        }

        $refund_amount = ($postData['is_payment_returned'] == 'Yes') ? $postData['refund_amount'] : 0;
        $data          = array(
            'cancelled_at'        => date("Y-m-d"),
            'status'              => 'Cancelled',
            'admin_remark'        => $postData['admin_remark'],
            'is_payment_returned' => $postData['is_payment_returned'],
            'refund_amount'       => $refund_amount
        );
        $this->db->where('id', $postData['course_booked_id']);
        if ($this->db->update('course_booked', $data)) {
            $this->session->set_flashdata('msgs', 'Booked has been Cancelled.');
            echo ajaxRespond('OK', $postData['course_booked_id']);
        } else {
            $this->session->set_flashdata('msge', "Couldn't booked cancel.");
            echo ajaxRespond("FAIL", "Couldn't booked cancel.");
        }
    }

    public function save_manual_payment()
    {
        ajaxAuthorized();

        $postData = $this->input->post();

        if (empty($postData['course_booked_id'])) {
            echo ajaxRespond("FAIL", "Course booked ID not found!");
            exit;
        }

        $payment = [
            'status'         => $postData['payment_status'],
            'total_pay'      => $postData['payment_amount'],
            'gateway'        => $postData['gateway'],
            'admin_comments' => $postData['admin_remark']
        ];
        $this->db->where('id', $postData['payment_id']);
        $this->db->update('course_payments', $payment);

        $booking = array(
            'status'       => $postData['booking_status'],
            'admin_remark' => $postData['admin_remark'],
        );
        $this->db->where('id', $postData['course_booked_id']);
        if ($this->db->update('course_booked', $booking)) {
            $this->session->set_flashdata('msgs', 'Booked has been Cancelled.');
            echo ajaxRespond('OK', $postData['course_booked_id']);
        } else {
            $this->session->set_flashdata('msge', "Couldn't booked cancel.");
            echo ajaxRespond("FAIL", "Couldn't booked cancel.");
        }
    }

    public function admin_note()
    {
        ajaxAuthorized();

        $post           = $this->input->post();
        $course_book_id = $post['course_booked_id'];

        if (empty($course_book_id)) {
            echo ajaxRespond("FAIL", "Course booked ID not found!");
            exit;
        }

        $data = array(
            'admin_remark' => $post['admin_remark'],
        );

        $this->db->where('id', $course_book_id);
        $update = $this->db->update('course_booked', $data);

        if ($update) {
            $this->session->set_flashdata('msgs', 'Booked has been Cancelled.');
            echo ajaxRespond('OK', $course_book_id);
        } else {
            $this->session->set_flashdata('msge', "Couldn't booked cancel.");
            echo ajaxRespond("FAIL", "Couldn't booked cancel.");
        }
    }

    public function reschedule()
    {
        $booking_id = (int)$this->input->post('id');

        $b = $this->Booked_model->getReschedule($booking_id);

        $data = [
            'id'             => $b->id,
            'course_id'      => $b->course_id,
            'seat_limit'     => $b->booking_limit,
            'remark'         => $b->admin_remark,
            'total_pay'      => $b->total_pay,
            'course_date_id' => $b->course_date_id
        ];
        echo $this->load->view('booked/reschedule', $data, true);
    }

    public function reschedule_save()
    {
        ajaxAuthorized();
        $booked_id      = (int)$this->input->post('booked_id');
        $course_date_id = (int)$this->input->post('course_date_id');

        $this->db->set('course_date_id', $course_date_id);
        $this->db->set('admin_remark', $this->input->post('admin_remark'));
        $this->db->where('id', $booked_id);
        $this->db->update('course_booked');

        echo ajaxRespond('OK', 'Course Rescheduled Successfully');


        $send_mail = $this->input->post('send_mail');;
        if ($send_mail == 'Yes') {
            $student            = $this->Booked_model->getStudentEmail($booked_id);
            $old_course_date_id = (int)$this->input->post('old_course_date_id');
            $option             = [
                'id'        => $student->id,
                'send_to'   => $student->email,
                'template'  => 'onCourseDateRescheduleNotify2Student',
                'variables' => [
                    '%last_name%' => $student->lname,
                    '%date_from%' => $this->Booked_model->getCourseDate($old_course_date_id),
                    '%date_to%'   => $this->Booked_model->getCourseDate($course_date_id),
                ]
            ];
            Modules::run('mail/send_mail', $option);
        }
    }

    public function attendance()
    {
        ajaxAuthorized();
        $id         = $this->input->post('id');
        $attendance = $this->input->post('attendance');

        $this->db->where('id', $id);
        $result = $this->db->update('course_booked', array('attendance' => $attendance));
        if ($result) {
            $this->session->set_flashdata('msgs', 'Attendance has been updated');
            echo ajaxRespond("OK", "Attendance has been updated");
        } else {
            echo ajaxRespond("FAIL", "Attendance Check Fail. Please try again..");
        }
    }
}
