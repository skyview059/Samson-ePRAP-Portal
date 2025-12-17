<?php defined('BASEPATH') or exit('No direct script access allowed');

class Booking extends Frontend_controller
{
    // every thing coming form Frontend Controller
    private $admin = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('course/Course_model', 'Course_model');
        $this->load->helper('course/course');

        $this->admin = (int) getLoginUserData('role_id');

        if (empty($this->student_id) and empty($this->admin) ) {
            redirect(site_url('login'));
        }
    }

    public function index()
    {                
        $page   = ( (int)$this->input->get('p')) ?? 1;
        $targetpath = build_pagination_url('booking', 'p', true );
        $limit  = 100;
        $start  = startPointOfPagination($limit,$page);       
        $total  = $this->Course_model->my_course_total_rows($this->student_id);
        $courses = $this->Course_model->get_my_courses($this->student_id,$limit, $start);
        $practices = $this->Course_model->get_my_practice_courses($this->student_id);

        $data = [
            'courses' => $courses,
            'practices' => $practices,
            'pagination' => getPaginator($total, $page, $targetpath, $limit),
            'start' => 0
        ];
        
        $this->viewMemberContent('booking/my-course', $data);
    }
    
    public function course()
    {   
        $course_payment_id = ( (int)$this->input->get('id')) ?? null;
        $paymentInfo = [];
        if($course_payment_id){
            $this->db->from('course_payments');
            $this->db->where('student_id', $this->student_id);
            $this->db->where('id', $course_payment_id);
            $this->db->where('(purchased_at + INTERVAL 30 MINUTE) >=', date("Y-m-d H:i:s"));
            $paymentInfo = $this->db->get()->row_array();
            if(empty($paymentInfo)){
                $this->session->set_flashdata('msge', "Session Expired!");
                redirect(site_url('booking/course'));
            }
        }
        
        $this->db->select('cb.course_id');
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT'); 
        $this->db->where('cb.course_payment_id', $course_payment_id );
        $this->db->where('cp.student_id', $this->student_id);
        $this->db->where_in('cp.status', ['Paid', 'Due']);
        
        $this->db->group_by('cb.course_id');
        $pCourses = $this->db->get_compiled_select();
        
        
        $this->db->select('c.id,c.name as course,c.price,c.booking_limit');
        $this->db->select('c.category_id as index, cc.name as category, p_courses.course_id as p_course_id');
        $this->db->from('courses as c');
        $this->db->join('course_categories as cc', 'c.category_id=cc.id', 'LEFT');        
        $this->db->join("($pCourses) as p_courses", 'p_courses.course_id=c.id', 'LEFT');  
        $this->db->order_by('cc.id','ASC');  
        $this->db->order_by('c.id','ASC');          
        $courses = $this->db->get()->result();
                
        $data = [];
        foreach($courses as $c ){
            $data[$c->index]['category']    = $c->category;
            $data[$c->index]['courses'][]   = [
                'id'    => $c->id,
                'name'  => $c->course,
                'price' => $c->price,
                'limit' => $c->booking_limit,
                'isSelected' => ($c->p_course_id) ? 1 : 0,
                'dates' => $this->getDates($c->id, $c->booking_limit, $course_payment_id ),
            ];
        }
        
//        dd( $data );
        $view_data = [
            'course_plans' => $data,
            'course_payment_id' => $course_payment_id,
            'payment_info' => $paymentInfo,
        ];        
        $this->viewMemberContent('booking/book-course', $view_data );
    }
    
    private function getDates( $course_id, $limit, $payment_id = 0 ){        
        
        $sql = "SELECT COUNT(*) FROM `course_booked` WHERE `course_date_id` = `course_dates`.`id`";        
        
        $isAlreadyBooked = "SELECT course_date_id, COUNT(cb.id) as qty, p.status FROM `course_booked` as cb 
            LEFT JOIN `course_payments` as p on cb.course_payment_id=p.id
            WHERE p.student_id = {$this->student_id} and (p.status = 'Paid' and (cb.status = 'Pending' or cb.status = 'Confirmed')) GROUP BY course_date_id";
        
        if($payment_id){
            $isInCart = "SELECT course_date_id as inCart FROM `course_booked` as cb 
                LEFT JOIN `course_payments` as p on cb.course_payment_id=p.id
                WHERE p.student_id = {$this->student_id} and p.id = {$payment_id} and p.status = 'Due' GROUP BY course_date_id";            
        }
        
        
        $this->db->select('id, DATE_FORMAT(`start_date`, "%d/%m/%Y %h:%i %p") AS s_date, start_date');
        $this->db->select("({$limit} - ({$sql})) as AvailableSeat");                
        $this->db->select('IFNULL(isBooked.qty, 0) AS qty, isBooked.status');                
        $this->db->join("({$isAlreadyBooked}) as isBooked", 'course_dates.id=isBooked.course_date_id','LEFT');        
        if($payment_id){
            $this->db->select('isInCart.inCart');   
            $this->db->join("({$isInCart}) as isInCart", 'course_dates.id=isInCart.inCart','LEFT');        
        }
        $this->db->where('course_id', $course_id );        
        $this->db->where('start_date >=', 'now()', FALSE );
        $this->db->order_by('start_date');
        $dates = $this->db->get('course_dates')->result();        
        return json_encode($dates);
    }
    
    public function add_to_cart() {
        ajaxAuthorized();
        
        $error    = true;   //inisial PHP error set true
        $postData = $this->input->post();

        if(empty($postData['id'])){
            echo ajaxRespond("FAIL", "At least a course must be selected!");
            exit;
        }
        $totalPay = 0;
        $totalItems = 0;
        foreach ($postData['id'] as $courseId => $course) {
            $this->db->select('id, price, name');        
            $this->db->where('id', $courseId ); 
            $couseInfo = $this->db->get('courses')->row();
            
            if(empty($course)){
                echo ajaxRespond("FAIL", "At least a date must be selected for ".$couseInfo->name);
                exit;
            }
            
            $totalPay += $couseInfo->price;
            $totalItems++;            
        }
      
        /*=====  trans_start  ======*/
        $this->db->trans_begin();
        
        $coursePaymentId = $postData['course_payment_id'];
        if(!empty($coursePaymentId)){            
            $data = array(
                'total_items'       => $totalItems,
                'total_pay'         => $totalPay,
                'admin_comments'    => $postData['admin_comments']
            );
            $this->db->where('id', $coursePaymentId );
            $this->db->update('course_payments', $data );
            
        } else {
            
            $coursePayment = array(
                'student_id' => $this->student_id,
                'purchased_at'  => date("Y-m-d H:i:s"),
                'total_items'  => $totalItems,
                'total_pay'  => $totalPay,
                'admin_comments'  => $postData['admin_comments'],
                'invoice_id'  => time(),
                'status'  => 'Due'
            );
            $this->db->insert('course_payments', $coursePayment);
            $coursePaymentId = $this->db->insert_id();
            if (!$coursePaymentId) {
                $error = false;//Set PHP error flag false
            }
        }
        
              
        $courseBookedData = [];        
        foreach ($postData['id'] as $courseId => $course) {
            $courseBookedData[] = [
                'course_payment_id' => $coursePaymentId,
                'course_id'         => $courseId,
                'course_date_id'    => $postData['slot_id'][$courseId],
                'status'            => 'Pending'                
            ];
        }
        
        $this->db->where('course_payment_id', $coursePaymentId)->delete('course_booked');
        if (!$this->db->insert_batch('course_booked', $courseBookedData)) {
            $error = false;//Set PHP error flag false
        }
        
        if (($this->db->trans_status() === FALSE) || ($error === false)) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('msge', "Couldn't booking new course(s)");
            echo ajaxRespond("FAIL", "Couldn't booking new course(s)");
        } else {
            $this->db->trans_commit();
//            $this->session->set_flashdata('msgs', 'New Course(s) has been booked. You can make the payment.');
            echo ajaxRespond('OK', $coursePaymentId);
        }         
    }
    
    public function checkout($coursePaymentId)
    {
        //$now = date('Y-m-d H:i:s');
        $this->db->select('*');
        //$this->db->select('TIMESTAMPDIFF(SECOND,purchased_at, "'.$now.'" ) as payout_limit');        
        $this->db->from('course_payments');
        $this->db->where('student_id', $this->student_id);
        $this->db->where('id', $coursePaymentId);
        $this->db->where('(purchased_at + INTERVAL 30 MINUTE) >=', date("Y-m-d H:i:s"));
        $paymentInfo = $this->db->get()->row();
                        
        if(empty($paymentInfo)){
            $this->session->set_flashdata('msge', "Session Expired!");
            redirect(site_url('booking'));
        }
        
        $payout_limit = findOutPayoutTime($paymentInfo->purchased_at);
        
//        $IsPaypalLive = getSettingItem("IsPaypalLive");
        $IsPaypalLive = "No";
        $data = [
            'SiteName'          => getSettingItem('SiteTitle'),
            'PhoneNumber'       => getSettingItem('PhoneNumber'),
            'IncomingEmail'     => getSettingItem('IncomingEmail'),
            
            'paypal_email'      => getSettingItem('PaypalEmail'),    
            'paypal_url'        => 'https://www.sandbox.paypal.com/cgi-bin/webscr',    
            
            'worldpay_active'   => getSettingItem('IsWorldpayActive'),            
                                            
            'invoice_id'        => $paymentInfo->invoice_id,
            'item_qty'          => $paymentInfo->total_items,
            'item_price'        => $paymentInfo->total_pay,
            'cart_id'           => $coursePaymentId,            
            'payout_limit'      => $payout_limit,
        ];

        if($IsPaypalLive == 'Yes'){
            $data['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr';
        }
        $this->viewMemberContent('booking/checkout', $data );
    }
    
    public function invoice($id) {
        ajaxAuthorized();
        $this->db->select("cp.*, CONCAT(s.fname, ' ', IF(s.mname IS NULL or s.mname = '', '', CONCAT(s.mname, ' ')), s.lname) as full_name, s.email");
        $this->db->from('course_payments as cp');
        $this->db->join('students as s', 's.id=cp.student_id', 'left');               
        $this->db->where('cp.id', $id);
        $paymentInfo = $this->db->get()->row();
        
        $this->db->select('cb.course_payment_id, cb.course_id, cb.course_payment_id');
        $this->db->select("c.name as course, c.price, c.duration, c.booking_limit, cc.name as category");
        $this->db->select('(DATE_FORMAT(cd.start_date, "%d/%m/%Y %h:%i %p")) as start_date');
        $this->db->from('course_booked as cb');
        $this->db->join('course_dates as cd', 'cd.id=cb.course_date_id', 'LEFT');
        $this->db->join('courses as c', 'c.id=cb.course_id', 'LEFT');
        $this->db->join('course_categories as cc', 'cc.id=c.category_id', 'LEFT');
        $this->db->where('cb.course_payment_id', $id);
        $courses = $this->db->get()->result();

        $data['payment'] = $paymentInfo;
        $data['courses'] = $courses;
        
        $html = $this->load->view('frontend/booking/invoice', $data, true);
        echo ajaxRespond('OK', $html);        
    }
    
    public function payment()
    {
        $now = DATE('Y-m-d H:i:s');
        $this->db->select('id,invoice_id,purchased_at,total_items,total_pay,gateway,status');
        $this->db->select("IF((purchased_at + INTERVAL 30 MINUTE) > '{$now}', 'No', 'Yes') as timeout" );
        $this->db->where('student_id', $this->student_id );
        $this->db->order_by('id', 'DESC' );
        $payments = $this->db->get('course_payments')->result();        
                
        $data = [
            'payments' => $payments,
            'start' => 0,
        ];
        $this->viewMemberContent('booking/payment', $data );
    }
    
     public function cancel() {
        ajaxAuthorized();
        
        $postData = $this->input->post();
        $booked_id = (int) $postData['course_booked_id'];
        
        if(empty($booked_id)){
            echo ajaxRespond("FAIL", "Course booked ID not found!");
            exit;
        }
                
        $data = array(
            'status'            => 'Cancelled',
            'cancelled_at'      => date("Y-m-d"),            
            'student_remark'    => $postData['student_remark'],            
            'refund_amount'     => $postData['refund_amount']
        );

        $this->db->where('id', $booked_id );
        if ($this->db->update('course_booked', $data)) {
            
            $this->cancel_mail( $booked_id );
            
            $this->session->set_flashdata('msgs', 'Booked has been Cancelled.');
            echo ajaxRespond('OK', $booked_id );
        } else {
            $this->session->set_flashdata('msge', "Couldn't booked cancel.");
            echo ajaxRespond("FAIL", "Couldn't booked cancel.");
        }
    }
    
    private function cancel_mail( $id ){
        $this->load->model('course/Booked_model', 'Booked_model');
        $cancel = $this->Booked_model->getBookingCancelationMail($id);
        if(!$cancel){ return false; }        
        
        $SystemInfo = $this->load->view('frontend/booking/cancel-email', $cancel, true); 
                
        $option      = [            
            'id'        => '0',
            'send_to'   => $cancel->email,
            'send_bcc'  => getSettingItem('IncomingEmail'),
            'template'  => 'onCancelBookingNotify2Student',            
            'variables' => [
                '%Name%'   => $cancel->fname,
                '%CompanyName%'   => getSettingItem('SiteTitle'),
                '%SystemInfo%'   => $SystemInfo
            ]
        ];
        Modules::run('mail/send_mail', $option );   
    }

    public function stripe_payment()
    {
        require_once APPPATH.'third_party/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));

        //Get job id
        $coursePaymentId = (int)$this->input->post('cart_id');

        $this->db->select('*');
        $this->db->from('course_payments');
        $this->db->where('student_id', $this->student_id);
        $this->db->where('id', $coursePaymentId);
        $this->db->where('(purchased_at + INTERVAL 30 MINUTE) >=', date("Y-m-d H:i:s"));
        $paymentInfo = $this->db->get()->row();

        if(empty($paymentInfo)){
            echo json_encode(['success' => false, 'message' => 'Session Expired!', 'data' => []]);
            exit;
        }

        //Get booking course
        $this->db->select('c.name as course');
        $this->db->from('course_booked as cb');
        $this->db->join('courses as c', 'c.id=cb.course_id');
        $this->db->where('course_payment_id', $coursePaymentId );
        $courses = $this->db->get()->result();

        $description = '';
        foreach ($courses as $c ){
            $description .= $c->course . ', ';
        }

        $amount = $paymentInfo->total_pay;
        $message = null;
        $success = false;
        $data = [];

        $stripe = new \Stripe\StripeClient([
            'api_key' => $this->config->item('stripe_secret'),
        ]);

        $customer = $this->getOrCreateStripeCustomer($stripe);

        try {
            $paymentIntent = $stripe->paymentIntents->create([
                'customer'    => $customer,
                'payment_method_types' => ['card'],
                'amount'      => $amount * 100,
                'currency'    => $this->config->item('stripe_currency'),
                'description' => $description,
                'metadata'    => [
                    'card_id' => $coursePaymentId,
                ],
            ]);
            echo json_encode([
                'success' => true,
                'payment' => $paymentIntent
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {

            http_response_code(400);
            error_log($e->getError()->message);

            echo json_encode([
                'success' => false,
                'message' => $e->getError()->message,
            ]);
        }
    }

    private function getOrCreateStripeCustomer($stripe)
    {
        // get current id
        $student = $this->db->from('students')
            ->where('email', getLoginStudentData('student_email') )
            ->get()->row();

        // if exist
        if ($student->stripe_cus_id){
            $customer = $stripe->customers->retrieve($student->stripe_cus_id, []);
        }
        else{
            // create stripe customer
            $customer = $stripe->customers->create([
                'name'  => getLoginStudentData('student_name'),
                'email' => getLoginStudentData('student_email'),
            ]);

            // update student
            $this->db->where('email', getLoginStudentData('student_email') )
                ->set('stripe_cus_id', $customer->id)
                ->update('students');
        }
        return $customer->id;
    }


    public function make_payment()
    {
        
        //include Stripe PHP library
        require_once APPPATH.'third_party/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
        
        //Get job id
        $coursePaymentId = (int)$this->input->post('cart_id');
        
        //$now = date('Y-m-d H:i:s');
        $this->db->select('*');
        //$this->db->select('TIMESTAMPDIFF(SECOND,purchased_at, "'.$now.'" ) as payout_limit');        
        $this->db->from('course_payments');
        $this->db->where('student_id', $this->student_id);
        $this->db->where('id', $coursePaymentId);
        $this->db->where('(purchased_at + INTERVAL 30 MINUTE) >=', date("Y-m-d H:i:s"));
        $paymentInfo = $this->db->get()->row();
        
        if(empty($paymentInfo)){
            echo json_encode(['success' => false, 'message' => 'Session Expired!', 'data' => []]);
            exit;
        }
        
        //Get booking course
        $this->db->select('c.name as course');
        $this->db->from('course_booked as cb');
        $this->db->join('courses as c', 'c.id=cb.course_id');
        $this->db->where('course_payment_id', $coursePaymentId );
        $courses = $this->db->get()->result();
        $description = '';
        foreach ($courses as $c ){
            $description .= $c->course . ', ';
        }


        $message = null;
        $success = false;
        $charge = null;
        $chargeJson = null;
        $data = [];
        
        try {
            
            //Creates timestamp that is needed to make up orderid
            //You can use any alphanumeric combination for the orderid. Although each transaction must have a unique orderid.
            $orderid = strftime('Y').'-'.$coursePaymentId;
//            $amount = 1;
            $amount = $paymentInfo->total_pay;
            
            //charge a credit or a debit card
            $charge = \Stripe\Charge::create([
                'amount'      => $amount*100,
                'currency'    => $this->config->item('stripe_currency'),
                'source'      => $this->input->post('stripeToken'),
                'description' => $description,
                'metadata'    => [
                    'card_id' => $orderid,
                ],
            ]);
           
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $body = $e->getJsonBody();
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $body = $e->getJsonBody();
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $body = $e->getJsonBody();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $body = $e->getJsonBody();
        }
        //Get response message
        $message = isset($body['error']['message']) ? $body['error']['message'] : null;
        
        if ($charge) {
            
            //retrieve charge details
            $chargeJson = $charge->jsonSerialize();
            
            $gatewayRespond = json_encode( $chargeJson );
            //START::Log Section////
            $log_path = APPPATH . '/logs/stripe.txt';
            $payment_log = date('Y-m-d H:i:s A') . ' | ' . $gatewayRespond . "\r\n";
            file_put_contents($log_path, $payment_log, FILE_APPEND);
            //END::Log Section////

            //check whether the charge is successful
            if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
                
                $data = array(
                    'status'  => 'Paid',
                    'gateway'  => 'Stripe',
                    'gateway_respond'  => $gatewayRespond
                );

                $this->db->where('id', $coursePaymentId );
                $this->db->update('course_payments', $data);

                $this->db->where('course_payment_id', $coursePaymentId );
                $this->db->update('course_booked', ['status' => 'Confirmed']);

                $this->bookingConfirmationMail( $coursePaymentId  );

                $success = true;
                $message = 'Payment made successfully.';

            } else {

                // insert response into db
                $success = false;
                $message = 'Something went wrong.';
            }
        }

        echo json_encode([
            'success' => $success, 
            'message' => $message, 
            'data' => $data
        ]);
        
    }

    public function confirm_payment()
    {
        $paymentIntent = $this->input->post('payment');
        $coursePaymentId = (int)$this->input->post('cart_id');

        $success = false;
        $message = '';
        $data = [];

        if ($paymentIntent) {
            $gatewayRespond = json_encode( $paymentIntent );

            //START::Log Section////
            $log_path = APPPATH . '/logs/stripe.txt';
            $payment_log = date('Y-m-d H:i:s A') . ' | ' . $gatewayRespond . "\r\n";
            file_put_contents($log_path, $payment_log, FILE_APPEND);
            //END::Log Section////

            //check whether the charge is successful
            if ($paymentIntent['status'] == 'succeeded') {
                $data = array(
                    'status'    => 'Paid',
                    'gateway'   => 'Stripe',
                    'gateway_respond'  => $gatewayRespond
                );

                $this->db->where('id', $coursePaymentId );
                $this->db->update('course_payments', $data);

                $this->db->where('course_payment_id', $coursePaymentId );
                $this->db->update('course_booked', ['status' => 'Confirmed']);

                $this->bookingConfirmationMail( $coursePaymentId  );

                $success = true;
                $message = 'Payment made successfully.';

            } else {
                $success = false;
                $message = 'Something went wrong.';
            }
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
    }

    public function flutterwave_confirm_payment()
    {
        $paymentIntent = $this->input->post('payment');
        $coursePaymentId = (int)$this->input->post('cart_id');

        $success = false;
        $message = '';
        $data = [];

        if ($paymentIntent) {
            $gatewayRespond = json_encode( $paymentIntent );

            //START::Log Section////
            $log_path = APPPATH . '/logs/flutterwave.txt';
            $payment_log = date('Y-m-d H:i:s A') . ' | ' . $gatewayRespond . "\r\n";
            file_put_contents($log_path, $payment_log, FILE_APPEND);
            //END::Log Section////

            //check whether the charge is successful
            if ($paymentIntent['status'] == 'successful') {
                $data = array(
                    'status'    => 'Paid',
                    'gateway'   => 'Flutterwave',
                    'gateway_respond'  => $gatewayRespond
                );

                $this->db->where('id', $coursePaymentId );
                $this->db->update('course_payments', $data);

                $this->db->where('course_payment_id', $coursePaymentId );
                $this->db->update('course_booked', ['status' => 'Confirmed']);

//                $this->bookingConfirmationMail( $coursePaymentId  );

                $success = true;
                $message = 'Payment made successfully.';

            } else {
                $success = false;
                $message = 'Something went wrong.';
            }
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $paymentIntent
        ]);
    }

    public function bookingConfirmationMail( $payment_id  ){
                        
        $paymentInfo = $this->getPayment( $payment_id );
        $option      = [            
            'id'        => $paymentInfo->student_id,
            'send_to'   => $paymentInfo->email,            
            'send_bcc'  => getSettingItem('IncomingEmail'),
            'template'  => 'onCourseBookingNotify2Student',            
            'variables' => [
                '%invoice%'   => $this->invoiceEmail( $payment_id, $paymentInfo )
            ]
        ];
        Modules::run('mail/send_mail', $option );                
    }
    
    private function getPayment( $id ){
        $this->db->select('cp.*, s.id as student_id, CONCAT(s.fname, " ", ifnull(s.mname, ""), " ", s.lname) as full_name, s.email');
        $this->db->from('course_payments as cp');
        $this->db->join('students as s', 's.id=cp.student_id', 'left');               
        $this->db->where('cp.id', $id);
        return $this->db->get()->row();
    }
    
    private function invoiceEmail($id, $payment) {        
        $this->db->select('cb.course_payment_id, cb.course_id, cb.course_payment_id');
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