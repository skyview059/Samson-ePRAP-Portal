<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Wa {
    public $ci;
    static function getLinks($id = 0, $link_type = 'Whatsapp'){
        $ci =& get_instance();
        $ci->db->select('l.id, l.title, l.link');
        $ci->db->from('whatsapp_links as l');
        $ci->db->where('l.status', 'Publish');
        $ci->db->where('l.link_type', $link_type);
        $links = $ci->db->get()->result();

        $options = '';
        foreach ($links as $link) {
            $options .= '<option value="' . $link->id . '" ';
            $options .= ($link->id == $id ) ? 'selected="selected"' : '';
            $options .= ">{$link->title}</option>";
        }
        return $options;
    }

    static function getMocks( $id = 0){
        $ci =& get_instance();
        $ci->db->select('e.id as m_id, e.name as m_name');
        $ci->db->select('es.id as s_id,es.datetime as s_time, label as s_label');
        $ci->db->from('exam_schedules as es');
        $ci->db->join('exams as e', 'e.id=es.exam_id','LEFT');
        $ci->db->where('DATE(datetime) >=', date('Y-m-d'), false );
        $ci->db->where('es.status', 'Unpublished' );
        $mocks = $ci->db->get()->result();

        $g_mocks = [];
        foreach($mocks as $m ){
            $g_mocks[$m->m_id]['id'] = $m->m_id;
            $g_mocks[$m->m_id]['name'] = $m->m_name;
            $g_mocks[$m->m_id]['items'][] = [
               's_id' => $m->s_id,
               's_time' => globalDateFormat($m->s_time) .' '. $m->s_label
            ];
        }


        $options = '';
        foreach ($g_mocks as $mock) {
            $options .= "<optgroup label=\"{$mock['name']}\">";

                foreach ($mock['items'] as $item ) {
                    $options .= '<option value="' . $item['s_id'] . '" ';
                    $options .= ($item['s_id'] == $id ) ? 'selected="selected"' : '';
                    $options .= ">{$item['s_time']}</option>";
                }

            $options .= '</optgroup>';
        }

        return $options;
    }

    
    static function getCourses( $id =0 ){
        
        $ci =& get_instance();
        $ci->db->select('c.id as c_id, c.name as c_name');
        $ci->db->select('cc.id as cat_id,cc.name as cat_name');        
        $ci->db->from('courses as c');                
        $ci->db->join('course_categories as cc', 'cc.id=c.category_id','LEFT');                
        $ci->db->where('status', 'Active' );        
        $courses = $ci->db->get()->result();        

        $cats = [];
        foreach($courses as $c ){
            $cats[$c->cat_id]['id'] = $c->cat_id;
            $cats[$c->cat_id]['name'] = $c->cat_name;
            $cats[$c->cat_id]['items'][] = [
               'c_id' => $c->c_id, 
               'c_name' => $c->c_name             
            ];
        }
                
        $options = '';
        foreach ($cats as $course ) {
            $options .= "<optgroup label=\"{$course['name']}\">";
    
                foreach ($course['items'] as $item ) {
                    $options .= '<option value="' . $item['c_id'] . '" ';
                    $options .= ($item['c_id'] == $id ) ? 'selected="selected"' : '';
                    $options .= ">{$item['c_name']}</option>";
                }
                
            $options .= '</optgroup>';
        }
        
        return $options;
    }
        
    static function getPractices( $id =0 ){
        
        $ci =& get_instance();
        $ci->db->select('p.id as p_id, p.name as p_name');
        $ci->db->select('ps.id as ps_id, ps.label as ps_label');        
        $ci->db->from('practices as p');                
        $ci->db->join('practice_schedules as ps', 'p.id=ps.practice_id','LEFT');                
        $ci->db->where('ps.status', 'Published' );        
        $practices = $ci->db->get()->result();
        
        $cats = [];
        foreach($practices as $c ){
            $cats[$c->p_id]['id'] = $c->p_id;
            $cats[$c->p_id]['name'] = $c->p_name;
            $cats[$c->p_id]['items'][] = [
               'ps_id' => $c->ps_id, 
               'ps_label' => $c->ps_label             
            ];
        }                
        
                
        $options = '';
        foreach ($cats as $course ) {
            $options .= "<optgroup label=\"{$course['name']}\">";
    
                foreach ($course['items'] as $item ) {
                    $options .= '<option value="' . $item['ps_id'] . '" ';
                    $options .= ($item['ps_id'] == $id ) ? 'selected="selected"' : '';
                    $options .= ">{$item['ps_label']}</option>";
                }
                
            $options .= '</optgroup>';
        }
        
        return $options;
    }
    
    static function getCountries($country_id = 0, $label = '--Select Country--' ) {
        $ci = & get_instance();
        $countries = $ci->db->get_where('countries', ['type' => '1', 'parent_id' => '0'])->result();
        $options = "<option value=\"0\">{$label}</option>";
        foreach ($countries as $country) {
            $options .= '<option value="' . $country->id . '" ';
            $options .= ($country->id == $country_id ) ? 'selected="selected"' : '';
            $options .= '>' . $country->name . '</option>';
        }
        return $options;
    }  
    
    static function hasWhatsApp( $RelID = 0, $link_for = 'Course' ){
        $ci =& get_instance();        
        $ci->db->select('wl.link');     
        $ci->db->from('whatsapp_links as wl');
        $ci->db->join('whatsapp_link_relations as wlr', 'wl.id=wlr.wa_link_id', 'LEFT');
        $ci->db->where('wl.link_for', $link_for );
        $ci->db->where('wlr.rel_id',  $RelID );
        $data =  $ci->db->get()->row();        
        if($data){
            return "<b><a href='{$data->link}' target=\"_blank\"><i class='fa fa-whatsapp text-green text-bold'></i> Open</a></b>";
        } else {
            return "<i class='fa fa-whatsapp text-red'></i>";
        }
    }
}
