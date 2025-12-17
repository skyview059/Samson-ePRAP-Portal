<?php echo getStudentProcessBar(); ?>
<div class="page-title">
    <h3>Update My Profile</h3>
</div>

<form action="<?php echo $action; ?>" method="post" id="user_form" class="form-horizontal"
      enctype="multipart/form-data">
    
    <div class="row">
        <div class="col-md-10">
            <div class="webcam_preview">
                <div class="inner">
                    <div id="active_camera"></div>
                    <span class="btn btn-primary" id="take_snapshot" onClick="take_snapshot()" style="display:none">
                        <i class="fa fa-save"></i>                     
                        Take Snapshot
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">

            <fieldset>
                <legend>Basic Information</legend>
                <div class="form-group">
                    <label for="first_name" class="col-sm-4 control-label">Full Name <sup>*</sup></label>
                    <div class="col-sm-8">
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
                            <span class="col-md-3" style="padding: 0;">
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
                    <label for="email" class="col-sm-4 control-label">Email <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Email Address"
                               value="<?php echo $email; ?>" readonly/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender" class="col-sm-4 control-label">Gender <sup>*</sup></label>
                    <div class="col-sm-8" style="padding-top:8px;">
                        <?php echo htmlRadio('gender', $gender, array('Male' => 'Male', 'Female' => 'Female')); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Phone <sup>*</sup></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                        <span class="input-group-btn">
                            <select name="phone[code]" class="form-control select2" style="width: 190px">
                                <?php echo getPhoneCode($phone_code); ?>
                            </select>
                        </span>

                            <input type="text" class="form-control" name="phone[number]"
                                   id="phone" placeholder="Phone Number"
                                   value="<?php echo $phone; ?>"/>
                        </div>
                        <?php echo form_error('phone[code]') ?>
                        <?php echo form_error('phone[number]') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="whatsapp" class="col-sm-4 control-label">WhatsApp <sup>*</sup></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                        <span class="input-group-btn">
                            <select name="whatsapp[code]" class="form-control select2" style="width: 190px">
                                <?php echo getPhoneCode($whatsapp_code); ?>
                            </select>
                        </span>
                            <input type="tel" maxlength="15" onKeyPress="return DigitOnly(event);"
                                   name="whatsapp[number]" id="whatsapp" placeholder="07766554433"
                                   class="form-control" value="<?php echo $whatsapp; ?>">
                        </div>
                        <?php echo form_error('whatsapp[code]'); ?>
                        <?php echo form_error('whatsapp[number]'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ethnicity_id" class="col-sm-4 control-label">Ethnicity <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="ethnicity_id" class="form-control" id="ethnicity_id">
                            <option value="">--Select Ethnicity--</option>
                            <?php echo getDropDownEthnicitys($ethnicity_id); ?>
                        </select>
                        <?php echo form_error('ethnicity_id'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="occupation" class="col-sm-4 control-label">Occupation <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="occupation" class="form-control" id="occupation">
                            <option value="">--Select Occuption--</option>
                            <?php echo getDropDownOccuptions($occupation); ?>
                        </select>
                        <?php echo form_error('occupation'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="purpose_of_registration" class="col-sm-4 control-label">Purpose of Registration <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="purpose_of_registration" class="form-control" id="purpose_of_registration">
                            <option value="">--Select Purpose of Registration--</option>
                            <?php echo getDropDownPurposeOfRegistration($purpose_of_registration); ?>
                        </select>
                        <?php echo form_error('purpose_of_registration'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="gmc_number" class="col-sm-4 control-label"><?php echo $number_type . ' Number'; ?></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control" readonly="readonly"
                                   value="<?php echo $gmc_number; ?>"/>
                            <span class="input-group-addon">View only</span>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset>
                <legend>Exam Details</legend>

                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Exam Name <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Exam Name"
                               value="<?php echo $exam_name; ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Exam Centre <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Exam Centre"
                               value="<?php echo $exam_centre; ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Exam Date <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Exam Date"
                               value="<?php echo globalDateFormat($exam_date); ?>" readonly/>
                    </div>
                </div>

            </fieldset>

            <fieldset>
                <legend>Address</legend>

                <div class="form-group">
                    <label for="address_line1" class="col-sm-4 control-label">Address Line1 <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="address_line1" id="address_line1" placeholder="Enter Address Line1"
                               value="<?php echo $address_line1; ?>"/>
                        <?php echo form_error('address_line1') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address_line2" class="col-sm-4 control-label">Address Line2</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="address_line2" id="address_line1" placeholder="Enter Address Line2"
                               value="<?php echo $address_line2; ?>"/>
                        <?php echo form_error('address_line2') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="postcode" class="col-sm-4 control-label">Postcode <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="postcode" id="postcode" placeholder="Enter Postcode"
                               value="<?php echo $postcode; ?>"/>
                        <?php echo form_error('postcode') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="country_id" class="col-sm-4 control-label">Country of Origin<sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="country_id" class="form-control" id="country_id">
                            <?php echo getDropDownCountries($country_id); ?>
                        </select>
                        <?php echo form_error('country_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="country_id" class="col-sm-4 control-label">Current Location <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="present_country_id" class="form-control" id="present_country_id">
                            <?php echo getDropDownCountries($present_country_id); ?>
                        </select>
                        <?php echo form_error('present_country_id') ?>
                    </div>
                </div>

            </fieldset>

            <fieldset>
                <legend>Security Question</legend>

                <div class="form-group">
                    <label for="secret_question_1" class="col-sm-4 control-label">Question 1 <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="secret_question_1" class="form-control" id="secret_question_1">
                            <?php echo getSecretQuestions($secret_question_1); ?>
                        </select>
                        <?php echo form_error('secret_question_1') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="answer_1" class="col-sm-4 control-label">Answer <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="answer_1"
                               id="answer_1" value="<?php echo $answer_1; ?>"/>
                        <?php echo form_error('answer_1') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="secret_question_2" class="col-sm-4 control-label">Question 2 <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="secret_question_2" class="form-control" id="secret_question_2">
                            <?php echo getSecretQuestions($secret_question_2); ?>
                        </select>
                        <?php echo form_error('secret_question_2') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="answer_2" class="col-sm-4 control-label">Answer <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="answer_2"
                               id="answer_2" value="<?php echo $answer_2; ?>"/>
                        <?php echo form_error('answer_2') ?>
                    </div>
                </div>

            </fieldset>


            <div class="form-group">
                <div class="col-sm-9 col-md-offset-3">
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>
                    <a href="<?php echo site_url('profile'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Cancel
                    </a>
                </div>
            </div>

        </div>
        

        <div class="col-md-3">
            <div class="text-center">

<!--                <div id="active_camera"></div>-->

                <div id="preview_area">
                    <?php echo getPhoto_v3($photo, $gender, $fname, 250, 250, 2, true ); ?>
                </div>


                <div class="btn btn-default  btn-block btn-file" style="margin: 15px 0;">
                    <i class="fa fa-image"></i> &nbsp;
                    Select from Local Storage
                    <input type="file" onchange="return instantShowUploadImage(this, '#preview_area');" name="photo" id="photo" accept="image/*" />
                </div>


                <span class="btn btn-warning btn-block" onClick="setup();">
                    <i class="fa fa-camera"></i> &nbsp;
                    Capture From Camera
                </span>
                
                <span class="btn btn-default hidden" id="refresh" onClick="reset();">
                    <i class="fa fa-refresh"></i>                     
                    Reset
                </span>


                <input type="hidden" name="photo_old" value="<?php echo $photo; ?>"/>
                <input type="hidden" id="hidden_src" name="webcam_photo" value=""/>
                <input type="hidden" id="set_webcam" name="set_webcam" value="false"/>

            </div>            
        </div>
    </div>
</form>


<script type="text/javascript" src="assets/lib/plugins/webcam/webcam.min.js"></script>
<script language="JavaScript">
    Webcam.set({
        width: 400,
        height: 400,
        image_format: 'jpeg',
        jpeg_quality: 100,
        fps: 45
    });
    Webcam.set("constraints", {
        optional: [{ minWidth: 600 }]
    });

    function setup() {
        Webcam.reset();
        Webcam.attach('#active_camera');
        $('#preview_area').css('display', 'none');
        $('.webcam_preview').slideDown('slow');
        $('#take_snapshot').toggle();
        $('.btn-file').css('display', 'none');
    }

    function take_snapshot() {
        Webcam.snap(function (data_uri) {
            $('#results').attr('src', data_uri);
            $('#hidden_src').val(data_uri);
            $('#set_webcam').val('true');
            $('#take_snapshot, #active_camera').css('display', 'none');
            $('#preview_area').css('display', 'block');
            $('#refresh').removeClass('hidden');
        });
    }

    function reset() {
        Webcam.reset();
        Webcam.attach('#active_camera');
        $('#preview_area').css('display', 'block');
        location.reload();
    }
</script>