<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => '',

    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
//    'database' => 'samson_plab_exam',
    'database' => 'samson_assessment_live',

//    'hostname' => 'shareddb-s.hosting.stackcp.net',
//    'username' => 'samson_plab_examine-3132353bd6',
//    'password' => 'ls3h77qqxl',
//    'database' => 'samson_plab_examine-3132353bd6',

    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

/* For only Keeping DB Backup Logs */
$db['sqlite'] = array(
    'dsn'      => 'sqlite:DB/backup_logs.sqlite',   // path/to/database
    'dbdriver' => 'pdo'
);
