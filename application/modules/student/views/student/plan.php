<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Individual Development Plan <small>for <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'development_plan') ?>">Development Plan</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'plan'); ?>

    <?php if(!$plans){ ?>
    <div class="panel panel-default">
  <div class="panel-body"><h3>No Development Plan Found!</h3><br><a href="admin/development_plan/create" class="btn btn-primary">
                Setup Plan
            </a></div>
</div>
    <?php } ?>
    
    <?php foreach ($plans as $plan) { ?>
    <div class="panel panel-default">
  <div class="panel-heading"><?php echo globalDateFormat($plan->created_at); ?><span class="col-md-6 pull-right text-right">
          <a style="background: none; text-decoration: underline;font-weight: 600;" href="<?php echo site_url(Backend_URL . 'development_plan/update/' . $plan->id) ?>"
                                class="btn btn-primary"> <i class="fa fa-edit"></i> Edit</a>
                        <a style="background: none; text-decoration: underline;font-weight: 600;" href="<?php echo site_url(Backend_URL . 'development_plan/delete_action/' . $plan->id) ?>"
                                class="btn btn-danger" onclick="javasciprt: return confirm('Are You Sure ?')">
                            <i class="fa fa-trash"></i> Delete</a>
                    </span></div>
  <div class="panel-body"> <table class="table table-bordered table-striped">
                    <tr>
                        <td width="150">Aims</td>
                        <td width="5">:</td>
                        <td><?php echo $plan->aims; ?></td>
                    </tr>
                    <tr>
                        <td>Goals</td>
                        <td>:</td>
                        <td><?php echo $plan->goals; ?></td>
                    </tr>
                    <tr>
                        <td>Achievement</td>
                        <td>:</td>
                        <td><?php echo $plan->achievement; ?></td>
                    </tr>
                    <tr>
                        <td>Date of Achievement</td>
                        <td>:</td>
                        <td><?php echo globalDateFormat($plan->date_of_achievement); ?></td>
                    </tr>
                    <tr>
                        <td>Next Review Date</td>
                        <td>:</td>
                        <td><?php echo globalDateFormat($plan->review_date); ?></td>
                    </tr>
                    <tr>
                        <td>Future Plan</td>
                        <td>:</td>
                        <td><?php echo $plan->future_plan; ?></td>
                    </tr>
                    <tr>
                        <td>Review</td>
                        <td>:</td>
                        <td><?php echo $plan->review; ?></td>
                    </tr>
                </table></div>
</div>
    <?php } ?>
</section>