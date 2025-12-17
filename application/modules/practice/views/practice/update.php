<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Mock Practice<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>practice">Mock Practice</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<?php load_module_asset('practice', 'css'); ?>
<section class="content personaldevelopment"><?php echo practiceTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update Mock Practice</h3>
            <?php echo $this->session->flashdata('message'); ?>
            <?php // echo validation_errors(); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                
                <div class="form-group">
                    <label for="label" class="col-sm-2 control-label">
                        Title                    
                    </label>
                    <div class="col-sm-2">
                        <input name="label" value="<?php echo $label; ?>" 
                               placeholder="Eg. 1st Practice, 2nd Practice" id="label" 
                               type="text" maxlength="120" class="form-control"/> 
                    </div>
                </div>  
                
                <div class="form-group">
                    <label for="practice_id" class="col-sm-2 control-label">Practice Name <sup>*</sup></label>
                    <div class="col-sm-10">
                        <select name="practice_id" class="form-control" id="practice_id">                        
                            <?php echo getPracticeDropDown($practice_id); ?>
                        </select>
                        <?php echo form_error('practice_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_centre_id" class="col-sm-2 control-label">Practice Centre <sup>*</sup></label>
                    <div class="col-sm-10">
                        <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                            <option value="">-- Select Practice Centre --</option>
                            <?php echo ExamCenterDroDown($exam_centre_id, 0); ?>
                        </select>
                        <?php echo form_error('exam_centre_id') ?>
                    </div>
                </div>                              

                <div class="form-group">
                    <label for="datetime" class="col-sm-2 control-label">Practice Date & Time <sup>*</sup></label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" autocomplete="off" class="form-control js_datepicker" name="date" id="date" placeholder="Datetime"
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


                <div class="form-group">
                    <label for="seat" class="col-sm-2 control-label">
                        Practice Duration <sup>*</sup>
                    </label>
                    <div class="col-sm-2">                      
                        <div class="input-group">                   
                            <select class="form-control" name="duration">
                                <?php echo numeric_dropdown_2(0, 12, 1, $duration); ?>
                            </select> 
                            <span class="input-group-addon">Hours</span>
                        </div>
                    </div>
                </div>        

                <div class="form-group">
                    <label for="seat" class="col-sm-2 control-label">
                        Student Limit
                        <sup>*</sup>
                    </label>
                    <div class="col-sm-2">
                        <select class="form-control" name="seat" id="seat">
                            <?php echo numericDropDown2(1, 100, 1, $seat); ?>
                        </select>                    
                    </div>
                </div>
                <div class="form-group">
                    <label for="seat" class="col-sm-2 control-label">
                        Status
                        <sup>*</sup>
                    </label>
                    <div class="col-sm-2">
                        <select class="form-control" name="status" id="status">
                            <?php echo selectOptions($status, [
                                'Draft' => 'Draft',
                                'Published' => 'Publish to Student Portal',
//                                'Cancelled' => 'Cancel',
                            ]); ?>
                        </select>                    
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo site_url(Backend_URL . 'practice?id=' . $practice_id) ?>" class="btn btn-default">
                            Back to Practice List
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php load_module_asset('practice', 'js'); ?>