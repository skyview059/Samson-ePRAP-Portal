<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('course', 'css'); ?>
<section class="content-header">
    <h1>Practice Package <small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li><a href="<?php echo Backend_URL ?>course/practice_package">Practice Package</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <?php echo practicePackageTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update Practice Package</h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="exam_id" class="col-sm-2 control-label">Select Practice<sup>*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="exam_id" id="exam_id" required>
                            <?php echo getExamNameDropDown($exam_id, '-- Select Practice --'); ?>
                        </select>
                        <?php echo form_error('exam_id'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Title<sup>*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title"
                               placeholder="Package Title" value="<?php echo $title; ?>" required/>
                        <?php echo form_error('title'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="description"
                                  rows="6"><?php echo $description; ?></textarea>
                        <?php echo form_error('description'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-sm-2 control-label">Price<sup>*</sup></label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="price" id="price"
                                   placeholder="Price" value="<?php echo $price; ?>" required/>
                            <span class="input-group-addon">GBP</span>
                        </div>
                        <?php echo form_error('price') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="duration" class="col-sm-2 control-label">Duration<sup>*</sup></label>
                    <div class="col-sm-2">
                        <select class="form-control" name="duration" id="duration" required>
                            <?php
                            echo selectOptions($duration, [
                                ''         => '-- Select Duration --',
                                '7 days'   => '7 days',
                                '15 days'  => '15 days',
                                '1 month'  => '1 month',
                                '2 months' => '2 months',
                                '3 months' => '3 months',
                                '6 months' => '6 months',
                                '1 year'   => '1 year',
                                '2 years'  => '2 years'
                            ]);
                            ?>
                        </select>
                        <?php echo form_error('duration'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="scenario_type" class="col-sm-2 control-label">Scenario Type<sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php echo htmlRadio('scenario_type', $scenario_type, array(
                            'Old'  => 'PLAB 2 scenario bank',
                            'New'  => 'PLAB 2 new scenario',
                            'Both' => 'Complete PLAB2 scenario bank'
                        )); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Status<sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php echo htmlRadio('status', $status, array('Active' => 'Active', 'Inactive' => 'Inactive')); ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                        <a href="<?php echo site_url(Backend_URL . 'course/practice_package') ?>"
                           class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
