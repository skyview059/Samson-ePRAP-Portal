<div class="page-title">
    <h3>Setup Your Personal Development Plan</h3>
</div>

<div class="box">
    <div class="box-body">
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
        <div id="student_btn" class="form-group">                    
            <div class="col-md-12 text-center">                    
                <button type="submit" class="btn btn-primary">Save</button> 
                <a href="<?php echo site_url('personal-development-plan') ?>" class="btn btn-default">Cancel</a>
            </div>
        </div>
         
        <?php echo form_close(); ?>
    </div>
</div>