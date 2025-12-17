<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Student Database <small> </small>
        <?php
        echo anchor(site_url(Backend_URL . 'student/create'),
            ' + Register New Student',
            'class="btn btn-default"'
        );
        ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">student</li>
    </ol>
</section>
<?php load_module_asset('student', 'css'); ?>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">All Student</div>
        <div class="panel-body">
            <?php $this->load->view('index_filter'); ?>
            <?php if (!$students) {
                echo '<p class="ajax_notice">No Student Found</p>';
            } ?>
            <form method="post" id="assignment" onsubmit="return save_assignment(event);">
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
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th width="145">Phone</th>
                            <th width="150">ID-Number</th>
                            <th width="160">Register At</th>
                            <th class="text-center" width="80">Status</th>
                            <th class="text-center" width="160">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($students as $student) {
                            $options = "<input class='mark' type='checkbox' name='s_ids[{$student->id}]' value='{$student->id}'>";
                            $link    = site_url(Backend_URL . '/student/read/' . $student->id);
                            ?>
                            <tr>
                                <td><label><?php echo $options; ?><?php echo ++$start; ?></label></td>
                                <td class="text-center">
                                    <?php echo getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?>
                                </td>
                                <td><a href="<?= $link; ?>"><?php echo $student->fname; ?></a>
                                    <p style="margin: 5px 0 0 0;">
                                        <?php if (empty($student->note)) { ?>
                                            <a href="<?= site_url(Backend_URL . 'student/update/' . $student->id); ?>"
                                               class="btn btn-link no-padding">
                                                <i class="fa fa-file-text-o"></i>
                                                Add
                                            </a>
                                        <?php } else { ?>
                                            <span class="btn btn-link open_popup no-padding"
                                                  data-note="<?= nl2br_fk($student->note); ?>">
                                            <i class="fa fa-file-text-o"></i> 
                                            View
                                        </span>
                                        <?php } ?>
                                    </p>
                                </td>
                                <td><a href="<?= $link; ?>"><?php echo $student->lname; ?></a>
                                    <p style="margin-top: 5px;"><?php echo studentIsVerified($student->verified); ?></p>
                                </td>
                                <td><a href="<?= $link; ?>"><?php echo $student->email; ?></a>
                                    <div class="progress-group">
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    &nbsp;&nbsp;<i
                                            class="fa fa-mobile-phone"></i> <?php echo "+{$student->phone_code}{$student->phone}"; ?>
                                    <br/>
                                    <i class="fa fa-whatsapp"></i> <?php echo "+{$student->whatsapp_code}{$student->whatsapp}"; ?>
                                </td>
                                <td><?php echo "{$student->number_type}-{$student->gmc_number}"; ?></td>
                                <td><?php echo globalDateTimeFormat($student->created_at); ?></td>
                                <td class="text-center">
                                    <?php echo studentStatus($student->status, $student->id); ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    echo anchor(site_url(Backend_URL . 'student/update/' . $student->id), '<i class="fa fa-fw fa-edit"></i> Update', 'class="btn btn-xs btn-warning"');
                                    echo anchor(site_url(Backend_URL . 'student/login/' . $student->id), '<i class="fa fa-fw fa-gear"></i> Login', 'class="btn btn-xs btn-info" target="_blank"');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-addon">Select Teacher for Assign Student:</span>
                            <select class="form-control" name="teacher_id">
                                <?php echo getDropDownUserList($tid); ?>
                            </select>
                            <span class="input-group-btn">
                                <input class="btn btn-primary" type="submit" value="Assign">                            
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3" id="respond">
                    </div>
                </div>
            </form>
            <div class="box-footer text-center">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>

</section>

<div class="modal fade" id="note_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Student Note/Remark</h4>
            </div>
            <div class="modal-body" id="show_note"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.open_popup').on('click', function () {
        var note = $(this).attr('data-note');
        $('#show_note').html(note);
        $('#note_popup').modal({show: 'false'});
    });

    function checkUncheck() {
        var len = $(".mark:checked").length;
        if (len) {
            jQuery('.mark').prop('checked', '');
        } else {
            jQuery('.mark').prop('checked', 'checked');
        }
    }

    function statusUpdate(post_id, status) {
        $.ajax({
            url       : 'admin/student/set_status',
            type      : 'POST',
            dataType  : "json",
            data      : {status: status, post_id: post_id},
            beforeSend: function () {
                $('#active_status_' + post_id).html('Updating...');
            },
            success   : function (jsonRespond) {
                $('#active_status_' + post_id)
                    .html(jsonRespond.Status)
                    .removeClass('btn-default btn-danger btn-success')
                    .addClass(jsonRespond.Class);
            }
        });
    }

    function save_assignment(e) {
        e.preventDefault();
        var assignment = $('#assignment').serialize();
        $.ajax({
            url       : 'admin/student/save_assignment',
            type      : 'POST',
            dataType  : "json",
            data      : assignment,
            beforeSend: function () {
                $('#respond').css('display', 'block').html('Updating...');
            },
            success   : function (respond) {
                $('#respond').html(respond.Msg);
                setTimeout(function () {
                    $('#respond').fadeOut();
                }, 2000);

            }
        });
        return false;
    }
</script>    