<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function wlinks($url)
{        
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return "<a href='{$url}' class='btn btn-link no-padding' target='_blank'>Group Link <i class='fa fa-external-link'></i> </a>";
    } else {
        return ("<span class='text-red'><i class='fa fa-times'></i> Invalid URL </span>");
    }
}

function getDropDownWhatsapp($link_for = 'Mock', $id=0, $label = '--Select or Create New --') {
    $ci = & get_instance();
    
    $ci->db->select('id,title,link_for, IF(status="Draft", "disabled", "") as coloring, status' );        
    $ci->db->where('link_for',  $link_for );
    $wa_links = $ci->db->get('whatsapp_links')->result();
    
    $options = "<option value='0'>{$label}</option>";
    foreach ($wa_links as $link) {
        $options .= "<option value='{$link->id}' {$link->coloring}";
        $options .= ($id == $link->id) ? ' selected' : '';
        $options .= ">{$link->title} - {$link->link_for} - {$link->status}</option>";
    }
    $options .= "<option value='new'> + Create New Group</option>";
    return $options;
}

function waTabs($id, $active_tab)
{
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        'update' => '<i class="fa fa-edit"></i>  Update',
        'log' => '<i class="fa fa-send"></i> Link Sent Log'        
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "whatsapp/{$link}/{$id}\"";
        $html .= ($link == $active_tab ) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function waCountryTabs( $active_tab = '')
{
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        '' => '<i class="fa fa-whatsapp"></i>  Whats App',
        'country' => '<i class="fa fa-flag"></i> Country',        
        'graph' => '<i class="fa fa-adjust"></i> Student in Graph Chart'        
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "whatsapp/{$link}\"";
        $html .= ($link == $active_tab ) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function loadWhatsAppWidget( $link_for ){
    $ci =& get_instance();
    $data['link_for'] = $link_for;
    return $ci->load->view('whatsapp/whatsapp/widget', $data, true );
}

function qty( $int ){
    return ($int > 0) ? $int : '-';
}