<?php if(!$plans){ ?>
<div class="box-body text-center soft-warning">
    <h3 class="box-title">No Individual Learning Plan Found!</h3>
    <a href="individual-learning-plan?tab=add" class="btn btn-primary">
        <i class="fa fa-plus"></i> 
        Click Here to Setup Your Plan
    </a>
</div>
<?php } else { ?>




<div class="page-title">
    <h3>Individual Learning Plan</h3>
</div>

<ul class="nav nav-tabs">
  <li class="active"><a href="individual-learning-plan"> <i class="fa fa-bars"></i> My Plans</a></li>
  <li><a href="individual-learning-plan?tab=add"><i class="fa fa-plus"></i> Add New Plan</a></li>
</ul>

<?php foreach ($plans as $plan) { ?>
    <div class="box no-border">
        <div class="box-header with-border">           
            <h3 class="box-title"><?php echo globalDateFormat($plan->created_at); ?></h3>               
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="220">Aims</td>
                    <td><?php echo $plan->aims; ?></td>
                </tr>
                <tr>
                    <td>Goals</td>
                    <td><?php echo $plan->goals; ?></td>
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
                    <td><?php echo $plan->future_plan; ?></td>
                </tr>
                <tr>
                    <td>Review</td>
                    <td><?php echo $plan->review; ?></td>
                </tr>
            </table>
        </div>
    </div>
<?php } ?>

<?php } ?>