<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 11th March, 2021
 * Imported from OT Batch Mailer
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Process extends MX_Controller{
    
    private $site_title = 'Site Title';
    private $subject = 'Someone try to send mail without subject';
    public $send_from = 'public@mail.com';
    public $from_name = '';
    public $send_to = 'admin@mail.com';
    public $body;
    private $ip;
    private $income;
    
    
    function __construct(){
        parent::__construct();
        $this->ip           = $this->input->ip_address();
        $this->send_from    = getSettingItem('OutgoingEmail');
        $this->send_to      = getSettingItem('IncomingEmail');
        $this->from_name    = getSettingItem('ComName');
        $this->site_title   = $this->from_name;
        $this->income       = getSettingItem('IncomingEmail');
        $this->return_path  = $this->send_to;
    }

    public function index(){
        ob_start();
        @ini_set("output_buffering", "Off");
        @ini_set('implicit_flush', 1);
        @ini_set('zlib.output_compression', 0);
        @ini_set('max_execution_time', 1200);
        header( 'Content-type: text/html; charset=utf-8' );
        
        echo "<title>Mail Sending Report</title>";
        echo "<p>Mail Sending Report...</p><hr/>"; 
                        
        $this->db->where('status','Pending');
//        $this->db->limit(10);
        $queues = $this->db->get('mail_queues')->result();
        
        if(!$queues){
            echo '<p>All Email Has Been Sent.</p>'; 
            echo '<p><a href="'. site_url( Backend_URL . 'mailer' ) .'">Click here to Start Mail</a></p>'; 
            return false;            
        }

        $sl = 0;
        foreach($queues as $m){
            $this->send_to  = $m->send_to;
            $this->subject  = $m->subject;
            $this->body     = $m->body;
            
            ++$sl;
            $send = $this->send();
            if( $send ){
                $this->setAsSent( $m->id );
                echo "<span style='color:green;'>" . sprintf('%03d',$sl) .") OK : {$m->send_to}</span><br/>";
            } else {
                echo "<span style='color:red;'>" . sprintf('%03d',$sl) .") Fail: {$m->send_to}</span><br/>";
            }
            $this->wait($sl,50);
            ob_flush();
            flush();            
        }  
        
        
    }
    private function setAsSent( $id ){
        $this->db->set('status','Sent');
        $this->db->set('sent_at', 'now()', false );
        $this->db->where('id', $id );
        $this->db->update('mail_queues');
    }

    private function wait($row=100,$limit=100){
        if(is_int($row/$limit)){
            sleep(1);
        }
    }
    
    private function send() {        
        $mail = new PHPMailer();
      
//        $mail->isSMTP();        
//        $mail->SMTPDebug = 0;
//        $mail->Host     = 'mail.samsonplab.co.uk';
//        $mail->Port     = 465;
//        $mail->SMTPAuth = true;
//        $mail->Username = 'smtp@samsonplab.co.uk';
//        $mail->Password = '4See^v+~5[D?';
//        
        $mail->setFrom($this->send_from, $this->from_name);
        $mail->addAddress($this->send_to);
        $mail->addReplyTo($this->send_from, $this->from_name);

        $server = $this->input->server('SERVER_NAME');

        $mail->HeaderLine('MIME-Version', '1.0');
        $mail->HeaderLine('X-Mailer', 'PHP/' . phpversion());
        $mail->HeaderLine('Return-Path', $this->return_path);
        $mail->HeaderLine('X-Mailer', "Microsoft Office Outlook, Build 11.0.5510");
        $mail->HeaderLine("X-MimeOLE", "Produced By Microsoft MimeOLE V6.00.2800.1441");
        $mail->HeaderLine('Content-Transfer-encoding', '8bit');
        $mail->HeaderLine('Organization', $server);
        $mail->HeaderLine('Message-ID', "<" . md5(uniqid(time())) . "@{$server}>");
        $mail->HeaderLine('X-MSmail-Priority', 'Normal');
        $mail->HeaderLine('X-Sender', $this->send_from);
        $mail->HeaderLine('X-AntiAbuse', "This is a solicited email for - $server mailing list.");
        $mail->HeaderLine('X-AntiAbuse', "Servername - {$server}");
        $mail->HeaderLine('X-AntiAbuse', $this->send_from);       

        $mail->isHTML(true);                
        $mail->Subject  = $this->subject;
        $mail->Body     = $this->getDefaultLayout($this->body);
        $mail->AltBody  = strip_tags_fk($this->body);

        return ($mail->send()) ?  true :  false;        
    }

    private function getDefaultLayout( $MailBody = '') {
        $template =  $this->load->view('email_templates/layout-active', '', true);
        return str_replace("%MailBody%", $MailBody, $template);
    }
}