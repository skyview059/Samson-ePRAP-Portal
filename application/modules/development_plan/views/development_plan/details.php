<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Individual Learning Plan <small>for <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'development_plan') ?>">Development Plan</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php // echo development_planTabs($id, 'read'); ?>

    <?php foreach ($plans as $plan) { ?>
        <div class="box no-border">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title"><?php echo globalDateFormat($plan->created_at); ?></h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?php echo site_url(Backend_URL . 'development_plan/update/' . $plan->id) ?>"
                                class="btn btn-primary"> <i class="fa fa-edit"></i> Edit</a>
                        <a href="<?php echo site_url(Backend_URL . 'development_plan/delete_action/' . $plan->id) ?>"
                                class="btn btn-danger" onclick="javasciprt: return confirm('Are You Sure ?')">
                            <i class="fa fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
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
                    <tr>
                        <td>Note</td>
                        <td>:</td>
                        <td><?php echo $plan->note; ?></td>
                    </tr>
                </table>
            </div>

        </div>
    <?php } ?>


</section>