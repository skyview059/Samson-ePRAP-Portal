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

<script type="application/javascript">
    $(document).ready(function () {
        const practiceRoles = JSON.parse(localStorage.getItem('practice_roles'));
        if (practiceRoles) {
            $('#stateYourRoleForm #candidate_id').val(practiceRoles.candidate_id);
            $('#stateYourRoleForm #patient_id').val(practiceRoles.patient_id);
            $('#stateYourRoleForm #examiner_id').val(practiceRoles.examiner_id);
        }
        const practiceTime = JSON.parse(localStorage.getItem('practice_time'));
        if (practiceTime) {
            $('#setTimerForm #reading_time').val(practiceTime.reading_time);
            $('#setTimerForm #practice_time').val(practiceTime.practice_time);
            $('#setTimerForm #feedback_time').val(practiceTime.feedback_time);
            $('#setTimerForm #total_time').val(practiceTime.total_time);
        }
    });

    function startPractice() {
        $('#stateYourRoleModal').modal('show');
    }

    $('#stateYourRoleForm').submit(function (e) {
        e.preventDefault();
        const roleForm = $(this);

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

        roleForm.find('button[type="submit"]').html('Processing...').attr('disabled', true);
        localStorage.setItem('practice_roles', JSON.stringify({
            candidate_id: candidate_id,
            patient_id  : patient_id,
            examiner_id : examiner_id
        }));

        roleForm.slideUp(500, function () {
            roleForm.find('button[type="submit"]').html('Confirm & Next <i class="fa fa-long-arrow-right"></i>').attr('disabled', false);
            $("#setTimerForm").slideDown(500);
        });
    });

    $('#setTimerForm').submit(function (e) {
        e.preventDefault();
        const timerForm = $(this);
        timerForm.find('button[type="submit"]').html('Processing...').attr('disabled', true);

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

        timerForm.find('button[type="submit"]').html('Confirm <i class="fa fa-long-arrow-right"></i>').attr('disabled', false);

        window.location.href = '<?= site_url('scenario-practice/exam/practice/' . $exam_id); ?>';
    });

    function calculateTotalTime() {
        const readingTime  = $('#setTimerForm #reading_time').val();
        const practiceTime = $('#setTimerForm #practice_time').val();
        const feedbackTime = $('#setTimerForm #feedback_time').val();
        const total        = parseFloat(readingTime) + parseFloat(practiceTime) + parseFloat(feedbackTime);
        if (total) {
            $('#setTimerForm #total_time').val(total);
        } else {
            $('#setTimerForm #total_time').val(0);
        }
    }
</script>