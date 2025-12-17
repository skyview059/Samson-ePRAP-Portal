<style>
    #scenarioPracticeContent {
        -webkit-user-select: none; /* Disable text selection in Safari */
        -moz-user-select: none; /* Disable text selection in Firefox */
        -ms-user-select: none; /* Disable text selection in IE/Edge */
        user-select: none; /* Disable text selection in modern browsers */
    }
    img {
        pointer-events: none;
        -webkit-user-drag: none;
    }

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

<div class="container-fluid" id="scenarioPracticeContent">
    <div class="row">
        <div class="col-md-8">
            <div style="padding: 50px 0">
                <h2><?php echo $practice->presentation; ?></h2>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div style="padding-top: 60px">
                <h4 style="text-align: right; font-weight: 700; margin-bottom: 15px;">Student ID
                    No: <?= showStudentID(); ?></h4>
                <button class="btn btn-primary" onclick="startPractice()">Practice <i
                            class="fa fa-long-arrow-right"></i></button>
                <button type="button" class="btn btn-info" onclick="copyURL()"><i class="fa fa-copy"></i> Copy Scenario
                    URL
                </button>
                <a href="<?= site_url('scenario-practice/exam/view/' . $practice->exam_id); ?>" class="btn btn-primary">
                    <i class="fa fa-long-arrow-left"></i> Back to Scenarios</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#examiner_information" data-toggle="tab">Examiner</a>
                        </li>
                        <li>
                            <a href="#candidate_instructions" data-toggle="tab">Candidate</a>
                        </li>
                        <li>
                            <a href="#patient_information" data-toggle="tab">Patient</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="candidate_instructions">
                            <?= $practice->candidate_instructions; ?>
                        </div>

                        <div class="tab-pane" id="patient_information">
                            <?php if ($practice->candidate_instructions): ?>
                                <h3 class="title">Candidate Information:</h3>
                                <?= $practice->candidate_instructions; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->patient_information): ?>
                                <h3 class="title">Patient Information:</h3>
                                <?= $practice->patient_information; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->examiner_information): ?>
                                <h3 class="title">Examiner Information:</h3>
                                <?= $practice->examiner_information; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->setup): ?>
                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->setup): ?>
                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->exam_findings): ?>
                                <h3 class="title">Examination Findings:</h3>
                                <?= $practice->exam_findings; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->approach): ?>
                                <h3 class="title">Approach:</h3>
                                <?= $practice->approach; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->explanation): ?>
                                <h3 class="title">Explanation:</h3>
                                <?= $practice->explanation; ?>
                                <br><br>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane active" id="examiner_information">
                            <?php if ($practice->candidate_instructions): ?>
                                <h3 class="title">Candidate Information:</h3>
                                <?= $practice->candidate_instructions; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->patient_information): ?>
                                <h3 class="title">Patient Information:</h3>
                                <?= $practice->patient_information; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->examiner_information): ?>
                                <h3 class="title">Examiner Information:</h3>
                                <?= $practice->examiner_information; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->setup): ?>
                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->setup): ?>
                                <h3 class="title">Set up:</h3>
                                <?= $practice->setup; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->exam_findings): ?>
                                <h3 class="title">Examination Findings:</h3>
                                <?= $practice->exam_findings; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->approach): ?>
                                <h3 class="title">Approach:</h3>
                                <?= $practice->approach; ?>
                                <br><br>
                            <?php endif; ?>

                            <?php if ($practice->explanation): ?>
                                <h3 class="title">Explanation:</h3>
                                <?= $practice->explanation; ?>
                                <br><br>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="stateYourRoleModal" tabindex="-1" role="dialog" aria-labelledby="stateYourRoleModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="stateYourRoleForm" method="post" action="#">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="stateYourRoleModalLabel">Set roles for practice</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="candidate_id">Candidate<sup>*</sup></label>
                        <input type="number" class="form-control" id="candidate_id" name="candidate_id"
                               placeholder="Enter your partner ID" required>
                    </div>
                    <div class="form-group">
                        <label for="patient_id">Patient<sup>*</sup></label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id"
                               placeholder="Enter your partner ID" required>
                    </div>
                    <div class="form-group">
                        <label for="examiner_id">Examiner<sup>*</sup></label>
                        <input type="number" class="form-control" id="examiner_id" name="examiner_id"
                               placeholder="Enter your partner ID" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Confirm & Next <i
                                class="fa fa-long-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- Set time for practice-->
            <form id="setTimerForm" method="post" action="#" style="display: none">
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
                            <input type="text" class="form-control" id="reading_time" name="reading_time" value="2"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="practice_time">Practice Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="practice_time" name="practice_time"
                                   value="8"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="feedback_time">Feedback<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="feedback_time" name="feedback_time"
                                   value="10"
                                   onkeyup="calculateTotalTime();">
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="total_time">Total Time<sup>*</sup></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="total_time" name="total_time" value="20"
                                   readonly>
                            <div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Confirm <i
                                class="fa fa-long-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('frontend/scenario_practice/start_practice_modal'); ?>

<script type="application/javascript">
    function copyURL() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function () {
            toastr.success('Scenario URL copied to your clipboard');
        }, function (err) {
            toastr.error('Failed to copy URL');
        });
    }

    document.addEventListener('contextmenu', function (event) {
        event.preventDefault(); // Disable right-click
    });

    document.addEventListener('keydown', function (event) {
        if (event.ctrlKey && (event.key === "c" || event.key === "x" || event.key === "u")) {
            event.preventDefault(); // Disable Ctrl+C, Ctrl+X, and Ctrl+U (view source)
        }
    });

    document.onkeydown = function (event) {
        if (event.keyCode === 123) { // F12 key
            return false;
        }
        if (event.ctrlKey && event.shiftKey && (event.keyCode === 73 || event.keyCode === 74)) {
            return false; // Ctrl+Shift+I (DevTools) and Ctrl+Shift+J (Console)
        }
    };
</script>
