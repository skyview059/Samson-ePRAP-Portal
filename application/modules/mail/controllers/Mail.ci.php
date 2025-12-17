<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mail extends MX_Controller
{

    private $site_title = '--Title Not Setup--';
    private $subject    = 'Someone try to send mail without subject';
    public  $send_from  = 'noreply@eprap.com';
    public  $from_name  = '--Flick Media Ltd--';
    public  $send_to    = 'support@flickmedialtd.com';
    public  $return_path = 'webmaster@gmail.com';
    public  $bcc        = false;
    public  $body;
    private $ip;

    public function __construct()
    {
        parent::__construct();
        $this->ip = $this->input->ip_address();
        // Must Be Self Domain Email or SMTP
        // $this->send_from   = getSettingItem('OutgoingEmail');
        $this->send_from   = '';
        $this->send_to     = getSettingItem('IncomingEmail');
        $this->from_name   = getSettingItem('SiteTitle');
        $this->site_title  = $this->from_name;
        $this->return_path = $this->send_to;
    }

    public function index()
    {
        redirect(site_url());
    }

    public function test()
    {
        $this->subject = 'Test Mail || ' . $this->site_title;

        $this->body    = '<p>Lorem Ipsum is simply dummy text of the printing '
            . 'and typesetting industry. Lorem Ipsum has '
            . 'been the industry</p>';
        $this->send_to = 'fftfaisal@gmail.com';
        echo $this->send();
    }

    public function send_query($options)
    {
        $this->subject = $options['subject'];
        $this->send_to = $options['send_to'];
        $this->body    = $options['message'];
        echo $this->send();
    }

    public function send_mail($opt, $view = false)
    {

        $this->send_to = $opt['send_to'];
        if (isset($opt['send_bcc'])) {
            $this->bcc = $opt['send_bcc'];
        }
        $mail = $this->replace_vars($opt['template'], $opt['variables']);

        $this->subject = $mail['subject'];
        $this->body    = $mail['body'];
        if ($view) {
            echo "<h1>{$this->subject}</h1>";
            echo '<hr/>';
            echo $this->body;
            exit;
        }

        $this->log();
        $this->save_in_db($opt['template'], $opt['id'], 0);
        echo $this->send();
    }

    private function replace_vars($use_template, $varables = [])
    {
        $template = $this->useEmailTeamplate($use_template);
        $search   = array_keys($varables);
        $replace  = array_values($varables);
        return [
            'subject' => $this->filterEmailSubject($template->title, $this->from_name),
            'body'    => str_replace($search, $replace, $template->template)
        ];
    }

    public function sendNotifyForMessage($option = array())
    {
        $this->send_to = $option['send_to'];
        $this->subject = $option['subject'];
        $this->body    = $option['body'];
        $this->log();
        $this->save_in_db('notifyForMessage', $option['id'], 1);
        return $this->send();
    }


    public function onMockRequestAction($option = array())
    {

        $this->send_to  = $option['send_to'];
        $template       = $option['template'];
        $templateSender = $this->useEmailTeamplate($template);
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, $option);
        $this->log();
        $this->save_in_db($template, $option['id'], 1);
        return $this->send();
    }

    public function pwd_mail($array = array())
    {
        $email = $array['email'];
        $token = $array['_token'];

        $user            = $this->db->get_where('users', ['email' => $email])->row();
        $this->send_to   = $email;
        $this->from_name = $this->site_title;

        $templateSender = $this->useEmailTeamplate('onRequestForgotPassword');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'      => base_url("auth/reset_password?token={$token}&email={$email}"),
            'fullname' => "{$user->first_name} {$user->last_name}"
        ]);

        $this->log();
        $this->save_in_db('onRequestForgotPassword', $user->id, 1);
        return $this->send();
    }


    public function student_pwd_mail($array = array())
    {
        $email = $array['email'];
        $token = $array['_token'];

        $user            = $this->db->get_where('students', ['email' => $email])->row();
        $this->send_to   = $email;
        $this->send_from = 'smtp@eprap.com';
        $this->from_name = "{$user->fname} {$user->lname}";

        $templateSender = $this->useEmailTeamplate('onRequestForgotPasswordStudent');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'      => base_url("reset-password?token={$token}&email={$email}"),
            'fullname' => $user->fname
        ]);

        $this->log();
        $this->save_in_db('onRequestForgotPasswordStudent', $user->id, 1);
        return $this->send();
    }


    public function onStudentRegistration($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('onStudentRegistration');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'       => site_url('login'),
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
        ]);

        $this->log();
        $this->save_in_db('onStudentRegistration', $data['id'], 0);
        return $this->send();
    }


    public function onStudentRegistrationWelcome($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('onStudentRegistrationWelcome');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'        => site_url('login'),
            'full_name'  => $data['full_name'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'verify_url' => base_url() . 'verify_email?token=' . $data['token'],
        ]);

        $this->log();
        $this->save_in_db('onStudentRegistrationWelcome', $data['id'], 0);
        return $this->send();
    }

    public function onExistingStudentCourseBooked($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('onExistingStudentCourseBooked');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'       => site_url('login'),
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
        ]);

        $this->log();
        $this->save_in_db('onExistingStudentCourseBooked', $data['id'], 0);
        return $this->send();
    }

    public function onCompanyRegistration($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('onCompanyRegistration');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'       => site_url('login'),
            'full_name' => $data['full_name'],
            'username'  => $data['username'],
            'password'  => $data['password'],
        ]);

        $this->log();
        $this->save_in_db('onCompanyRegistration', $data['id'], 0);
        return $this->send();
    }

    private function useEmailTeamplate($slug = '')
    {
        $this->db->select('title,template');
        $this->db->where('slug', $slug);
        $data = $this->db->get('email_templates')->row();
        if ($data) {
            return $data;
        } else {
            return (object)array('template' => 'Empty', 'title' => "Unknown Subject || {$this->from_name}");
        }
    }

    private function getDefaultLayout($MailBody = '')
    {
        $template = $this->load->view('email_templates/layout-active', '', true);
        return str_replace("%MailBody%", $MailBody, $template);
    }

    private function filterEmailSubject($subject = null, $placeholder = '')
    {
        $search  = ['%SiteTitle%', '%subject%'];
        $replace = [$this->site_title, $placeholder];
        return str_replace($search, $replace, $subject);
    }

    private function filterEmailBody($template = null, $placeholders = array(0))
    {
        $placeholders['SiteTitle'] = getSettingItem('SiteTitle');
        $placeholders['signature'] = getSettingItem('FooterSignature');
        //if ($template && count($placeholders)) {
        foreach ($placeholders as $key => $value) {
            $template = str_replace("%{$key}%", $value, $template);
        }
//        }
        return $template;
    }

    private function log()
    {
        $log_path = APPPATH . '/logs/mail_log.txt';
        $send_to  = is_array($this->send_to) ? implode(',', $this->send_to) : $this->send_to;
        $mail_log = date('Y-m-d H:i:s A') . ' | ' . $this->ip . ' | ' . $this->subject . ' | ' . $this->send_from . ' | ' . $send_to . "\r\n";
        file_put_contents($log_path, $mail_log, FILE_APPEND);
    }

    private function save_in_db($mail_type = 'general', $receiver_id = 0, $sender_id = 0)
    {
        $send_to = is_array($this->send_to) ? implode(',', $this->send_to) : $this->send_to;
        $data = [
            'mail_type'   => $mail_type,
            'sender_id'   => $sender_id,
            'receiver_id' => $receiver_id,
            'mail_from'   => $this->send_from,
            'mail_to'     => $send_to,
            'subject'     => $this->subject,
            'body'        => $this->getDefaultLayout($this->body),
            'sent_at'     => date('Y-m-d H:i:s')
        ];
        $this->db->insert('mails', $data);
    }

    private function send()
    {
        $this->load->library('email');
        

        if (PHP_OS !== 'WINNT') {
            // $config['protocol'] = 'sendmail';
            // $config['mailpath'] = '/usr/sbin/sendmail';
            $config['protocol']     = 'smtp';
            $config['smtp_host']    = 'mail.eprap.com';
            $config['smtp_port']    = '465';
            $config['smtp_timeout'] = '5';
            $config['smtp_user']    = 'smtp@eprap.com';
            $config['smtp_pass']    = 'Aw953e337';
            $config['smtp_crypto']  = 'ssl';
        }

        $config['charset']  = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from($this->send_from, $this->from_name);
        $this->email->to($this->send_to);
        $this->email->reply_to($this->send_from, $this->from_name);
        //$this->email->cc('another@another-example.com');

        if ($this->bcc) {
            $this->email->bcc($this->bcc);
        }

        $body = $this->getDefaultLayout($this->body);
        $this->email->subject($this->subject);
        $this->email->message($body);
        $this->email->set_alt_message($this->body);

        if ($this->email->send()) {
            return ajaxRespond('OK', '<p class="ajax_success">Mail sent successfully</p>');
        } else {
            return $this->email->print_debugger();
            // return ajaxRespond('Fail', '<p class="ajax_error">Mail Not Sent! Please try Again</p>');
        }
    }

    public function pwd_reset_notification($data = array())
    {
        $this->send_to = $data['email'];
        $this->subject = 'Your Password has Been Reset by Admin';
        $body          = <<<EOT
    <p>Your Login Details<br/>
    ------------------------------ <br/>
    Login URL: {$data['url']} <br/>
    Username: {$data['email']} <br/>
    Password: {$data['pass']}</p>
EOT;
        $this->body    = $body;

        $this->log();
        $this->save_in_db('onStudentPasswordUpdate', $data['id'], 0);
        return $this->send();
    }

    public function resultPublished($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('resultPublished');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'       => site_url('results'),
            'full_name' => $data['full_name']
        ]);

        $this->log();
        $this->save_in_db('resultPublished', $data['id'], 0);
        return $this->send();
    }

    public function resultUnpublished($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('resultUnpublished');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'       => site_url('login'),
            'full_name' => $data['full_name']
        ]);

        $this->log();
        $this->save_in_db('resultUnpublished', $data['id'], 0);
        return $this->send();
    }

    public function assessorScenarioAssigned($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('assessorScenarioAssigned');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'           => site_url('login'),
            'full_name'     => $data['full_name'],
            'scenario_name' => $data['scenario_name']
        ]);

        $this->log();
        $this->save_in_db('assessorScenarioAssigned', $data['id'], 0);
        return $this->send();
    }

    public function assessorScenarioUnassigned($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('assessorScenarioUnassigned');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'           => site_url('login'),
            'full_name'     => $data['full_name'],
            'scenario_name' => $data['scenario_name']
        ]);

        $this->log();
        $this->save_in_db('assessorScenarioUnassigned', $data['id'], 0);
        return $this->send();
    }

    public function examCancel($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('examCancel');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'url'         => site_url('login'),
            'full_name'   => $data['full_name'],
            'exam_name'   => $data['exam_name'],
            'datetime'    => $data['datetime'],
            'centre_name' => $data['centre_name'],
        ]);

        $this->log();
        $this->save_in_db('examCancel', $data['id'], 0);
        return $this->send();
    }

    public function sendVerificationCode($data = array())
    {
        $this->send_to   = $data['email'];
        $this->from_name = getSettingItem('SiteTitle');

        $this->subject = "Verification Code || {$this->from_name}";
        $this->body    = nl2br_fk($data['body']);

        $this->log();
        $this->save_in_db('sendVerificationCode', $data['id'], 0);
        return $this->send();
    }

    public function send_error_report_to_dev($option = [])
    {
        $this->send_to  = 'flickmedialtd@gmail.com';
        $this->subject  = "Error: {$this->from_name} URL: " . site_url();
        $this->body     = $option['body'];        
        $this->bcc      = false;
        $this->send();
    }

    public function sendStudyPlanMail($data = array())
    {
        $mail_array = [];
        foreach ($data['emails'] as $key => $email) {
            if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $mail_array[] = $email;
            }
        }

        $this->send_to   = $mail_array;
        $this->from_name = getSettingItem('SiteTitle');

        $templateSender = $this->useEmailTeamplate('sendStudyPlanMail');
        $this->subject  = $this->filterEmailSubject($templateSender->title, $this->from_name);

        $this->body = $this->filterEmailBody($templateSender->template, [
            'sender_name' => $data['sender_name'],
            'exam_name'   => $data['exam_name'],
            'subject_name'     => $data['subject_name'],
            'topic_name'       => $data['topic_name'],
            'topic_item_name'  => $data['topic_item_name'],
            'start_date_time'  => $data['start_date_time'],
            'end_date_time'    => $data['end_date_time'],
            'duration'         => $data['duration'],
            'zoom_link'        => $data['zoom_link'],
        ]);

        $this->log();
        $this->save_in_db('sendStudyPlanMail', 0, $data['sender_id']);
        return $this->send();
    }
}