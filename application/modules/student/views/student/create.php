<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>Register New Student</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>student">Student</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Add New Student</div>
  <div class="panel-body"><form action="<?php echo $action; ?>" method="post" id="user_form" class="form-horizontal"
                  enctype="multipart/form-data">

                <div class="form-group">
                    <label for="first_name" class="col-sm-2 control-label">Full Name <sup>*</sup></label>
                    <div class="col-sm-6">
                        <div class="row">
                            <span class="col-md-3">
                                <select name="title" class="form-control select2">
                                    <?php echo getNameTitle($title); ?>
                                </select>
                            </span>
                            <span class="col-md-4">
                                <input type="text" class="form-control" name="fname" id="fname"
                                       placeholder="First Name" value="<?php echo $fname; ?>"/>
                                       <?php echo form_error('fname') ?>
                            </span>                            
                            <span class="col-md-5">
                                <input type="text" class="form-control" name="lname" id="lname"
                                       placeholder="Last Name" value="<?php echo $lname; ?>"/>
                                       <?php echo form_error('lname') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email <sup>*</sup></label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email Address"
                                   value="<?php echo $email; ?>"/>
                            <span class="input-group-addon">Must be Unique</span>
                        </div>
                        <?php echo form_error('email') ?>
                    </div>
                </div>
               
                <div class="form-group">
                    <label for="exam_ids" class="col-sm-2 control-label">Interested Exams</label>
                    <div class="col-sm-6">
                        <select name="exam_ids[]" class="form-control select2" id="exam_ids" multiple="multiple"
                                data-placeholder="-- Select Exams --" style="width:100%">
                            <?php echo getExamNameMultiDropDown($exam_ids); ?>
                        </select>
                        <?php echo form_error('exam_ids[]') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_centre_id" class="col-sm-2 control-label">Exam Centre</label>
                    <div class="col-sm-6">
                        <select name="exam_centre_id" class="form-control select2" id="exam_centre_id">
                            <?php echo getExamCentreDropDown($exam_centre_id); ?>
                        </select>
                        <?php echo form_error('exam_centre_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_date" class="col-sm-2 control-label">Exam Date</label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control js_datepicker" name="exam_date" id="exam_date"
                                   placeholder="Exam Date"
                                   value="<?php echo $exam_date; ?>"/>
                        </div>
                        <?php echo form_error('exam_date') ?>
                    </div>
                </div>                

                <div class="form-group">
                    <label for="whatsapp" class="col-sm-2 control-label">WhatsApp</label>
                    <div class="col-sm-6 customcountry">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="whatsapp_code" class="form-control select2" style="width: 190px">
                                    <?php echo getPhoneCode($whatsapp_code); ?>
                                </select>
                            </span>
                            <input type="tel" maxlength="15" onKeyPress="return DigitOnly(event);"
                                   name="whatsapp" id="whatsapp" placeholder="77xxxxxx"
                                   class="form-control" value="<?php echo $whatsapp; ?>">
                        </div>
                        <?php echo form_error('whatsapp_code'); ?>
                        <?php echo form_error('whatsapp'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender" class="col-sm-2 control-label">Gender</label>
                    <div class="col-sm-6" style="padding-top:8px;">
                        <?php echo htmlRadio('gender', $gender, array('Male' => 'Male', 'Female' => 'Female')); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="photo" class="col-sm-2 control-label">Photo</label>
                    <div class="col-sm-6">
                        <input type="file" name="photo" id="photo"/>
                        <?php echo form_error('photo') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="note" class="col-sm-2 control-label">Note</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="note" id="note" rows="3"
                                  placeholder="Write note here.."><?php echo $note; ?></textarea>
                        <?php echo form_error('note'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-md-offset-2">
                        <a href="<?php echo site_url(Backend_URL . 'student') ?>" class="btn btn-default"><i
                                    class="fa fa-long-arrow-left"></i> Cancel & Back to List</a>
                        <button type="submit" class="btn btn-primary">Register & Continue <i
                                    class="fa fa-long-arrow-right"></i></button>
                    </div>
                </div>

                <p class="text-red">
                    <em><b>Note:</b> Student will receive a welcome email including their login details
                    </em>
                </p>
            </form>
        </div>
</div>

</section>
