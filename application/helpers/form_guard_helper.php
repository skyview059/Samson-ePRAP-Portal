<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Captcha-free bot protection for the public registration forms
 * (sign-up, book-course, book-subscription).
 *
 *  - Honeypot : a visually hidden input that humans never fill, bots usually do.
 *  - Time trap: a signed timestamp rendered with the form. Submits that come back
 *               too fast, too late, or without a valid signature are rejected.
 *               Direct POSTs (form never loaded) have no token and fail.
 *  - IP limit : caps how many student accounts one IP can create per hour.
 *
 * The token is stateless (HMAC) on purpose: the booking forms run inside a
 * cross-site iframe where session cookies are often blocked by the browser.
 */

define('FORM_GUARD_HONEYPOT', 'user_ref_code');
define('FORM_GUARD_MIN_SECONDS', 3);
define('FORM_GUARD_MAX_SECONDS', 60 * 60 * 12);
define('FORM_GUARD_MAX_PER_IP', 5);

function form_guard_signature($form, $timestamp)
{
    return hash_hmac('sha256', $form . '|' . $timestamp, (string)config_item('form_guard_key'));
}

/**
 * Hidden inputs to print inside the <form>.
 *
 * @param string $form form identifier, must match the one passed to form_guard_check()
 *
 * @return string
 */
function form_guard_fields($form)
{
    $timestamp = time();
    $html      = '<div aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">';
    $html     .= '<label for="' . FORM_GUARD_HONEYPOT . '">Leave this field empty</label>';
    $html     .= '<input type="text" name="' . FORM_GUARD_HONEYPOT . '" id="' . FORM_GUARD_HONEYPOT . '" value="" tabindex="-1" autocomplete="off">';
    $html     .= '</div>';
    $html     .= '<input type="hidden" name="_fg_ts" value="' . $timestamp . '">';
    $html     .= '<input type="hidden" name="_fg_sig" value="' . form_guard_signature($form, $timestamp) . '">';
    return $html;
}

/**
 * Validate the honeypot + signed timestamp of the current POST.
 *
 * @param string $form
 *
 * @return string|false user facing error message, or false when the request looks human
 */
function form_guard_check($form)
{
    $CI        =& get_instance();
    $honeypot  = $CI->input->post(FORM_GUARD_HONEYPOT);
    $timestamp = (int)$CI->input->post('_fg_ts');
    $signature = (string)$CI->input->post('_fg_sig');

    $reason = false;
    if ($honeypot !== null && $honeypot !== '') {
        $reason = 'honeypot';
    } elseif (!$timestamp || !hash_equals(form_guard_signature($form, $timestamp), $signature)) {
        $reason = 'token';
    } elseif ((time() - $timestamp) < FORM_GUARD_MIN_SECONDS) {
        $reason = 'too_fast';
    } elseif ((time() - $timestamp) > FORM_GUARD_MAX_SECONDS) {
        $reason = 'expired';
    }

    if (!$reason) {
        return false;
    }

    log_message('error', "[form_guard] {$form} rejected ({$reason}) ip=" . $CI->input->ip_address() . ' email=' . substr((string)$CI->input->post('email'), 0, 100));

    if ($reason === 'expired') {
        return 'This page has expired. Please reload the page and try again.';
    }
    return 'We could not verify your submission. Please reload the page and try again.';
}

/**
 * True when this IP already created too many student accounts in the last hour.
 *
 * @return bool
 */
function form_guard_ip_limited()
{
    $CI =& get_instance();
    $ip = $CI->input->ip_address();

    $count = $CI->db->from('students')
        ->where('tmp_ip_addr', $ip)
        ->where('created_at >=', date('Y-m-d H:i:s', time() - 3600))
        ->count_all_results();

    if ($count >= FORM_GUARD_MAX_PER_IP) {
        log_message('error', "[form_guard] ip limit reached ip={$ip}");
        return true;
    }
    return false;
}

/**
 * Form validation rule: a person's name made of letters (any language),
 * spaces, apostrophes, dots and hyphens. Blocks URLs, digits, emoji and
 * the "New message from Binance ->> ..." style spam payloads.
 *
 * Usage: set_rules('fname', 'first name', 'trim|required|max_length[50]|valid_person_name')
 *        set_message('valid_person_name', FORM_GUARD_NAME_MESSAGE)
 *
 * @param string $name
 *
 * @return bool
 */
function valid_person_name($name)
{
    return (bool)preg_match("/^[\p{L}\p{M}][\p{L}\p{M} '.\-]*$/u", (string)$name);
}

define('FORM_GUARD_NAME_MESSAGE', 'The {field} may only contain letters, spaces, apostrophes, dots and hyphens.');
