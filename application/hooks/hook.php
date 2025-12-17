<?php

$hook['pre_controller'][] = array(
    'class'    => 'ExceptionHook',
    'function' => 'SetExceptionHandler',
    'filename' => 'ExceptionHook.php',
    'filepath' => 'hooks'
);
