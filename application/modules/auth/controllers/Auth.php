<?php defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

/*
 * Author: Khairul Azam
 * Date : 2016-10-13
 */

class Auth extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'Auth_model' );
        $this->load->library( 'Sms' );
        $this->load->library( 'form_validation' );
    }

    public function login()
    {
        ajaxAuthorized();
        $username = $this->security->xss_clean( trim_fk( $this->input->post( 'username' ) ) );
        $password = $this->security->xss_clean( trim_fk( $this->input->post( 'password' ) ) );
        $remember = ( $this->input->post( 'remember' ) ) ? ( 60 * 60 * 24 * 7 ) : 0;

        if ( !filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Please enter a valid user name</p>' );
            exit;
        }
       
        $user = $this->Auth_model->find( $username );

        if ( !$user ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Incorrect Username!</p>' );
            exit;
        }

        if ( password_verify( $password, $user->password ) == false ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Incorrect Password!</p>' );
            exit;
        }

        if ( $user->status == 'Inactive' ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Your account is not active.</p>' );
            exit;
        }

        //Get user identify token from cookie
//        $get_token = getUserIdentifyData( $user->id, 'token' );
//        $mobile_no = $user->mobile_no;
//
//        if ( empty( $get_token ) && strlen( $mobile_no ) >= 10 ) {
//
//            $OTP      = Sms::OTP( 4 );
//            $sms_body = Sms::template( $OTP );
//            Sms::send( $mobile_no, $sms_body );
//            $this->session->set_userdata( 'otp_info', [
//                'otp_code'  => $OTP,
//                'mobile_no' => $mobile_no,
//                'user_id'   => $user->id,
//                'email'     => $user->email,
//                'password'  => $password,
//            ] );
//            echo ajaxRespond( 'OTP', $OTP );
//            exit;
//        }

        // Start::Send OTP to Email
        $this->sendToEmail($user, $password);
        // End::Send OTP to Email

        $history_id  = $this->login_history( $user );
        $cookie_data = json_encode( [
            'user_id'   => $user->id,
            'user_mail' => $user->email,
            'role_id'   => $user->role_id,
            'name'      => $user->first_name . ' ' . $user->last_name,
            'password'  => $password,
            'history'   => $history_id,
        ] );

        $cookie = [
            'name'   => 'login_data',
            'value'  => base64_encode( $cookie_data ),
            'expire' => $remember,
            'secure' => false,
        ];

        $this->input->set_cookie( $cookie );
        $this->session->set_userdata( $cookie );

        echo ajaxRespond( 'OK', '<p class="ajax_success">Login Success</p>' );
        exit;
    }

    public function otp_resend()
    {
        ajaxAuthorized();

        $opt_info  = $this->session->userdata( 'otp_info' );
        $mobile_no = $opt_info['mobile_no'];

        if ( empty( $opt_info['user_id'] ) && strlen( $mobile_no ) <= 9 ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">User Not Found!</p>' );
            exit;
        }

        $OTP      = Sms::OTP( 4 );
        $sms_body = Sms::template( $OTP );
        $email    = isset( $_GET['mail'] ) ? true : false;

        if ( $email == true ) {
            $option = [
                'id'    => $opt_info['user_id'],
                'email' => $opt_info['email'],
                'body'  => $sms_body,
            ];
            Modules::run( 'mail/sendVerificationCode', $option );
        } else {
            Sms::send( $mobile_no, $sms_body );
        }

        $this->session->set_userdata( 'otp_info', [
            'otp_code'  => $OTP,
            'mobile_no' => $opt_info['mobile_no'],
            'user_id'   => $opt_info['user_id'],
            'password'  => $opt_info['password'],
            'email'     => $opt_info['email'],
        ] );

        echo ajaxRespond( 'OK', $OTP );
        exit;
    }

    public function otp_resend_x()
    {
        $opt_info  = $this->session->userdata( 'otp_info' );
        $mobile_no = $opt_info['mobile_no'];

        if ( empty( $opt_info['student_id'] ) && strlen( $mobile_no ) <= 9 ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Student Not Found!</p>' );
            exit;
        }

        $OTP      = Sms::OTP( 4 );
        $sms_body = Sms::template( $OTP );
        $email    = isset( $_GET['mail'] ) ? true : false;

        if ( $email == true ) {
            $option = [
                'id'    => $opt_info['student_id'],
                'email' => $opt_info['student_email'],
                'body'  => $sms_body
            ];
            Modules::run( 'mail/sendVerificationCode', $option );
        } else {
            Sms::send( $mobile_no, $sms_body );
        }

        $this->session->set_userdata( 'otp_info', [
            'otp_code'   => $OTP,
            'mobile_no'  => $opt_info['mobile_no'],
            'student_id' => $opt_info['student_id'],
            'password'   => $opt_info['password'],
        ] );
        echo ajaxRespond( 'OK', $OTP );
        exit;
    }

    public function otp_verify(){

        $opt_info = $this->session->userdata( 'otp_info' );
        $otp_code = $this->input->post( 'otp_code' );
        $user     = $this->db->select( 'id, role_id, first_name, last_name, email, status, password' )
            ->get_where( 'users', ['id' => $opt_info['user_id']] )
            ->row();

        //echo $this->db->last_query();

        if ( empty( $user ) ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Invalid User.</p>' );
            exit;
        }

        if ( empty( $otp_code ) ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Verification Code Not Found!.</p>' );
            exit;
        }

        if ( $otp_code != $opt_info['otp_code'] ) {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">This Verification Code is Invalid!</p>' );
            exit;
        }

        /* Generate token */
        $token           = bin2hex( random_bytes( 10 ) );
        $identify_cookie = [
            'name'   => $user->id . '_user_identify',
            'value'  => base64_encode( json_encode( ['token' => $token] ) ),
            'expire' => ( 365 * 24 * 60 * 60 ),
            'secure' => false,
        ];
        $this->input->set_cookie( $identify_cookie );

        $history_id  = $this->login_history( $user );
        $cookie_data = json_encode( [
            'user_id'   => $user->id,
            'user_mail' => $user->email,
            'role_id'   => $user->role_id,
            'name'      => $user->first_name . ' ' . $user->last_name,
            'password'  => $opt_info['password'],
            'history'   => $history_id,
        ] );

        $cookie = [
            'name'   => 'login_data',
            'value'  => base64_encode( $cookie_data ),
            'expire' => ( 60 * 60 * 24 * 7 ),
            'secure' => false,
        ];

        $this->input->set_cookie( $cookie );
        $this->session->set_userdata( $cookie );

        $this->session->unset_userdata( 'otp_info' );
        echo ajaxRespond( 'OK', '<p class="ajax_success">Login Success</p>' );
        exit;
    }

    public function logout(){
        $cookie = [
            'name'   => 'login_data',
            'value'  => false,
            'expire' => -84000,
            'secure' => false,
        ];

        $this->input->set_cookie( $cookie );
        $this->session->unset_userdata( 'name' );
        $this->session->unset_userdata( 'value' );
        $this->session->unset_userdata( 'expire' );
        $this->session->unset_userdata( 'secure' );
        $this->session->unset_userdata( 'history' );
        $this->logout_history();

        redirect( site_url( 'admin/login' ) );
    }

    public function login_form(){
        $this->load->view( 'auth/login' );
    }

    public function forgot_pass(){
        $email    = $this->input->post( 'forgot_mail' );
        $is_exist = $this->db->get_where( 'users', ['email' => $email] )->num_rows();

        if ( $is_exist ) {
            $hash_email = password_hash( $email, PASSWORD_DEFAULT );

            $array = [
                'email'  => $email,
                'Status' => 'OK',
                '_token' => $hash_email,
                'Msg'    => '<p class="ajax_success">Reset password link sent to your email </p>',
            ];

            Modules::run( 'mail/pwd_mail', $array );
            echo json_encode( $array );
        } else {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Email address not found!</p>' );
        }
    }

    public function reset_password(){
        $this->load->view( 'auth/reset' );
    }

    public function reset_password_action(){
        $reset_token = $this->input->post( 'verify_token' );
        $email       = trim_fk( $this->input->post( 'email' ) );

        $new_pass  = $this->input->post( 'new_password' );
        $re_pass   = $this->input->post( 'retype_password' );
        $hash_pass = password_encription( $new_pass );

        /* send mail here */ 
        if ( password_verify( $email, $reset_token ) ) {
            if ( $new_pass == $re_pass ) {
                $this->db->set( 'password', $hash_pass );
                $this->db->where( 'email', $email );
                $this->db->update( 'users' );
                echo ajaxRespond( 'OK', '<p class="ajax_success">Password Reset Successfully</p>' );
            } else {
                echo ajaxRespond( 'Fail', '<p class="ajax_error">Confirm Password Not Match</p>' );
            }

        } else {
            echo ajaxRespond( 'Fail', '<p class="ajax_error">Token Not Match</p>' );
        }

    }

    private function login_history( $user ){
        $this->load->library( 'user_agent' );
        if ( $this->agent->is_browser() ) {
            $agent = $this->agent->browser();
        } elseif ( $this->agent->is_robot() ) {
            $agent = $this->agent->robot();
        } elseif ( $this->agent->is_mobile() ) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $history = [
            'user_id'    => $user->id,
            'login_time' => date( 'Y-m-d H:s:i' ),
            'ip'         => getenv( 'REMOTE_ADDR' ),
            'role_id'    => $user->role_id,
            'token'      => getUserIdentifyData( $user->id, 'token' ),
            'browser'    => $agent,
            'device'     => $this->agent->platform(),
        ];

        $this->db->insert( 'user_logs', $history );
        return $this->db->insert_id();
    }

    private function logout_history(){
        $id = getLoginUserData( 'history' );
        $this->db->set( 'logout_time', date( 'Y-m-d H:i:s' ) );
        $this->db->where( 'id', $id );
        $this->db->update( 'user_logs' );
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
            'user_id'       => $user->id,
            'email'         => $user->email,
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
                'user_id'       => $user->id,
                'email'         => $user->email,
                'password'      => $password,
            ]);

            echo ajaxRespond('OTP', $OTP);
            exit;
        }
    }
}
