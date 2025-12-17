<?php defined('BASEPATH') OR exit('No direct script access allowed');

function viewLogDetails( $string ){
    $array  = json_decode($string, true );        
    print_r("<pre>{$array}</pre>");
}

function phoneLength( $string ){
    return strlen($string);
}