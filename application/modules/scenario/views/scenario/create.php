<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Scenario <small><?php echo $button ?></small>
        <a href="<?php echo site_url(Backend_URL . 'scenario') ?>"
           class="btn btn-default">
            Back
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>scenario">Scenario</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">Add New Scenario</div>
        <div class="panel-body"><?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="exam_id" class="col-sm-2">Exam <sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_id" class="form-control" id="exam_id">
                        <?php echo ExamCourseDroDown($exam_id); ?>
                    </select>
                    <?php echo form_error('exam_id') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="reference_number" class="col-sm-2">Scenario No</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" onkeypress="return DigitOnly(event);" maxlength="10" class="form-control"
                               name="reference_number" id="reference_number"
                               placeholder="Reference Number" value="<?php echo $reference_number; ?>"/>
                        <span class="input-group-addon">Max 10 Characters</span>
                    </div>
                    <?php echo form_error('reference_number') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="presentation" class="col-sm-2">Presentation <sup>*</sup></label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="text" maxlength="255" class="form-control" name="presentation" id="presentation"
                               placeholder="Presentation"
                               value="<?php echo $presentation; ?>"/>
                        <span class="input-group-addon">Max 255 Characters</span>
                    </div>
                    <?php echo form_error('presentation') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2">Diagnosis <sup>*</sup></label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="text" maxlength="255" class="form-control" name="name" id="name" placeholder="Diagnosis"
                               value="<?php echo $name; ?>"/>
                        <span class="input-group-addon">Max 255 Characters</span>
                    </div>
                    <?php echo form_error('name') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="candidate_instructions" class="col-sm-2">Candidate Instructions</label>
                <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="candidate_instructions"
                                  id="candidate_instructions"><?php echo $candidate_instructions; ?></textarea>
                    <?php echo form_error('candidate_instructions') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="patient_information" class="col-sm-2">Patient Information</label>
                <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="patient_information" id="patient_information"><?php echo $patient_information; ?></textarea>
                    <?php echo form_error('patient_information') ?>
                </div>
            </div>

            <div class="form-group">
                    <label for="examiner_information" class="col-sm-2">Examiner's Prompt</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="examiner_information" id="examiner_information"><?php echo $examiner_information; ?></textarea>
                        <?php echo form_error('examiner_information') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="examiner_information" class="col-sm-2">Set up</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="setup" id="setup"><?php echo $setup; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="examiner_information" class="col-sm-2">Examination Findings</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="exam_findings" id="exam_findings"><?php echo $exam_findings; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="examiner_information" class="col-sm-2">Approach</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="approach" id="approach"><?php echo $approach; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="examiner_information" class="col-sm-2">Explanation</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="explanation" id="explanation"><?php echo $explanation; ?></textarea>
                    </div>
                </div>            

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                        <a href="<?php echo site_url(Backend_URL . "scenario?id={$id}") ?>"
                        class="btn btn-default">Cancel</a>
                    </div>
                </div>
            <?php echo form_close(); ?></div>
    </div>

</section>

<?php loadCKEditor5ClassicBasic(['#candidate_instructions', '#patient_information', '#examiner_information', '#setup', '#exam_findings', '#approach', '#explanation']); ?>