<div class="page-title">
    <h3>Submit Your Learning Plan</h3>
</div>

<ul class="nav nav-tabs">
  <li><a href="individual-learning-plan"> <i class="fa fa-bars"></i> My Plans</a></li>
  <li class="active"><a href="individual-learning-plan?tab=add"><i class="fa fa-plus"></i> Add New Plan</a></li>
</ul>


<div class="box-body" style="margin-top: 25px;">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>    

    <div class="form-group">
        <label for="aims" class="col-sm-3 control-label">Aims</label>
        <div class="col-sm-9">
            <textarea class="form-control" rows="3" name="aims" id="aims"
                      placeholder="Aims"><?php echo $aims; ?></textarea>
            <?php echo form_error('aims') ?>
        </div>
    </div>
    <div class="form-group">
        <label for="goals" class="col-sm-3 control-label">Goals</label>
        <div class="col-sm-9">
            <textarea class="form-control" rows="3" name="goals" id="goals"
                      placeholder="Goals"><?php echo $goals; ?></textarea>
            <?php echo form_error('goals') ?>
        </div>
    </div>
    <div class="form-group">
        <label for="date_of_achievement" class="col-sm-3 control-label">Date of Achievement</label>
        <div class="col-sm-3">
            <input type="text" autocomplete="off" class="form-control js_datepicker date_picker_icon" name="date_of_achievement" id="date_of_achievement"
                   placeholder="Date of Achievement" value="<?php echo $date_of_achievement; ?>"/>
            <?php echo form_error('date_of_achievement') ?>
        </div>
    </div>
    <div class="form-group">
        <label for="achievement" class="col-sm-3 control-label">Achievement</label>
        <div class="col-sm-9" style="padding-top:8px;">
            <?php echo htmlRadio('achievement', $achievement, array('Yes' => 'Yes', 'No' => 'No')); ?>
            <p><?php echo form_error('achievement') ?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="review_date" class="col-sm-3 control-label">Next Review Date</label>
        <div class="col-sm-3">
            <input type="text" autocomplete="off" class="form-control js_datepicker date_picker_icon" name="review_date" id="review_date"
                   placeholder="Next Review Date" value="<?php echo $review_date; ?>"/>
            <?php echo form_error('review_date') ?>
        </div>
    </div>
    <div class="form-group">
        <label for="future_plan" class="col-sm-3 control-label">Future Plan</label>
        <div class="col-sm-9">
            <textarea class="form-control"  rows="3" name="future_plan" id="future_plan"
                      placeholder="Future Plan"><?php echo $future_plan; ?></textarea>
            <?php echo form_error('future_plan') ?>
        </div>
    </div>
    

    <div class="col-md-9 col-md-offset-3" style="padding-left:5px;">        
        <button type="submit" class="btn btn-primary">Save Your Plan</button>
        <a href="<?php echo site_url('individual-learning-plan') ?>" class="btn btn-default">Cancel</a>
    </div>
    <?php echo form_close(); ?>
</div>