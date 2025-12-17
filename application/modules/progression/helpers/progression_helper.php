<?php defined('BASEPATH') OR exit('No direct script access allowed');

function progressionTabs($id, $active_tab) {
    $html = '<ul class="tabsmenu">';
    $tabs = [
            'read'   => 'Details',
            'update' => 'Update',
            'delete' => 'Delete',
        ];

    foreach ($tabs as $link=>$tab) {
            $html .= '<li><a href="' . Backend_URL ."progression/{$link}/{$id}\"";
            $html .= ($link == $active_tab ) ? ' class="active"' : '';
            $html .= '>'. $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function hideRepeatedName($new,$old) {
    return ($new !== $old ) ? $new : '&nbsp; &nbsp; &nbsp; |----';
}

function findUsed($id) {
    $ci =& get_instance();
    return $ci->db->where('progression_id',$id)->count_all_results('student_progressions');
}