<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Personal Development Plan<small><?php echo $button ?></small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>personal_dev_plan">Personal Development Plan</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo personal_dev_planTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="text-center"><b class="text-red"><?php echo $student_name; ?></b></h3>
            <h3 class="no-margin text-center ">Personal Development Plan</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <input type="hidden" name="student_id" value="<?php echo $id; ?>" /> 
                <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                
                    
                
                <div class="box-body">
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
                            <?php foreach ($plans as $p) { ?>
                            
                                <input name="plan[<?= $p->domain_id; ?>][created_at]" value="<?= $created_at; ?>" type="hidden" />                                
                                <input name="plan[<?= $p->domain_id; ?>][domain_id]" value="<?= $p->domain_id; ?>" type="hidden" />                                
                                <input name="plan[<?= $p->domain_id; ?>][student_id]" value="<?= $student_id; ?>" type="hidden" class="student_id" />
                                <tr>
                                    <th><?php echo $p->domain; ?></th>
                                    <td class="no-padding"><textarea class="form-control" name="plan[<?= $p->domain_id; ?>][specific_development]"><?php echo $p->specific_development; ?></textarea></td>
                                    <td class="no-padding"><textarea class="form-control" name="plan[<?= $p->domain_id; ?>][theses_development]"><?php echo $p->theses_development; ?></textarea></td>
                                    <td class="no-padding"><textarea class="form-control" name="plan[<?= $p->domain_id; ?>][i_have_achieved]"><?php echo $p->i_have_achieved; ?></textarea></td>
                                    <td class="no-padding"><textarea class="form-control" name="plan[<?= $p->domain_id; ?>][timescale]"><?php echo $p->timescale; ?></textarea></td>
                                    <td class="no-padding"><textarea class="form-control" name="plan[<?= $p->domain_id; ?>][evaluation_and_outcome]"><?php echo $p->evaluation_and_outcome; ?></textarea></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>  

                <div class="form-group">
                    <div class="col-md-12 text-center">
                        
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                        <a href="<?php echo site_url(Backend_URL . 'personal_dev_plan') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>