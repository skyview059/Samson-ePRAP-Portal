<style>
    .tabbable-panel {
        border: 1px solid #eee;
        padding: 10px;
    }

    /* Default mode */
    .tabbable-line > .nav-tabs {
        border: none;
        margin: 0;
    }

    .tabbable-line > .nav-tabs > li {
        margin-right: 2px;
        padding-left: 35px;
        padding-right: 35px;
    }

    .tabbable-line > .nav-tabs > li > a {
        border: 0;
        margin-right: 0;
        color: #737373;
    }

    .tabbable-line > .nav-tabs > li > a > i {
        color: #a6a6a6;
    }

    .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
        border-bottom: 4px solid #fbcdcf;
    }

    .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
        border: 0;
        background: none !important;
        color: #333333;
    }

    .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
        color: #a6a6a6;
    }

    .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
        margin-top: 0;
    }

    .tabbable-line > .nav-tabs > li.active {
        border-bottom: 4px solid #f3565d;
        position: relative;
    }

    .tabbable-line > .nav-tabs > li.active > a {
        border: 0;
        color: #333333;
    }

    .tabbable-line > .nav-tabs > li.active > a > i {
        color: #404040;
    }

    .tabbable-line > .tab-content {
        margin-top: -3px;
        background-color: #fff;
        border: 0;
        border-top: 1px solid #eee;
        padding: 15px 0;
    }

    .portlet .tabbable-line > .tab-content {
        padding-bottom: 0;
    }

    /* Below tabs mode */

    .tabbable-line.tabs-below > .nav-tabs > li {
        border-top: 4px solid transparent;
    }

    .tabbable-line.tabs-below > .nav-tabs > li > a {
        margin-top: 0;
    }

    .tabbable-line.tabs-below > .nav-tabs > li:hover {
        border-bottom: 0;
        border-top: 4px solid #fbcdcf;
    }

    .tabbable-line.tabs-below > .nav-tabs > li.active {
        margin-bottom: -2px;
        border-bottom: 0;
        border-top: 4px solid #f3565d;
    }

    .tabbable-line.tabs-below > .tab-content {
        margin-top: -10px;
        border-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .form-group label {
        font-size: 15px;
        font-weight: normal;
    }

    h3.title {
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: underline;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h2 style="padding: 50px 0"><?php echo $practice->presentation; ?></h2>
        </div>
        <div class="col-md-4 text-right">
            <div style="padding-top: 60px">
                <h4 style="text-align: right; font-weight: 700; margin-bottom: 15px;">Student ID
                    No: <?= getLoginStudentData('student_id') ?></h4>
                <button type="button" class="btn btn-info" onclick="copyURL()"><i class="fa fa-copy"></i> Copy Practice
                    URL
                </button>
                <button type="button" class="btn btn-danger" id="end_practice_btn"
                        data-practice-id="<?php echo $practice->id; ?>"><i class="fa fa-power-off"></i>
                    End Practice
                </button>
                <a href="<?= site_url('scenario-practice/exam/practice/' . $practice->exam_id); ?>"
                   class="btn btn-primary">
                    <i class="fa fa-long-arrow-left"></i> Back to Scenarios</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs" id="tabs">
                        <?php if ($student_id == $practice->candidate_id): ?>
                            <li class="active-old">
                                <a href="#candidate_instructions" data-toggle="tab">Candidate</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($student_id == $practice->patient_id): ?>
                            <li class="active-old">
                                <a href="#patient_information" data-toggle="tab">Patient</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($student_id == $practice->examiner_id): ?>
                            <li class="active-old">
                                <a href="#examiner_information" data-toggle="tab">Examiner</a>
                            </li>
                            <li>
                                <a href="#mark_scheme" data-toggle="tab">Mark Scheme</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="#marking_criteria" data-toggle="tab">Marking Criteria</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <?php if ($student_id == $practice->candidate_id): ?>
                            <div class="tab-pane active-old" id="candidate_instructions">
                                <?= $practice->candidate_instructions; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($student_id == $practice->patient_id): ?>
                            <div class="tab-pane active-old" id="patient_information">
                                <h3 class="title">Candidate Information:</h3>
                                <?= $practice->candidate_instructions; ?>
                                <br><br>

                                <h3 class="title">Patient Information:</h3>
                                <?= $practice->patient_information; ?>
                                <br><br>

                                <h3 class="title">Examiner Information:</h3>
                                <?= $practice->examiner_information; ?>
                                <br><br>

                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>

                                <h3 class="title">Examination Findings:</h3>
                                <?= $practice->exam_findings; ?>
                                <br><br>

                                <h3 class="title">Approach:</h3>
                                <?= $practice->approach; ?>
                                <br><br>

                                <h3 class="title">Explanation:</h3>
                                <?= $practice->explanation; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($student_id == $practice->examiner_id): ?>
                            <div class="tab-pane active-old" id="examiner_information">
                                <h3 class="title">Candidate Information:</h3>
                                <?= $practice->candidate_instructions; ?>
                                <br><br>

                                <h3 class="title">Patient Information:</h3>
                                <?= $practice->patient_information; ?>
                                <br><br>

                                <h3 class="title">Examiner Information:</h3>
                                <?= $practice->examiner_information; ?>
                                <br><br>

                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>

                                <h3 class="title">Examination Findings:</h3>
                                <?= $practice->exam_findings; ?>
                                <br><br>

                                <h3 class="title">Approach:</h3>
                                <?= $practice->approach; ?>
                                <br><br>

                                <h3 class="title">Explanation:</h3>
                                <?= $practice->explanation; ?>
                            </div>

                            <div class="tab-pane" id="mark_scheme">
                                <?php $this->load->view('frontend/scenario_practice/mark_scheme_tab', $markScheme); ?>
                            </div>
                        <?php endif; ?>

                        <div class="tab-pane" id="marking_criteria">
                            <?= $practice->marking_criteria; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" style="position: sticky; top: 20px; z-index: 99;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Time Left</h3>
                </div>
                <div class="panel-body text-left">
                    <table class="table table-bordered">
                        <tr>
                            <td>Reading Time</td>
                            <td><span id="reading_time"
                                      data-value="<?= $reading_time; ?>"><?= timeToSec($reading_time); ?> minutes</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Practice Time</td>
                            <td><span id="practice_time"
                                      data-value="<?= $practice_time; ?>"><?= timeToSec($practice_time); ?> minutes</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Feedback Time</td>
                            <td><span id="feedback_time"
                                      data-value="<?= $feedback_time; ?>"><?= timeToSec($feedback_time); ?> minutes</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Time</th>
                            <th>
                                <span id="total_time"
                                      data-value="<?= $total_time; ?>"><?= timeToSec($total_time); ?> minutes</span>
                            </th>
                        </tr>
                    </table>

                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="start_timer_btn"><i class="fa fa-play"></i>
                            Start Timer
                        </button>
                        <button type="button" class="btn btn-light" id="changeTimeModalBtn"><i
                                    class="fa fa-clock-o"></i> Change Time
                        </button>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Student Role Information</h3>
                </div>
                <div class="panel-body text-left">
                    <table class="table table-bordered">
                        <tr>
                            <td>Candidate</td>
                            <td><?= ($practice->candidate_id == $student_id) ? '<strong style="color: green;">You are candidate</strong>' : getStudentName($practice->candidate_id) ?></td>
                        </tr>
                        <tr>
                            <td>Patient</td>
                            <td><?= ($practice->patient_id == $student_id) ? '<strong style="color: green;">You are patient</strong>' : getStudentName($practice->patient_id) ?></td>
                        </tr>
                        <tr>
                            <td>Examiner</td>
                            <td><?= ($practice->examiner_id == $student_id) ? '<strong style="color: green;">You are examiner</strong>' : getStudentName($practice->examiner_id) ?></td>
                        </tr>
                    </table>
                    <div class="text-center">
                        <button type="button" class="btn btn-light" id="changeRoleModalBtn"><i
                                    class="fa fa-edit"></i> Change Role
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Change Time Modal -->
<div class="modal fade" id="changeTimeModal" tabindex="-1" role="dialog" aria-labelledby="changeTimeModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="changeTimeForm" method="post" action="#">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Set time for practice</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reading_time">Reading Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="reading_time" name="reading_time"
                                   value="<?= $reading_time; ?>"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="practice_time">Practice Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="practice_time" name="practice_time"
                                   value="<?= $practice_time; ?>"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="feedback_time">Feedback<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="feedback_time" name="feedback_time"
                                   value="<?= $feedback_time; ?>"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="total_time">Total Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="total_time" name="total_time"
                                   value="<?= $total_time; ?>"
                                   readonly>
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div class="modal fade" id="changeRoleModal" tabindex="-1" role="dialog" aria-labelledby="changeRoleModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="changeRoleForm" method="post" action="#">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="changeRoleModalLabel">Set roles for practice</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="candidate_id">Candidate<sup>*</sup></label>
                        <input type="number" class="form-control" id="candidate_id" name="candidate_id"
                               placeholder="Enter your partner ID" value="<?= $practice->candidate_id; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="patient_id">Patient<sup>*</sup></label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id"
                               placeholder="Enter your partner ID" value="<?= $practice->patient_id; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="examiner_id">Examiner<sup>*</sup></label>
                        <input type="number" class="form-control" id="examiner_id" name="examiner_id"
                               placeholder="Enter your partner ID" value="<?= $practice->examiner_id; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript">

    $('#tabs').find('li:first-child').addClass('active');
    $('#tab-content').find('div.tab-pane:first-child').addClass('active');

    $('#changeTimeModalBtn').click(function () {
        $('#changeTimeModal').modal('show');
    });

    $('#changeTimeForm').submit(function (e) {
        e.preventDefault();
        const changeTimeForm = $(this);
        const readingTime    = changeTimeForm.find('#reading_time').val();
        const practiceTime   = changeTimeForm.find('#practice_time').val();
        const feedbackTime   = changeTimeForm.find('#feedback_time').val();
        const totalTime      = changeTimeForm.find('#total_time').val();

        $.ajax({
            url       : '<?= site_url('scenario-practice/change-practice-time') ?>',
            type      : 'POST',
            dataType  : 'json',
            data      : {
                practice_id  : '<?= $practice->id ?>',
                reading_time : readingTime,
                practice_time: practiceTime,
                feedback_time: feedbackTime,
                total_time   : totalTime
            },
            beforeSend: function () {
                changeTimeForm.find('button[type="submit"]').html('Processing...').attr('disabled', true);
            },
            success   : function (response) {
                changeTimeForm.find('button[type="submit"]').html('<i class="fa fa-save"></i> Save').attr('disabled', false);
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#changeTimeModal').modal('hide');
                    window.location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error     : function (response) {
                toastr.error('Failed to update time');
            }
        });
    });

    function calculateTotalTime() {
        const readingTime  = $('#changeTimeForm #reading_time').val();
        const practiceTime = $('#changeTimeForm #practice_time').val();
        const feedbackTime = $('#changeTimeForm #feedback_time').val();
        const total        = parseFloat(readingTime) + parseFloat(practiceTime) + parseFloat(feedbackTime);
        if (total) {
            $('#changeTimeForm #total_time').val(total);
        } else {
            $('#changeTimeForm #total_time').val(0);
        }
    }

    $('#changeRoleModalBtn').click(function () {
        $('#changeRoleModal').modal('show');
    });

    $('#changeRoleForm').submit(function (e) {
        e.preventDefault();
        const changeRoleForm = $(this);
        const candidateId    = changeRoleForm.find('#candidate_id').val();
        const patientId      = changeRoleForm.find('#patient_id').val();
        const examinerId     = changeRoleForm.find('#examiner_id').val();

        $.ajax({
            url       : '<?= site_url('scenario-practice/change-practice-roles') ?>',
            type      : 'POST',
            dataType  : 'json',
            data      : {
                practice_id: '<?= $practice->id ?>',
                candidate_id: candidateId,
                patient_id: patientId,
                examiner_id: examinerId
            },
            beforeSend: function () {
                changeRoleForm.find('button[type="submit"]').html('Processing...').attr('disabled', true);
            },
            success   : function (response) {
                changeRoleForm.find('button[type="submit"]').html('<i class="fa fa-save"></i> Save').attr('disabled', false);
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#changeRoleModal').modal('hide');
                    window.location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error     : function (response) {
                toastr.error('Failed to update roles');
            }
        });
    });

    $('#end_practice_btn').click(function () {
        const practice_id = $(this).data('practice-id');
        if (confirm('Are you sure you want to end this practice?')) {
            $.ajax({
                url       : '<?= site_url('scenario-practice/set-practice-status') ?>',
                type      : 'POST',
                dataType  : 'json',
                data      : {practice_id: practice_id, status: 'Complete'},
                beforeSend: function () {
                    $(this).prop('disabled', true);
                },
                success   : function (response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        window.location.href = '<?= site_url('scenario-practice/practice/summary/' . $practice->id); ?>';
                    } else {
                        toastr.error(response.message);
                    }
                },
                error     : function (response) {
                    toastr.error('Failed to end practice');
                }
            });
        }
    });

    // Start the timer
    $('#start_timer_btn').click(function () {
        $(this).prop('disabled', true);
        playAudioSound();
        totalTimeCountdown();
        readingTimeCountdown();
    });

    function totalTimeCountdown() {
        let totalTime    = $('#total_time').data('value');
        let totalSeconds = totalTime * 60;
        const interval = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#total_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#total_time').html('<span style="color: red">Time Up</span>');
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    // Reading time countdown
    function readingTimeCountdown() {
        let readingTime  = $('#reading_time').data('value');
        let totalSeconds = readingTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="color: green; font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#reading_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#reading_time').html('<span style="color: red">Time Up</span>');
                practiceTimeCountdown();
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    function practiceTimeCountdown() {
        let practiceTime = $('#practice_time').data('value');
        let totalSeconds = practiceTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="color: green; font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#practice_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#practice_time').html('<span style="color: red">Time Up</span>');
                feedbackTimeCountdown();
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    function feedbackTimeCountdown() {
        let feedbackTime = $('#feedback_time').data('value');
        let totalSeconds = feedbackTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="color: green; font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#feedback_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#feedback_time').html('<span style="color: red">Time Up</span>');
                playAudioSound();
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    function copyURL() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function () {
            toastr.success('Practice URL copied to your clipboard');
        }, function (err) {
            toastr.error('Failed to copy URL');
        });
    }

    function playAudioSound() {
        const audio = new Audio('<?= site_url() ?>assets/audio/arcade-player-select-2036.wav');
        audio.play();
    }
</script>
