<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function personal_dev_planTabs($id, $active_tab)
{
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        'details' => 'Details',
        'update' => 'Update',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "personal_dev_plan/{$link}/{$id}\"";
        $html .= ($link == $active_tab ) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function findUses( $id, $find ){
    return isset($find[$id]) ? $find[$id] : 0;
}