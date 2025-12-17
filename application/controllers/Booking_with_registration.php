<?php defined('BASEPATH') or exit('No direct script access allowed');

class Booking_with_registration extends MX_Controller
{

    public function index()
    {
        $post         = $this->input->post();
        $post['pass'] = randDomPasswordGenerator(6);
        // if user is authenticated, set auth id otherwise register new user
        $student_id = getLoginStudentData('student_id') ? getLoginStudentData('student_id') : $this->register($post);

        // if user is authenticated, don't need to set user data in cookie
        if (!getLoginStudentData('student_id')) {
            $this->setLogin($post, $student_id);
        }

        $course_payment_id = $this->createCartAndCheckout($post, $student_id);
        $this->SendWelcomeEmail($post, $student_id);

        redirect(site_url("booking/checkout/{$course_payment_id}" . ($post['first_promoter'] ? "?ref={$post['first_promoter']}" : '')));
    }

    public function purchase_practice_action()
    {
        $student_id = getLoginStudentData('student_id');
        if (!$student_id) {
            redirect(site_url('login'));
        }

        $practice_id   = intval($this->input->post('practice_id'));
        $package_id    = intval($this->input->post('package_id'));
        $practice_data = $this->db->where('id', $practice_id)->get('exams')->row();
        $package_data  = $this->db->where('id', $package_id)->get('practice_packages')->row();
        if (!$practice_data || !$package_data) {
            redirect(site_url('scenario-practice'));
        }

        $cart = [
            'student_id'   => $student_id,
            'purchased_at' => date('Y-m-d H:i:s'),
            'total_items'  => 1,
            'total_pay'    => $package_data->price,
            'invoice_id'   => time(),
            'status'       => 'Due'
        ];
        $this->db->insert('course_payments', $cart);
        $course_payment_id = $this->db->insert_id();

        $courseBookedData = [
            'course_payment_id'   => $course_payment_id,
            'course_id'           => $practice_id, // practice id is same as course id
            'practice_package_id' => $package_id,
            'course_date_id'      => 0,
            'booked_price'        => $package_data->price,
            'status'              => 'Pending',
            'type'                => 'practice',
            'expiry_date'         => date('Y-m-d', strtotime('+'.$package_data->duration))
        ];
        $this->db->insert('course_booked', $courseBookedData);

        redirect(site_url("booking/checkout/{$course_payment_id}"));
    }

    public function process()
    {
        $post         = $this->input->post();
        $post['pass'] = randDomPasswordGenerator(6);

        $hasError = $this->validate();
        if ($hasError) {
            echo $hasError;
            exit;
        }
        echo ajaxRespond('OK', 'Process...');
    }

    public function validate()
    {
        $this->load->library('form_validation');

        // Start::This validation is not required if the user is authenticated
        if (!getLoginStudentData('student_id')) {
            $this->form_validation->set_rules('first_name', 'first name', 'required');
            $this->form_validation->set_rules('last_name', 'last name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required|numeric');
            $this->form_validation->set_rules('country_id', 'country', 'required|numeric');
        }
        // End::This validation is not required if the user is authenticated

        $this->form_validation->set_rules('id[]', 'course', 'required');
        $this->form_validation->set_rules('slot_id[]', 'course slot', 'required');
        $this->form_validation->set_rules('personal_data', 'Personal Data Collect', 'required');
        $this->form_validation->set_rules('terms_and_conditions', 'Terms and Conditions', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            return json_encode([$errors]);
        } else {
            return false;
        }
    }

    private function register($post)
    {
        $user = $this->db->from('students')
            ->where('email', $post['email'])
            ->get()->row();

        //  If the user already exists, update his profile
        if ($user) {
            $this->db->where('id', $user->id)
                ->set('fname', $post['first_name'])
                ->set('lname', $post['last_name'])
                ->set('password', password_encription($post['pass']))
                ->set('phone_Code', $post['phone_code'])
                ->set('phone', $post['phone'])
                ->set('country_id', $post['country_id'])
                ->update('students');

            return $user->id;
        } else {
            $data = array(
                'fname'      => $post['first_name'],
                'lname'      => $post['last_name'],
                'email'      => $post['email'],
                'password'   => password_encription($post['pass']),
                'phone_Code' => $post['phone_code'],
                'phone'      => $post['phone'],
                'country_id' => $post['country_id'],
            );
            $this->db->insert('students', $data);

            return $this->db->insert_id();
        }
    }

    private function SendWelcomeEmail($post, $student_id)
    {
        $user = $this->db->from('students')
            ->where('email', $post['email'])
            ->get()->row();

        $date        = new DateTime('+1 day');
        $expire_time = $date->format('Y-m-d H:i:s');

        $str_data     = array(
            'student_id'  => $student_id,
            'expire_time' => $expire_time
        );
        $token_encode = encode(json_encode($str_data), 'studentsignup');

        $mail_data = [
            'id'        => $student_id,
            'full_name' => $post['first_name'] . ' ' . $post['last_name'],
            'email'     => $post['email'],
            'password'  => $post['pass'],
            'token'     => $token_encode
        ];

        if ($user) {
            Modules::run('mail/onExistingStudentCourseBooked', $mail_data);
        } else {
            Modules::run('mail/onStudentRegistrationWelcome', $mail_data);
        }
    }

    private function setLogin($post, $student_id)
    {
        $cookie_data = json_encode([
            'student_id'      => $student_id,
            'student_email'   => $post['email'],
            'number_type'     => 'GMC',
            'student_gmc'     => '12345678',
            'student_name'    => $post['first_name'] . ' ' . $post['last_name'],
            'password'        => $post['pass'],
            'student_history' => 1
        ]);

        $cookie = [
            'name'   => 'student_data',
            'value'  => base64_encode($cookie_data),
            'expire' => (60 * 60 * 24 * 7),
            'secure' => false
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);
    }

    private function createCartAndCheckout($post, $student_id)
    {
        $coupon_name = $post['coupon_name'];
        $packageIds  = $post['id'];

        // get coupon details
        $coupon = $this->db->from('promocodes')
            ->where('code', $coupon_name)
            ->where('status', 'Public')
            ->get()->row();

        // get courses
        $courseIDs = [];
        $courses   = $this->db->where('promocode_id', isset($coupon->id) ? $coupon->id : '')
            ->get('promocode_course')
            ->result();

        foreach ($courses as $course) {
            $courseIDs[] = $course->course_id;
        }

        // check:: course ID exist or not in promo code
        $matchedID = 0;
        foreach ($courseIDs as $courseID) {
            if (in_array($courseID, $packageIds)) {
                $matchedID = $courseID;
            }
        }

        // check:: already use or not
        if ($coupon) {
            $payment = $this->db->from('course_payments')
                ->where('student_id', getLoginStudentData('student_id'))
                ->where('promo_code', $coupon->code)
                ->get()->row();

            $coupon->already_used = (bool)$payment;
        }

        // get students
        $students = $this->db->select('student_id')
            ->from('promocode_student')
            ->where('promocode_id', isset($coupon->id) ? $coupon->id : '')
            ->get()->result();

        $studentIDs = [];
        foreach ($students as $student) {
            $studentIDs[] = intval($student->student_id);
        }


        $total_amount = 0;
        $coupon_name  = null;

        foreach ($packageIds as $packageId) {
            $course = $this->db->from('courses')->where(['id' => $packageId, 'status' => 'Active'])->get()->row();

            if (!$coupon || $packageId !== $matchedID || $coupon->uses >= $coupon->uses_limit || $coupon->end_date < date('Y-m-d') || ($coupon->already_used && $coupon->use_multiple !== 'yes')) {
                $total_amount += $course->price;
                continue;
            }

            if ($coupon->is_special && !in_array(auth('student')->id, $studentIDs)) {
//                echo "special :" . auth('student')->id;

                $total_amount += $course->price;
                continue;
            }

//            else {
//                echo "wrong user";
//                $total_amount += $course->price;
//                continue;
//            }

            $coupon_name = $coupon->code;
            // increment uses limit
            $this->db->where('id', $coupon->id)->set('uses', 'uses + 1', FALSE)->update('promocodes');

            if ($coupon->discount_type === 'Fixed') {
                $total_amount += $course->price - $coupon->amount;
            } elseif ($coupon->discount_type === 'Percentage') {
                $total_amount += $course->price - ($course->price * $coupon->amount / 100);
            }
        }

        $cart = [
            'student_id'     => $student_id,
            'purchased_at'   => date('Y-m-d H:i:s'),
            'total_items'    => count($post['id']),
            'total_pay'      => $total_amount,
            'invoice_id'     => time(),
            'admin_comments' => $post['customer_comments'],
            'promo_code'     => $coupon_name,
        ];

        $this->db->insert('course_payments', $cart);
        $course_payment_id = $this->db->insert_id();

        $courseBookedData = [];
        foreach ($post['id'] as $courseId => $course) {
            $courseBookedData[] = [
                'course_payment_id' => $course_payment_id,
                'course_id'         => $courseId,
                'course_date_id'    => $post['slot_id'][$courseId],
                'status'            => 'Pending'
            ];
        }
        $this->db->insert_batch('course_booked', $courseBookedData);
        return $course_payment_id;
    }

    // this check for authenticate user
    public function checkPromocode()
    {
        $code = $this->input->get('coupon');

        // get coupon details
        $coupon = $this->db
            ->from('promocodes')
            ->where('code', $code)
            ->where('status', 'Public')
            ->get()->row();

        if ($coupon) {
            // get courses
            $courses = $this->db->select('course_id')
                ->from('promocode_course')
                ->where('promocode_id', $coupon->id)
                ->get()->result();


            $courseIds = [];
            foreach ($courses as $course) {
                $courseIds[] = intval($course->course_id);
            }

            $coupon->courses = $courseIds;

            // get students
            $students = $this->db->select('student_id')
                ->from('promocode_student')
                ->where('promocode_id', $coupon->id)
                ->get()->result();

            $studentIDs = [];
            foreach ($students as $student) {
                $studentIDs[] = intval($student->student_id);
            }

            $coupon->students = $studentIDs;

            // check:: already use or not
            $payment = $this->db->from('course_payments')
                ->where('student_id', getLoginStudentData('student_id'))
                ->where('promo_code', $coupon->code)
                ->get()->row();

            // Check if $coupon is an object before accessing its properties
            $coupon->already_used = (bool)$payment;
        }

        echo json_encode(['promocode' => $coupon]);
    }

    public function guestLogin()
    {
        ajaxAuthorized();
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please enter a valid email</p>');
            exit;
        }

        $student = $this->db->from('students')
            ->where('email', $email)
            ->get()->row();

        if (!$student) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Email!</p>');
            exit;
        }

        if (password_verify($password, $student->password) == false) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Incorrect Password!</p>');
            exit;
        }

        if ($student->status == 'Inactive') {
            echo ajaxRespond('Fail', '<p class="ajax_error">Your account is not active.</p>');
            exit;
        }

        $cookie_data = json_encode([
            'student_id'      => $student->id,
            'student_email'   => $student->email,
            'number_type'     => $student->number_type,
            'student_gmc'     => $student->gmc_number,
            'student_name'    => $student->fname . ' ' . $student->lname,
            'password'        => $password,
            'student_history' => 1
        ]);

        $cookie = [
            'name'   => 'student_data',
            'value'  => base64_encode($cookie_data),
            'expire' => (60 * 60 * 24 * 7),
            'secure' => false,
        ];

        $this->input->set_cookie($cookie);
        $this->session->set_userdata($cookie);

        echo ajaxRespond('OK', '<p class="ajax_success">Login Success</p>');
        exit;
    }
}