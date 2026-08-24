<?php

defined('BASEPATH') or exit('No direct script access allowed');

function countConversation($id)
{
    $ci = &get_instance();
    $ci->db->where('parent_id', $id);
    return $ci->db->count_all_results('messages') + 1;
}

function lastReplyStatus($id)
{
    $ci = &get_instance();
    $ci->db->select('status');
    $ci->db->where('parent_id', $id);
    $reply = $ci->db->get('messages')->row();
    return ($reply) ? $reply->status : 'Seen';
}