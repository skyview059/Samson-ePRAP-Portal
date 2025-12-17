<?php

defined('BASEPATH') or exit('No direct script access allowed');

function getShortContent($long_text = '', $show = 100)
{
    $filtered_text = strip_tags_fk($long_text);
    if ($show < strlen($filtered_text)) {
        return substr_fk($filtered_text, 0, $show) . '...';
    } else {
        return $filtered_text;
    }
}

function dd($data, $file = array())
{
    echo '<pre>';
    print_r($data);
    if ($file) {
        print_r($file);
    }
    echo '</pre>';
    exit;
}

function pp($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function getLoginUserData($key = '')
{
    //key: user_id, user_mail, role_id, name, photo
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');
    $global = json_decode(base64_decode_fk($data->input->cookie("{$prefix}login_data", false)));
    return isset($global->$key) ? $global->$key : null;
}

function getLoginStudentData($key = '')
{
    //key: student_id, student_email, student_gmc, student_name, student_history
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');
    $global = json_decode(base64_decode_fk($data->input->cookie("{$prefix}student_data", false)));

    return isset($global->$key) ? $global->$key : null;
}

function showStudentID($id = false)
{
    if ($id) {
        return sprintf('%05d', $id);
    } else {
        return sprintf('%05d', getLoginStudentData('student_id'));
    }
}

function auth($guard = null)
{
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');

    $student = json_decode(base64_decode_fk($data->input->cookie("{$prefix}student_data", false)));
    $user    = json_decode(base64_decode_fk($data->input->cookie("{$prefix}login_data", false)));

    if (is_null($guard) && $user) {
        return $data->db->select("CONCAT(first_name, ' ', last_name) as full_name, users.*")
            ->from('users')
            ->where('id', $user->user_id)
            ->get()->row();
    }

    if ($guard === 'student' && $student) {
        return $data->db->select("CONCAT(fname, ' ', IF(mname IS NULL or mname = '', '', CONCAT(mname, ' ')), lname) as full_name, students.*")
            ->from('students')
            ->where('id', $student->student_id)
            ->get()->row();
    }

//    throw new InvalidArgumentException('Use student guard if you are a student.');
}

function getStudentExamData($key = '')
{
    //key: student_id, user_mail, role_id, name, photo
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');
    $global = json_decode(base64_decode_fk($data->input->cookie("{$prefix}exam_data", false)));
    return isset($global->$key) ? $global->$key : null;
}

function getStudentIdentifyData($student_id, $key = '')
{
    //key: student_id, student_email, student_gmc, student_namestudent_name, student_history
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');
    $global = json_decode(base64_decode_fk($data->input->cookie("{$prefix}{$student_id}_identify", false)));
    return isset($global->$key) ? $global->$key : null;
}

function getUserIdentifyData($user_id, $key = '')
{
    //key: student_id, student_email, student_gmc, student_namestudent_name, student_history
    $data   =& get_instance();
    $prefix = $data->config->item('cookie_prefix');
    $global = json_decode(base64_decode_fk($data->input->cookie("{$prefix}{$user_id}_user_identify", false)));
    return isset($global->$key) ? $global->$key : null;
}

function numericDropDown($i = 0, $end = 12, $incr = 1, $selected = 0)
{
    $option = '';
    for ($i; $i <= $end; $i += $incr) {
        $option .= '<option value="' . sprintf('%02d', $i) . '"';
        $option .= ($selected == $i) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function numericDropDown2($i = 0, $end = 12, $incr = 1, $selected = 0)
{
    $option = '';
    for ($i; $i <= $end; $i += $incr) {
        $option .= '<option value="' . sprintf('%02d', $i) . '"';
        $option .= ($selected == sprintf('%02d', $i)) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function htmlRadio($name = 'input_radio', $selected = '', $array = ['Male' => 'Male', 'Female' => 'Female'], $attr = '')
{
    $radio = '';
    $id    = 0;
    // $radio .= '<div style="padding-top:8px;">';
    if (count($array)) {
        foreach ($array as $key => $value) {
            $id++;
            $radio .= '<label>';
            $radio .= '<input type="radio" name="' . $name . '" id="' . $name . '_' . $id . '" ' . $attr;
            $radio .= (trim_fk($selected) == $key) ? ' checked ' : '';
            $radio .= 'value="' . $key . '" />&nbsp;' . $value;
            $radio .= '&nbsp;&nbsp;&nbsp;</label>';
        }
    }
    // $radio .= '</div>';
    return $radio;
}

function selectOptions($selected = '', $array = null)
{
    $options = '';
    if (count($array)) {
        foreach ($array as $key => $value) {
            $options .= '<option value="' . $key . '" ';
            $options .= ($key == $selected) ? ' selected="selected"' : '';
            $options .= ">{$value}</option>";
        }
    }
    return $options;
}

function load_module_asset($module = null, $type = 'css', $script = null)
{
    $file = ($type == 'css') ? 'style.css.php' : 'script.js.php';
    if ($script) {
        $file = $script;
    }

    $path = APPPATH . '/modules/' . $module . '/assets/' . $file;
    if ($module && file_exists($path)) {
        include($path);
    }
}

function startPointOfPagination($limit = 25, $page = 0)
{
    return ($page) ? ($page - 1) * $limit : 0;
}

function getPaginator($total_row = 100, $currentPage = 1, $targetpath = '#&p', $limit = 25)
{
    $stages = 2;
    $page   = intval($currentPage);
    $start  = ($page) ? ($page - 1) * $limit : 0;

    // Initial page num setup
    $page = ($page == 0) ? 1 : $page;
    $prev = $page - 1;
    $next = $page + 1;

    $lastpage   = ceil($total_row / $limit);
    $LastPagem1 = $lastpage - 1;
    $paginate   = '';

    if ($lastpage > 1) {
        $paginate .= '<div class="row">';
        $paginate .= '<div class="col-md-12">';
        $paginate .= '<ul class="pagination low-margin no-margin">';
        $paginate .= '<li class="disabled"><a>Total: ' . $total_row . '</a></li>';

        // Previous
        $paginate .= ($page > 1) ? "<li><a href='$targetpath=$prev'>&lt; Pre</a></li>" : "<li class='disabled'><a> &lt; Pre</a></li>";
        // Pages
        if ($lastpage < 7 + ($stages * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
            }
        } elseif ($lastpage > 5 + ($stages * 2)) {
            // Beginning only hide later pages
            if ($page < 1 + ($stages * 2)) {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }
                $paginate .= "<li class='disabled'><a>...</a></li>";
                $paginate .= "<li><a href='$targetpath=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpath=$lastpage'>$lastpage</a></li>";
            } // Middle hide some front and some back
            elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                $paginate .= "<li><a href='$targetpath=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpath=2'>2</a></li>";
                $paginate .= "<li><a>...</a></li>";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }

                $paginate .= "<li><a>...</a></li>";
                $paginate .= "<li><a href='$targetpath=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpath=$lastpage'>$lastpage</a><li>";
            } else {

                // End only hide early pages
                $paginate .= "<li><a href='$targetpath=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpath=2'>2</a></li>";
                $paginate .= "<li><a>...</a></li>";

                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                    $paginate .= ($counter == $page) ? "<li class='active'><a>$counter</a></li>" : "<li><a href='$targetpath=$counter'>$counter</a></li>";
                }
            }
        }
        // Next

        $paginate .= ($page < $counter - 1) ? "<li><a href='$targetpath=$next'>Next &gt;</a></li>" : "<li class='disabled'><a>Next &gt;</a></li>";

        $paginate .= "</ul>";
        $paginate .= "<div class='clearfix'></div>";
        $paginate .= "</div>";
        $paginate .= "</div>";
    }
    return $paginate;
}

function ageCalculator($date = null)
{
    if ($date) {
        $tz  = new DateTimeZone('Europe/London');
        $age = DateTime::createFromFormat('Y-m-d', $date, $tz)->diff(new DateTime('now', $tz))->y;
        return $age . ' years';
    } else {
        return 'Unknown';
    }
}

function sinceCalculator($date = null)
{
    if ($date) {
        $date = date('Y-m-d', strtotime($date));
        $tz   = new DateTimeZone('Europe/London');
        $age  = DateTime::createFromFormat('Y-m-d', $date, $tz)
            ->diff(new DateTime('now', $tz));

        $result = '';
        $result .= ($age->y) ? $age->y . 'y ' : '';
        $result .= ($age->m) ? $age->m . 'm ' : '';
        $result .= ($age->d) ? $age->d . 'd ' : '';
        $result .= ($age->h) ? $age->h . 'h ' : '';
        return $result;
    } else {
        return 'Unknown';
    }
}

function password_encription($string = '')
{
    return password_hash($string, PASSWORD_BCRYPT);
}

function get_admin_email()
{
    return getSettingItem('OutgoingEmail');
}

function getSettingItem($setting_key = null)
{
    $ci = &get_instance();
    $ci->db->cache_on();
    $setting = $ci->db->get_where('settings', ['label' => $setting_key])->row();
    $ci->db->cache_off();
    return isset($setting->value) ? $setting->value : false;
}

function getRoleName($role_id = 0)
{
    $ci   = &get_instance();
    $role = $ci->db
        ->select('role_name')
        ->get_where('roles', ['id' => $role_id])
        ->row();
    return ($role) ? $role->role_name : 'unknown';
}

function getCountryName($country_id = 0)
{
    $ci      = &get_instance();
    $country = $ci->db
        ->select('name')
        ->get_where('countries', ['id' => $country_id])
        ->row();
    if ($country) {
        return $country->name;
    } else {
        return 'Unknown';
    }
}

function getEthnicityName($ethnicity_id = 0)
{
    $ci        = &get_instance();
    $ethnicity = $ci->db
        ->select('category,name')
        ->get_where('ethnicities', ['id' => $ethnicity_id])
        ->row();
    if ($ethnicity) {
        return "{$ethnicity->category} > {$ethnicity->name}";
    } else {
        return 'Unknown';
    }
}

function getDropDownCountries($country_id = 0, $label = '--Select Country--')
{
    $ci        = &get_instance();
    $countries = $ci->db->get_where('countries', ['type' => '1', 'parent_id' => '0'])->result();
//    $options = "<option value=\"0\">{$label}</option>";
    $options = "<option disabled selected>{$label}</option>";
    foreach ($countries as $country) {
        $options .= '<option value="' . $country->id . '" ';
        $options .= ($country->id == $country_id) ? 'selected="selected"' : '';
        $options .= '>' . $country->name . '</option>';
    }
    return $options;
}

function getSpecialtiesCheckbox($string = '', $name = 'specialty_ids')
{
    $ci = &get_instance();


    $selected = is_array($string) ? $string : explode(',', $string);
    $ci->db->select('id,name');
    $ci->db->order_by('name', 'ASC');
    $results = $ci->db->get('student_job_specialties')->result();

    $options = '<div class="row">';

    foreach ($results as $result) {
        $options .= '<div class="col-md-6">';
        $options .= '<label>';
        $options .= "<input type='checkbox' name='{$name}[{$result->id}]' value='{$result->id}'";
        $options .= in_array($result->id, $selected) ? ' checked="checked"' : '';
        $options .= '> &nbsp;' . $result->name . '</label>';
        $options .= '</div>';
    }
    $options .= '</div>';
    return $options;
}

function getSpecialtyRow($selected = array())
{
    $ci = &get_instance();

    $name = 'specialties';
    $s_id = 1;

    $select = array();
    foreach ($selected as $se) {
        $select[$se['specialty_id']] = $se['experience'];
    }

    $ci->db->select('id,name');
    $ci->db->order_by('name', 'ASC');
    $results = $ci->db->get('student_job_specialties')->result();

    $options = '<table class="table table-striped table-bordered">';

    foreach ($results as $r) {
        if (array_key_exists($r->id, $select)) {
            $checked = ' checked="checked"';
            $value   = $select[$r->id];
            $class   = '';
        } else {
            $checked = '';
            $value   = 0;
            $class   = 'hidden';
        }

        $options .= '<tr>';
        $options .= '<td><label>';
        $options .= "<input name='{$name}[{$r->id}][student_id]' value='{$s_id}' type='hidden'/>";
        $options .= "<input type='checkbox' class='tickbox' name='{$name}[{$r->id}][specialty_id]' value='{$r->id}' ";
        $options .= $checked;
        $options .= '> &nbsp;' . $r->name . '</label>';
        $options .= "<p id='range_{$r->id}' class='{$class}'>
                Experience <span id='exp_txt_{$r->id}'>{$value} Months</span>
                <input id='exp_{$r->id}' oninput='makeTextExp({$r->id});' name='{$name}[{$r->id}][experience] min='0' step='1' value='{$value}' max='120'  type='range'></p></td>";
        $options .= '</tr>';
    }
    $options .= '</table>';
    return $options;
}

function getSpecialtyExperienceName($selected = 0)
{
    $array = [
        1   => '1 month',
        2   => '2 months',
        3   => '3 months',
        4   => '4 months',
        5   => '5 months',
        6   => '6 months',
        11  => 'less than 1 year',
        23  => 'less than 2 year',
        35  => 'less than 3 year',
        47  => 'less than 4 year',
        59  => 'less than 5 year',
        60  => 'more than 5 years',
        120 => 'more than 10 years'
    ];
    if ($selected) {
        return $array[$selected];
    } else {
        return false;
    }
}

function getSpecialtyNameByIds($string = '')
{
    $ci  = &get_instance();
    $ids = explode(',', $string);

    $ci->db->select('name');
    $ci->db->order_by('name', 'ASC');
    $ci->db->where_in('id', $ids);
    $results = $ci->db->get('student_job_specialties')->result();
    $html    = '';
    foreach ($results as $result) {
        $html .= $result->name . ', ';
    }
    return rtrim_fk($html, ', ');
}

function getSpecialtyNameByStudentID($student_id = 0)
{
    $ci = &get_instance();

    $ci->db->select('r.id, s.name, r.experience');
    $ci->db->order_by('s.name', 'ASC');
    $ci->db->where('r.student_id', $student_id);
    $ci->db->from('student_job_specialty_rel as r');
    $ci->db->join('student_job_specialties as s', 'r.specialty_id = s.id', 'LEFT');
    $results = $ci->db->get()->result();

    $html = '';
    foreach ($results as $result) {
        $html .= $result->name . ' (<b>' . $result->experience . '</b>), ';
    }
    return rtrim_fk($html, ', ');
}

function getDropDownEthnicitys($ethnicity_id = 0)
{
    $ci      =& get_instance();
    $restuls = $ci->db->get('ethnicities')->result();

    $ethnicities = [];
    foreach ($restuls as $row) {
        $ethnicities[$row->category][] = [
            'id'   => $row->id,
            'name' => $row->name,
        ];
    }
    $options = '';
    foreach ($ethnicities as $category => $items) {
        $options .= "<optgroup label=\"{$category}\">";
        foreach ($items as $item) {
            $options .= '<option value="' . $item['id'] . '" ';
            $options .= ($item['id'] == $ethnicity_id) ? 'selected="selected"' : '';
            $options .= '>' . $item['name'] . '</option>';
        }
        $options .= '</optgroup>';
    }
    return $options;
}

function bdDateFormat($data = '0000-00-00')
{
    return ($data == '0000-00-00') ? 'Unknown' : date('d/m/y', strtotime($data));
}

function isCheck($checked = 0, $match = 1)
{
    $checked = ($checked);
    return ($checked == $match) ? 'checked="checked"' : '';
}

function getCurrency($selected = '&pound')
{
    $codes = [
        '&pound'  => "&pound; GBP",
        '&dollar' => "&dollar; USD",
        '&nira'   => "&#x20A6; NGN"
    ];
    $row   = '<select name="data[Setting][Currency]" class="form-control">';
    foreach ($codes as $key => $option) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $option . '</option>';
    }
    $row .= '</select>';
    return $row;
}

function globalDateTimeFormat($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == '') {
        return '--';
    }
    return date('d/m/Y h:i A', strtotime($datetime));
}

function dayLeftOfExam($datetime = '0000-00-00 00:00:00')
{
    if (in_array($datetime, [NULL, '', '0000-00-00', '0000-00-00 00:00:00'])) {
        return '--';
    }
    $future     = strtotime($datetime); //Future date.
    $timefromdb = time();               //source time
    $timeleft   = $future - $timefromdb;
    $days       = round((($timeleft / 24) / 60) / 60);
    if ($days >= 0 and $days <= 2) {
        return "<b class='red'>{$days}  Day(s)</b>";
    } elseif ($days >= 3 and $days <= 5) {
        return "<b class='yellow'>{$days} Days</b>";
    } elseif ($days >= 6) {
        return "<b class='green'>{$days}  Days</b>";
    } else {
        return '--';
    }
}

function globalDateFormat($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == null) {
        return '--';
    }
    return date('dS M, Y', strtotime($datetime));
}

function globalTimeOnly($datetime = '0000-00-00 00:00:00')
{
    if ($datetime == '0000-00-00 00:00:00' or $datetime == '0000-00-00' or $datetime == null) {
        return 'Unknown';
    }
    return date('h:i A', strtotime($datetime));
}

function returnJSON($array = [])
{
    return json_encode($array);
}

function ajaxRespond($status = 'FAIL', $msg = 'Fail! Something went wrong')
{
    return returnJSON(['Status' => strtoupper($status), 'Msg' => $msg]);
}

function ajaxAuthorized()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    } else {
        $url  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
        $html = '';
        $html .= '<center>';
        $html .= '<h1 style="color:red;">Access Denied !</h1>';
        $html .= '<hr>';
        $html .= '<p>It seems that you might come here via an unauthorised way</p>';
        $html .= '<p style="color:red;">It may happen for incomplete page loading. Please go back, refresh and try again.</p>';
        $html .= "<p><a href=\"{$url}\">Go Back</a></p>";
        $html .= '</center>';
        die($html);
    }
}

function globalCurrencyFormat($amount = 0, $sign = '&pound;')
{
    if ($sign != '&pound;') {
        $prefix = getSettingItem('Currency');
    } else {
        $prefix = $sign;
    }

    if (is_null($amount) or empty($amount)) {
        return 0;
    } else {
        return $prefix . number_format_fk($amount, 2);
    }
}

function bdContactNumber($contact = null)
{
    if ($contact && strlen($contact) == 11) {
        return substr_fk($contact, 0, 5) . '-' . substr_fk($contact, 5, 3) . '-' . substr_fk($contact, 8, 3);
    } else {
        return $contact;
    }
}

function getPaginatorLimiter($selected = 100)
{
    $range  = [100, 500, 1000, 2000, 5000];
    $option = '';
    foreach ($range as $limit) {
        $option .= '<option';
        $option .= ($selected == $limit) ? ' selected' : '';
        $option .= '>' . $limit . '</option>';
    }
    return $option;
}

function getUserNameByID($user_id = 0)
{
    $username = '<label class="label label-danger">User Deleted</label>';
    if ($user_id) {
        $ci   = &get_instance();
        $user = $ci->db->get_where('users', ['id' => $user_id])->row();
        if ($user) {
            $username = $user->first_name . ' ' . $user->last_name;
        }
    }
    return $username;
}

function getUserNameEmailByID($user_id = 0)
{
    $username = '<label class="label label-danger">User Deleted</label>';
    if ($user_id) {
        $ci   = &get_instance();
        $user = $ci->db->get_where('users', ['id' => $user_id])->row();
        if ($user) {
            $username = $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')';
        }
    }
    return $username;
}

function statusLevel($status = null)
{
    if ($status == 'Publish') {
        return '<span class="label label-success">Publish</span>';
    } elseif ($status == 'Draft') {
        echo '<span class="label label-warning">Draft</span>';
    }
}

function two_date_diff($form, $today)
{
    $date1 = new DateTime($form);
    $date2 = new DateTime($today);
    $diff  = $date1->diff($date2);

    if ($diff->d == 0) {
        return 'Same Day';
    } else {
        return $diff->d . ' Day ago';
    }
}

function numeric_dropdown($i = 0, $end = 12, $incr = 1, $selected = 0)
{
    $option = '';
    for ($i; $i <= $end; $i += $incr) {
        $option .= '<option value="' . $i . '"';
        $option .= ($selected == $i) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function numeric_dropdown_2($i = 0, $end = 12, $incr = 1, $selected = 0)
{
    $option = '';
    for ($i; $i <= $end; $i += $incr) {
        $option .= '<option value="' . sprintf('%02d', $i) . '"';
        $option .= ($selected == $i) ? ' selected' : '';
        $option .= '>' . sprintf('%02d', $i) . '</option>';
    }
    return $option;
}

function encode($string, $key = 'my_encript_key')
{
    $key    = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j      = 0;
    $hash   = null;
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr_fk($string, $i, 1));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr_fk($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return $hash;
}

function decode($string, $key = 'my_encript_key')
{
    $key    = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j      = null;
    $hash   = null;
    for ($i = 0; $i < $strLen; $i += 2) {
        $ordStr = hexdec(base_convert(strrev(substr_fk($string, $i, 2)), 36, 16));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr_fk($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

function getMyEncodeKey($string, $salt, $key)
{
    $deocode   = decode($string, $salt);
    $json_data = json_decode($deocode);
    if (isset($json_data->$key)) {
        return $json_data->$key;
    } else {
        return false;
    }
}

function removeImage($photo = null)
{
    $filename = dirname(APPPATH) . '/' . $photo;
    if ($photo && file_exists($filename)) {
        unlink($filename);
    }
    return TRUE;
}

function removeFile($file = null)
{
    $filename = dirname(APPPATH) . '/' . $file;
    if ($file && file_exists($filename)) {
        unlink($filename);
    }
    return TRUE;
}

function getLocationList($selected = 0, $type = 0, $parent_id = 0)
{
    $ci = &get_instance();

    $ci->db->where('type', $type);

    if ($parent_id) {
        $ci->db->where('parent_id', $parent_id);
    }

    $results = $ci->db->get('countries')->result();

    $options = '';
    foreach ($results as $row) {
        $options .= '<option value="' . $row->id . '" ';
        $options .= ($row->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $row->name . '</option>';
    }
    return $options;
}

function uploadPhoto($FILE = array(), $path = '', $name = '')
{
    $handle = new \Verot\Upload\Upload($FILE);
    if ($handle->uploaded) {
        $handle->file_new_name_body   = $name;
        $handle->image_resize         = true;
        $handle->image_x              = 350;
        $handle->image_ratio_y        = true;
        $handle->file_force_extension = true;
        $handle->file_new_name_ext    = 'jpg';
        $handle->process($path);
        if ($handle->processed) {
            return stripslashes($handle->file_dst_pathname);
        }
    }
    return '';
}

function build_pagination_url($link = 'listing', $page = 'page', $ext = false)
{
    $array = $_GET;
    $url   = $link . '?';

    unset($array[$page]);
    unset($array['_']);

    if ($array) {
        $url .= \http_build_query($array);
    }
    if ($ext) {
        $url .= "&{$page}";
    }
    return $url;
}

function more_text($text, $id = 1, $limit = 200)
{
    $html      = '';
    $plain_txt = strip_tags_fk($text);
    $leanth    = strlen($plain_txt);
    $short_txt = substr_fk($plain_txt, 0, $limit);

    if ($leanth >= $limit) {
        $html .= '<span id="less' . $id . '">';
        $html .= str_replace("\n", "<br/>", $short_txt);
        $html .= '....&nbsp;<span style="color:#f60; cursor:pointer;" onClick="view_full_text(\'' . $id . '\');">more&rarr;</span>';
        $html .= '</span>';

        $html .= '<span id="more' . $id . '" style="display:none">';
        $html .= $text;
        $html .= '&nbsp;<span style="color:#f60; cursor:pointer;" onClick="view_full_text(\'' . $id . '\');">&larr;Less</span>';
        $html .= '</span>';
    } else {
        return $html .= $text;
    }
    return $html;

//    js need
//    function view_full_text(id){	
//        $('#less'+id).toggle();
//        $('#more'+id).toggle();
//    }
}

function timePassed($date_time = '0000-00-00 00:00:00')
{
    $return = '';

    if ($date_time == '0000-00-00 00:00:00') {
        $return = '';
    } else {

        $timestamp    = (int)strtotime($date_time);
        $current_time = time();
        $diff         = $current_time - $timestamp;

        $intervals = array(
            'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60
        );
        if ($diff == 0) {
            $return = 'just now';
        }
        if ($diff < 60) {
            $return = $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
        }
        if ($diff >= 60 && $diff < $intervals['hour']) {
            $diff   = floor($diff / $intervals['minute']);
            $return = $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
        }
        if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
            $diff   = floor($diff / $intervals['hour']);
            $return = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
        }
        if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
            $diff   = floor($diff / $intervals['day']);
            $return = $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
        }
        if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
            $diff   = floor($diff / $intervals['week']);
            $return = $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
        }
        if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
            $diff   = floor($diff / $intervals['month']);
            $return = $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
        }
        if ($diff >= $intervals['year']) {
            $diff   = floor($diff / $intervals['year']);
            $return = $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
        }
    }
    return $return;
}

function time_elapsed_string($datetime, $full = false)
{
    $now  = new DateTime;
    $ago  = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Calculate weeks and days without modifying $diff
    $weeks = floor($diff->d / 7);
    $days = $diff->d - ($weeks * 7);

    $units = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    $result = array();
    foreach ($units as $k => $v) {
        if ($k == 'w' && $weeks) {
            $result[] = $weeks . ' ' . $v . ($weeks > 1 ? 's' : '');
        } elseif ($k == 'd' && $days) {
            $result[] = $days . ' ' . $v . ($days > 1 ? 's' : '');
        } elseif ($k != 'w' && $k != 'd' && $diff->$k) {
            $result[] = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
    }

    if (!$full) {
        $result = array_slice($result, 0, 1);
    }
    return $result ? implode(', ', $result) . ' ago' : 'just now';
}

function getCurrencyCode()
{
    $prefix = getSettingItem('Currency');
    switch ($prefix) {
        case '&pound;':
            return 'GBP';
        case '&#x9f3;':
            return 'BDT';
        case '&dollar;':
            return 'USD';
        case '&euro;':
            return 'EUR';
        default:
            return 'GBP';
    }
}

function getEmailById($user_id = 0)
{
    $CI   = &get_instance();
    $user = $CI->db->select('email')->where('id', $user_id)->get('users')->row();
    return ($user) ? $user->email : null;
}

function featured_status($data_id, $status)
{
    if ($status == 'Yes') {
        return "<span id=\"fea_$data_id\"><span title='Click to unfeature' class=\"ajax_active\" onClick=\"change_featured_status($data_id,$status);\"><i class=\"fa fa-retweet\"></i> Yes</span></span>";
    } else {
        return "<span id=\"fea_$data_id\"><span title='Click to Feature' class=\"ajax_inactive\" onClick=\"change_featured_status($data_id,$status);\"><i class=\"fa fa-retweet\"></i> No</span></span>";
    }
}

function getUserData($user_id = 0, $filde_name = 'id')
{
    $CI   =& get_instance();
    $user = $CI->db->select($filde_name)->from('users')->where('id', $user_id)->get()->row();
    if ($user) {
        return $user->$filde_name;
    } else {
        return $id = 0;
    }
}

function getPhoto($photo, $name = '', $noPhotoWidth = '150', $noPhotoHeight = '150')
{
    $filename = dirname(BASEPATH) . '/' . $photo;
    if ($photo && file_exists($filename)) {
        return $photo;
    } else {
        if ($name) {
            $text = firstLetterOfEachWord($name);
        } else {
            $text = getSettingItem('NoPhotoText');
        }
        return 'holder.js/' . $noPhotoWidth . 'x' . $noPhotoHeight . '?size=14&text=' . $text;
    }
}

function firstLetterOfEachWord($str, $limit = 2)
{
    $ret = '';
    foreach (explode(' ', $str) as $word) {
        $ret .= strtoupper($word[0]);
    }
    return substr_fk($ret, 0, $limit);
}

function getPhotoWithTimThumb($photo, $width = '110', $height = '110', $zc = 2)
{
    $filename = dirname(BASEPATH) . "/{$photo}";
    if ($photo && file_exists($filename)) {
        return base_url('timthumb.php?src=' . base_url($photo) . "&h={$height}&w={$width}&zc={$zc}");
    } else {
        return 'uploads/no-photo.jpg';
    }
}

function getPhoneCode($selected = '44')
{
    if ($selected == '') {
        $selected = '44';
    }
    $codes = [
        '213'   => 'Algeria (+213)',
        '376'   => 'Andorra (+376)',
        '244'   => 'Angola (+244)',
        '1264'  => 'Anguilla (+1264)',
        '1268'  => 'Antigua &amp; Barbuda (+1268)',
        '54'    => 'Argentina (+54)',
        '374'   => 'Armenia (+374)',
        '297'   => 'Aruba (+297)',
        '61'    => 'Australia (+61)',
        '43'    => 'Austria (+43)',
        '994'   => 'Azerbaijan (+994)',
        '1242'  => 'Bahamas (+1242)',
        '973'   => 'Bahrain (+973)',
        '880'   => 'Bangladesh (+880)',
        '1246'  => 'Barbados (+1246)',
        '375'   => 'Belarus (+375)',
        '32'    => 'Belgium (+32)',
        '501'   => 'Belize (+501)',
        '229'   => 'Benin (+229)',
        '1441'  => 'Bermuda (+1441)',
        '975'   => 'Bhutan (+975)',
        '591'   => 'Bolivia (+591)',
        '387'   => 'Bosnia Herzegovina (+387)',
        '267'   => 'Botswana (+267)',
        '55'    => 'Brazil (+55)',
        '673'   => 'Brunei (+673)',
        '359'   => 'Bulgaria (+359)',
        '226'   => 'Burkina Faso (+226)',
        '257'   => 'Burundi (+257)',
        '855'   => 'Cambodia (+855)',
        '237'   => 'Cameroon (+237)',
        '1'     => 'Canada (+1)',
        '238'   => 'Cape Verde Islands (+238)',
        '1345'  => 'Cayman Islands (+1345)',
        '236'   => 'Central African Republic (+236)',
        '56'    => 'Chile (+56)',
        '86'    => 'China (+86)',
        '57'    => 'Colombia (+57)',
        '269'   => 'Comoros (+269)',
        '242'   => 'Congo (+242)',
        '682'   => 'Cook Islands (+682)',
        '506'   => 'Costa Rica (+506)',
        '385'   => 'Croatia (+385)',
        '53'    => 'Cuba (+53)',
        '90392' => 'Cyprus North (+90392)',
        '357'   => 'Cyprus South (+357)',
        '42'    => 'Czech Republic (+42)',
        '45'    => 'Denmark (+45)',
        '253'   => 'Djibouti (+253)',
        '1809'  => 'Dominica (+1809)',
        '1809'  => 'Dominican Republic (+1809)',
        '593'   => 'Ecuador (+593)',
        '20'    => 'Egypt (+20)',
        '503'   => 'El Salvador (+503)',
        '240'   => 'Equatorial Guinea (+240)',
        '291'   => 'Eritrea (+291)',
        '372'   => 'Estonia (+372)',
        '251'   => 'Ethiopia (+251)',
        '500'   => 'Falkland Islands (+500)',
        '298'   => 'Faroe Islands (+298)',
        '679'   => 'Fiji (+679)',
        '358'   => 'Finland (+358)',
        '33'    => 'France (+33)',
        '594'   => 'French Guiana (+594)',
        '689'   => 'French Polynesia (+689)',
        '241'   => 'Gabon (+241)',
        '220'   => 'Gambia (+220)',
        '7880'  => 'Georgia (+7880)',
        '49'    => 'Germany (+49)',
        '233'   => 'Ghana (+233)',
        '350'   => 'Gibraltar (+350)',
        '30'    => 'Greece (+30)',
        '299'   => 'Greenland (+299)',
        '1473'  => 'Grenada (+1473)',
        '590'   => 'Guadeloupe (+590)',
        '671'   => 'Guam (+671)',
        '502'   => 'Guatemala (+502)',
        '224'   => 'Guinea (+224)',
        '245'   => 'Guinea - Bissau (+245)',
        '592'   => 'Guyana (+592)',
        '509'   => 'Haiti (+509)',
        '504'   => 'Honduras (+504)',
        '852'   => 'Hong Kong (+852)',
        '36'    => 'Hungary (+36)',
        '354'   => 'Iceland (+354)',
        '91'    => 'India (+91)',
        '62'    => 'Indonesia (+62)',
        '98'    => 'Iran (+98)',
        '964'   => 'Iraq (+964)',
        '353'   => 'Ireland (+353)',
        '972'   => 'Israel (+972)',
        '39'    => 'Italy (+39)',
        '1876'  => 'Jamaica (+1876)',
        '81'    => 'Japan (+81)',
        '962'   => 'Jordan (+962)',
        '7'     => 'Kazakhstan (+7)',
        '254'   => 'Kenya (+254)',
        '686'   => 'Kiribati (+686)',
        '850'   => 'Korea North (+850)',
        '82'    => 'Korea South (+82)',
        '965'   => 'Kuwait (+965)',
        '996'   => 'Kyrgyzstan (+996)',
        '856'   => 'Laos (+856)',
        '371'   => 'Latvia (+371)',
        '961'   => 'Lebanon (+961)',
        '266'   => 'Lesotho (+266)',
        '231'   => 'Liberia (+231)',
        '218'   => 'Libya (+218)',
        '417'   => 'Liechtenstein (+417)',
        '370'   => 'Lithuania (+370)',
        '352'   => 'Luxembourg (+352)',
        '853'   => 'Macao (+853)',
        '389'   => 'Macedonia (+389)',
        '261'   => 'Madagascar (+261)',
        '265'   => 'Malawi (+265)',
        '60'    => 'Malaysia (+60)',
        '960'   => 'Maldives (+960)',
        '223'   => 'Mali (+223)',
        '356'   => 'Malta (+356)',
        '692'   => 'Marshall Islands (+692)',
        '596'   => 'Martinique (+596)',
        '222'   => 'Mauritania (+222)',
        '269'   => 'Mayotte (+269)',
        '52'    => 'Mexico (+52)',
        '691'   => 'Micronesia (+691)',
        '373'   => 'Moldova (+373)',
        '377'   => 'Monaco (+377)',
        '976'   => 'Mongolia (+976)',
        '1664'  => 'Montserrat (+1664)',
        '212'   => 'Morocco (+212)',
        '258'   => 'Mozambique (+258)',
        '95'    => 'Myanmar (+95)',
        '264'   => 'Namibia (+264)',
        '674'   => 'Nauru (+674)',
        '977'   => 'Nepal (+977)',
        '31'    => 'Netherlands (+31)',
        '687'   => 'New Caledonia (+687)',
        '64'    => 'New Zealand (+64)',
        '505'   => 'Nicaragua (+505)',
        '227'   => 'Niger (+227)',
        '234'   => 'Nigeria (+234)',
        '683'   => 'Niue (+683)',
        '672'   => 'Norfolk Islands (+672)',
        '670'   => 'Northern Marianas (+670)',
        '47'    => 'Norway (+47)',
        '968'   => 'Oman (+968)',
        '680'   => 'Palau (+680)',
        '92'    => 'Pakistan (+92)',
        '507'   => 'Panama (+507)',
        '675'   => 'Papua New Guinea (+675)',
        '595'   => 'Paraguay (+595)',
        '51'    => 'Peru (+51)',
        '63'    => 'Philippines (+63)',
        '48'    => 'Poland (+48)',
        '351'   => 'Portugal (+351)',
        '1787'  => 'Puerto Rico (+1787)',
        '974'   => 'Qatar (+974)',
        '262'   => 'Reunion (+262)',
        '40'    => 'Romania (+40)',
        '7'     => 'Russia (+7)',
        '250'   => 'Rwanda (+250)',
        '378'   => 'San Marino (+378)',
        '239'   => 'Sao Tome &amp; Principe (+239)',
        '966'   => 'Saudi Arabia (+966)',
        '221'   => 'Senegal (+221)',
        '381'   => 'Serbia (+381)',
        '248'   => 'Seychelles (+248)',
        '232'   => 'Sierra Leone (+232)',
        '65'    => 'Singapore (+65)',
        '421'   => 'Slovak Republic (+421)',
        '386'   => 'Slovenia (+386)',
        '677'   => 'Solomon Islands (+677)',
        '252'   => 'Somalia (+252)',
        '27'    => 'South Africa (+27)',
        '34'    => 'Spain (+34)',
        '94'    => 'Sri Lanka (+94)',
        '290'   => 'St. Helena (+290)',
        '1869'  => 'St. Kitts (+1869)',
        '1758'  => 'St. Lucia (+1758)',
        '249'   => 'Sudan (+249)',
        '597'   => 'Suriname (+597)',
        '268'   => 'Swaziland (+268)',
        '46'    => 'Sweden (+46)',
        '41'    => 'Switzerland (+41)',
        '963'   => 'Syria (+963)',
        '886'   => 'Taiwan (+886)',
        '7'     => 'Tajikstan (+7)',
        '66'    => 'Thailand (+66)',
        '228'   => 'Togo (+228)',
        '676'   => 'Tonga (+676)',
        '1868'  => 'Trinidad &amp; Tobago (+1868)',
        '216'   => 'Tunisia (+216)',
        '90'    => 'Turkey (+90)',
        '7'     => 'Turkmenistan (+7)',
        '993'   => 'Turkmenistan (+993)',
        '1649'  => 'Turks &amp; Caicos Islands (+1649)',
        '688'   => 'Tuvalu (+688)',
        '256'   => 'Uganda (+256)',
        '44'    => 'UK (+44)',
        '380'   => 'Ukraine (+380)',
        '971'   => 'United Arab Emirates (+971)',
        '598'   => 'Uruguay (+598)',
        '1'     => 'USA (+1)',
        '7'     => 'Uzbekistan (+7)',
        '678'   => 'Vanuatu (+678)',
        '379'   => 'Vatican City (+379)',
        '58'    => 'Venezuela (+58)',
        '84'    => 'Vietnam (+84)',
        '84'    => 'Virgin Islands - British (+1284)',
        '84'    => 'Virgin Islands - US (+1340)',
        '681'   => 'Wallis &amp; Futuna (+681)',
        '969'   => 'Yemen (North)(+969)',
        '967'   => 'Yemen (South)(+967)',
        '260'   => 'Zambia (+260)',
        '263'   => 'Zimbabwe (+263)',
    ];
    $row   = '';
    foreach ($codes as $key => $option) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $option . '</option>';
    }
    return $row;
}

function deadline($date)
{
    $today = date('Y-m-d');
    $date1 = new DateTime($date);
    $date2 = new DateTime($today);
    $diff  = $date1->diff($date2);

    if ($diff->days >= 0) {
        return globalDateFormat($date) . "<br/><small style=\"color: red\"> ({$diff->days} Days Left)</small>";
    } else {
        return globalDateFormat($date) . '<br/><small style="color: red">(Expired)</span></small>';
    }
}

function dateTimeDifference($timestamp = null)
{
    $start_date = new DateTime($timestamp);
    $end_date   = new DateTime(date('Y-m-d H:i:s'));
    $diff       = $start_date->diff($end_date);

    $days  = $diff->days;
    $hours = $diff->h;

    if ($days >= 7) {
        return globalDateFormat($timestamp);
    }
    if ($days >= 1) {
        return "{$days} days ago";
    }
    if ($days == 0 && $hours >= 1) {
        return "{$hours} hours ago";
    }
    if ($days == 0 && $hours == 0) {
        return "{$diff->i} min ago";
    }
}

function randomPassword($chars = 6)
{
    $alphabet    = 'abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass        = array();               //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $chars; $i++) {
        $n      = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function password_strength($pwd)
{
    $errors = '';
    if (strlen($pwd) < 6) {
        $errors .= '<p class="strong"><i class="fa fa-warning"></i> Password too short!</p>';
    }

    if (!preg_match('#[0-9]+#', $pwd)) {
        $errors .= '<p class="strong"><i class="fa fa-warning"></i> Password must include at least one number!</p>';
    }

    if (!preg_match('#[!@$%^&*()]+#', $pwd)) {
        $errors .= '<p class="strong"><i class="fa fa-warning"></i> Password must include at least one special character!</p>';
    }

    if (!preg_match('#[a-zA-Z]+#', $pwd)) {
        $errors .= '<p class="strong"><i class="fa fa-warning"></i> Password must include at least one letter!</p>';
    }
    return $errors;
}

//GMC or GDC or NMC dropdown list
function getNumberType($selected = 'GMC')
{

    $numbers = [
        'GMC' => 'GMC [Doctor]',
        'GDC' => 'GDC [Dentist]',
        'NMC' => 'NMC [Nurse]',
    ];
    $row     = '';
    foreach ($numbers as $key => $option) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= ">{$option}</option>";
    }
    return $row;
}

function getExamCentreDropDownForFrontend($selected = 0, $centre_country_id = 0)
{
    $ci = &get_instance();
    $ci->db->where('country_id', $centre_country_id);
    $exams = $ci->db->get('exam_centres')->result();

    $options = '<option value="">-- Select Centre Name --</option>';
    foreach ($exams as $exam) {
        $options .= '<option value="' . $exam->id . '" ';
        $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $exam->name . '</option>';
    }
    $options .= '<option value="other">No Found! Please Specify</option>';
    return $options;
}

function getExamNameDropDownForFrontend($selected = 0, $lavel = '-- Select Exam Name --')
{
    $ci    = &get_instance();
    $exams = $ci->db->select('id, name')->get('exams')->result();

    $options = '<option value="">'.$lavel.'</option>';
    foreach ($exams as $exam) {
        $options .= '<option value="' . $exam->id . '" ';
        $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $exam->name . '</option>';
    }
    return $options;
}

//Name title dropdown lists
function getNameTitle($selected = 'Mr')
{
    if ($selected == '') {
        $selected = 'Mr';
    }
    $titles = [
        'Mr'    => 'Mr',
        'Miss'  => 'Miss',
        'Ms'    => 'Ms',
        'Mrs'   => 'Mrs',
        'Dr'    => 'Dr',
        'Sir'   => 'Sir',
        'Other' => 'Other',
    ];
    $row    = '';
    foreach ($titles as $key => $option) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $option . '</option>';
    }
    return $row;
}

function getSecretQuestions($question_id = 0)
{
    $ci              = &get_instance();
    $secretQuestions = $ci->db->get('secret_questions')->result();
    $options         = '<option value="">--Select Secret Question--</option>';
    foreach ($secretQuestions as $question) {
        $options .= '<option value="' . $question->id . '" ';
        $options .= ($question->id == $question_id) ? 'selected="selected"' : '';
        $options .= '>' . $question->questions . '</option>';
    }
    return $options;
}

function getDropDownOccuptions($selected = '')
{
    $occuptions = [
        'Doctor'  => 'Doctor',
        'Nurse'   => 'Nurse',
        'Student' => 'Student',
        'Dentist' => 'Dentist'
    ];
    $row        = '';
    foreach ($occuptions as $key => $occuption) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $occuption . '</option>';
    }
    return $row;
}

function getDropDownPurposeOfRegistration($selected = '')
{
    $purposes = [
        'PLAB Part 2'        => 'PLAB Part 2',
        'ORE Part 2'         => 'ORE Part 2',
        'MRCP Part 2'        => 'MRCP Part 2',
        'MRCS'               => 'MRCS',
        'Plab 1'             => 'Plab 1',
        'OET/IELTS'          => 'OET/IELTS',
        'CSA Part-1(Online)' => 'CSA Part-1(Online)'
    ];
    $row      = '';
    foreach ($purposes as $key => $purpose) {
        $row .= '<option value="' . htmlentities($key) . '"';
        $row .= ($selected == $key) ? ' selected' : '';
        $row .= '>' . $purpose . '</option>';
    }
    return $row;
}

function uploadFile($FILE = [], $path = 'uploads/certificate/', $name = 'cert')
{
    $handle = new \Verot\Upload\Upload($FILE);
    if ($handle->uploaded) {
        $handle->file_new_name_body = uniqid("{$name}_");
        $handle->process($path);
        if ($handle->processed) {
            return stripslashes($handle->file_dst_pathname);
        }
    }
    return '';
}

function download_attachment($file)
{
    $link = '';
    if (empty($file)) {
        return 'No File';
    }
    $filepath = dirname(APPPATH) . "/{$file}";

    if (file_exists($filepath)) {
        $link = '<a href="' . base_url($file) . '" title=" Download File"><i class="fa fa-download"></i> Download</a>';
    } else {
        $link = '<em style="color:#196da4;">No File</em>';
    }
    return $link;
}

function filePreviewBtn($file)
{
    $btn       = '';
    $file_path = dirname(BASEPATH) . "/{$file}";
    if (!$file or !file_exists($file_path)) {
        return '<span class="btn btn-xs btn-default" title="No Preview"><i class="fa fa-search" aria-hidden="true"></i> Preview </span>';
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $file_url = '//docs.google.com/viewer?url=' . base_url($file);
    } else {
        $file_url = base_url($file);
    }

    $btn .= "<a href=\"{$file_url}\" target=\"_blank\" class=\"btn btn-xs btn-success\" title=\"Preview\">";
    $btn .= '<i class="fa fa-search-plus"></i> Preview ';
    $btn .= '</a>';
    return $btn;

}

function getMultipleDropDownOccuptions($selected = [])
{
    if (empty($selected)) {
        $selected = ['What'];
    }
    $types = [
        'GMC' => ' Doctor &nbsp;&nbsp;&nbsp;&nbsp;',
        'GDC' => ' Dentist &nbsp;&nbsp;&nbsp;&nbsp;',
        'NMC' => ' Nurse &nbsp;&nbsp;&nbsp;&nbsp;'
    ];
    $row   = '';
    foreach ($types as $key => $title) {
        $row .= '<label>';
        $row .= "<input type='checkbox' name='allowed[]' value='{$key}' ";
        $row .= (in_array($key, $selected)) ? ' checked' : '';
        $row .= ">{$title}</label>";
    }
    return $row;

}

function getMAC()
{
//    $MAC = exec('getmac'); 
//    // Storing 'getmac' value in $MAC 
//    $MAC = strtok($MAC, ' '); 

//    $mac = system('arp -an');
//    echo $mac;
    ob_start();
    system('getmac');
    $content = ob_get_contents();
    ob_clean();
    return substr_fk($content, strpos($content, '\\') - 20, 17);
}

function randDomPasswordGenerator($length = 10)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index  = rand(0, $count - 1);
        $result .= mb_substr_fk($chars, $index, 1);
    }
    return $result;
}

function onlineStudentCount()
{
    $ci = &get_instance();
    $ci->db->select('COUNT(*) as qty');
    $ci->db->from('student_logs');
    $ci->db->where('(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(login_time)) <', 60 * 60 * 3);
    $ci->db->where('logout_time IS NULL');
    $query  = $ci->db->get();
    $result = $query->row();
    return $result->qty;
}

function totalStudentCount()
{
    $ci = &get_instance();
    $ci->db->from('students');
    $ci->db->where('status', 'Active');
    return $ci->db->count_all_results();
}

function loadCKEditor5ClassicBasic($selectors = [], $cdn = true, $min_height = 200)
{
    $ci                 = &get_instance();
    $data               = [];
    $data['cdn']        = $cdn;
    $data['min_height'] = $min_height;
    foreach ($selectors as $key => $selector) {
        if ($key > 0 && $cdn === true) {
            $data['cdn'] = false;
        }
        $data['selector'] = $selector;
        $ci->load->view('global/ckeditor5/classic_basic', $data);
    }
}

function loadCKEditor5ClassicFull($selectors = [], $cdn = true, $min_height = 300, $v = 'auto')
{
    $ci                 = &get_instance();
    $data               = [];
    $data['cdn']        = $cdn;
    $data['min_height'] = $min_height;
    foreach ($selectors as $key => $selector) {
        if ($key > 0 && $cdn === true) {
            $data['cdn'] = false;
        }
        $data['selector'] = $selector;
        $ci->load->view('global/ckeditor5/classic_full', $data);
    }
}