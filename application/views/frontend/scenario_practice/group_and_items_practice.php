<style>
    #accordion {
        margin: auto;
        max-width: 100%;
        padding-top: 15px;
    }

    .panel-heading a {
        display: block;
        position: relative;
        font-weight: bold;

        &::after {
            content: "";
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 5px;
            position: absolute;
            right: 0;
            top: 0;
            transform: rotate(45deg);
        }

        &[aria-expanded="true"]::after {
            transform: rotate(-135deg);
            top: 5px;
        }
    }

    .custom_title {
        cursor: pointer;
    }

    .panel-title > a:hover {
        text-decoration: none;
    }

    .custom_title:hover {
        text-decoration: underline;
        color: #6C00A1;
        font-weight: bold;
        transition: color 0.4s, font-weight 0.4s;
    }

    .form-group label {
        font-size: 15px;
        font-weight: normal;
    }

    li.diagnosis_title {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        transition: background-color 0.3s;
    }

    li.diagnosis_title:hover {
        background-color: #f5f5f5;
    }

    .viewDiagnosisBtn {
        display: none;
        padding: 0;
        margin: 0 0 0 10px;
        font-size: 12px;
        color: #007bff;
    }

    li.diagnosis_title .view_result {
        margin-right: 10px;
    }

    li.diagnosis_title .view_result, li.diagnosis_title .undo_btn {
        display: none;
        color: red;
    }

    li.diagnosis_title:hover .view_result, li.diagnosis_title:hover .undo_btn, li.diagnosis_title:hover .viewDiagnosisBtn {
        display: inline-block;
    }

    li.diagnosis_title .status_completed {
        color: green;
        font-size: 14px;
        font-weight: bold
    }

    .show_subject_items {
        font-size: 13px;
        margin-left: 15px;
        background: #D7D7D7;
        padding: 2px 10px;
        border-radius: 5px;
        color: black;
    }

    .show_subject_items.success {
        background: green;
        color: white;
    }

    .panel-body button.resetSubjectItems {
        position: absolute;
        margin-top: -60px;
        right: 0;
        margin-right: 100px;
        font-weight: 700;
        font-size: 14px;
        color: red;
    }
</style>

<div class="row" style="padding-bottom: 15px">
    <div class="col-md-5">
        <h2 style="margin-top: 0;"><?= $exam->name; ?> /UKMLA OSCE Practice</h2>
        <p>Total scenarios: 
            <span id="total_scenarios_count" class="label label-info"></span>
            <span style="font-size: 11pt;color: #c70000; padding-left:50px;">Please select scenario to start practising</span>    
        </p>
    </div>
    <div class="col-md-7 text-right">
        <button type="button" class="btn btn-xs btn-primary" id="changeRoleModalBtn">Change Roles</button>
        <button type="button" class="btn btn-xs btn-primary" id="changeTimeModalBtn">Change Time</button>
        <button type="button" class="btn btn-xs btn-success" id="showCompletedItems">Completed</button>
        <button type="button" class="btn btn-xs btn-warning" id="showIncompletedItems">Incompleted</button>
        <button type="button" class="btn btn-xs btn-info" id="showAllItems">All Scenario</button>
        <button type="button" class="btn btn-xs btn-primary" id="showHideFullList">Show Full List</button>
        <button type="button" class="btn btn-xs btn-danger" id="resetFullBank">Reset Full Bank</button>
        <a href="<?= site_url('scenario-practice'); ?>" class="btn btn-xs btn-primary">Back to Exams</a>
    </div>
</div>

<?php if ($scenarioItems): ?>
    <?php foreach ($scenarioItems as $subject): ?>
        <div class="panel-group" id="accordion">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion"
                           href="#collapseItem<?php echo $subject->subject_id; ?>"><?php echo $subject->subject_name; ?></a>
                    </h3>
                </div>
                <div id="collapseItem<?php echo $subject->subject_id; ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <button type="button" class="btn btn-xs btn-link resetSubjectItems">Reset</button>
                        <div class="row">
                            <ol>
                                <?php
                                foreach ($subject->topics as $topic):
                                    echo '<li><h4 style="font-weight: bold">' . $topic->topic_name . '</h4></li>';
                                    echo '<ol>';
                                    $sl = 0;
                                    foreach ($topic->topic_items as $item):
                                        ?>
                                        <li id="li-<?= $item->id; ?>"
                                            class="diagnosis_title status_<?= strtolower($item->status); ?>">
                                            <div>
                                                <span><?php echo ++$sl; ?>.</span>
                                                <span class="custom_title"
                                                      data-item_id="<?= $item->id; ?>"
                                                      data-exam_id="<?= $exam->id; ?>"
                                                      data-practice_id="<?= $item->practice_id; ?>">
                                                    <?= $item->presentation; ?>
                                                </span>
                                            </div>
                                            <div class="pull-right">
                                                <!--                                                <button class="viewDiagnosisBtn btn btn-link"-->
                                                <!--                                                        data-id="-->
                                                <?php //= $item->id
                                                ?><!--">View Diagnosis-->
                                                <!--                                                </button>-->
                                                <span id="status_<?php echo $item->id; ?>">
                                                    <?php echo scenarioStatus($item->id, $item->status, $item->practice_id); ?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php
                                    endforeach;
                                    echo '</ol>';
                                endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

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
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
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
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="application/javascript">

    $('.custom_title').click(function () {
        const item_id     = parseInt($(this).data('item_id'));
        const exam_id     = parseInt($(this).data('exam_id'));
        const practice_id = parseInt($(this).data('practice_id'));

        const practiceTime  = JSON.parse(localStorage.getItem('practice_time'));
        const practiceRoles = JSON.parse(localStorage.getItem('practice_roles'));
        if (!practiceTime) {
            toastr.error('Please set time before starting practice');
            return;
        }
        if (!practiceRoles) {
            toastr.error('Please set roles before starting practice');
            return;
        }

        $.ajax({
            url       : '<?= site_url('scenario-practice/generate-practice-url'); ?>',
            type      : "POST",
            dataType  : "json",
            data      : {
                item_id      : item_id,
                exam_id      : exam_id,
                practice_id  : practice_id,
                practice_roles: practiceRoles,
                practice_time : practiceTime
            },
            beforeSend: function () {
                toastr.info('Generating practice URL...');
            },
            success   : function (response) {
                toastr.clear();
                if (response.status === 'success') {
                    toastr.success(response.message);
                    window.location.href = response.url;
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });


    // Start of the change time & role scripts
    $('#changeTimeModalBtn').click(function () {
        const practiceTime = JSON.parse(localStorage.getItem('practice_time'));
        if (practiceTime) {
            $('#changeTimeForm #reading_time').val(practiceTime.reading_time);
            $('#changeTimeForm #practice_time').val(practiceTime.practice_time);
            $('#changeTimeForm #feedback_time').val(practiceTime.feedback_time);
            $('#changeTimeForm #total_time').val(practiceTime.total_time);
        }
        $('#changeTimeModal').modal('show');
    });

    $('#changeTimeForm').submit(function (e) {
        e.preventDefault();
        const timerForm    = $(this);
        const readingTime  = parseFloat(timerForm.find('#reading_time').val());
        const practiceTime = parseFloat(timerForm.find('#practice_time').val());
        const feedbackTime = parseFloat(timerForm.find('#feedback_time').val());
        const totalTime    = parseFloat(timerForm.find('#total_time').val());

        localStorage.setItem('practice_time', JSON.stringify({
            reading_time : readingTime,
            practice_time: practiceTime,
            feedback_time: feedbackTime,
            total_time   : totalTime
        }));
        toastr.success('Time set successfully');
        $('#changeTimeModal').modal('hide');
    });

    $('#changeRoleModalBtn').click(function () {
        const practiceRoles = JSON.parse(localStorage.getItem('practice_roles'));
        if (practiceRoles) {
            $('#changeRoleForm #candidate_id').val(practiceRoles.candidate_id);
            $('#changeRoleForm #patient_id').val(practiceRoles.patient_id);
            $('#changeRoleForm #examiner_id').val(practiceRoles.examiner_id);
        }
        $('#changeRoleModal').modal('show');
    });

    $('#changeRoleForm').submit(function (e) {
        e.preventDefault();
        const roleForm     = $(this);
        const candidate_id = parseInt(roleForm.find('#candidate_id').val());
        const patient_id   = parseInt(roleForm.find('#patient_id').val());
        const examiner_id  = parseInt(roleForm.find('#examiner_id').val());
        const student_id   = parseInt(<?= $student_id; ?>);

        if(!candidate_id || !patient_id || !examiner_id) {
            toastr.error('Please fill all fields');
            return;
        }
        if(candidate_id !== student_id && patient_id !== student_id && examiner_id !== student_id) {
            toastr.error('You must be one of the candidate, patient or examiner, you can not be all three. Please enter your ID in one of the fields. Your ID: ' + student_id);
            return;
        }

        // Save roles to localStorage
        localStorage.setItem('practice_roles', JSON.stringify({
            candidate_id: candidate_id,
            patient_id  : patient_id,
            examiner_id : examiner_id
        }));
        toastr.success('Roles changed successfully');
        $('#changeRoleModal').modal('hide');
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
    // End of the change time & role scripts


    // Start show or hide options
    $('#showCompletedItems').click(function () {
        $('.status_incomplete').hide();
        $('.status_complete').show();
    });

    $('#showIncompletedItems').click(function () {
        $('.status_complete').hide();
        $('.status_incomplete').show();
    });

    $('#showAllItems').click(function () {
        $('.status_complete').show();
        $('.status_incomplete').show();
    });

    $('#showHideFullList').click(function () {
        const text = $(this).text();
        if (text === 'Show Full List') {
            $(this).text('Hide Full List');
            $('.panel-collapse').addClass('in');
        } else {
            $(this).text('Show Full List');
            $('.panel-collapse').removeClass('in');
        }
    });
    // End show or hide options

    // Start of the count items script
    document.addEventListener('DOMContentLoaded', function () {
        // count li.diagnosis_title by accordion item
        countItems();
    });

    function countItems() {
        const accordionItems = document.querySelectorAll('.panel-collapse');
        let totalItems       = 0;
        let totalCompleted   = 0;
        accordionItems.forEach(function (item) {
            const count          = item.querySelectorAll('li.diagnosis_title').length;
            const countCompleted = item.querySelectorAll('li.diagnosis_title .status_completed').length;

            // remove previous count
            if (item.previousElementSibling.querySelector('a').querySelector('.show_subject_items')) {
                item.previousElementSibling.querySelector('a').querySelector('.show_subject_items').remove();
            }
            item.previousElementSibling.querySelector('a').innerHTML += ` <span class="show_subject_items" style="margin-left: 15px;">${countCompleted}/${count}</span>`;

            if (countCompleted === count && countCompleted > 0) {
                // remove previous completed
                if (item.previousElementSibling.querySelector('a').querySelector('.show_subject_items.success')) {
                    item.previousElementSibling.querySelector('a').querySelector('.show_subject_items.success').remove();
                }
                item.previousElementSibling.querySelector('a').innerHTML += ` <span class="show_subject_items success" style="background-color: green;">Completed</span>`;
            }
            totalItems += count;
            totalCompleted += countCompleted;
        });
        // show total completed items
        document.querySelector('#total_scenarios_count').innerHTML = `${totalCompleted}/${totalItems}`;
    }

    // End of the count items script

    // $('.viewDiagnosisBtn').click(function () {
    //     const item_id = $(this).data('id');
    //     $('#li-' + item_id + ' .custom_title').toggleClass('hidden');
    //     $('#li-' + item_id + ' .viewDiagnosisBtn').text(function (i, text) {
    //         return text.trim() === "View Diagnosis" ? "Hide Diagnosis" : "View Diagnosis";
    //     });
    // });

    function setStatus(item_id, status) {
        $.ajax({
            url       : '<?= site_url('scenario-practice/set-status'); ?>',
            type      : "POST",
            dataType  : "text",
            data      : {item_id: item_id, status: status},
            beforeSend: function () {
                $('#status_' + item_id).html('<span style="color: #F7C600">Processing...</span>');
            },
            success   : function (msg) {
                $('#status_' + item_id).html(msg);
                countItems();
            }
        });
    }

    $('.resetSubjectItems').click(function () {
        const $this     = $(this);
        const $panel    = $this.closest('.panel');
        const $collapse = $panel.find('.panel-collapse');
        const $items    = $collapse.find('li.diagnosis_title');
        $items.each(function () {
            const $item   = $(this);
            const item_id = $item.attr('id').split('-')[1];
            setStatus(item_id, 'Incomplete');
        });
        countItems();
    });

    $('#resetFullBank').click(function () {
        if (confirm('Are you sure to reset all scenarios?')) {
            const $items = $('li.diagnosis_title');
            $items.each(function () {
                const $item   = $(this);
                const item_id = $item.attr('id').split('-')[1];
                setStatus(item_id, 'Incomplete');
            });
            countItems();
        }
    });
</script>
