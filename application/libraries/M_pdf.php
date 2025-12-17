<?php

if (!defined('BASEPATH')){ exit('No direct script access allowed'); }

class M_pdf {

    public $param;
    public $pdf;

    public function __construct($param = "'c', 'A4-L'")
    {
        $this->ci = & get_instance();

        include_once __DIR__ . '/../../vendor/autoload.php';
        $this->param = $param;
        $this->pdf = new \Mpdf\Mpdf();
    }

}
