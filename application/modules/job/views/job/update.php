<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php // load_module_asset('users', 'css'); ?>
<link href="assets/lib/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css"/>
<?php load_module_asset('job','css'); ?>
<section class="content-header">
    <h1>Job<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>job">Job</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <!--    --><?php //echo jobTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update Job</h3>
            <?php echo $this->session->flashdata('message'); ?>
            <!--            --><?php //echo validation_errors(); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">

                <?php if ($manage_all): ?>
                    <div class="form-group">
                        <label for="user_id" class="col-sm-2 control-label">Recruitment Manager :<sup>*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control js_select2" name="user_id" id="user_id">
                                <?php echo getDPRecruitmentManager($user_id); ?>
                            </select>
                            <?php echo form_error('user_id') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="post_title" class="col-sm-2 control-label">Post Title :<sup>*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="post_title" id="post_title"
                               placeholder="Post Title" value="<?php echo $post_title; ?>"/>
                        <?php echo form_error('post_title') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="deadline" class="col-sm-2 control-label">Application Deadline :<sup>*</sup></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="form-control job_deadline_picker date_picker_icon" name="deadline"
                                   id="deadline" placeholder="Deadline" value="<?php echo $deadline; ?>"/>

                            <span class="input-group-addon">Default Date After 1 Month</span>
                            <?php echo form_error('deadline') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                <label for="job_for" class="col-sm-2 control-label">Job For :<sup>*</sup></label>
                <div class="col-sm-10" style="padding-top:8px;">
                    <?php
                        echo htmlRadio('job_for', $job_for, [
                            'Doctor' => 'Doctor',
                            'Dentist' => 'Dentist',
                            'Nurse' => 'Nurse',
                        ]);
                    ?>
                    <?php /*
                    <select name="job_for" id="job_for" class="form-control">
                        <option value="">Select</option>
                        <?php echo Tools::getJobFor($job_for); ?>
                    </select>
                     * 
                     */ ?>
                    <?php echo form_error('job_for') ?>
                </div>
            </div>


                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Description :<sup>*</sup></label>
                    <div class="col-sm-10">
                        <textarea name="description" rows="10" id="description"
                                  class="form-control"><?php echo $description; ?></textarea>
                        <?php echo form_error('description') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="location" class="col-sm-2 control-label">Job Location :<sup>*</sup></label>
                    <div class="col-sm-10">
                        <input id="latitude" name="lat" type="hidden" value="<?php echo $lat; ?>">
                        <input id="longitude" name="lng" type="hidden" value="<?php echo $lng; ?>">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            <input type="text" class="form-control" name="location" id="autocomplete" autocomplete="off" placeholder="Location"
                               value="<?php echo $location; ?>"/>
                            <span class="input-group-addon">Address/Postcode</span>
                        </div>                    
                        <?php echo form_error('location') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="skills" class="col-sm-2 control-label">Skills :</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="skills" id="skills"
                                  placeholder="Skills"><?php echo $skills; ?></textarea>
                        <?php echo form_error('skills') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="benefit" class="col-sm-2 control-label">Benefit :</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="benefit" id="benefit"
                                  placeholder="Benefit"><?php echo $benefit; ?></textarea>
                        <?php echo form_error('benefit') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="job_type" class="col-sm-2 control-label">Job Type<sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php echo htmlRadio('job_type', $job_type, array('Full Time' => 'Full Time', 'Part Time' => 'Part Time')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="vacancy" class="col-sm-2 control-label">Job Vacancy :<sup>*</sup></label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="form-control input-sm" name="vacancy" id="vacancy"
                                   placeholder="Job Vacancy" onkeypress="return DigitOnly(event);"
                                   value="<?php echo $vacancy; ?>" maxlength="3"/>
                        </div>
                        <?php echo form_error('vacancy') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="salary_type" class="col-sm-2 control-label">Salary Type<sup>*</sup></label>
                    <div class="col-sm-3" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('salary_type', $salary_type, [
                                'Negotiable' => 'Negotiable',
                                'Yearly' => 'Yearly',
                                'Hourly' => 'Hourly'
                            ]
                        );
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <div class="<?= $salary_type != 'Negotiable' ? 'show' : 'hidden'; ?>" id="salary_area">
                            <div class="input-group">
                                <input type="number" class="form-control input-sm" name="rate" id="rate"
                                       onkeypress="return DigitOnly(event);" step="any" value="<?php echo $rate; ?>"/>
                                <span class="input-group-addon"><i class="fa fa-gbp"></i> GBP</span>
                            </div>
                            <?php echo form_error('rate') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="service_hour" class="col-sm-2 control-label">Service Hour :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="service_hour" id="service_hour"
                               placeholder="9am-5pm or 10pm to 8am or 8am to 4pm" value="<?php echo $service_hour; ?>"/>
                        <?php echo form_error('service_hour') ?>
                    </div>
                </div>

                <?php if ($manage_all): ?>
                <div class="form-group">
                    <label for="featured" class="col-sm-2 control-label">Featured :<sup>*</sup></label>
                    <div class="col-sm-10"
                         style="padding-top:8px;"><?php echo htmlRadio('featured', $featured, array('Yes' => 'Yes', 'No' => 'No')); ?></div>
                </div>
                <?php else: ?>
                    <input type="hidden" name="featured" value="<?php echo $featured; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Status :<sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('status', $status, array(
                            'Draft' => 'Draft',
                            'Publish' => 'Publish',
                            'Expired' => 'Expired'
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Save Changes
                        </button>
                        <a href="<?php echo site_url(Backend_URL . 'job') ?>" class="btn btn-default">
                            Cancel
                        </a>
                    </div>
                </div>
                

            </form>
        </div>
    </div>
</section>
<!-- Page Script -->
<?php load_module_asset('job','js'); ?>