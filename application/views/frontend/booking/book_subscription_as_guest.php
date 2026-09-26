<?php

if ( $this->input->get('iframe') ){
    require_once 'book_subscription_new_ui.php';
}else{
    require_once 'book_subscription_old_ui.php';
}
