<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1><?php echo $course_name; ?> <small>Mock Exam Details</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Mock Exam</a></li>
        <li class="active">Scenario</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo onlineMockTabs($id, 'scenario'); ?>
    <div class="box no-border">

        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="pull-left">
                        <button class="btn btn-primary hide_on_print"
                                onclick="setScenarios(<?php echo $id; ?>);">
                            <i class="fa fa-random"></i>
                            Manage Scenarios
                        </button>
                        <button class="btn btn-warning hide_on_print"
                                style="margin-right: 15px;" data-toggle="modal" data-target="#scenario_time_popup">
                            <i class="fa fa-clock-o"></i>
                            Manage Scenario Default Time
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

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th width="40" class="text-center">SL</th>
                        <th width="100" class="text-center">Scenario No</th>
                        <th>Scenario Name</th>
                        <th class="text-center">Reading Time</th>
                        <th class="text-center">Practice Time</th>
                        <th class="text-center hide_on_print" width="500">Action</th>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($scenarios as $scenario) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo sprintf('%02d', $sl++); ?></td>
                            <td class="text-center"><?php echo sprintf('%03d', $scenario->reference_number); ?></td>
                            <td>
                                <?php echo $scenario->name; ?>
                                <em class="hide_on_print"><br/> <?php echo getAssessorsName($scenario->id); ?> </em>
                            </td>
                            <td class="text-center"><?php echo $scenario->reading_time; ?> min</td>
                            <td class="text-center"><?php echo $scenario->practice_time; ?> min</td>
                            <td class="text-center hide_on_print">
                                <button class="btn btn-xs btn-primary edit_scenario_time_btn"
                                        data-id="<?php echo $scenario->id; ?>"
                                        data-reading_time="<?php echo $scenario->reading_time; ?>"
                                        data-practice_time="<?php echo $scenario->practice_time; ?>">
                                    <i class="fa fa-clock-o"></i> Change Time
                                </button>
                                <button onclick="linkAssessor(<?php echo $scenario->id; ?>);" class="btn btn-xs btn-primary">
                                    <i class="fa fa-user-md"></i> Assign Scenario
                                </button>
                                <a href="<?php echo site_url(Backend_URL . 'scenario/print/' . $scenario->sid . '?is=instructions') ?>"
                                   target="_blank" class="btn btn-xs btn-primary">
                                    <i class="fa fa-print"></i>
                                    Candidate Instructions
                                </a>
                                <a href="<?php echo site_url(Backend_URL . 'scenario/print/' . $scenario->sid . '?is=full') ?>"
                                   target="_blank" class="btn btn-xs btn-primary">
                                    <i class="fa fa-print"></i>
                                    Scenario
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="box-footer with-border text-center hide_on_print">
                <button class="btn btn-primary print_btn" onclick="return window.print();">
                    <i class="fa fa-print"></i>
                    Print List
                </button>

                <a href="admin/online_mock/print_candidate_inst/<?= $id; ?>" target="_blank"
                   class="btn btn-primary print_btn">
                    <i class="fa fa-print"></i>
                    Print Candidate Instructions
                </a>

                <a href="admin/online_mock/print_full_scenario/<?= $id; ?>" target="_blank"
                   class="btn btn-primary print_btn">
                    <i class="fa fa-print"></i>
                    Print Full Scenario
                </a>
            </div>
        </div>
</section>

<!-- Default time popup -->
<div class="modal fade" tabindex="-1" id="scenario_time_popup" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0 auto;">
            <form method="POST" id="scenario_time_form">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Scenario Default Time</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="reading_time">Reading Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="reading_time" name="reading_time" value="<?php echo $reading_time; ?>">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="practice_time">Practice Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="practice_time" name="practice_time" value="<?php echo $practice_time; ?>">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_time_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span> Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="individual_scenario_time_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-content modal-sm" style="margin: 0 auto;">
        <div class="modal-content">
            <form method="POST" id="individual_scenario_time_form">
                <input type="hidden" name="scenario_rel_id" id="scenario_rel_id" value="0"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Change Time</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reading_time">Reading Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="reading_time" name="reading_time" value="0">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="practice_time">Practice Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="practice_time" name="practice_time" value="0">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_assessor_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="scenario_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="scenarios">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Manage Scenarios
                        <big>
                            (Selected:
                            <b id="selected"><?php echo countExamScenario($id); ?></b>)
                        </big>
                    </h4>
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
                    <!--                    <button onclick="save_marked_scenario();" type="button" class="btn btn-success">
                                            <i class="fa fa-save"></i>
                                            Save Changes
                                        </button>-->

                </div>
            </form>


        </div>
    </div>
</div>

<div class="modal fade" id="assessor_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="assessor">
                <input type="hidden" name="scenario_rel_id" id="scenario_rel_id" value="0"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">List of Assessor</h4>
                </div>

                <div class="modal-body">
                    <div class="js_assessor"></div>
                    <div class="assessor_box" style="max-height:450px; overflow-y:scroll; padding-right: 10px;"></div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_assessor_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="save_assessor();" type="button" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Save Assessor
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="application/javascript">

    $( ".edit_scenario_time_btn" ).on( "click", function() {
        const id = $(this).data('id');
        const reading_time = $(this).data('reading_time');
        const practice_time = $(this).data('practice_time');
        const Form = $('#individual_scenario_time_form');
        Form.find('input[name="scenario_rel_id"]').val(id);
        Form.find('input[name="reading_time"]').val(reading_time);
        Form.find('input[name="practice_time"]').val(practice_time);
        $('#individual_scenario_time_popup').modal('show');
    })


    $("#individual_scenario_time_form").submit(function(e){
        e.preventDefault();
        const FormData = $(this).serialize();
        $.ajax({
            url       : "admin/online_mock/save_individual_scenario_time",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                toastr.info('Please wait...');
            },
            success   : function (response) {
                toastr.clear();
                toastr.success(response.Msg);
                $('#individual_scenario_time_popup').modal('hide');
                location.reload();
            }
        });
    });


    $("#scenario_time_form").submit(function(e){
        e.preventDefault();
        const FormData = $(this).serialize();
        $.ajax({
            url       : "admin/online_mock/save_scenario_time",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                toastr.info('Please wait...');
            },
            success   : function (msg) {
                toastr.clear();
                toastr.success(msg.msg);
                $('#scenario_time_popup').modal('hide');
                location.reload();
            }
        });
    });

    function linkAssessor(id) {

        $('.js_assessor').empty();
        $('#assessor_popup').modal({
            show    : 'false',
            backdrop: 'static'
        });
        $('#scenario_rel_id').val(id);

        $.ajax({
            url       : "admin/online_mock/get_assessor?id=" + id,
            type      : "POST",
            dataType  : "html",
            data      : {id: id},
            beforeSend: function () {
                $('.assessor_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success   : function (msg) {
                $('.assessor_box').html(msg);
            }
        });
    }

    function save_assessor() {
        var FormData = $('#assessor').serialize();

        $.ajax({
            url       : "admin/online_mock/save_assessor",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                $('.js_assessor').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (jsonRespond) {
                $('.js_assessor').html(jsonRespond.Msg);
                setTimeout(function () {
                    $('#assessor_popup').modal('toggle');
                }, 2000);
            }
        });
    }

    function setScenarios(id) {

        $('.js_update_respond').empty();
        $('#scenario_popup').modal({
            show    : 'false',
            backdrop: 'static'
        });

        $.ajax({
            url       : "admin/scenario/get?id=" + id,
            type      : "POST",
            dataType  : "html",
            data      : {id: id},
            beforeSend: function () {
                $('.scenarios_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success   : function (msg) {
                $('.scenarios_box').html(msg);
            }
        });
    }

    function save_marked_scenario() {
        var FormData = $('#scenarios').serialize();
        $.ajax({
            url       : "admin/scenario/save",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (jsonRespond) {
                $('.js_respond').html(jsonRespond.Msg);
//                setTimeout(function(){ $('#scenario_popup').modal('toggle'); }, 2000);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        });
    }

    $(document).keypress(function (e) {
        //var unicode = e.charCode ? e.charCode : e.keyCode;
        //console.log( unicode );
        if (e.keyCode === 27) {
            $('#close_assessor_modal').click();
            $('#close_scenario_modal').click();
        }
    });

    $(document).on('hide.bs.modal', '#scenario_popup', function () {
        setTimeout(function () {
            location.reload();
        }, 1000);
        //Do stuff here
    });

</script>    