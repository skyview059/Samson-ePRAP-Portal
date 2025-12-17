<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Doctor <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'doctor') ?>">Doctor</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-user-md"></i>
                        Details Profile</h3>
                </div>
                <div class="box-body">

                    <fieldset>
                        <legend>Basic Information</legend>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td width="200"><?= $number_type; ?> Number</td>
                                <td width="5">:</td>
                                <td><?php echo $gmc_number; ?></td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td><?= "{$title} {$fname} {$lname}"; ?></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>:</td>
                                <td><?= $gender; ?></td>
                            </tr>
                            <tr>
                                <td>Ethnicity</td>
                                <td>:</td>
                                <td><?= getEthnicityName($ethnicity_id); ?></td>
                            </tr>

                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><?= $email; ?></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td>:</td>
                                <td><?= "+{$phone_code}{$phone}"; ?></td>
                            </tr>
                            <tr>
                                <td>WhatsApp</td>
                                <td>:</td>
                                <td><?= "+{$whatsapp_code}{$whatsapp}"; ?></td>
                            </tr>
                            <tr>
                                <td>Occupation</td>
                                <td>:</td>
                                <td><?= $occupation; ?></td>
                            </tr>
                            <tr>
                                <td>Purpose of Registration</td>
                                <td>:</td>
                                <td><?= $purpose_of_registration; ?></td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset>
                        <legend>Exam Details</legend>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td width="200">Exam</td>
                                <td width="5">:</td>
                                <td><?= getExamName($exam_id); ?></td>
                            </tr>
                            <tr>
                                <td>Exam Centre</td>
                                <td>:</td>
                                <td><?= getExamCentreName($exam_centre_id); ?></td>
                            </tr>
                            <tr>
                                <td>Exam Date</td>
                                <td>:</td>
                                <td><?= globalDateFormat($exam_date); ?></td>
                            </tr>
                        </table>
                    </fieldset>


                    <fieldset>
                        <legend>Address</legend>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td width="200">Address Line 1</td>
                                <td width="5">:</td>
                                <td><?= $address_line1; ?></td>
                            </tr>
                            <tr>
                                <td>Address Line 2</td>
                                <td>:</td>
                                <td><?= $address_line2; ?></td>
                            </tr>
                            <tr>
                                <td>Postcode</td>
                                <td>:</td>
                                <td><?= $postcode; ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>:</td>
                                <td><?= ($country_id); ?></td>
                            </tr>
                            <tr>
                                <td>Current Location</td>
                                <td>:</td>
                                <td><?= ($present_country_id); ?></td>
                            </tr>
                        </table>
                    </fieldset>

                    <?php if ($job_profile): ?>
                        <fieldset>
                            <legend>Job Profile</legend>
                            <table class="table table-striped">
                                <tr>
                                    <td width="280">Internship</td>
                                    <td width="5">:</td>
                                    <td><?php echo $job_profile->internship; ?></td>
                                </tr>
                                <?php if ($job_profile->internship == 'Yes'): ?>
                                    <tr>
                                        <td>Internship Description</td>
                                        <td>:</td>
                                        <td><?php echo $job_profile->internship_txt; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Do you have any Specialty?</td>
                                    <td>:</td>
                                    <td><?php echo $job_profile->specialty; ?></td>
                                </tr>
                                <tr>
                                    <td>Specialty</td>
                                    <td>:</td>
                                    <td><?php echo getSpecialtyNameByStudentID($job_profile->student_id); ?></td>
                                </tr>

                                <tr>
                                    <td>Interests</td>
                                    <td>:</td>
                                    <td><?php echo getSpecialtyNameByIds($job_profile->interest_ids); ?></td>
                                </tr>
                                <tr>
                                    <td>UK Status</td>
                                    <td>:</td>
                                    <td><?php echo ($job_profile->uk_status == 'Other') ? $job_profile->uk_status_other : $job_profile->uk_status; ?></td>
                                </tr>
                                <tr>
                                    <td>Do you have a right to work to work?</td>
                                    <td>:</td>
                                    <td><?php echo $job_profile->right_to_work; ?></td>
                                </tr>
                                <tr>
                                    <td>Postgraduate professional qualifications</td>
                                    <td>:</td>
                                    <td><?php echo $job_profile->professional_qualifications; ?></td>
                                </tr>
                                <tr>
                                    <td>Training courses</td>
                                    <td>:</td>
                                    <td><?php echo ($job_profile->training_courses == 'Other') ? $job_profile->training_courses_other : $job_profile->training_courses; ?></td>
                                </tr>
                                <tr>
                                    <td>Audit</td>
                                    <td>:</td>
                                    <td><?php echo $job_profile->audit; ?></td>
                                </tr>
                                <tr>
                                    <td>Research</td>
                                    <td>:</td>
                                    <td><?php echo $job_profile->research; ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header text-center">
                    <?php echo getPhoto_v3($photo, $gender, $lname, 250, 250); ?>
                </div>
                <div class="box-body">
                    <!--<img src="<?php // getPhoto($photo); ?>"/>-->
                    <table class="table table-striped">
                        <tr>
                            <td width="120">Status</td>
                            <td width="5">:</td>
                            <td><?= $status; ?></td>
                        </tr>
                        <tr>
                            <td>Created on</td>
                            <td>:</td>
                            <td><?= $created_at; ?></td>
                        </tr>
                        <tr>
                            <td>Last Login</td>
                            <td>:</td>
                            <td><?= getStudentLastLogin($id); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-list-ul"></i>
                        Add to Your Shortlist
                    </h3>
                </div>

                <form class="form-horizontal" name="shortlisted">
                    <div class="box-body">

                        <?php if (in_array($role_id, [1, 2])) { ?>
                            <div class="form-group">
                                <label for="manager_id" class="col-sm-4 control-label">Rec. Manager<sup>*</sup></label>
                                <div class="col-sm-8">
                                    <select name="manager_id" class="form-control select2" id="manager_id">
                                        <?php echo getDPRecruitmentManager($manager_id); ?>
                                    </select>
                                    <?php echo form_error('shortlisted_status') ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label for="shortlisted_status" class="col-sm-4 control-label">Status<sup>*</sup></label>
                            <div class="col-sm-8">
                                <select name="shortlisted_status" class="form-control" id="shortlisted_status">
                                    <option value="">--Select Status--</option>
                                    <?php echo Tools::status($shortlisted_status); ?>
                                </select>
                                <?php echo form_error('shortlisted_status') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post_id_val" class="col-sm-4 control-label">Job Post<sup>*</sup></label>
                            <div class="col-sm-8">
                                <input value="<?php echo $job_position; ?>" list="post_id" name="post_id"
                                       id="post_id_val" placeholder="Job Position" class="form-control"/>
                                <datalist id="post_id">
                                    <?php echo recruitmentPosts($job_position); ?>
                                </datalist>
                                <?php echo form_error('post_id') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-4 control-label">Remarks</label>
                            <div class="col-sm-8">
                                <input type="remarks" class="form-control" name="remarks" id="remarks"
                                       placeholder="Enter You Remarks" value="<?php echo $remarks; ?>"/>
                                <?php echo form_error('remarks') ?>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer text-center with-border">

                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $id; ?>"/>
                        <a href="<?= site_url(Backend_URL . 'doctor') ?>" class="btn btn-default">
                            <i class="fa fa-long-arrow-left"></i>
                            Back
                        </a>
                        <button type="button" class="btn btn-primary" id="shortlist_save">
                            <i class="fa fa-save"></i>
                            Save to List
                        </button>

                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-compose-message">
                            <i class="fa fa-envelope"></i> Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php $this->load->view('message/message/new_message_modal', ['id' => $id, 'subject' => 'subject']); ?>
    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-files-o"></i> Uploaded File/Document</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th width='120'>File</th>
                    <th width='150'>Date & Time</th>
                    <th width='120' class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($files as $file) {
                    $link = site_url(Backend_URL . 'student/file_delete/' . $file->id);
                    ?>
                    <tr>
                        <td><?php echo $file->title; ?></td>
                        <td><?php echo download_attachment($file->file); ?></td>
                        <td><?php echo globalDateTimeFormat($file->timestamp); ?></td>
                        <td class="text-center">
                            <a href="//docs.google.com/viewer?url=<?= base_url($file->file); ?>" target="_blank"
                               class="btn btn-xs btn-success" data-id="<?= $file->id; ?>" title="Preview">
                                <i class="fa fa-search-plus"></i>
                                Preview
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-signal"></i> Stage of Progression</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Stage</th>
                    <th width="120">File</th>
                    <th width='150'>Date & Time</th>
                    <th width='120' class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($progress as $p) { ?>
                    <tr>
                        <td><?php echo $p->title; ?></td>
                        <td><?php echo download_attachment($p->file); ?></td>
                        <td><?php echo globalDateTimeFormat($p->timestamp); ?></td>
                        <td class="text-center">
                            <a href="//docs.google.com/viewer?url=<?= base_url($p->file); ?>" target="_blank"
                               class="btn btn-xs btn-success" data-id="<?= $p->id; ?>" title="Preview">
                                <i class="fa fa-search-plus"></i>
                                Preview
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).on('click', '#shortlist_save', function () {
        var status = $('#shortlisted_status').val();
        var post_id = $('#post_id_val').val();
        var manager_id = $('#manager_id').val();
        var remarks = $('#remarks').val();
        var student_id = $('#student_id').val();

        $.ajax({
            url: 'admin/doctor/shortlist_save',
            type: 'POST',
            dataType: 'json',
            data: {student_id: student_id, status: status, post_id: post_id, manager_id: manager_id, remarks: remarks},
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    toastr.success(jsonRespond.Msg);
                    location.reload();
                } else if (jsonRespond.Status === 'FAIL') {
                    toastr.error(jsonRespond.Msg);
                } else {
                    toastr.error("Something went wrong please try again");
                }
            }
        });
        return false;
    });
</script>