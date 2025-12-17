<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Mock Exam <small><?php echo $button ?></small> 
        <a href="<?php echo site_url(Backend_URL . "exam?id={$exam_id}"); ?>" class="btn btn-default">
            Back
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
        <li class="active">Setup</li>
    </ol>
</section>
<?php load_module_asset('exam', 'css'); ?>
<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">Setup New Mock Exam</div>
        <div class="panel-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="exam_id" class="col-sm-2">Mock Exam<sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_id" class="form-control" id="exam_id">                        
                        <?php echo ExamCourseDroDown($exam_id); ?>
                    </select>
                    <?php echo form_error('exam_id') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exam_centre_id" class="col-sm-2">Exam Centre <sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                        <option value="">-- Select Exam Centre --</option>
                        <?php echo ExamCenterDroDown($exam_centre_id, $exam_id); ?>
                    </select>
                    <?php echo form_error('exam_centre_id') ?>
                </div>
            </div>            

            <div class="form-group">
                <label for="datetime" class="col-sm-2">Exam Date & Time <sup>*</sup></label>
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



            <!-- New Field -->            

            <div class="form-group">
                <label for="student_limit" class="col-sm-2">
                    Student Limit
                    <sup>*</sup>
                </label>
                <div class="col-sm-2">
                    <select class="form-control" name="student_limit" id="student_limit">
                        <?php echo numericDropDown2(1, 500, 1, $student_limit); ?>
                    </select>                    
                </div>
            </div>                        
            <div class="form-group">
                <label for="gmc_exam_dates" class="col-sm-2">
                    GMC/NMC/GDC Exam Dates                    
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control multi-dp" 
                           name="gmc_exam_dates" id="gmc_exam_dates" 
                           placeholder="Exam Dates"
                           value="<?php echo $gmc_exam_dates; ?>"/> 
                </div>
            </div>                        
            <div class="form-group">
                <label for="label" class="col-sm-2">
                    Label                    
                </label>
                <div class="col-sm-2">
                    <input name="label" value="<?php echo $label; ?>" 
                           placeholder="Eg. 1st Mock, 2nd Mock" id="label" 
                           type="text" maxlength="120" class="form-control"/> 
                </div>
            </div>                        
            <!-- New Field -->



            <div class="form-group">
                <label for="pass_station" class="col-sm-2">Stations Required to Pass<sup>*</sup></label>
                <div class="col-sm-2">
                    <select class="form-control" name="pass_station">
                        <?php echo numericDropDown2(3, 15, 1, $pass_station) ?>
                    </select>
                    <?php echo form_error('pass_station') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="passing_criteria" class="col-sm-2">Passing Criteria <sup>*</sup></label>
                <div class="col-sm-8">
                    <textarea name="passing_criteria" id="passing_criteria" rows="10" 
                              class="form-control">From August 2020, candidates must achieve or exceed both the pass mark and gain a pass in minimum of %PassStation% stations to pass the %NameOfMockTest% assessment.

You scored %YourScore% and passed %PassedStations% stations. 
A minimum score of %MinPassMarkRequired% was required to pass.

Your scores of each station.</textarea>                    
                </div>
            </div>

            <div class="form-group">
                <label for="zoom_link" class="col-sm-2">Zoom Link</label>
                <div class="col-sm-10">
                    <input type="url" class="form-control"
                           name="zoom_link" id="zoom_link"
                           placeholder="Zoom Meeting URL" autocomplete="off"
                           value="<?php echo $zoom_link; ?>"/>
                </div>
            </div>

            <?php /* ?>
            <style type="text/css"> select option:disabled { color: #929292; font-style: italic; } </style>            

            <?= loadWhatsAppWidget('Mock'); ?>
            <?php */ ?>

            <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">                
                <a href="<?php echo site_url(Backend_URL . "exam?id={$exam_id}") ?>" class="btn btn-default"><i
                        class="fa fa-long-arrow-left"></i> Cancel & Back to List</a>
                <button type="submit" class="btn btn-primary">Create & Continue <i
                        class="fa fa-long-arrow-right"></i></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div> 
</section>

<?php load_module_asset('exam', 'js'); ?>