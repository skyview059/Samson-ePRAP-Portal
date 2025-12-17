<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>
        <?php echo $exam_name; ?> - Practice Scenario Topics <small>Control panel</small>
        <a class="btn btn-default" href="<?php echo site_url('admin/scenario/practice') ?>">Back</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'scenario/practice') ?>">Practice Scenarios</a></li>
        <li class="active">Marking Criteria</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo $exam_name; ?> - Marking Criteria - Update</div>

        <div class="panel-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                        <textarea class="form-control" rows="10" name="marking_criteria"
                                  id="marking_criteria"
                                  placeholder="Enter Marking Criteria"><?php echo $marking_criteria; ?></textarea>
                    <?php echo form_error('marking_criteria') ?>
                </div>

                <div class="form-group text-center">
                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>"/>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?php echo site_url(Backend_URL . 'scenario/practice') ?>" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php loadCKEditor5ClassicBasic(['#marking_criteria']); ?>