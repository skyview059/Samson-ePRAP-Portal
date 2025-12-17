<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Individual Learning Plan <small><?php echo $button ?></small> <a
                href="<?php echo site_url(Backend_URL . 'development_plan') ?>" class="btn btn-default">Back</a></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>development_plan">Individual Learning Plan</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Add New Development Plan</div>
  <div class="panel-body">
      <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="student_id" class="col-sm-2">Student<sup>*</sup></label>
                <div class="col-sm-10">
                    <select class="form-control select2" name="student_id" id="student_id">
                        <option value="0">-- Select Student --</option>
                        <?php echo getStudentList($student_id); ?>
                    </select>
                    <?php echo form_error('student_id') ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="aims" class="col-sm-2">Aims</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" name="aims" id="aims"
                              placeholder="Aims"><?php echo $aims; ?></textarea>
                    <?php echo form_error('aims') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="goals" class="col-sm-2">Goals</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" name="goals" id="goals"
                              placeholder="Goals"><?php echo $goals; ?></textarea>
                    <?php echo form_error('goals') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_achievement" class="col-sm-2">Date of Achievement</label>
                <div class="col-sm-3">
                    <input type="text" autocomplete="off" class="form-control js_datepicker date_picker_icon" name="date_of_achievement" id="date_of_achievement"
                           placeholder="Date of Achievement" value="<?php echo $date_of_achievement; ?>"/>
                    <?php echo form_error('date_of_achievement') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="achievement" class="col-sm-2">Achievement</label>
                <div class="col-sm-10"
                     style="padding-top:8px;"><?php echo htmlRadio('achievement', $achievement, array('Yes' => 'Yes', 'No' => 'No')); ?></div>
            </div>
            <div class="form-group">
                <label for="review_date" class="col-sm-2">Next Review Date</label>
                <div class="col-sm-3">
                    <input type="text" autocomplete="off" class="form-control js_datepicker date_picker_icon" name="review_date" id="review_date"
                           placeholder="Next Review Date" value="<?php echo $review_date; ?>"/>
                    <?php echo form_error('review_date') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="future_plan" class="col-sm-2">Future Plan</label>
                <div class="col-sm-10">
                    <textarea class="form-control"  rows="3" name="future_plan" id="future_plan"
                              placeholder="Future Plan"><?php echo $future_plan; ?></textarea>
                    <?php echo form_error('future_plan') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="review" class="col-sm-2">Review</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" name="review" id="review"
                              placeholder="Review"><?php echo $review; ?></textarea>
                    <?php echo form_error('review') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="review" class="col-sm-2">Note</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" name="note" id="review"
                              placeholder="Note"><?php echo $note; ?></textarea>
                    <?php echo form_error('note') ?>
                </div>
            </div>

            <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url(Backend_URL . 'development_plan') ?>" class="btn btn-default">Cancel</a>
            </div>
            <?php echo form_close(); ?>
  </div>
</div>

</section>