<?php echo getStudentProcessBar(); ?>
<div class="page-title">
    <h3>Update My Job Profile</h3>
</div>

<form action="<?php echo site_url('student_portal/job_profile_update_action'); ?>" method="post" id="user_form"
      class="form-horizontal"
      enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-12">

            <div class="form-group">
                <label for="email" class="col-sm-4 control-label">Internship <sup>*</sup></label>
                <div class="col-sm-8">
                    <select class="form-control" name="internship">
                        <option value="0">Select</option>
                        <?php echo selectOptions($internship, [
                            1 => '1 Year',
                            2 => '2 Years',
                            3 => '3 Years',
                            4 => '4 Years',
                            5 => '5 Years',
                            6 => '6 Years',
                            7 => '7 Years',
                            8 => '8 Years',
                            9 => '9 Years',
                            10 => '10 Years',
                        ]); ?>
                    </select>
                    <?php echo form_error('internship'); ?>
                </div>
            </div>            

            <div class="form-group">
                <label for="specialty_ids" class="col-sm-4 control-label">Specialty <sup>*</sup></label>
                <div class="col-sm-8">
                    <?php echo getSpecialtiesCheckbox($specialty_ids); ?>
                    <?php echo form_error('specialty_ids'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="specialty_experience" class="col-sm-4 control-label">How many years of experience in this
                    specialty? <sup>*</sup></label>
                <div class="col-sm-8">
                    <select class="form-control" name="specialty_experience">
                        <option value="0">Select</option>
                        <?php echo selectOptions($specialty_experience, [
                            1 => '1 month',
                            2 => '2 months',
                            3 => '3 months',
                            4 => '4 months',
                            5 => '5 months',
                            6 => '6 months',
                            11 => 'less than 1 year',
                            23 => 'less than 2 year',
                            35 => 'less than 3 year',
                            47 => 'less than 4 year',
                            59 => 'less than 5 year',
                            60 => 'more than 5 years',
                            120 => 'more than 10 years'
                        ]); ?>
                    </select>
                    <?php echo form_error('specialty_experience'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="interest_ids" class="col-sm-4 control-label">Which specialty are you interested in?<sup>*</sup></label>
                <div class="col-sm-8">
                    <?php echo getSpecialtiesCheckbox($interest_ids, 'interest_ids'); ?>
                    <?php echo form_error('interest_ids'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="uk_status" class="col-sm-4 control-label">UK Status <sup>*</sup></label>
                <div class="col-sm-3">
                    <select class="form-control" name="uk_status" id="uk_status">
                        <option value="0">Select</option>
                        <?php echo selectOptions($uk_status, [
                            'British' => 'British',
                            'Visa with right work' => 'Visa with right work',
                            'Visa with no right to work' => 'Visa with no right to work',
                            'Refugee' => 'Refugee',
                            'Leave to remain' => 'Leave to remain',
                            'Indefinite leave to remain' => 'Indefinite leave to remain',
                            'Other' => 'Other',
                        ]); ?>
                    </select>
                    <?php echo form_error('uk_status'); ?>
                </div>
                <div class="col-sm-5 <?php echo ($uk_status != 'Other') ? 'hidden' : ''; ?>"
                     id="uk_status_other_area">
                    <input name="uk_status_other" value="<?php echo $uk_status_other; ?>" class="form-control" type="text"/>
                </div>
            </div>

            <div class="form-group">
                <label for="right_to_work" class="col-sm-4 control-label">Do you have a right to work in the UK?
                    <sup>*</sup></label>
                <div class="col-sm-8">
                    <?php echo htmlRadio('right_to_work', $right_to_work, ['Yes' => 'Yes', 'No' => 'No']); ?>
                    <?php echo form_error('right_to_work'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="professional_qualifications" class="col-sm-4 control-label">Postgraduate professional
                    qualifications<sup>*</sup></label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="professional_qualifications" rows="5"
                              maxlength="990"><?php echo $professional_qualifications; ?></textarea>
                    <?php echo form_error('professional_qualifications'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="training_courses" class="col-sm-4 control-label">Training courses <sup>*</sup></label>
                <div class="col-sm-3">
                    <select class="form-control" name="training_courses" id="training_courses">
                        <option value="0">Select</option>
                        <?php echo selectOptions($training_courses, [
                            'ALS' => 'ALS',
                            'APLS' => 'APLS',
                            'BLS' => 'BLS',
                            'ATLS' => 'ATLS',
                            'Other' => 'Other',
                        ]); ?>
                    </select>
                    <?php echo form_error('training_courses'); ?>
                </div>
                <div class="col-sm-5 <?php echo ($training_courses != 'Other') ? 'hidden' : ''; ?>"
                     id="training_courses_other_area">
                    <input name="training_courses_other" value="<?php echo $training_courses_other; ?>" class="form-control" type="text"/>
                </div>
            </div>

            <div class="form-group">
                <label for="audit" class="col-sm-4 control-label">Audit<sup>*</sup></label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="audit" rows="5"
                              maxlength="990"><?php echo $audit; ?></textarea>
                    <?php echo form_error('audit'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="research" class="col-sm-4 control-label">Research<sup>*</sup></label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="research" rows="5"
                              maxlength="990"><?php echo $research; ?></textarea>
                    <?php echo form_error('research'); ?>
                </div>
            </div>
            <div class="form-group" style="padding-bottom:50px;">
                <div class="col-sm-8 col-md-offset-4">
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>
                    <a href="<?php echo site_url('job-profile'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Cancel
                    </a>
                </div>
            </div>

        </div>

    </div>
</form>
<script language="JavaScript">
    $(document).on('change', '#training_courses', function () {
        var value = $(this).val();
        if (value === 'Other') {
            $('#training_courses_other_area').removeClass('hidden');
        } else {
            $('#training_courses_other_area').addClass('hidden');
        }
    });

    $(document).on('change', '#uk_status', function () {
        var value = $(this).val();
        if (value === 'Other') {
            $('#uk_status_other_area').removeClass('hidden');
        } else {
            $('#uk_status_other_area').addClass('hidden');
        }
    });
</script>