<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function number_format_fk($number = '', $digit = 2)
{
    return ($number) ? number_format($number, $digit) : 0;
}

function urlencode_fk($pram = '')
{
    return ($pram) ? urlencode($pram) : '';
}

function urldecode_fk($pram = '')
{
    return ($pram) ? urldecode($pram) : '';
}

function json_decode_fk($pram = '', $array = false )
{
    return ($pram) ? json_decode($pram, $array) : [];
}

function strtolower_fk($pram = '')
{
    return strtolower($pram ?: '');
}

function base64_decode_fk($pram = '')
{
    return base64_decode($pram ?: '');
}

function strtotime_fk($pram = '')
{
    return strtotime($pram ?: '');
}

function ucfirst_fk($pram = '')
{
    return ucfirst($pram ?: '');
}

function strip_tags_fk($pram = '')
{
    return strip_tags($pram ?: '');
}

function trim_fk($pram = '')
{
    return trim($pram ?: '');
}
function rtrim_fk($pram = '')
{
    return rtrim($pram ?: '');
}

function nl2br_fk($pram = '')
{
    return nl2br($pram ?: '');
}

function substr_fk($pram = '', $offset=0,$length=1)
{
    if(empty($pram)){
        return '';
    } else {
        return substr($pram, $offset,$length);
    }    
}

function mb_substr_fk($pram = '', $offset=0,$length=1)
{
    return mb_substr($pram ?: '', $offset,$length);
}
