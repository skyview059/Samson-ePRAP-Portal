<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function development_planTabs($id, $active_tab)
{
    $html = '<ul class="tabsmenu">';
    $tabs = [
        'details' => 'Details',
        'update' => 'Update',
        'delete' => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "development_plan/{$link}/{$id}\"";
        $html .= ($link == $active_tab ) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function planQty($id){
    $ci =& get_instance();
    $ci->db->where('student_id', $id );
    return $ci->db->count_all_results('student_development');
}