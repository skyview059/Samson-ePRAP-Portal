<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Exam <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'exam'); ?>">Exam</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo examTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header text-center">
            <center>
                <h2 class="no-margin"><?php echo $course_name; ?></h2>
                <h4>
                    <?php echo ($centre_name); ?>, <?php echo ($centre_address); ?><br/>
                    <?php echo globalDateTimeFormat($datetime); ?>
                </h4>                
            </center>
        </div>

        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th width="40">SL</th>
                    <th width="220">Scenario Name</th>
                    <th>Candidate INSTRUCTIONS</th>
                </tr>
                <?php
                $sl = 1;
                foreach ($stations as $station) {
                    ?>
                    <tr>
                        <td><?php echo $sl++; ?>.</td>
                        <td><?php echo $station->name; ?></td>
                        <td><?php echo nl2br_fk($station->description); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <p class="show_on_print"><em>Printed at <?php echo date('dS M, Y - h:i A');?></em></p>
        </div>        

        <div class="box-footer with-border text-center hide_on_print">
            <button class="btn btn-primary print_btn" onclick="return window.print();">
                <i class="fa fa-print"></i> 
                Print
            </button>
        </div>
    </div>
</section>