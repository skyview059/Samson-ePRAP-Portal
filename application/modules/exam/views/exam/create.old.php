<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Mock Exam <small><?php echo $button ?></small> 
        <a href="<?php echo site_url(Backend_URL . "exam?id={$exam_id}" ); ?>" class="btn btn-default">
            Back
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
        <li class="active">Setup</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Setup New Mock Exam</h3>
        </div>

        <div class="box-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="exam_id" class="col-sm-2 control-label">Mock Exam<sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_id" class="form-control" id="exam_id">                        
                        <?php echo ExamCourseDroDown($exam_id); ?>
                    </select>
                    <?php echo form_error('exam_id') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exam_centre_id" class="col-sm-2 control-label">Exam Centre <sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                        <option value="">-- Select Exam Centre --</option>
                        <?php echo ExamCenterDroDown($exam_centre_id, $exam_id); ?>
                    </select>
                    <?php echo form_error('exam_centre_id') ?>
                </div>
            </div>            

            <div class="form-group">
                <label for="datetime" class="col-sm-2 control-label">Exam Date <sup>*</sup></label>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" class="form-control js_datepicker" name="date" id="date" placeholder="Datetime"
                               value="<?php echo $date; ?>"/>                        
                    </div>
                    <?php echo form_error('date') ?>                        
                </div>
            </div>
            <div class="form-group">
                <label for="datetime" class="col-sm-2 control-label">Exam Time <sup>*</sup></label>
                <div class="col-sm-5">
                    <div class="input-group">                        
                        <span class="input-group-addon">Hours</span>
                        <select class="form-control" name="hour">
                            <?php echo numeric_dropdown_2(0, 12, 1, $hour); ?>
                        </select>
                        <span class="input-group-addon">Minutes</span>
                        <select class="form-control" name="min">
                            <?php echo numeric_dropdown_2(0, 59, 5, $min); ?>
                        </select>
                        <span class="input-group-addon">AM/PM</span>
                        <select class="form-control" name="am_pm">
                            <?php echo selectOptions($am_pm, ['am' => 'AM', 'pm' => 'PM']); ?>
                        </select>
                    </div>
                    <?php echo form_error('date') ?> <?php echo form_error('hour'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="pass_station" class="col-sm-2 control-label">Stations Required to Pass<sup>*</sup></label>
                <div class="col-sm-2">
                    <select class="form-control" name="pass_station">
                        <?php echo numericDropDown2(5, 15, 1, $pass_station) ?>
                    </select>
                    <?php echo form_error('pass_station') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="passing_criteria" class="col-sm-2 control-label">Passing Criteria <sup>*</sup></label>
                <div class="col-sm-8">
                    <textarea name="passing_criteria" id="passing_criteria" rows="10" 
                              class="form-control">From August 2020, candidates must achieve or exceed both the pass mark and gain a pass in minimum of %PassStation% stations to pass the %NameOfMockTest% assessment.

You scored %YourScore% and passed %PassedStations% stations a minimum scored of %MinPassMarkRequired% was required to pass 
Your scores of each station.
                    </textarea>                    
                </div>
            </div>

            <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <a href="<?php echo site_url(Backend_URL . "exam?id={$exam_id}") ?>" class="btn btn-default"><i
                            class="fa fa-long-arrow-left"></i> Cancel & Back to List</a>
                <button type="submit" class="btn btn-primary">Create & Continue <i
                            class="fa fa-long-arrow-right"></i></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>