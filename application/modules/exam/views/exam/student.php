<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<style type="text/css">
    .table thead tr th,
    .table tbody tr td {
        vertical-align: middle;
    }
     .ck-editor__editable {
         min-height: 200px;
     }
</style>
<section class="content-header">
    <h1>Mock Exam <small>Student List</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'exam') ?>">Mock Exam</a></li>
        <li class="active">Students</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo examTabs($id, 'student'); ?>
    <div class="box no-border">
        <form method="post" id="student_list" onsubmit="return send_link(event);">
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
                            Centre: <?php echo($centre_name); ?>, <?php echo($centre_address); ?><br/>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>GMC-Exam-Date</th>
                            <th>Number</th>
                            <th>Phone</th>
                            <th>Booked At</th>
                            <th>Attendance</th>
                            <th class="text-center hide_on_print" width="170">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($students as $student) {
                            $options = "<input name='students[]' value='{$student->id}' class='mark' type='checkbox'/>";
                            ?>
                            <tr>
                                <td><label><?= $options . ' ' . sprintf('%02d', ++$start); ?></label></td>
                                <td><?php echo getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?></td>
                                <td><?php echo "{$student->fname} {$student->mname} {$student->lname}"; ?></td>
                                <td> <?= $student->email ?> </td>
                                <td><?php echo globalDateFormat($student->exam_date); ?></td>
                                <td><?php echo "{$student->number_type}-{$student->gmc_number}"; ?></td>
                                <td>
                                    <a href="tel:<?php echo "+{$student->phone_code}{$student->phone}"; ?>">
                                        <i class="fa fa-mobile-phone"></i> <?php echo "+{$student->phone_code}{$student->phone}"; ?>
                                        <br/>
                                    </a>
                                    <a href="https://wa.me/<?= "+{$student->whatsapp_code}{$student->whatsapp}"; ?>"
                                       target="_blank">
                                        <i class="fa fa-whatsapp"></i> <?php echo "+{$student->whatsapp_code}{$student->whatsapp}"; ?>
                                    </a>
                                </td>
                                <td><?php echo globalDateTimeFormat($student->assign_at); ?></td>
                                <td>
                                <span class="label <?= ($student->attendance) ? 'label-success' : 'label-warning' ?> btn-xs"> 
                                <?= ($student->attendance) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-fw fa-close"></i>'; ?>
                                <?= ($student->attendance) ? 'Yes' : 'No'; ?>
                                </span>
                                </td>
                                <td class="text-center hide_on_print">
                                    <?php
                                    echo anchor(
                                        site_url(Backend_URL . 'student/read/' . $student->id),
                                        '<i class="fa fa-fw fa-external-link"></i> Preview',
                                        'class="btn btn-xs btn-primary" target="_blank"'
                                    );
                                    if ($student->exam_status == 'Enrolled') {
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
                        <button class="btn btn-success" type="button" onclick="sendLinkModal()">
                            <i class="fa fa-send"></i>
                            Create Group
                        </button>
                    </div>
<!--                                    <div class="col-md-5">-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">Select Whatsapp Group to Assign Student:</span>-->
<!--                                            <select class="form-control" name="whatsapp_id">-->
<!--                                                --><?php //= getDropDownWhatsapp(); ?>
<!--                                            </select>-->
<!--                                            <span class="input-group-btn">-->
<!--                                                <button class="btn btn-primary" type="submit">-->
<!--                                                    <i class="fa fa-send"></i>-->
<!--                                                    Send Link-->
<!--                                                </button>-->
<!--                                            </span>-->
<!--                                        </div>-->
<!--                                    </div>-->
                    <div class="col-md-7">
                        <button type="button" class="btn btn-primary print_btn" onclick="return window.print();">
                            <i class="fa fa-print"></i>
                            Print
                        </button>
                        <a href="admin/exam/student_export_csv/<?= $id; ?>" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            Download CSV
                        </a>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
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

<!-- Send Link to Students Modal -->
<div class="modal fade" id="send_link" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="send_link_form" class="form-horizontal">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Send Link to Students</h4>
                    <p>You select <span class="student_selected_count" style="font-weight: bold;">0</span> students to send link</p>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="link_type" class="col-sm-2 control-label">Link Type <sup>*</sup></label>
                        <div class="col-sm-10">
                            <?php
                            echo htmlRadio('link_type', 'Whatsapp', [
                                'Whatsapp' => 'WhatsApp',
                                'Telegram' => 'Telegram',
                                'Email'    => 'Email'
                            ], 'class="link_type"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group" id="whatsapp_link_area">
                        <label for="whatsapp_link_id" class="col-sm-2 control-label">Whatsapp Link<sup>*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="whatsapp_link_id" id='whatsapp_link_id'>
                                <option value="0">--Select WhatsApp Group--</option>
                                <?php echo wa::getLinks(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="telegram_link_area" style="display: none;">
                        <label for="telegram_link_id" class="col-sm-2 control-label">Telegram Link<sup>*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="telegram_link_id" id='telegram_link_id'>
                                <option value="0">--Select Telegram Group--</option>
                                <?php echo wa::getLinks(0, 'Telegram'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="mail_body_area">
                        <label for="mail_body" class="col-sm-2 control-label">Mail Body<sup>*</sup></label>
                        <div class="col-sm-10">
                            <textarea name="mail_body" id="mail_body" class="form-control" rows="15"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="send_link();" type="button" class="btn btn-success">
                        <i class="fa fa-send"></i>
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/lib/plugins/ckeditor5/classic/build/ckeditor.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create(document.querySelector('#mail_body'), {
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },
            toolbar: {
                items: [
                    'fontSize', 'fontColor', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList',
                    'alignment', 'insertTable', 'horizontalLine', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    $(document).ready(function () {
        $('.link_type').on('change', function() {
            if($(this).val() === 'Whatsapp') {
                $('#whatsapp_link_area').show();
                $('#telegram_link_area').hide();
            } else if($(this).val() === 'Telegram') {
                $('#whatsapp_link_area').hide();
                $('#telegram_link_area').show();
            } else {
                $('#whatsapp_link_area').hide();
                $('#telegram_link_area').hide();
            }
        });

        $('#whatsapp_link_id, #telegram_link_id').on('change', function() {
            const link_id  = $(this).val();
            const link_type = $('input[name="link_type"]:checked').val();
            $.ajax({
                type      : 'POST',
                data      : {link_id: link_id, link_type: link_type},
                url       : 'admin/whatsapp/get_link_data',
                dataType  : 'json',
                beforeSend: function () {
                    toastr.info('Please wait...');
                },
                success   : function (respond) {
                    toastr.clear();
                    if(respond.Status === 'OK') {
                        toastr.success('Link loaded successfully!');
                        editor.setData(`${respond.Msg.title} <br/> ${respond.Msg.link} <br/><br/> Thanks <br/> Team Samson`);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });

    });

    function sendLinkModal() {
        const len = $(".mark:checked").length;
        if (len) {
            $('.student_selected_count').text(len);
            $('#send_link').modal({
                show    : 'false',
                backdrop: 'static'
            });
        } else {
            alert('Please select at least one student');
        }
    }

    function checkUncheck() {
        const len = $(".mark:checked").length;
        if (len) {
            $('.mark').prop('checked', '');
        } else {
            $('.mark').prop('checked', 'checked');
        }
    }

    function send_link() {
        let student_list = $('#student_list').serialize();

        // post additional data
        student_list += '&link_type=' + $('input[name="link_type"]:checked').val();
        student_list += '&whatsapp_link_id=' + $('#whatsapp_link_id').val();
        student_list += '&telegram_link_id=' + $('#telegram_link_id').val();
        student_list += '&mail_body=' + editor.getData('html');

        $.ajax({
            url       : 'admin/whatsapp/send_link',
            type      : 'POST',
            dataType  : "json",
            data      : student_list,
            beforeSend: function () {
                toastr.info('Please wait...');
            },
            success   : function (respond) {
                toastr.clear();
                if(respond.Status === 'OK') {
                    toastr.success(respond.Msg);
                    location.reload();
                } else {
                    toastr.error(respond.Msg);
                }
            }
        });
        return false;
    }

    function linkStudent(id, exam_id) {
        $('.js_update_respond').empty();
        $('#scenario_popup').modal({
            show    : 'false',
            backdrop: 'static'
        });
        $.ajax({
            url       : "admin/student/get?id=" + id,
            type      : "POST",
            dataType  : "html",
            data      : {id: id, exam_id: exam_id},
            beforeSend: function () {
                $('.scenarios_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success   : function (msg) {
                $('.scenarios_box').html(msg);
            }
        });
    }

    function save_marked_student() {
        const FormData = $('#scenarios').serialize();
        $.ajax({
            url       : "admin/student/save",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                $('.js_respond').html(respond.Msg);
                if (respond.Status === 'OK') {
                    setTimeout(function () { location.reload(); }, 2000);
                }
            }
        });
    }

    function studentStatusChange(student_exam_id) {
        $('#change_status').modal({
            show    : 'false',
            backdrop: 'static'
        });
        $.ajax({
            url       : "admin/exam/get_student_exams/" + student_exam_id,
            type      : "GET",
            dataType  : "html",
            beforeSend: function () {
                $('.student_exams_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success   : function (msg) {
                $('.student_exams_box').html(msg);
            }
        });
    }

    function save_student_exam_status() {
        const FormData = $('#student_exam_status').serialize();
        $.ajax({
            url       : "admin/exam/assign_exam_set_status",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                $('.js_respond').html(respond.Msg);
                if (respond.Status === 'OK') {
                    setTimeout(function () { location.reload(); }, 1000);
                }
            }
        });
    }
</script>