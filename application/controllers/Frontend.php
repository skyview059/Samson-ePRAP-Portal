<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontend extends Frontend_controller
{
    public $student_id;
    public $CI;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('exam/exam');

        $this->student_id = getLoginStudentData('student_id');
    }

    public function ckImageUpload()
    {
        // Upload file


        echo json_encode([
            'url' => 'https://avatars.githubusercontent.com/u/55717927?v=4&size=64'
        ]);
    }

    public function book_course()
    {
        $course_payment_id = ((int)$this->input->get('id')) ?? null;
        $paymentInfo       = [];
        if ($course_payment_id) {
            $this->db->from('course_payments');
            $this->db->where('student_id', $this->student_id);
            $this->db->where('id', $course_payment_id);
            $this->db->where('(purchased_at + INTERVAL 30 MINUTE) >=', date("Y-m-d H:i:s"));
            $paymentInfo = $this->db->get()->row_array();
            if (empty($paymentInfo)) {
                $this->session->set_flashdata('msge', "Session Expired!");
                redirect(site_url('booking/course'));
            }
        }

        $this->db->select('cb.course_id');
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT');
        $this->db->where('cb.course_payment_id', $course_payment_id);
        $this->db->where('cp.student_id', $this->student_id);
        $this->db->where_in('cp.status', ['Paid', 'Due']);

        $this->db->group_by('cb.course_id');
        $pCourses = $this->db->get_compiled_select();


        $this->db->select('c.id,c.name as course,c.price,c.booking_limit');
        $this->db->select('c.category_id as index, cc.name as category, p_courses.course_id as p_course_id');
        $this->db->from('courses as c');
        $this->db->join('course_categories as cc', 'c.category_id=cc.id', 'LEFT');
        $this->db->join("($pCourses) as p_courses", 'p_courses.course_id=c.id', 'LEFT');
        $this->db->where('c.status', 'Active');
        $this->db->order_by('cc.id', 'ASC');
        $this->db->order_by('c.id', 'ASC');
        $courses = $this->db->get()->result();

        $data = [];
        foreach ($courses as $c) {
            $data[$c->index]['category']  = $c->category;
            $data[$c->index]['courses'][] = [
                'id'         => $c->id,
                'name'       => $c->course,
                'price'      => $c->price,
                'limit'      => $c->booking_limit,
                'isSelected' => ($c->p_course_id) ? 1 : 0,
                'dates'      => $this->getDates($c->id, $c->booking_limit, $course_payment_id),
            ];
        }

        $view_data = [
            'course_plans'      => $data,
            'course_payment_id' => $course_payment_id,
            'payment_info'      => $paymentInfo
        ];

        $this->viewFrontContent('frontend/booking/book_course_as_guest', $view_data);
    }

    private function getDates($course_id, $limit, $payment_id = 0)
    {
        $student_id = 771;
        $sql        = "SELECT COUNT(*) FROM `course_booked` WHERE `course_date_id` = `course_dates`.`id`";

        $isAlreadyBooked = "SELECT course_date_id, COUNT(cb.id) as qty, p.status FROM `course_booked` as cb 
            LEFT JOIN `course_payments` as p on cb.course_payment_id=p.id
            WHERE p.student_id = {$student_id} and (p.status = 'Paid' and (cb.status = 'Pending' or cb.status = 'Confirmed')) GROUP BY course_date_id";

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
        $this->db->where('start_date >=', 'now()', FALSE);
        $this->db->order_by('start_date');
        $dates = $this->db->get('course_dates')->result();
        return json_encode($dates);
    }

    public function sign_up()
    {
        $digit1 = mt_rand(10, 20);
        $digit2 = mt_rand(1, 9);
        if (mt_rand(0, 1) === 1) {
            $math           = "$digit1 + $digit2";
            $correct_answer = $digit1 + $digit2;
        } else {
            $math           = "$digit1 - $digit2";
            $correct_answer = $digit1 - $digit2;
        }
        $this->session->set_userdata('correct_answer', $correct_answer);


        $data = array(
            'button'                  => 'Create',
            'action'                  => site_url(Backend_URL . 'student/create_action'),
            'id'                      => set_value('id'),
            'number_type'             => set_value('number_type', null),
            'gmc_number'              => set_value('gmc_number'),
            'title'                   => set_value('title'),
            'fname'                   => set_value('fname'),
            'mname'                   => set_value('mname'),
            'lname'                   => set_value('lname'),
            'email'                   => set_value('email'),
            'phone_code'              => set_value('phone[code]'),
            'phone'                   => set_value('phone[number]'),
            'whatsapp_code'           => set_value('whatsapp[code]'),
            'whatsapp'                => set_value('whatsapp[number]'),
            'occupation'              => set_value('occupation'),
            'password'                => set_value('password'),
            'gender'                  => set_value('gender', 'Male'),
            'math'                    => $math,

            'exam_id'        => set_value('exam_id'),
            'exam_date'      => set_value('exam_date'),

            'answer'            => set_value('answer'),
            'secret_question_1' => set_value('secret_question_1'),
            'answer_1'          => set_value('answer_1'),
            'contact_by_rm'     => set_value('contact_by_rm', 'Yes'),
            'photo'             => set_value('photo')
        );
        $this->viewFrontContent('frontend/sign_up', $data);
    }

    public function sign_up_action()
    {
        $this->isPost();
        $this->_rules_sign_up();

        if ($this->form_validation->run() == FALSE) {
            $this->sign_up();
        } else {
            $phone          = $this->input->post('phone', TRUE);
            $whatsapp       = $this->input->post('whatsapp', TRUE);
            $gmc_number     = (int)$this->input->post('gmc_number', TRUE);

            $data = array(
                'number_type'             => $this->input->post('number_type', TRUE),
                'gmc_number'              => ($gmc_number) ?: null,
                'title'                   => $this->input->post('title', TRUE),
                'fname'                   => $this->input->post('fname', TRUE),
                'mname'                   => $this->input->post('mname', TRUE),
                'lname'                   => $this->input->post('lname', TRUE),
                'email'                   => $this->input->post('email', TRUE),
                'phone_code'              => $phone['code'],
                'phone'                   => $phone['number'],
                'whatsapp_code'           => $whatsapp['code'],
                'whatsapp'                => $whatsapp['number'],
                'occupation'              => $this->input->post('occupation', TRUE),
                'purpose_of_registration' => getExamName(intval($this->input->post('exam_id', TRUE))),
                'password'                => password_encription($this->input->post('password')),
                'tmp_ip_addr'             => $this->input->ip_address(),
                'gender'                  => $this->input->post('gender', TRUE),

                'exam_id'        => intval($this->input->post('exam_id', TRUE)),
                'exam_date'      => $this->input->post('exam_date', TRUE),

                'secret_question_1' => $this->input->post('secret_question_1', TRUE),
                'answer_1'          => $this->input->post('answer_1', TRUE),
                'contact_by_rm'     => ($this->input->post('contact_by_rm')) ? 'Yes' : 'No',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            );

            $this->db->insert('students', $data);
            $student_id = $this->db->insert_id();

            $date        = new DateTime('+1 day');
            $expire_time = $date->format('Y-m-d H:i:s');

            $str_data     = array(
                'student_id'  => $student_id,
                'expire_time' => $expire_time
            );
            $token_encode = encode(json_encode($str_data), 'studentsignup');

            $mail_data = [
                'id'        => $student_id,
                'full_name' => $this->input->post('fname', TRUE) . ' ' . $this->input->post('mname', TRUE) . ' ' . $this->input->post('lname', TRUE),
                'email'     => $this->input->post('email', TRUE),
                'password'  => $this->input->post('password'),
                'token'     => $token_encode
            ];
            Modules::run('mail/onStudentRegistrationWelcome', $mail_data);

            //correct answer remove from session
            $this->session->unset_userdata('correct_answer');
            $this->session->set_flashdata('msgs', 'Registration Successful');
            redirect(site_url('login?msg=success'));
        }
    }

    public function _rules_sign_up()
    {
        $this->form_validation->CI =& $this;

        $this->form_validation->set_rules('gmc_number', 'GMC number', 'trim|min_length[7]|max_length[7]|callback_unique_student_number');

        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[100]|valid_email|callback_unique_email');
        $this->form_validation->set_rules('fname', 'first name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('mname', 'middle name', 'trim|max_length[50]');
        $this->form_validation->set_rules('lname', 'last name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('phone[number]', 'phone', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[13]|callback_valid_password');
        $this->form_validation->set_rules('passconf', 'Confirm Password', 'trim|required|min_length[6]|max_length[13]|matches[password]');

        $this->form_validation->set_rules('exam_id', 'purpose of registration', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a purpose of registration'
        ]);
        $this->form_validation->set_rules('exam_date', 'Exam date', 'trim|required');

        $this->form_validation->set_rules('answer', 'Answer', 'trim|required|callback_check_captcha_answer');
        $this->form_validation->set_rules('secret_question_1', 'Secret Question 1', 'trim|required');
        $this->form_validation->set_rules('answer_1', 'Answer 1', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
    }

    function unique_student_number()
    {
        $id          = $this->input->post('id');
        $number_type = $this->input->post('number_type');// get number type
        $gmc_number  = $this->input->post('gmc_number'); // get student name
        $this->db->select('id');
        $this->db->from('students');
        $this->db->where('number_type', $number_type);
        $this->db->where('gmc_number', $gmc_number);
        if (!empty($id)) {
            $this->db->where('id!=', $id);
        }
        $query = $this->db->get();
        $num   = $query->num_rows();
        if ($num > 0) {
            $this->form_validation->set_message('unique_student_number', 'This ' . $number_type . ' number already in used');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function unique_email()
    {

        $id    = $this->input->post('id');
        $email = $this->input->post('email');// get email
        $this->db->select('id');
        $this->db->from('students');
        $this->db->where('email', $email);
        if (!empty($id)) {
            $this->db->where('id!=', $id);
        }
        $query = $this->db->get();
        $num   = $query->num_rows();
        if ($num > 0) {
            $this->form_validation->set_message('unique_email', 'This email already in used');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_captcha_answer()
    {
        $answer         = $this->input->post('answer', TRUE);
        $correct_answer = $this->session->userdata('correct_answer');
        if ($correct_answer != $answer) {
            $this->form_validation->set_message('check_captcha_answer', 'Please enter captcha currect answer!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Validate the password
     *
     * @param string $password
     *
     * @return bool
     */
    function valid_password($password = '')
    {
        $password                  = trim_fk($password);
        $regex_lowercase_uppercase = '/[a-zA-Z]/';
        $regex_number              = '/[0-9]/';
        $regex_special             = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
        if (empty($password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase_uppercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one letter.');
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
            return FALSE;
        }
        if (strlen($password) < 5) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 6 characters in length.');
            return FALSE;
        }
        if (strlen($password) > 12) {
            $this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 12 characters in length.');
            return FALSE;
        }
        return TRUE;
    }

    public function viewFrontContent($view, $data = [])
    {

        $iframe               = ($this->input->get('iframe')) ? true : false;
        $data['display_none'] = ($iframe) ? 'style="display:none;"' : '';

        $this->load->view('frontend/header', $data);
        $this->load->view($view, $data);
        $this->load->view('frontend/footer', $data);
    }

    public function verify_email()
    {
        $token        = $this->input->get('token');
        $token_decode = json_decode(decode($token, 'studentsignup'));

        if (empty($token_decode)) {
            $this->session->set_flashdata('msgs', 'Token Not Found!');
            redirect(site_url('login'));
        }

        $this->db->select('id,verified,email,title,fname,lname,gmc_number');
        $this->db->from('students');
        $this->db->where('id', $token_decode->student_id);
        $this->db->where('verified', 'No');
        $student = $this->db->get()->row();

        if (empty($student)) {
            $this->session->set_flashdata('msge', 'Invalid Token!');
            redirect(site_url('login'));
        }

        if ($student->verified == 'Yes') {
            $this->session->set_flashdata('msgs', 'This account has been already verified!');
            redirect(site_url('login'));
        }

        if ($student->id != $token_decode->student_id) {
            $this->session->set_flashdata('msgs', 'The token does not matcht!');
            redirect(site_url('login'));
        }

        if ($token_decode->expire_time < date("Y-m-d H:i:s")) {
            $this->session->set_flashdata('msgs', 'Token time expire!');
            redirect(site_url('login'));
        }

        $updateData = [
            'status'   => 'Active',
            'verified' => 'Yes'
        ];
        $this->db->where('id', $student->id);
        $this->db->update('students', $updateData);

        $cookie_data = json_encode([
            'student_id'      => $student->id,
            'student_email'   => $student->email,
            'student_gmc'     => $student->gmc_number,
            'student_name'    => "{$student->title} {$student->fname} {$student->lname}",
            'student_history' => 0
        ]);

        $cookie = [
            'name'   => 'student_data',
            'value'  => base64_encode($cookie_data),
            'expire' => 0,
            'secure' => true
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);


        $this->session->set_flashdata('msgs', 'Your account has been verified.');
        redirect(site_url('login'));
    }

    public function getCentreByCountry()
    {
        $country_id = $this->input->post('id');

        echo getExamCentreDropDownForFrontend(0, $country_id);
    }

    public function alter()
    {

        // $this->db->query("ALTER TABLE `result_details` ADD COLUMN `pass_mark` DECIMAL(5,2) NULL DEFAULT NULL AFTER `step`;");
//         $this->db->query("ALTER TABLE `exam_schedules`
//	ADD COLUMN `status` ENUM('Published','Unpublished') NULL DEFAULT 'Unpublished' AFTER `datetime`,
//	ADD COLUMN `published_at` DATETIME NULL DEFAULT NULL AFTER `status`;");

//        $this->db->query("ALTER TABLE `scenarios` CHANGE `exam_course_id` `exam_id` INT(11) NOT NULL;");
//        $this->db->query("ALTER TABLE `exam_schedules` CHANGE `exam_course_id` `exam_id` INT(11) NOT NULL;");
//        $this->db->query("ALTER TABLE `scenarios` CHANGE `reference_number` `reference_number` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `id`");
//        $this->db->query("ALTER TABLE `scenarios` CHANGE `reference_number` `reference_number` INT(10) NULL DEFAULT NULL;");

//        
//        $tbl = 'CREATE TABLE `scenario_to_assessors` ( `id` int(11) NOT NULL, `scenario_rel_id` int(11) NOT NULL, `assessor_id` int(11) NOT NULL COMMENT "user_id", `user_id` INT NOT NULL, `timestamp` datetime NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
//        $this->db->query($tbl);
//        $this->db->query("ALTER TABLE `scenario_to_assessors` ADD PRIMARY KEY (`id`);");
//        $this->db->query("ALTER TABLE `scenario_to_assessors` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
//        
//        
//       $sql = "ALTER TABLE `students`
//                ADD COLUMN `number_type` ENUM('GMC','GDC','NMC') NOT NULL DEFAULT 'GMC' AFTER `id`,
//                DROP INDEX `gmc_number`;
//
//            ALTER TABLE `students`
//                ADD COLUMN `exam_id` INT NULL DEFAULT NULL AFTER `status`,
//                ADD COLUMN `exam_centre_id` INT NULL DEFAULT NULL AFTER `exam_id`,
//                ADD COLUMN `exam_date` DATE NULL DEFAULT NULL AFTER `exam_centre_id`;";
//        $this->db->query( $sql );
//        
//        
//        $sql_2 = "ALTER TABLE `result_details`
//                ADD COLUMN `avg_borderline_score` DECIMAL(7,2) NULL DEFAULT NULL COMMENT 'Average  Borderline Score 	' AFTER `step`,
//                ADD COLUMN `coefficient_mark` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Add  Coefficient' AFTER `avg_borderline_score`,
//                CHANGE COLUMN `pass_mark` `pass_mark` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Generate pass  Score  = Average  Borderline Score +Add  Coefficient' AFTER `coefficient_mark`;";
//        $this->db->query( $sql_2  );
//        echo $this->db->last_query();
    }


    function webhookData()
    {
        $webhookPayload = '{
                      "event": {
                        "type": "lead_signup",
                        "created_at": "2024-04-23T12:00:00Z"
                      },
                      "data": {
                        "id": "123456789",
                        "state": "signup",
                        "first_name": "John",
                        "last_name": "Doe",
                        "email": "johndoe@example.com",
                        "uid": "987654321",
                        "customer_since": null,
                        "plan_name": null,
                        
                        "promoter": {
                          "id": "987654",
                          "cust_id": "54321",
                          "auth_token": "abcdef123456",
                          "earnings_balance": {
                            "total": 1000,
                            "currency": "USD"
                          },
                          "current_balance": {
                            "remaining": 800,
                            "currency": "USD"
                          },
                          "paid_balance": {
                            "paid": 200,
                            "currency": "USD"
                          },
                          "email": "promoter@example.com",
                          "temp_password": "temporary123",
                          "profile": {
                            "age": 30,
                            "location": "New York",
                            "interests": ["hiking", "reading"]
                          }
                        },
                        
                        "promotion": {
                          "campaign_id": "789",
                          "promoter_id": "987654",
                          "status": "active",
                          "promo_code": "SPRINGSALE",
                          "ref_id": "54321",
                          "ref_link": "https://example.com/referral",
                          "current_offer": {
                            "discount_percentage": 20,
                            "valid_until": "2024-05-31T23:59:59Z"
                          }
                        }
                      }
                    }
                    ';


        json_decode($webhookPayload, true);
        print_r($webhookPayload);
    }


    function firstpromoterWebhook()
    {
        $webhook_data = file_get_contents('http://eprap_portal.test/webhook-row-data');
        $payload      = json_decode($webhook_data, true);

        //  $request = $this->input->get();
        $type = $payload['event']['type'];

        if ($type === 'lead_signup') {
            $promocode = $this->db->from('promocodes')
                ->where('code', $payload['data']['promotion']['promo_code'])
                ->where('status', 'Public')
                ->get()->row();

            if (!$promocode) {
                $this->db->insert('promocodes', [
                    'user_id'       => $payload['data']['promoter']['id'],
                    'code'          => $payload['data']['promotion']['promo_code'],
                    'discount_type' => 'Fixed',
                    'amount'        => $payload['data']['promotion']['current_offer']['discount_percentage'],
                    'start_date'    => date('Y-m-d H:i:s'),
                    'end_date'      => $payload['data']['promotion']['current_offer']['valid_until'],
                    'uses_limit'    => 1,
                    'use_multiple'  => 'yes',
                    'status'        => 'Public',
                    'is_special'    => 0,
                    'created_on'    => date('Y-m-d H:i:s'),
                    'updated_on'    => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}