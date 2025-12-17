<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Individual Learning Plan <small>Delete</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>development_plan">Development Plan</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content">
    <?php // echo development_planTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="150">Name of Student</td>
                    <td width="5">:</td>
                    <td><?php echo $student_name; ?></td>
                </tr>
                <tr>
                    <td>Aims</td>
                    <td>:</td>
                    <td><?php echo $aims; ?></td>
                </tr>
                <tr>
                    <td>Goals</td>
                    <td>:</td>
                    <td><?php echo $goals; ?></td>
                </tr>
                <tr>
                    <td>Achievement</td>
                    <td>:</td>
                    <td>Date: <?php echo globalDateFormat($date_of_achievement); ?> || Result: <?php echo $achievement; ?></td>
                </tr>
                <tr>
                    <td>Next Review Date</td>
                    <td>:</td>
                    <td><?php echo globalDateFormat($review_date); ?></td>
                </tr>
                <tr>
                    <td>Future Plan</td>
                    <td>:</td>
                    <td><?php echo $future_plan; ?></td>
                </tr>
                <tr>
                    <td>Review</td>
                    <td>:</td>
                    <td><?php echo $review; ?></td>
                </tr>
                <tr>
                    <td>Created on</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($created_at); ?></td>
                </tr>
                <tr>
                    <td>Updated on</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($updated_at); ?></td>
                </tr>                
            </table>
        </div>
        <div class="box-footer">
            <?php echo anchor(site_url(Backend_URL . 'development_plan/delete_action/' . $id), '<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); ?>
        </div>
    </div>
</section>