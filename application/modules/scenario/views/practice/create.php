<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>  Practice Scenario Topic <small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL.'scenario/practice') ?>">Practice Scenarios</a></li>
        <li><a href="<?php echo site_url(Backend_URL.'scenario/practice/view/'.$exam_id) ?>">Groups</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">Add New Topic</div>
        <div class="panel-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="exam_id" class="col-sm-2">Exam <sup>*</sup></label>
                <div class="col-sm-10">
                    <select name="exam_id" class="form-control" id="exam_id">
                        <?php echo ExamCourseDroDown($exam_id); ?>
                    </select>
                    <?php echo form_error('exam_id'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2">Name <sup>*</sup></label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="text" maxlength="255" class="form-control" name="name" id="name" placeholder="Name"
                               value="<?php echo $name; ?>"/>
                        <span class="input-group-addon">Max 255 Characters</span>
                    </div>
                    <?php echo form_error('name'); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-10 col-md-offset-2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <button type="submit" class="btn btn-primary"><?php echo $button; ?></button>
                    <a href="<?php echo site_url(Backend_URL.'scenario/practice/view/'.$exam_id); ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</section>

