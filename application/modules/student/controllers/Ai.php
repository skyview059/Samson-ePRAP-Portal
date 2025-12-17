<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-18
 */

class Ai extends Admin_controller
{
    public $CI;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');        
    }

    public function index()
    {
                
        $students   = $this->search_student();        
        dd( $students );        
    }   
    
    function search_student()
    {
        $this->db->select('id,number_type,verified,occupation,photo,purpose_of_registration');        
        $this->db->where('status !=', 'Archive');                 
        $this->db->limit(25);
        return $this->db->get('students')->result();
    }
    
    function test(){                           
        $img1 = 'uploads/student/2020/08/MTU5NzM1NDU3NQ.jpg'; // Boy
        $img2 = 'uploads/student/2020/08/MTU5NzkzNDY2MQ.jpg'; // Car
        $img3 = 'vendor/mauricesvay/php-facedetection/lena512color.jpg'; // Model 
        
        $detector = new svay\FaceDetector('detection.dat');
        $detector->faceDetect( $img3 );
//        $detector->cropFaceToJpeg(); // Display in Browser
//        $detector->cropFaceToJpeg( 'kanny.jpg'); // Save in Dir 
        $detector->toJpeg();
//        $detector->getFace();
        //dd( $detector->toJson() );
    }
}