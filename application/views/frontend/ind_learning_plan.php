<h3>Individual Learning Plan</h3>
      <?php if(!$plans){ ?>
<h3>No Individual Learning Plan Found!</h3>    

<?php } else { ?>

<?php foreach ($plans as $plan) { ?>
<div class="panel panel-default">
    
  <div class="panel-heading">
      <?php echo globalDateFormat($plan->created_at); ?>
  </div>
    <div class="panel-body">
        
    <div class="box no-border">
        <div class="box-body">
            <table class="table table-striped">
                <tr>
                    <td width="220">Aims</td>
                    <td><?php echo nl2br_fk($plan->aims); ?></td>
                </tr>
                <tr>
                    <td>Goals</td>
                    <td><?php echo nl2br_fk($plan->goals); ?></td>
                </tr>
                <tr>
                    <td>Achievement</td>
                    <td><?php echo $plan->achievement; ?></td>
                </tr>
                <tr>
                    <td>Date of Achievement</td>
                    <td><?php echo globalDateFormat($plan->date_of_achievement); ?></td>
                </tr>
                <tr>
                    <td>Next Review Date</td>
                    <td><?php echo globalDateFormat($plan->review_date); ?></td>
                </tr>
                <tr>
                    <td>Future Plan</td>
                    <td><?php echo nl2br_fk($plan->future_plan); ?></td>
                </tr>
                <tr>
                    <td>Review</td>
                    <td><?php echo nl2br_fk($plan->review); ?></td>
                </tr>
                <tr>
                    <td>Note</td>
                    <td><?php echo nl2br_fk($plan->note); ?></td>
                </tr>
            </table>
        </div>
    </div>



    </div>
</div>
<?php } ?>
<?php } ?>

