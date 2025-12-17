<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .table thead tr th,
    .table tbody tr td{
        vertical-align: middle;
    }
</style>
<section class="content-header">
    <h1>Mock Exam <small>Student List</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'online_mock') ?>">Online Mock</a></li>
        <li class="active">Students</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo onlineMockTabs($id, 'student'); ?>
    <div class="box no-border">
        <form method="post" id="whatsapp" onsubmit="return send_link(event);">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="pull-left">
                        <button class="btn btn-primary pull-right hide_on_print"
                                onclick="linkStudent(<?php echo "{$id},{$exam_id}"; ?>);">
                            <i class="fa fa-hospital-o"></i>
                            Book Student for Exam
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <h2 class="no-margin">Exam Name: <?php echo $course_name; ?></h2>
                    <h4>
                        Centre: <?php echo ($centre_name); ?>, <?php echo ($centre_address); ?><br/>
                        Date & Time: <?php echo globalDateTimeFormat($datetime); ?>
                    </h4>
                </div>
            </div>
        </div>
      
        <div class="box-body">            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="70">
                                <label>
                                    <input type="checkbox" onclick="checkUncheck();"/>
                                    S/L
                                </label>
                            </th>
                            <th width="80">Photo</th>
                            <th>Name & Email</th>
                            <th>GMC-Exam-Date</th>
                            <th>Number</th>
                            <th>Phone</th>
                            <th>Booked At</th>
                            <th>Attendance</th>
                            <th class="text-center hide_on_print" width="170">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($students as $student) { 
                        $options = "<input name='students[]' value='{$student->id}' class='mark' type='checkbox'/>";
                        ?>
                        <tr>
                            <td><label><?= $options .' '. sprintf('%02d', ++$start); ?></label></td>                                                    
                            <td><?php echo getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?></td>
                            <td><?php echo "{$student->fname} {$student->mname} {$student->lname}"; ?><br><?php echo $student->email; ?></td>
                            <td><?php echo globalDateFormat($student->exam_date); ?></td>
                            <td><?php echo "{$student->number_type}-{$student->gmc_number}"; ?></td>
                            <td>
                            &nbsp;&nbsp;<i class="fa fa-mobile-phone"></i> <?php echo "+{$student->phone_code}{$student->phone}"; ?><br/>
                                    <i class="fa fa-whatsapp" ></i> <?php echo "+{$student->whatsapp_code}{$student->whatsapp}"; ?>                            
                            </td>
                            <td><?php echo globalDateTimeFormat($student->assign_at); ?></td>
                            <td>
                                <span class="label <?= ($student->attendance) ? 'label-success' : 'label-warning' ?> btn-xs"> 
                                <?= ($student->attendance) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-fw fa-close"></i>';?>
                                <?= ($student->attendance) ? 'Yes' : 'No';?>
                                </span>
                            </td>
                            <td class="text-center hide_on_print">
                                <?php
                                echo anchor(
                                    site_url(Backend_URL . 'student/read/' . $student->id), 
                                    '<i class="fa fa-fw fa-external-link"></i> Preview', 
                                    'class="btn btn-xs btn-primary" target="_blank"'
                                );
                                if($student->exam_status=='Enrolled'){
                                ?>
                                <span class="btn  btn-xs btn-danger" 
                                    onclick="studentStatusChange(<?php echo "{$student->student_exam_id}"; ?>);">
                                  <i class="fa fa-times"></i>
                                  Cancel
                                </span>
                                <?php } else { ?>
                                    <span class="btn  btn-xs btn-default disabled">
                                      <i class="fa fa-ban"></i>
                                      Canceled
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>            
        </div>

        <div class="box-footer with-border hide_on_print">
            <div id="respond"></div>
            <div class="row">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-addon">Select Whatsapp Group to Assign Student:</span>
                        <select class="form-control" name="whatsapp_id">
                            <?= getDropDownWhatsapp(); ?>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-send"></i>                                    
                                Send Link
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-7">
                    <span class="btn btn-primary print_btn" onclick="return window.print();">
                        <i class="fa fa-print"></i> 
                        Print
                    </span>
                </div>
            </div>
        </div>
        <p class="show_on_print">Printed at <?php echo date('d/m/Y h:i a'); ?></p>
        </form>
    </div>
</section>
<div class="modal fade" id="scenario_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="scenarios">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Book Student for Mock Exam</h4>
                </div>

                <div class="modal-body">
                    <div class="js_respond"></div>
                    <div class="scenarios_box" style="height:550px; overflow-y:scroll; padding-right: 10px;"></div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="save_marked_student();" type="button" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>                    
                </div>
            </form>


        </div>
    </div>
</div>

<div class="modal fade" id="change_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="student_exam_status">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Student Exam Cancel</h4>
                </div>

                <div class="modal-body">
                    <div class="js_respond"></div>
                    <div class="student_exams_box"></div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="save_student_exam_status();" type="button" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>                    
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function checkUncheck() {
        var len = $(".mark:checked").length;
        if (len) {
            jQuery('.mark').prop('checked', '');
        } else {
            jQuery('.mark').prop('checked', 'checked');
        }
    }
    
    function send_link(e) {
        e.preventDefault();
        var assignment = $('#whatsapp').serialize();
        $.ajax({
            url: 'admin/whatsapp/send_link',
            type: 'POST',
            dataType: "json",
            data: assignment,
            beforeSend: function () {
                $('#respond').css('display', 'block').html('Updating...');
            },
            success: function (respond) {
                
                $('#respond').html(respond.Msg);
                setTimeout(function () {
                    $('#respond').fadeOut();
                }, 2000);

            }
        });
        return false;
    }
    
    function linkStudent(id,exam_id) {

        $('.js_update_respond').empty();
        $('#scenario_popup').modal({
            show: 'false',
            backdrop: 'static'
        });

        $.ajax({
            url: "admin/student/get?id=" + id,
            type: "POST",
            dataType: "html",
            data: {id: id, exam_id: exam_id},
            beforeSend: function () {
                $('.scenarios_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success: function (msg) {
                $('.scenarios_box').html(msg);                
            }
        });
    }
    
    
    function save_marked_student(){
        var FormData = $('#scenarios').serialize();
        $.ajax({
            url: "admin/student/save",
            type: "POST",
            dataType: "json",
            data: FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                $('.js_respond').html(respond.Msg);                
                if(respond.Status === 'OK'){
                    setTimeout( function(){ location.reload(); }, 2000);
                }
            }
        });
    }
    
    function studentStatusChange(student_exam_id) {

        $('#change_status').modal({
            show: 'false',
            backdrop: 'static'
        });

        $.ajax({
            url: "admin/online_mock/get_student_exams/"+student_exam_id,
            type: "GET",
            dataType: "html",
            beforeSend: function () {
                $('.student_exams_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success: function (msg) {
                $('.student_exams_box').html(msg);                
            }
        });
    }
    
    function save_student_exam_status(){
        var FormData = $('#student_exam_status').serialize();
        $.ajax({
            url: "admin/online_mock/assign_exam_set_status",
            type: "POST",
            dataType: "json",
            data: FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                $('.js_respond').html(respond.Msg);                
                if(respond.Status === 'OK'){
                    setTimeout( function(){ location.reload(); }, 1000);
                }
            }
        });
    }
    
</script>