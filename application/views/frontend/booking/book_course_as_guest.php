<?php

if ( $this->input->get('iframe') ){
    require_once 'book_course_new_ui.php';
}else{
    require_once 'book_course_old_ui.php';
}
