<?php

defined('BASEPATH') or exit('No direct script access allowed');

function bookingTabAsStatus($status = 'Pending')
{
    //$html = '<ul class="tabsmenu">';

    $statuses = [
        'Pending' => 'Payment Pending',
        'Confirmed' => 'Payment Confirmed',
        'Cancelled' => 'Cancelled Bookings',
        '' => 'All',
    ];

    $html = '<ul class="hide_on_print nav nav-tabs admintab">';
    foreach ($statuses as $label => $value) {
        $html .= '<li><a href="' . Backend_URL . "course/booked?status={$label}\"";
        $html .= ($label == $status) ? ' class="active"' : '';
        $html .= '>' . $value . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function practiceBookingTabAsStatus($status = 'Pending')
{
    //$html = '<ul class="tabsmenu">';

    $statuses = [
        'Pending' => 'Payment Pending',
        'Confirmed' => 'Payment Confirmed',
        'Cancelled' => 'Cancelled Bookings',
        '' => 'All',
    ];

    $html = '<ul class="hide_on_print nav nav-tabs admintab">';
    foreach ($statuses as $label => $value) {
        $html .= '<li><a href="' . Backend_URL . "course/booked/practice?status={$label}\"";
        $html .= ($label == $status) ? ' class="active"' : '';
        $html .= '>' . $value . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function courseTabs($id, $active_tab)
{
    $html = '<ul class="tabsmenu">';
    $tabs = [
        'read' => 'Details',
        'update' => 'Update',
        'delete' => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "course/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function getCategoryName($id = 0)
{
    $ci =& get_instance();
    $ci->db->select('name');
    $ci->db->where('id', $id);
    $category = $ci->db->get('course_categories')->row();
    return ($category) ? $category->name : '--';
}

function getDropDownCategory($selected = 0)
{
    $ci =& get_instance();
    $ci->db->select('id,name');
    $categories = $ci->db->get('course_categories')->result();
    $options = '<option value="0">--Select--</option>';
    foreach ($categories as $c) {
        $options .= '<option value="' . $c->id . '" ';
        $options .= ($c->id == $selected) ? 'selected="selected"' : '';
        $options .= ">{$c->name}</option>";
    }
    return $options;
}

function getDropDownCourse($selected = 0)
{
    $ci =& get_instance();
    $ci->db->select('id,name');
    $courses = $ci->db->get('courses')->result();
    $options = '<option value="0">--Select--</option>';
    foreach ($courses as $c) {
        $options .= '<option value="' . $c->id . '" ';
        $options .= ($c->id == $selected) ? 'selected="selected"' : '';
        $options .= ">{$c->name}</option>";
    }
    return $options;
}

function getCourseDateSlotID($seleced_id = 0, $course_id = 0)
{
    $ci =& get_instance();
    $ci->db->select('id, DATE_FORMAT(start_date, "%d %b %Y") as start_date');
    $ci->db->where('course_id', $course_id);
    $ci->db->order_by('id', 'asc');
    $courses = $ci->db->get('course_dates')->result();
    $options = '<option value="0">--Select--</option>';
    foreach ($courses as $c) {
        $options .= '<option value="' . $c->id . '" ';
        $options .= ($c->id == $seleced_id) ? 'selected="selected"' : '';
        $options .= ">{$c->start_date}</option>";
    }
    return $options;
}

function showLabelTxt($text, $label)
{
    if (!empty($text)) {
        return "<b>{$label}</b>: " . nl2br_fk($text);
    }
}

function isPaymentReturned($status, $amount)
{
    if ($status == 'Yes') {
        return '<span class="label label-danger">Refunded: ' . GBP($amount) . '</span>';
    }
}

function smsNotify($status)
{
    if ($status == 'Yes') {
        return '<span class="label label-success">Notifyed via SMS</span>';
    }
}

function intOnly($qty)
{
    return ($qty > 0) ? "<span class='badge'>$qty</span>" : '--';
}

function isActive($status)
{
    return ($status == 'Active')
        ? "<span class='label label-success'>$status</span>"
        : "<span class='label label-danger'>$status</span>";
}

function isConfirmed($status)
{
    switch ($status) {
        case 'Confirmed':
        case 'Published':
        case 'Active':
            $result = "<span class='label label-success'>$status</span>";
            break;
        case 'Cancelled':
        case 'Inactive':
            $result = "<span class='label label-danger'>$status</span>";
            break;
        case 'Pending':
            $result = "<span class='label label-info'>$status</span>";
            break;
        default :
            $result = "<span class='label label-default'>$status</span>";
            break;
    }
    return $result;
}

function getBookingDates($course_id, $seat_limit, $course_date_id = 0)
{
    $ci =& get_instance();

    $sql = "SELECT COUNT(*) FROM `course_booked` WHERE `course_date_id` = `course_dates`.`id`";

    $ci->db->select('id, DATE_FORMAT(`start_date`, "%d/%m/%Y %h:%i %p") AS s_date');
    $ci->db->where('start_date > NOW()');
    $ci->db->select("({$seat_limit} - ({$sql})) as AvailableSeat");
    $ci->db->where('course_id', $course_id);
    $dates = $ci->db->get('course_dates')->result();

    $radios = "<div class='slots'>";
    foreach ($dates as $date) {
        $checked = ($date->id == $course_date_id) ? ' checked="checked"' : '';
        $radios .= '<label style="display:block;">';
        $radios .= " <input type='radio' name='course_date_id' value=\"{$date->id}\" {$checked} /> ";
        $radios .= "{$date->s_date} ({$date->AvailableSeat} seat available)";
        $radios .= "</label>";
    }
    $radios .= '</div>';
    return $radios;
}

function gatewaysRadio($name = 'gateway')
{
    $gateways = [
        'PayPal' => 'PayPal',
        'Stripe' => 'Stripe/Card',
        'WorldPay' => 'WorldPay',
        'Cash' => 'Cash',
        'Bank' => 'Bank',
        'Free' => 'Free'
    ];
    $radio = '';
    foreach ($gateways as $key => $value) {
        $radio .= '<label>';
        $radio .= '<input type="radio" name="' . $name . '" id="' . $key . '" ';
        $radio .= 'value="' . $key . '" />&nbsp;' . $value;
        $radio .= '&nbsp;&nbsp;&nbsp;</label>';
    }

    return $radio;
}

function getLocalCurrencyRate($amount, $source='NGN', $destination='USD')
{
    $data =& get_instance();

    // API endpoint
    $url = 'https://api.flutterwave.com/v3/transfers/rates';

    // Request parameters
    $params = array(
        'amount' => $amount,
        'destination_currency' => $destination,
        'source_currency' => $source
    );

    // Headers
    $headers = array(
        // 'Authorization: Bearer FLWSECK_TEST-da56c4922797ba69d6ef152af2b7bd6f-X'
        'Authorization: Bearer ' . $data->config->item('fw_secret_key')
    );

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }
    curl_close($ch);

    $json = json_decode($response);

    if($json->status === 'success'){
        return $json->data->source->amount;
    } else {
        return 0;
    }
}