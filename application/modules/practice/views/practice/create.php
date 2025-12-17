<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Mock Practice <small><?php echo $button ?></small> 
        <a href="<?php echo site_url(Backend_URL . "practice?id={$practice_id}" ); ?>" class="btn btn-default">
            Back
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>practice">Practice</a></li>
        <li class="active">Setup</li>
    </ol>
</section>
<?php load_module_asset('practice','css');?>
<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Setup New Mock Practice</div>
  <div class="panel-body">
      <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
      
            <div class="form-group">
                <label for="label" class="col-sm-2">
                    Title                    
                </label>
                <div class="col-sm-4">
                    <input name="label" value="<?php echo $label; ?>" 
                           placeholder="Eg. 1st Practical, 2nd Practical" id="label" 
                           type="text" maxlength="120" class="form-control"/> 
                </div>
            </div>   
      
            <!-- New Field -->
            <div class="form-group">
                <label for="practice_id" class="col-sm-2">Practice Category<sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="practice_id" class="form-control" id="practice_id">                        
                        <?php echo getPracticeDropDown($practice_id); ?>
                    </select>
                    <?php echo form_error('practice_id') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exam_centre_id" class="col-sm-2">Practice Centre <sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                        <option value="">-- Select Practice Centre --</option>
                        <?php echo ExamCenterDroDown($exam_centre_id, 0); ?>
                    </select>
                    <?php echo form_error('exam_centre_id') ?>
                </div>
            </div>            

            <div class="form-group">
                <label for="datetime" class="col-sm-2">Practice Time <sup>*</sup></label>                
                <div class="col-sm-5">
                    <div class="input-group">                        
                        <span class="input-group-addon">Hour</span>
                        <select class="form-control" name="hour">
                            <?php echo numeric_dropdown_2(0, 12, 1, $hour); ?>
                        </select>
                        <span class="input-group-addon">Minute</span>
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
                <label for="datetime" class="col-sm-2">Practice Dates <sup>*</sup> </label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" class="form-control multi-dp" name="multidp" value="<?= $multidp; ?>" placeholder="Muti Dates" />                        
                    </div> 
                    <?php echo form_error('multidp') ?>             
                </div>                
            </div>
            
            <div class="form-group">
                <label for="seat" class="col-sm-2">
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
                <label for="seat" class="col-sm-2">
                    Student Limit
                    <sup>*</sup>
                </label>
                <div class="col-sm-2">
                    <select class="form-control" name="seat" id="seat">
                        <?php echo numericDropDown2(1, 100, 1, $seat); ?>
                    </select>                    
                </div>
            </div>                        
                                    
            
            
            <?= loadWhatsAppWidget('Practice'); ?>

            <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <a href="<?php echo site_url(Backend_URL . "practice?id={$practice_id}") ?>" class="btn btn-default"><i
                            class="fa fa-long-arrow-left"></i> Cancel & Back to List</a>
                <button type="submit" class="btn btn-primary">Create & Continue <i
                            class="fa fa-long-arrow-right"></i></button>
            </div>
            <?php echo form_close(); ?>
  </div>
</div>
</section>
<?php load_module_asset('practice', 'js'); ?>