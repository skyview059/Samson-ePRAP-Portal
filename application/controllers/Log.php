<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends Frontend_controller {        
    public function index(){
        $log = file_get_contents( APPPATH . '/logs/mail_log.txt');
        echo '<pre>';
        echo $log;
        echo '</pre>';
    }
    
    public function backup_full_db(){       
//        $db      = $this->db->database;
//        $host    = $this->db->hostname;
//        $user    = $this->db->username;
//        $pass    = $this->db->password;
//                
//        $file_name  = $db . date('_Y-m-d_H-i-s');       
//        $dbPath     = dirname( BASEPATH ) . '/DB/' . $file_name;
//        $sql_string = "mysqldump --host={$host} --user={$user} --password={$pass} {$db} > {$dbPath}.sql";
//        system( $sql_string);        
//        echo site_url( "/DB/{$file_name}.sql" );        
    }
    public function alter(){       
        
//        $sql = 'CREATE TABLE `user_students_relation` (
//                `id` int(11) NOT NULL,
//                `user_id` int(11) NOT NULL COMMENT "As Teacher",
//                `student_id` int(11) NOT NULL,
//                `timestamp` datetime DEFAULT NULL,
//                PRIMARY KEY (`id`)
//              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
//        $this->db->query( $sql );

//        echo $this->db->last_query();
        
//                $this->db->query( 'ALTER TABLE `user_students_relation` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;' );
    }
    
    public function delete(){
//        $this->db->query('DELETE FROM `result_details` WHERE id in (  598,599,600,601,733 );');
//        echo $this->db->last_query();
    }
    public function fixing_phone(){
//        $numbers = $this->db->query('SELECT id,whatsapp FROM `students` WHERE phone LIKE "0%"')->result();        
//        dd( $numbers );
//        $data = [];
//        foreach( $numbers as $no ){
//            $data[] = [
//                'id' => $no->id,
//                'phone' => substr_fk($no->phone, 1, 1000),
//            ];
//        }
//        $this->db->update_batch('students',$data,'id');
//        echo $this->db->last_query();
    }
}