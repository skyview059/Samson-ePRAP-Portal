<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Practice Package <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'course') ?>">Course</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'course/practice_package') ?>">Practice Package</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo practicePackageTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="box-title">Details View</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <tr>
                    <td width="150">Practice</td>
                    <td width="5">:</td>
                    <td><?php echo getExamName($exam_id); ?></td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td>:</td>
                    <td><?php echo $title; ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>:</td>
                    <td><?php echo $description; ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td>:</td>
                    <td><?php echo GBP($price); ?></td>
                </tr>
                <tr>
                    <td>Duration</td>
                    <td>:</td>
                    <td><?php echo $duration; ?></td>
                </tr>
                <tr>
                    <td>Scenario Type</td>
                    <td>:</td>
                    <td><?php echo getPackageScenarioTypeName($scenario_type); ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?php echo $status; ?></td>
                </tr>
            </table>
        </div>

        <div class="box-footer">
            <a href="<?php echo site_url(Backend_URL . 'course/practice_package') ?>" class="btn btn-default">
                <i class="fa fa-long-arrow-left"></i> Back
            </a>
            <a href="<?php echo site_url(Backend_URL . 'course/practice_package/update/' . $id) ?>" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit
            </a>
        </div>
    </div>
</section>