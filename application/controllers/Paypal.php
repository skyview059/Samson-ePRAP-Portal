<?php defined('BASEPATH') or exit('No direct script access allowed');

class Paypal extends MX_Controller
{
    // every thing coming form Frontend Controller

    public function __construct()
    {
        parent::__construct();
    }

    public function ipn()
    {
        $post = json_encode($this->input->post());

        $log_path    = APPPATH . '/logs/paypal.txt';
        $payment_log = date('Y-m-d H:i:s A') . ' | ' . $post . "\r\n";
        file_put_contents($log_path, $payment_log, FILE_APPEND);

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return true;
        }

        $data = array(
            'status'          => 'Paid',
            'gateway'         => 'PayPal',
            'gateway_respond' => $post
        );

        $payment_id = (int)$this->input->post('custom');

        $this->db->where('id', $payment_id);
        $this->db->update('course_payments', $data);

        $this->db->where('course_payment_id', $payment_id);
        $this->db->update('course_booked', ['status' => 'Confirmed']);

        $course_booked = $this->db->get_where('course_booked', ['id' => $payment_id])->row();
        if ($course_booked->type == 'course') {
            $this->bookingConfirmationMail($payment_id);
        } else {
            $this->practiceBookingConfirmationMail($payment_id);
        }
    }

    public function practiceBookingConfirmationMail($payment_id)
    {
        $PaymentInfo = $this->getPayment($payment_id);
        $option      = [
            'id'        => $PaymentInfo->student_id,
            'send_to'   => $PaymentInfo->email,
            'send_bcc'  => getSettingItem('IncomingEmail'),
            'template'  => 'onPracticeBookingNotify2Student',
            'variables' => [
                '%invoice%' => $this->invoicePractice($payment_id, $PaymentInfo)
            ]
        ];
        Modules::run('mail/send_mail', $option);
    }

    public function bookingConfirmationMail($payment_id)
    {
        $PaymentInfo = $this->getPayment($payment_id);
        $option      = [
            'id'        => $PaymentInfo->student_id,
            'send_to'   => $PaymentInfo->email,
            'send_bcc'  => getSettingItem('IncomingEmail'),
            'template'  => 'onCourseBookingNotify2Student',
            'variables' => [
                '%invoice%' => $this->invoice($payment_id, $PaymentInfo)
            ]
        ];
        Modules::run('mail/send_mail', $option);
    }

    private function getPayment($id)
    {
        $this->db->select('cp.*, s.id as student_id, CONCAT(s.fname, " ", s.mname, " ", s.lname) as full_name, s.email');
        $this->db->from('course_payments as cp');
        $this->db->join('students as s', 's.id=cp.student_id', 'left');
        $this->db->where('cp.id', $id);
        return $this->db->get()->row();
    }

    private function invoicePractice($id, $payment)
    {
        $this->db->select('cb.course_payment_id, cb.course_id as practice_id, cb.course_payment_id,');
        $this->db->select("c.name, cp.total_pay as amount");
        $this->db->from('course_booked as cb');
        $this->db->join('exams as e', 'e.id = cb.course_id', 'LEFT');
        $this->db->join('course_payments as cp', 'cp.id = cb.course_payment_id', 'LEFT');
        $this->db->where('cb.course_payment_id', $id);
        $courses = $this->db->get()->result();

        $data['payment'] = $payment;
        $data['courses'] = $courses;

        return $this->load->view('frontend/booking/invoice-email-practice', $data, true);
    }

    private function invoice($id, $payment)
    {
        $this->db->select('cb.course_payment_id, cb.course_id, cb.course_payment_id,');
        $this->db->select("c.name as course, c.price, c.duration, c.booking_limit, cc.name as category");
        $this->db->select('(DATE_FORMAT(cd.start_date, "%d/%m/%Y %h:%i %p")) as start_date');
        $this->db->from('course_booked as cb');
        $this->db->join('course_dates as cd', 'cd.id=cb.course_date_id', 'LEFT');
        $this->db->join('courses as c', 'c.id=cb.course_id', 'LEFT');
        $this->db->join('course_categories as cc', 'cc.id=c.category_id', 'LEFT');
        $this->db->where('cb.course_payment_id', $id);
        $courses = $this->db->get()->result();

        $data['payment'] = $payment;
        $data['courses'] = $courses;

        return $this->load->view('frontend/booking/invoice-email', $data, true);
    }

}