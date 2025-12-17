<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Author: Khairul Azam
 * Date : 2016-10-13
 */

class Auth_student extends MX_Controller {
    public $CI;
    function __construct() {
        parent::__construct();
        $this->load->library('Sms');
        $this->load->library('form_validation');
    }

    public function index(){
        ajaxAuthorized();
    }

    public function login() {
        ajaxAuthorized();        

        $username = $this->security->xss_clean(trim_fk($this->input->post('username')));
        $password = $this->security->xss_clean(trim_fk($this->input->post('password')));
        $remember = ($this->input->post('remember')) ? (60 * 60 * 24 * 7) : 0;

        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter a valid email</p>');
            exit;
        }
                        
        $user = $this->find($username);
        // echo $this->db->last_query();
        if (!$user) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Email Address!</p>');
            exit;
        }
        if (password_verify($password, $user->password) == false) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Password!</p>');
            exit;
        }

        if ($user->status == 'Inactive') {
            echo ajaxRespond('Fail', '<p class="ajax_error">Your account is not active.</p>');
            exit;
        }

        // Start::Send OTP to mobile phone
            //  $this->sendToPhone($mobile_no, $user, $password);
        // End::Send OTP to mobile phone

        // Start::Send OTP to Email
            $this->sendToEmail($user, $password);
        // End::Send OTP to Email

        $history_id = $this->login_history( $user );

        $cookie_data = json_encode([
            'student_id'    => $user->id,
            'student_email' => $user->email,
            'number_type'   => $user->number_type,
            'student_gmc'   => $user->gmc_number,
            'student_name'  => $user->fname.' '.$user->mname.' '.$user->lname,
            'password'      => $password,
            'student_history' 	=> $history_id
        ]);

        $cookie = [
            'name'      => 'student_data',
            'value'     => base64_encode($cookie_data),
            'expire'    => $remember,
            'secure'    => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);

        echo ajaxRespond('OK', '<p class="ajax_success">Login Success</p>');
        exit;
    }
    
    public function otp_resend() {
        ajaxAuthorized();

        $opt_info   = $this->session->userdata('otp_info');
        $mobile_no  = $opt_info['mobile_no']; 
        
        if(empty($opt_info['student_id']) && strlen($mobile_no) <= 9 ){
            echo ajaxRespond('Fail', '<p class="ajax_error">Student Not Found!</p>');
            exit;
        }

        $OTP        = Sms::OTP(4);
        $sms_body   = Sms::template($OTP);
        $email      = isset($_GET['mail']) ? TRUE : FALSE;
        
        if($email == TRUE){
            $option = [
                'id'    => $opt_info['student_id'],
                'email' => $opt_info['student_email'],
                'body'  => $sms_body,                
            ];
            Modules::run('mail/sendVerificationCode', $option);
        } else {
            Sms::send($mobile_no, $sms_body );
        }
        
        $this->session->set_userdata('otp_info', [
            'otp_code'      => $OTP,
            'mobile_no'     => $opt_info['mobile_no'],
            'student_email' => $opt_info['student_email'],
            'student_id'    => $opt_info['student_id'],
            'password'      => $opt_info['password']
        ]);

        echo ajaxRespond('OK', $OTP);
        exit;
    }
    
    public function otp_verify() {
        $opt_info = $this->session->userdata('otp_info');
        $otp_code = $this->input->post('otp_code');
        $student = $this->db->select('id, fname, mname, lname, email, password, status, gmc_number, number_type')
                ->get_where('students', ['id' => $opt_info['student_id']] )
                ->row();
        
        if(empty($student)){
            echo ajaxRespond('Fail', '<p class="ajax_error">Invalid User.</p>');
            exit;
        }
        
        if(empty($otp_code)){
            echo ajaxRespond('Fail', '<p class="ajax_error">Verification Code Not Found!.</p>');
            exit;
        }
        
        if($otp_code!=$opt_info['otp_code']){
            echo ajaxRespond('Fail', '<p class="ajax_error">This Verification Code is Invalid!</p>');
            exit;
        }
        
        //Generate token
        $token = bin2hex(random_bytes(10));
        $identify_cookie = [
            'name' => $student->id.'_identify',
            'value' => base64_encode(json_encode(['token' => $token])),
            'expire' => (365*24*60*60),
            'secure' => false
        ];
        $this->input->set_cookie($identify_cookie);
        
        $history_id = $this->login_history( $student );
        $cookie_data = json_encode([
            'student_id' => $student->id,
            'student_email' => $student->email,
            'number_type' => $student->number_type,
            'student_gmc' => $student->gmc_number,
            'student_name' => $student->fname.' '.$student->mname.' '.$student->lname,
            'password' => $opt_info['password'],
            'student_history' 	=> $history_id
        ]);

        $cookie = [
            'name' => 'student_data',
            'value' => base64_encode($cookie_data),
            'expire' => (60 * 60 * 24 * 7),
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);
        
        $this->session->unset_userdata('otp_info');
        echo ajaxRespond('OK', '<p class="ajax_success">Login Success</p>');
        exit;
    }

    private function find($username){
        return $this->db
                ->select('id, fname, mname, lname, email, password, status, gmc_number, number_type')
                ->select('CONCAT("+",phone_code,phone) AS mobile_no')
                ->get_where('students', ['email' => $username] )
                ->row();
    }
   
    public function logout() {
        $cookie = [
            'name' => 'student_data',
            'value' => false,
            'expire' => -84000,
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('value');
        $this->session->unset_userdata('expire');
        $this->session->unset_userdata('secure');
        $this->session->unset_userdata('student_history');
        $this->logout_history();

        redirect(site_url('login'));
    }

    public function login_form() {
        $this->load->view('frontend/login');
    }
    
    public function forgot_pass() {
        ajaxAuthorized();
        $email      = $this->input->post('forgot_mail');
        $is_exist   = $this->db->get_where('students', ['email' => $email])->num_rows();

        if ($is_exist) {
            $hash_email = password_hash($email, PASSWORD_DEFAULT);
            $array = [
                'email' => $email,
                'Status' => 'OK',
                '_token' => $hash_email,
                'Msg' => '<p class="ajax_success">Reset password link sent to your email </p>'
            ];

            echo Modules::run('mail/student_pwd_mail', $array);
            // echo json_encode($array);
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Email address not found!</p>');
        }
    }

    public function reset_password() {
        $step = $this->input->get('step');
        $data = array('display_none'  => '');
        $this->load->view('frontend/header', $data);

        if($step == 2){
            $this->load->view('frontend/reset');
        }else{
            $data = array(
                'action'            => site_url('auth_student/question_verify_action'),
                'verify_token'      => $this->input->get('token'),
                'email'             => $this->input->get('email'),
                'secret_question_1' => set_value('secret_question_1'),
                'answer_1'          => set_value('answer_1'),
                'secret_question_2' => set_value('secret_question_2'),
                'answer_2'          => set_value('answer_2'),
            );
            $this->load->view('frontend/secret_question', $data);
        }
        $this->load->view('frontend/footer');
    }
    
    public function question_verify_action() {
        $this->form_validation->CI =& $this;
        
        $this->form_validation->set_rules('verify_token', 'Token', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('secret_question_1', 'Question 1', 'trim|required');
        $this->form_validation->set_rules('answer_1', 'Answer 1', 'trim|required|callback_valid_answer1');
        $this->form_validation->set_rules('secret_question_2', 'Question 2', 'trim|required');
        $this->form_validation->set_rules('answer_2', 'Answer 2', 'trim|required|callback_valid_answer2');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'action'            => site_url('auth_student/question_verify_action'),
                'verify_token'      => $this->input->post('verify_token'),
                'email'             => trim_fk($this->input->post('email')),
                'secret_question_1' => set_value('secret_question_1'),
                'answer_1'          => set_value('answer_1'),
                'secret_question_2' => set_value('secret_question_2'),
                'answer_2'          => set_value('answer_2'),
                'display_none'      => ''
            );            
            $this->load->view('frontend/header',  $data); 
            $this->load->view('frontend/secret_question', $data);
            $this->load->view('frontend/footer', $data);
            
        } else {
            
            $reset_token       = $this->input->post('verify_token');
            $email             = trim_fk($this->input->post('email'));
            
            $student = $this->db
                    ->select('id, secret_question_1, secret_question_2, answer_1, answer_2')
                    ->get_where('students', ['email' => $email] )
                    ->row();
            
            if(!$student){
                redirect(site_url('login'));
            }
            
            redirect(site_url('reset-password?token='.$reset_token.'&email='.$email.'&step=2'));
        }
       
    }
    
    function valid_answer1() {
        $email             = trim_fk($this->input->post('email'));
        $secret_question_1 = trim_fk($this->input->post('secret_question_1'));
        $answer_1          = trim_fk($this->input->post('answer_1'));
        
        $student = $this->db
                    ->select('id, secret_question_1, secret_question_2, answer_1, answer_2')
                    ->get_where('students', ['email' => $email] )
                    ->row();
        if ($student && strtoupper(trim_fk($student->answer_1)) != strtoupper(trim_fk($answer_1))) {
            $this->form_validation->set_message('valid_answer1', 'The answer does not match!');
            return FALSE;
        }else if($student && $student->secret_question_1 != $secret_question_1){
            $this->form_validation->set_message('valid_answer1', 'The answer does not match!');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function valid_answer2() {
        $email              = trim_fk($this->input->post('email'));
        $secret_question_2  = trim_fk($this->input->post('secret_question_2'));
        $answer_2           = trim_fk($this->input->post('answer_2'));
        
        $student = $this->db
                    ->select('id, secret_question_1, secret_question_2, answer_1, answer_2')
                    ->get_where('students', ['email' => $email] )
                    ->row();
        if ($student && strtoupper(trim_fk($student->answer_2)) != strtoupper(trim_fk($answer_2))) {
            $this->form_validation->set_message('valid_answer2', 'The answer does not match!');
            return FALSE;
        }else if($student && $student->secret_question_2 != $secret_question_2){
            $this->form_validation->set_message('valid_answer2', 'The answer does not match!');
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function reset_password_action() {
        $reset_token    = $this->input->post('verify_token');
        $email          = trim_fk($this->input->post('email'));

        $new_pass   = $this->input->post('new_password');
        $re_pass    = $this->input->post('retype_password');
        if ($new_pass != $re_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Confirm Password Not Match</p>');
            exit;
        }
        
        $error = password_strength($new_pass);
        if($error){
            echo ajaxRespond('Fail', $error );
            exit;
        }
        
        $hash_pass  = password_encription($new_pass);        
        if (!password_verify($email, $reset_token)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Token Not Match</p>');
            exit;
        }
        
        $this->db->set('password', $hash_pass);
        $this->db->where('email', $email);
        $this->db->update('students');
        echo ajaxRespond('OK', '<p class="ajax_success">Password Reset Successfully</p>');
                   
        
    }
  
    private function  login_history( $user ) {
        $this->load->library('user_agent');
        if ($this->agent->is_browser()){
            $agent = $this->agent->browser();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        }  elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
             $agent = 'Unidentified User Agent';
        }

        $history = [
            'student_id'    => $user->id,
            'login_time'    => date('Y-m-d H:s:i'),            
            'ip'            => getenv('REMOTE_ADDR'),
            'token'         => getStudentIdentifyData($user->id, 'token'),
            'browser'       => $agent,
            'device'        => $this->agent->platform(),
        ];
                
        $this->db->insert('student_logs', $history );
        return $this->db->insert_id();
    }
    
    private function logout_history(){
        $id     = getLoginStudentData('student_history');

        $this->db->set('logout_time', date('Y-m-d H:i:s') );        
        $this->db->where('id', $id );
        $this->db->update('student_logs');
    }

    private function sendToEmail($user, $password){
        $OTP        = Sms::OTP(4);
        $sms_body   = Sms::template($OTP);

        $option = [
            'id'    => $user->id,
            'email' => $user->email,
            'body'  => $sms_body,
        ];

        Modules::run('mail/sendVerificationCode', $option);

        $this->session->set_userdata('otp_info', [
            'otp_code'      => $OTP,
            'mobile_no'     => $user->mobile_no,
            'student_email' => $user->email,
            'student_id'    => $user->id,
            'password'      => $password
        ]);

        echo ajaxRespond('OTP', $OTP);
        exit;
    }

    private function sendToPhone($mobile_no, $user, $password){
        if(empty($get_token) && strlen($mobile_no) >= 10){
            $OTP        = Sms::OTP(4);
            $sms_body   = SMS::template($OTP);

            Sms::send($mobile_no, $sms_body );

            $this->session->set_userdata('otp_info', [
                'otp_code'      => $OTP,
                'mobile_no'     => $mobile_no,
                'student_id'    => $user->id,
                'student_email' => $user->email,
                'password'      => $password,
            ]);

            echo ajaxRespond('OTP', $OTP);
            exit;
        }
    }
}