<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Personal Development Plan  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'personal_dev_plan') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>personal_dev_plan">Personal Development Plan</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Add Personal development plan</div>
  <div class="panel-body">
      <form class="form-horizontal" method="get">
                <style type="text/css">
                    .select2-container--default .select2-selection--single {border-radius: 0px; }
                    .input-group .input-group-addon { background-color: #ffeded;}
                </style>
                <div class="form-group" style="margin-bottom: 35px;">                    
                    <div class="col-sm-6 col-md-offset-2">
                        <div class="input-group">
                            <span class="input-group-addon">Select Student to Setup Personal Development Plan</span>
                        
                            <select class="form-control select2" <?php echo $locked; ?> name="student_id" id="student_id">
                                <option value="0">-- Select Student --</option>
                                <?php echo getStudentList($student_id); ?>
                            </select> 
                        </div>
                    </div>                    
                </div>                
            </form>
            
                        
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>                            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="15%">Domain</th>
                        <th width="17.5%">What specific development needs do I have?</th>
                        <th width="17.5%">How will theses development needs be achieved?</th>
                        <th width="17.5%">How will make sure I have achieved and became competent?</th>
                        <th width="15%">Timescale</th>
                        <th width="17.5%">Evaluation and outcome?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($domains as $d ) {?>                    
                    <input type="hidden" name="plan[<?= $d->id; ?>][domain_id]" value="<?= $d->id; ?>" />
                    <input type="hidden" name="plan[<?= $d->id; ?>][created_at]" value="<?= $created_at; ?>" />
                    <input class="student_id" type="hidden" name="plan[<?= $d->id; ?>][student_id]" value="<?= $student_id; ?>" />
                    <tr>
                        <th><?php echo $d->domain; ?></th>
                        <td class="no-padding"><textarea class="form-control" name="plan[<?= $d->id; ?>][specific_development]"></textarea></td>
                        <td class="no-padding"><textarea class="form-control" name="plan[<?= $d->id; ?>][theses_development]"></textarea></td>
                        <td class="no-padding"><textarea class="form-control" name="plan[<?= $d->id; ?>][i_have_achieved]"></textarea></td>
                        <td class="no-padding"><textarea class="form-control" name="plan[<?= $d->id; ?>][timescale]"></textarea></td>
                        <td class="no-padding"><textarea class="form-control" name="plan[<?= $d->id; ?>][evaluation_and_outcome]"></textarea></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
           <hr/>
            <div id="student_btn" class="form-group <?php echo $hidden; ?>">                    
                <div class="col-md-12 text-center">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <button type="submit" class="btn btn-primary">Save</button> 
                    <a href="<?php echo site_url(Backend_URL . 'personal_dev_plan') ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
            <div id="student_notice" class="<?php echo $notice_hidden; ?>">
                <p class="ajax_notice">Please select student to setup personal development plan.</p>
            </div>
            <?php echo form_close(); ?>
  </div>
</div>
            
</section>
<?php load_module_asset('personal_dev_plan', 'js'); ?>