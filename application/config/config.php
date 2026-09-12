<?php

defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );
date_default_timezone_set( 'UTC' );
error_reporting(0);
$config['base_url'] = env('BASE_URL', 'https://eprap.test');

$config['index_page']        = '';
$config['uri_protocol']      = 'REQUEST_URI';
$config['url_suffix']        = '';
$config['language']          = 'english';
$config['charset']           = 'UTF-8';
$config['enable_hooks']      = true;
$config['subclass_prefix']   = 'MY_';
$config['composer_autoload'] = 'vendor/autoload.php';

$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-=';
//$config['permitted_uri_chars']    = '';

$config['allow_get_array']      = true;
$config['enable_query_strings'] = false;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd';

$config['log_threshold']        = 0; // 1,2,3,4
$config['log_path']             = '';
$config['log_file_extension']   = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format']      = 'Y-m-d H:i:s';
$config['error_views_path']     = '';
$config['cache_path']           = '';
$config['cache_query_string']   = false;
$config['encryption_key']       = '';

$config['sess_driver']              = 'database';
$config['sess_save_path']           = 'ci_sessions';

// $config['sess_driver']             = 'files';
$config['sess_cookie_name']        = 'eprap_ses_';
$config['sess_expiration']         = 7200;
// $config['sess_save_path']          = dirname(APPPATH) . '/temp/sessions/';
$config['sess_match_ip']           = false;
$config['sess_time_to_update']     = 7200; //60*60*24*7;
$config['sess_regenerate_destroy'] = false;

$config['cookie_prefix']   = 'ep_';
$config['cookie_domain']   = ''; /* localhost flickmedia.org */
$config['cookie_path']     = '/'; /* client/samson_plab_examine/ */
$config['cookie_secure']   = false;
$config['cookie_httponly'] = false;

$config['standardize_newlines'] = false;
$config['global_xss_filtering'] = false;

$config['csrf_protection']   = false;
$config['csrf_token_name']   = '_token';
$config['csrf_cookie_name']  = 'csrf_cookie_name';
$config['csrf_expire']       = 60 * 60; // 1 hour
$config['csrf_regenerate']   = true;
$config['csrf_exclude_uris'] = [];

$config['compress_output']    = false;
$config['time_reference']     = 'local';
$config['rewrite_short_tags'] = false;
$config['proxy_ips']          = '';
$config['modules_locations']  = [
    APPPATH . 'modules/' => '../modules/',
];

/* Stripe, PayPal & Flutterwave — keys/toggles come from .env, never hardcoded here */
$config['stripe_key']      = env('STRIPE_KEY', '');
$config['stripe_secret']   = env('STRIPE_SECRET', '');
$config['stripe_currency'] = env('STRIPE_CURRENCY', 'GBP');
$config['stripe_enable']   = filter_var(env('STRIPE_ENABLE'), FILTER_VALIDATE_BOOLEAN);

$config['fw_public_key'] = env('FW_PUBLIC_KEY', '');
$config['fw_secret_key'] = env('FW_SECRET_KEY', '');

$config['paypal_enable'] = filter_var(env('PAYPAL_ENABLE'), FILTER_VALIDATE_BOOLEAN);