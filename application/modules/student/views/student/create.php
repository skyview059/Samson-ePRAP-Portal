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
                            <span class="col-md-3">
                                <input type="text" class="form-control" name="fname" id="fname"
                                       placeholder="First Name" value="<?php echo $fname; ?>"/>
                                       <?php echo form_error('fname') ?>
                            </span>
                            <span class="col-md-3">
                                <input type="text" class="form-control" name="mname" id="mname"
                                       placeholder="Middle Name" value="<?php echo $mname; ?>"/>
                                       <?php echo form_error('mname') ?>
                            </span>
                            <span class="col-md-3">
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
                    <label for="ethnicity_id" class="col-sm-2 control-label">Ethnicity <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="ethnicity_id" class="form-control" id="ethnicity_id">
                            <option value="">--Select Ethnicity--</option>
                            <?php echo getDropDownEthnicitys($ethnicity_id); ?>
                        </select>
                        <?php echo form_error('ethnicity_id'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="occupation" class="col-sm-2 control-label">Occupation <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="occupation" class="form-control" id="occupation">
                            <option value="">--Select Occupation--</option>
                            <?php echo getDropDownOccuptions($occupation); ?>
                        </select>
                        <?php echo form_error('occupation'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="purpose_of_registration" class="col-sm-2 control-label">Purpose of Registration
                        <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="purpose_of_registration" class="form-control" id="purpose_of_registration">
                            <option value="">--Select Purpose of Registration--</option>
                            <?php echo getDropDownPurposeOfRegistration($purpose_of_registration); ?>
                        </select>
                        <?php echo form_error('purpose_of_registration'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address_line1" class="col-sm-2 control-label">Address Line1 <sup>*</sup></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="address_line1" id="address_line1"
                               placeholder="Enter Address Line1"
                               value="<?php echo $address_line1; ?>"/>
                        <?php echo form_error('address_line1') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address_line2" class="col-sm-2 control-label">Address Line2</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="address_line2" id="address_line1"
                               placeholder="Enter Address Line2"
                               value="<?php echo $address_line2; ?>"/>
                        <?php echo form_error('address_line2') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="postcode" class="col-sm-2 control-label">Postcode <sup>*</sup></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="postcode" id="postcode"
                               placeholder="Enter Postcode"
                               value="<?php echo $postcode; ?>"/>
                        <?php echo form_error('postcode') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="country_id" class="col-sm-2 control-label">Country <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="country_id" class="form-control" id="country_id">
                            <?php echo getDropDownCountries($country_id); ?>
                        </select>
                        <?php echo form_error('country_id') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gmc_number" class="col-sm-2 control-label">GMC/GDC/NMC Number <sup>*</sup></label>
                    <div class="col-sm-6 customcountry">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="number_type" class="form-control select2" style="width: 100px">
                                    <?php echo getNumberType($number_type); ?>
                                </select>
                            </span>
                            <input type="text" class="form-control" name="gmc_number" id="gmc_number"
                                   placeholder="Number" maxlength="7" minlength="7"
                                   onkeypress="return DigitOnly(event);"
                                   value="<?php echo $gmc_number; ?>"/>
                            <span class="input-group-addon">Must be Unique & be 7 digits only</span>
                        </div>
                        <?php echo form_error('number_type') ?>
                        <?php echo form_error('gmc_number') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_id" class="col-sm-2 control-label">Exam <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="exam_id" class="form-control select2" id="exam_id">
                            <?php echo getExamNameDropDown($exam_id); ?>
                        </select>
                        <?php echo form_error('exam_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_centre_id" class="col-sm-2 control-label">Exam Centre <sup>*</sup></label>
                    <div class="col-sm-6">
                        <select name="exam_centre_id" class="form-control select2" id="exam_centre_id">
                            <?php echo getExamCentreDropDown($exam_centre_id); ?>
                        </select>
                        <?php echo form_error('exam_centre_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_date" class="col-sm-2 control-label">Exam Date <sup>*</sup></label>
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
                    <label for="phone" class="col-sm-2 control-label">Phone <sup>*</sup></label>
                    <div class="col-sm-6 customcountry">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="phone_code" class="form-control select2" style="width: 190px">
                                    <?php echo getPhoneCode($phone_code); ?>
                                </select>
                            </span>
                            <input type="tel" maxlength="15" onKeyPress="return DigitOnly(event);"
                                   name="phone" id="phone" placeholder="77xxxxxx"
                                   class="form-control" value="<?php echo $phone; ?>">
                        </div>
                        <?php echo form_error('phone_code') ?>
                        <?php echo form_error('phone') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="whatsapp" class="col-sm-2 control-label">WhatsApp <sup>*</sup></label>
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
                    <label for="gender" class="col-sm-2 control-label">Gender <sup>*</sup></label>
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
            </form></div>
</div>

</section>
